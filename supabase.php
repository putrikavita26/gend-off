<?php
/**
 * GEND-OFF - Penghubung ke Supabase (Auth + REST). Semua panggilan terjadi DI SERVER (PHP).
 * Token pengguna hanya disimpan di $_SESSION dan tidak pernah dikirim ke browser.
 *
 * Isi file ini:
 *   - sb_request()            : pemanggil HTTP dasar (cURL)
 *   - sb_auth_*()             : daftar, masuk, perpanjang token, keluar (Supabase Auth)
 *   - sb_rest(), sb_rpc()     : akses tabel dan fungsi database (aturan RLS tetap berlaku)
 *   - daftar_user(), cek_login() : dipanggil oleh register.php dan login.php
 *
 * Konfigurasi dibaca dari config.local.php (satu folder). Butuh PHP 8.0+ dan ekstensi cURL.
 */

function sb_config(): array {
  static $cfg = null;
  if ($cfg === null) {
    $file = __DIR__ . '/config.local.php';
    $cfg = is_file($file) ? (require $file) : [];
    $cfg = array_merge(['url' => '', 'anon_key' => '', 'service_key' => '', 'verify_ssl' => true], (array)$cfg);
    $cfg['url'] = rtrim((string)$cfg['url'], '/');
  }
  return $cfg;
}

function sb_ready(): bool {
  $c = sb_config();
  return $c['url'] !== '' && $c['anon_key'] !== '';
}

/**
 * $o: key ('anon'|'service'), token (JWT pengguna), query (array), body (array|object), headers (array)
 * Hasil: ['status'=>int, 'data'=>mixed, 'error'=>?string]  (error terisi bila koneksi gagal)
 */
function sb_request(string $method, string $path, array $o = []): array {
  $c   = sb_config();
  $url = $c['url'] . $path;
  if (!empty($o['query'])) $url .= '?' . http_build_query($o['query']);
  $key = (($o['key'] ?? 'anon') === 'service') ? $c['service_key'] : $c['anon_key'];
  if ($key === '') return ['status' => 0, 'data' => null, 'error' => 'kunci_tidak_ada'];

  $h = ['apikey: ' . $key, 'Authorization: Bearer ' . ($o['token'] ?? $key), 'Accept: application/json'];
  foreach (($o['headers'] ?? []) as $x) $h[] = $x;
  $body = null;
  if (array_key_exists('body', $o)) {
    $body = json_encode($o['body'], JSON_UNESCAPED_UNICODE);
    $h[]  = 'Content-Type: application/json';
  }
  $ch = curl_init($url);
  curl_setopt_array($ch, [
    CURLOPT_CUSTOMREQUEST  => $method,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER     => $h,
    CURLOPT_TIMEOUT        => 15,
    CURLOPT_CONNECTTIMEOUT => 8,
    CURLOPT_SSL_VERIFYPEER => (bool)$c['verify_ssl'],
    CURLOPT_SSL_VERIFYHOST => $c['verify_ssl'] ? 2 : 0,
  ]);
  if ($body !== null) curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
  $raw    = curl_exec($ch);
  $status = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
  $err    = ($raw === false) ? curl_error($ch) : null;
  curl_close($ch);
  $data = ($raw !== false && $raw !== '') ? json_decode($raw, true) : null;
  return ['status' => $status, 'data' => $data, 'error' => $err];
}

function sb_ok(array $r): bool { return $r['error'] === null && $r['status'] >= 200 && $r['status'] < 300; }
function sb_err_code(array $r): string {
  $d = $r['data'];
  return is_array($d) ? (string)($d['error_code'] ?? $d['code'] ?? $d['error'] ?? '') : '';
}
function sb_err_msg(array $r): string {
  $d = $r['data'];
  return is_array($d) ? (string)($d['msg'] ?? $d['message'] ?? $d['error_description'] ?? '') : '';
}
function sb_log(string $konteks, array $r): void {   // log untuk pengembang (tanpa kata sandi/token)
  error_log("[GEND-OFF] $konteks status={$r['status']} kode=" . sb_err_code($r) . ' pesan=' . sb_err_msg($r) . ' jaringan=' . ($r['error'] ?? '-'));
}

/* ---------- Supabase Auth ---------- */
function sb_auth_signup(string $email, string $password, string $username): array {
  return sb_request('POST', '/auth/v1/signup', ['body' => ['email' => $email, 'password' => $password, 'data' => ['username' => $username]]]);
}
function sb_auth_login(string $email, string $password): array {
  return sb_request('POST', '/auth/v1/token', ['query' => ['grant_type' => 'password'], 'body' => ['email' => $email, 'password' => $password]]);
}
function sb_auth_refresh(string $refreshToken): array {
  return sb_request('POST', '/auth/v1/token', ['query' => ['grant_type' => 'refresh_token'], 'body' => ['refresh_token' => $refreshToken]]);
}
function sb_auth_logout(string $token): array {
  return sb_request('POST', '/auth/v1/logout', ['token' => $token, 'body' => new stdClass()]);
}
function sb_auth_hapus_user(string $uid): array {     // butuh service_key; dipakai hanya untuk membersihkan akun yatim
  return sb_request('DELETE', '/auth/v1/admin/users/' . rawurlencode($uid), ['key' => 'service']);
}

