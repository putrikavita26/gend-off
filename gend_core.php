<?php
/**
 * GEND-OFF - Inti data pengguna (berjalan di server PHP).
 *  - gend_ref()      : membaca aturan sistem pakar dari database (status gizi, aturan kalori, faktor aktivitas, batas kalori)
 *  - gend_hitung()   : perhitungan IMT -> status -> BMR -> TDEE -> target kalori (aturan diambil dari database)
 *  - gend_simpan_*() : menyimpan ke profil_user dan catatan_progres (dengan token pengguna, jadi RLS berlaku)
 *  - gend_state()    : membaca data pengguna untuk ditampilkan
 *  - gend_boot()     : dipanggil di awal halaman: cek login, muat data, kirim ke JavaScript
 */
require_once __DIR__ . '/supabase.php';
date_default_timezone_set('Asia/Jakarta');

const GEND_USIA_MIN = 18;   // aturan Permenkes 41/2014 untuk orang dewasa. Ubah di sini bila tim memutuskan lain.
const GEND_USIA_MAX = 80;

/* ---------- Aturan sistem pakar dari database ---------- */
function gend_ref(bool $segarkan = false): ?array {
  if (!$segarkan && !empty($_SESSION['gend_ref']) && time() < ($_SESSION['gend_ref_exp'] ?? 0)) return $_SESSION['gend_ref'];
  $t = sb_token();
  if (!$t) return null;
  $st = sb_rest('GET', 'status_gizi', $t, ['select' => 'id_status,nama_status,imt_min,imt_max,deskripsi', 'order' => 'imt_min.asc']);
  $ar = sb_rest('GET', 'aturan_rekomendasi', $t, ['select' => 'id_status,kode_aturan,penyesuaian_kkal,persen_karbo,persen_protein,persen_lemak,saran_pola_makan']);
  $ak = sb_rest('GET', 'faktor_aktivitas', $t, ['select' => 'id_aktivitas,nama_aktivitas,deskripsi,faktor', 'order' => 'faktor.asc']);
  $bk = sb_rest('GET', 'batas_kalori_minimum', $t, ['select' => 'jenis_kelamin,kalori_min']);
  foreach ([$st, $ar, $ak, $bk] as $r) {
    if (!sb_ok($r) || !is_array($r['data'])) { sb_log('gend_ref', $r); return null; }
  }
  $aturan = [];
  foreach ($ar['data'] as $a) $aturan[(int)$a['id_status']] = $a;
  $status = [];
  foreach ($st['data'] as $s) {
    $a = $aturan[(int)$s['id_status']] ?? null;
    $status[] = [
      'id' => (int)$s['id_status'], 'nama' => $s['nama_status'], 'min' => (float)$s['imt_min'], 'max' => (float)$s['imt_max'],
      'deskripsi' => $s['deskripsi'], 'kode' => $a['kode_aturan'] ?? '', 'adj' => $a ? (int)$a['penyesuaian_kkal'] : 0,
      'pk' => $a ? (float)$a['persen_karbo'] : 0, 'pp' => $a ? (float)$a['persen_protein'] : 0, 'pl' => $a ? (float)$a['persen_lemak'] : 0,
      'saran' => $a['saran_pola_makan'] ?? '',
    ];
  }
  $akt = [];
  foreach ($ak['data'] as $x) $akt[] = ['id' => (int)$x['id_aktivitas'], 'nama' => $x['nama_aktivitas'], 'deskripsi' => $x['deskripsi'], 'faktor' => (float)$x['faktor']];
  $floor = [];
  foreach ($bk['data'] as $x) $floor[$x['jenis_kelamin']] = (int)$x['kalori_min'];
  if (!$status || !$akt || !isset($floor['L'], $floor['P'])) return null;
  $ref = ['status' => $status, 'aktivitas' => $akt, 'floor' => $floor];
  $_SESSION['gend_ref'] = $ref;
  $_SESSION['gend_ref_exp'] = time() + 600;
  return $ref;
}

function gend_cari_status(float $imt, array $ref): ?array {
  $imt = round($imt, 1);                      // IMT dibulatkan 1 desimal sebelum dicocokkan (sama dengan aturan di database)
  foreach ($ref['status'] as $s) if ($imt >= $s['min'] && $imt < $s['max']) return $s;
  return null;
}

/* ---------- Perhitungan sistem pakar ---------- */
/**
 * $in: jk, tgl_lahir (Y-m-d), tb (cm), bb (kg), id_aktivitas
 * Hasil: ['ok'=>true,'hasil'=>[...]] atau ['ok'=>false,'error'=>'pesan']
 */
function gend_hitung(array $in, array $ref): array {
  $akt = null;
  foreach ($ref['aktivitas'] as $a) if ($a['id'] === (int)$in['id_aktivitas']) $akt = $a;
  if (!$akt) return ['ok' => false, 'error' => 'Tingkat aktivitas tidak valid.'];

  $dob = DateTime::createFromFormat('!Y-m-d', (string)$in['tgl_lahir']);
  $galat = DateTime::getLastErrors();
  if (!$dob || ($galat && ($galat['warning_count'] || $galat['error_count'])) || $dob->format('Y-m-d') !== $in['tgl_lahir'])
    return ['ok' => false, 'error' => 'Tanggal lahir tidak valid.'];
  $usia = (int)$dob->diff(new DateTime('today'))->y;
  if ($dob > new DateTime('today')) return ['ok' => false, 'error' => 'Tanggal lahir tidak boleh di masa depan.'];
  if ($usia < GEND_USIA_MIN || $usia > GEND_USIA_MAX)
    return ['ok' => false, 'error' => 'Sistem ini untuk usia ' . GEND_USIA_MIN . '–' . GEND_USIA_MAX . ' tahun (kategori IMT dewasa).'];

  $tb = (float)$in['tb']; $bb = (float)$in['bb'];
  $imt = round($bb / (($tb / 100) ** 2), 1);
  $st  = gend_cari_status($imt, $ref);
  if (!$st) return ['ok' => false, 'error' => 'IMT di luar rentang aturan.'];

  $bmr  = round(10 * $bb + 6.25 * $tb - 5 * $usia + ($in['jk'] === 'L' ? 5 : -161), 1);
  $tdee = round($bmr * $akt['faktor'], 1);
  $floor = $ref['floor'][$in['jk']];
  $target = round($tdee + $st['adj'], 1);
  $floorDipakai = false;
  if ($st['adj'] < 0 && $target < $floor) { $target = (float)$floor; $floorDipakai = true; }   // defisit tidak boleh di bawah batas minimum

  return ['ok' => true, 'hasil' => [
    'usia' => $usia, 'bmi' => $imt, 'status' => $st, 'bmr' => $bmr, 'tdee' => $tdee, 'faktor' => $akt['faktor'],
    'aktivitas' => $akt['nama'], 'adj' => $st['adj'], 'target' => $target, 'floor' => $floor, 'floor_dipakai' => $floorDipakai,
    'karbo_g' => (int)round($target * $st['pk'] / 100 / 4), 'protein_g' => (int)round($target * $st['pp'] / 100 / 4),
    'lemak_g' => (int)round($target * $st['pl'] / 100 / 9),
  ]];
}