/* ---------- Database (PostgREST) ---------- */
function sb_rest(string $method, string $tabel, ?string $token, array $query = [], $body = null, ?string $prefer = null): array {
  $o = ['query' => $query];
  if ($token) $o['token'] = $token;
  if ($body !== null) $o['body'] = $body;
  if ($prefer) $o['headers'] = ['Prefer: ' . $prefer];
  return sb_request($method, '/rest/v1/' . $tabel, $o);
}
function sb_rpc(string $fn, array $args, string $key = 'anon', ?string $token = null): array {
  $o = ['key' => $key, 'body' => (object)$args];
  if ($token) $o['token'] = $token;
  return sb_request('POST', '/rest/v1/rpc/' . $fn, $o);
}

/* ---------- Username ---------- */
/** true = tersedia, false = sudah dipakai atau tidak valid, null = gagal memeriksa */
function sb_username_tersedia(string $username): ?bool {
  $r = sb_rpc('username_tersedia', ['p_username' => $username]);
  if (!sb_ok($r) || !is_bool($r['data'])) { sb_log('username_tersedia', $r); return null; }
  return $r['data'];
}
/** Mencari email dari username. Hanya bisa bila service_key diisi (dipakai DI SERVER saja). */
function sb_email_dari_username(string $username): ?string {
  if (sb_config()['service_key'] === '') return null;
  $r = sb_rpc('user_id_dari_username', ['p_username' => $username], 'service');
  if (!sb_ok($r) || !is_string($r['data']) || $r['data'] === '') return null;
  $a = sb_request('GET', '/auth/v1/admin/users/' . rawurlencode($r['data']), ['key' => 'service']);
  return (sb_ok($a) && is_array($a['data']) && !empty($a['data']['email'])) ? (string)$a['data']['email'] : null;
}
/** Hasil: array berisi username | [] bila belum ada baris | null bila gagal membaca */
function sb_profil_ambil(string $token, string $uid): ?array {
  $r = sb_rest('GET', 'profil_user', $token, ['select' => 'username', 'user_id' => 'eq.' . $uid, 'limit' => 1]);
  if (!sb_ok($r) || !is_array($r['data'])) { sb_log('profil_ambil', $r); return null; }
  return $r['data'][0] ?? [];
}
function sb_profil_buat(string $token, string $uid, string $username): array {
  return sb_rest('POST', 'profil_user', $token, [], ['user_id' => $uid, 'username' => $username], 'return=minimal');
}

/* ---------- Sesi PHP ---------- */
function sb_session_store(array $s): void {
  $_SESSION['sb_access']  = (string)($s['access_token'] ?? '');
  $_SESSION['sb_refresh'] = (string)($s['refresh_token'] ?? '');
  $_SESSION['sb_exp']     = time() + (int)($s['expires_in'] ?? 3600) - 30;
}
/** Token akses yang masih berlaku (diperpanjang otomatis bila kedaluwarsa). null = harus login ulang. */
function sb_token(): ?string {
  if (empty($_SESSION['sb_access'])) return null;
  if (time() < ($_SESSION['sb_exp'] ?? 0)) return $_SESSION['sb_access'];
  if (empty($_SESSION['sb_refresh'])) return null;
  $r = sb_auth_refresh($_SESSION['sb_refresh']);
  if (!sb_ok($r) || empty($r['data']['access_token'])) { sb_log('refresh', $r); return null; }
  sb_session_store($r['data']);
  return $_SESSION['sb_access'];
}

/* ---------- Dipanggil oleh register.php ---------- */
/** Kembalikan ['ok'=>true] atau ['ok'=>false,'error'=>'pesan','field'=>'username|email|password|'] */
function daftar_user($username, $email, $password) {
  if (!sb_ready()) return ['ok' => false, 'error' => 'Koneksi ke database belum dikonfigurasi (config.local.php).', 'field' => ''];

  $tersedia = sb_username_tersedia($username);
  if ($tersedia === false) return ['ok' => false, 'error' => 'Username sudah dipakai, coba yang lain.', 'field' => 'username'];
  if ($tersedia === null)  return ['ok' => false, 'error' => 'Tidak dapat memeriksa username. Coba lagi sebentar lagi.', 'field' => ''];

  $r = sb_auth_signup($email, $password, $username);
  if (!sb_ok($r)) {
    sb_log('signup', $r);
    $kode = sb_err_code($r); $pesan = strtolower(sb_err_msg($r));
    if ($r['error'] !== null)                                   return ['ok' => false, 'error' => 'Tidak dapat terhubung ke server. Periksa koneksi internet.', 'field' => ''];
    if ($kode === 'user_already_exists' || str_contains($pesan, 'already registered'))
                                                                return ['ok' => false, 'error' => 'Email sudah terdaftar. Silakan masuk.', 'field' => 'email'];
    if ($kode === 'weak_password' || str_contains($pesan, 'password'))
                                                                return ['ok' => false, 'error' => 'Kata sandi terlalu lemah. Gunakan kombinasi yang lebih kuat.', 'field' => 'password'];
    if ($r['status'] === 429 || str_starts_with($kode, 'over_'))
                                                                return ['ok' => false, 'error' => 'Terlalu banyak percobaan. Coba lagi beberapa menit lagi.', 'field' => ''];
    if (str_contains($kode, 'email') || str_contains($pesan, 'email'))
                                                                return ['ok' => false, 'error' => 'Alamat email ini tidak dapat dipakai.', 'field' => 'email'];
    return ['ok' => false, 'error' => 'Pendaftaran gagal. Coba lagi nanti.', 'field' => ''];
  }

  $d = $r['data'];
  // Bila konfirmasi email aktif dan email sudah ada, Supabase membalas sukses tanpa identitas (sengaja disamarkan)
  if (is_array($d) && isset($d['user']['identities']) && $d['user']['identities'] === [])
    return ['ok' => false, 'error' => 'Email sudah terdaftar. Silakan masuk.', 'field' => 'email'];

  // Konfirmasi email MATI: sesi langsung ada, profil dibuat sekarang dengan token pengguna itu sendiri (aturan RLS berlaku).
  // Konfirmasi email AKTIF: tidak ada sesi; profil dibuat saat login pertama (lihat cek_login).
  $token = $d['access_token'] ?? null;
  $uid   = $d['user']['id'] ?? ($d['id'] ?? null);
  if ($token && $uid) {
    $p = sb_profil_buat($token, $uid, $username);
    if (!sb_ok($p)) {
      sb_log('profil_buat', $p);
      if ($p['status'] === 409) {                       // username bentrok (balapan dua pendaftar)
        if (sb_config()['service_key'] !== '') sb_auth_hapus_user($uid);   // bersihkan akun yatim
        return ['ok' => false, 'error' => 'Username baru saja dipakai orang lain, coba yang lain.', 'field' => 'username'];
      }
      // kegagalan lain: akun sudah terbentuk; profil dilengkapi otomatis saat login pertama
    }
    sb_auth_logout($token);                              // pendaftaran selesai; pengguna masuk lewat halaman login
  }
  return ['ok' => true];
}

/* ---------- Dipanggil oleh login.php ---------- */
/**
 * Kembalikan ['id','username','email','session'=>[...]] bila cocok, atau null bila gagal.
 * Pesan khusus (selain "salah") ditaruh di $GLOBALS['sb_login_error'].
 */
function cek_login($identity, $password) {
  $GLOBALS['sb_login_error'] = null;
  if (!sb_ready()) { $GLOBALS['sb_login_error'] = 'Koneksi ke database belum dikonfigurasi (config.local.php).'; return null; }

  if (str_contains($identity, '@')) {
    $email = $identity;
  } else {
    if (!preg_match('/^[A-Za-z0-9_]{3,20}$/', $identity)) return null;
    if (sb_config()['service_key'] === '') {
      $GLOBALS['sb_login_error'] = 'Masuk dengan username belum diaktifkan di server ini. Gunakan email.';
      return null;
    }
    $email = sb_email_dari_username($identity);
    if ($email === null) return null;                    // username tidak ada: pesan sama dengan "sandi salah"
  }

  $r = sb_auth_login($email, $password);
  if (!sb_ok($r) || empty($r['data']['access_token'])) {
    sb_log('login', $r);
    $kode = sb_err_code($r);
    if ($r['error'] !== null)             $GLOBALS['sb_login_error'] = 'Tidak dapat terhubung ke server. Periksa koneksi internet.';
    elseif ($kode === 'email_not_confirmed') $GLOBALS['sb_login_error'] = 'Email belum dikonfirmasi. Periksa kotak masuk email kamu.';
    elseif ($r['status'] === 429 || str_starts_with($kode, 'over_')) $GLOBALS['sb_login_error'] = 'Terlalu banyak percobaan. Coba lagi beberapa menit lagi.';
    return null;
  }

  $s     = $r['data'];
  $token = $s['access_token'];
  $uid   = $s['user']['id'] ?? '';
  if ($uid === '') return null;

  $profil = sb_profil_ambil($token, $uid);
  if ($profil === null) { $GLOBALS['sb_login_error'] = 'Gagal membaca profil. Coba lagi.'; return null; }
  if ($profil === []) {                                   // profil belum ada: lengkapi dari username saat mendaftar
    $u = (string)($s['user']['user_metadata']['username'] ?? '');
    if (!preg_match('/^[A-Za-z0-9_]{3,20}$/', $u)) { $GLOBALS['sb_login_error'] = 'Profil belum lengkap. Hubungi admin.'; return null; }
    $p = sb_profil_buat($token, $uid, $u);
    if (!sb_ok($p)) { sb_log('profil_buat_login', $p); $GLOBALS['sb_login_error'] = 'Profil belum lengkap (username bentrok). Hubungi admin.'; return null; }
    $profil = ['username' => $u];
  }

  return ['id' => $uid, 'username' => $profil['username'], 'email' => $s['user']['email'] ?? $email, 'session' => $s];
}