/* ---------- Simpan ---------- */
function gend_simpan_deteksi(string $token, string $uid, array $in, array $h): array {
  $profil = [
    'jenis_kelamin' => $in['jk'], 'tgl_lahir' => $in['tgl_lahir'], 'berat_badan' => (float)$in['bb'], 'tinggi_badan' => (float)$in['tb'],
    'target_bb' => (float)$in['goal'], 'lingkar_perut' => (float)$in['wa'], 'lingkar_perut_estimasi' => (bool)$in['we'],
    'id_aktivitas' => (int)$in['id_aktivitas'], 'riwayat_penyakit' => ($in['pen'] !== '' ? $in['pen'] : null),
    'bmi' => $h['bmi'], 'bmr' => $h['bmr'], 'tdee' => $h['tdee'], 'target_kalori_harian' => $h['target'], 'id_status' => $h['status']['id'],
  ];
  $r = sb_rest('PATCH', 'profil_user', $token, ['user_id' => 'eq.' . $uid], $profil, 'return=representation');
  if (!sb_ok($r)) { sb_log('simpan_profil', $r); return ['ok' => false, 'error' => 'Gagal menyimpan profil. Coba lagi.']; }
  if (empty($r['data'])) {                                   // baris profil belum ada (mis. pendaftaran terputus): buat dulu
    $u = (string)($_SESSION['username'] ?? '');
    $p = sb_rest('POST', 'profil_user', $token, [], array_merge(['user_id' => $uid, 'username' => $u], $profil), 'return=minimal');
    if (!sb_ok($p)) { sb_log('buat_profil', $p); return ['ok' => false, 'error' => 'Gagal menyimpan profil. Coba lagi.']; }
  }
  return gend_catat_progres($token, $uid, [
    'bb_terbaru' => (float)$in['bb'], 'lingkar_perut' => (float)$in['wa'], 'bmi' => $h['bmi'], 'bmr_recalculated' => $h['bmr'],
    'tdee_recalculated' => $h['tdee'], 'id_status' => $h['status']['id'],
  ]);
}

/** Upsert catatan hari ini; kolom yang tidak dikirim tidak ditimpa. */
function gend_catat_progres(string $token, string $uid, array $kolom): array {
  $row = array_merge(['user_id' => $uid, 'tgl_pencatatan' => date('Y-m-d')], $kolom);
  $r = sb_rest('POST', 'catatan_progres', $token, ['on_conflict' => 'user_id,tgl_pencatatan'], $row, 'resolution=merge-duplicates,return=minimal');
  if (!sb_ok($r)) { sb_log('catat_progres', $r); return ['ok' => false, 'error' => 'Gagal menyimpan catatan progres. Coba lagi.']; }
  return ['ok' => true];
}

/* ---------- Baca ---------- */
/** Hasil: ['profil'=>array|null, 'progres'=>[['d','bb','lp'],...]] atau null bila gagal membaca. */
function gend_state(string $token, string $uid): ?array {
  $p = sb_rest('GET', 'profil_user', $token, [
    'select' => 'username,jenis_kelamin,tgl_lahir,berat_badan,tinggi_badan,target_bb,lingkar_perut,lingkar_perut_estimasi,id_aktivitas,riwayat_penyakit,bmi,bmr,tdee,target_kalori_harian,id_status',
    'user_id' => 'eq.' . $uid, 'limit' => 1]);
  $c = sb_rest('GET', 'catatan_progres', $token, [
    'select' => 'tgl_pencatatan,bb_terbaru,lingkar_perut', 'user_id' => 'eq.' . $uid, 'order' => 'tgl_pencatatan.desc', 'limit' => 365]);
  if (!sb_ok($p) || !is_array($p['data']) || !sb_ok($c) || !is_array($c['data'])) { sb_log('gend_state', sb_ok($p) ? $c : $p); return null; }
  $row = $p['data'][0] ?? null;
  $profil = null;
  if ($row && $row['jenis_kelamin'] !== null && $row['bmi'] !== null) {     // data tubuh sudah lengkap (aturan: semua atau tidak sama sekali)
    $profil = [
      'username' => $row['username'], 'jk' => $row['jenis_kelamin'], 'tgl_lahir' => $row['tgl_lahir'], 'bb' => (float)$row['berat_badan'],
      'tb' => (float)$row['tinggi_badan'], 'goal' => (float)$row['target_bb'], 'lp' => (float)$row['lingkar_perut'], 'we' => (bool)$row['lingkar_perut_estimasi'],
      'id_aktivitas' => (int)$row['id_aktivitas'], 'pen' => (string)($row['riwayat_penyakit'] ?? ''), 'bmi' => (float)$row['bmi'],
      'bmr' => (float)$row['bmr'], 'tdee' => (float)$row['tdee'], 'target' => (float)$row['target_kalori_harian'], 'id_status' => (int)$row['id_status'],
    ];
  }
  $progres = [];
  foreach (array_reverse($c['data']) as $x)
    $progres[] = ['d' => $x['tgl_pencatatan'], 'bb' => $x['bb_terbaru'] !== null ? (float)$x['bb_terbaru'] : null, 'lp' => $x['lingkar_perut'] !== null ? (float)$x['lingkar_perut'] : null];
  return ['profil' => $profil, 'progres' => $progres];
}

/* ---------- Halaman ---------- */
function gend_csrf(): string {
  if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(16));
  return $_SESSION['csrf'];
}
function gend_json(array $data, int $kode = 200): void {
  http_response_code($kode);
  header('Content-Type: application/json; charset=utf-8');
  echo json_encode($data, JSON_UNESCAPED_UNICODE);
  exit;
}
/** Untuk endpoint API: wajib login, metode POST, dan token CSRF di header. Mengembalikan [token, uid, isi JSON]. */
function gend_api_awal(): array {
  if (session_status() === PHP_SESSION_NONE) session_start();
  if ($_SERVER['REQUEST_METHOD'] !== 'POST') gend_json(['ok' => false, 'error' => 'Metode tidak diizinkan.'], 405);
  if (empty($_SESSION['user_id'])) gend_json(['ok' => false, 'error' => 'Sesi berakhir. Silakan masuk lagi.', 'login' => true], 401);
  $csrf = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
  if (empty($_SESSION['csrf']) || !hash_equals($_SESSION['csrf'], $csrf)) gend_json(['ok' => false, 'error' => 'Permintaan tidak valid. Muat ulang halaman.'], 403);
  $token = sb_token();
  if (!$token) gend_json(['ok' => false, 'error' => 'Sesi berakhir. Silakan masuk lagi.', 'login' => true], 401);
  $isi = json_decode(file_get_contents('php://input') ?: '', true);
  if (!is_array($isi)) gend_json(['ok' => false, 'error' => 'Data tidak terbaca.'], 400);
  return [$token, (string)$_SESSION['user_id'], $isi];
}

/**
 * Dipanggil di awal halaman yang butuh login. $butuhData = true: bila data tubuh belum diisi,
 * pengguna diarahkan ke halaman Deteksi Obesitas lebih dulu.
 */
function gend_boot(bool $butuhData = false): array {
  if (empty($_SESSION['user_id'])) { header('Location: login.php'); exit; }
  $token = sb_token();
  if (!$token) { session_unset(); header('Location: login.php'); exit; }
  $uid = (string)$_SESSION['user_id'];
  $ref = gend_ref();
  $st  = gend_state($token, $uid);
  if ($ref === null || $st === null) {
    http_response_code(503);
    echo '<!doctype html><meta charset="utf-8"><title>GEND-OFF</title><body style="font-family:sans-serif;padding:40px"><h2>Data belum bisa dimuat</h2><p>Terjadi gangguan saat menghubungi database. <a href="">Coba muat ulang</a>.</p></body>';
    exit;
  }
  if ($butuhData && $st['profil'] === null) { header('Location: deteksi_obesitas.php?mulai=1'); exit; }
  return ['uid' => $uid, 'csrf' => gend_csrf(), 'ref' => $ref, 'profil' => $st['profil'], 'progres' => $st['progres']];
}

/** Menghasilkan <script> yang membuat window.GEND dan mengisi localStorage dari database. Taruh di <head>. */
function gend_boot_script(array $G): string {
  $json = json_encode($G, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
  $js = file_get_contents(__DIR__ . '/gend_boot.js');
  return "<script>window.GEND=$json;</script>\n<script>\n$js\n</script>\n";
}
