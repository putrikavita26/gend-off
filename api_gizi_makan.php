<?php
/** API pencatatan makanan gizi: semua data dibatasi oleh sesi dan RLS pengguna. */
require_once __DIR__ . '/gend_core.php';
[$token, $uid, $in] = gend_api_awal();

function gizi_api_error(array $r, string $context): void {
  sb_log($context, $r);
  if (($r['status'] ?? 0) === 404) {
    gend_json([
      'ok' => false,
      'setup_required' => true,
      'error' => 'Tabel pencatatan belum tersedia. Jalankan supabase_gizi_planner.sql di SQL Editor Supabase.',
    ], 503);
  }
  gend_json(['ok' => false, 'error' => 'Gagal mengakses data makanan. Coba lagi nanti.'], 502);
}

function gizi_num($value, string $label, float $min, float $max, bool $nullable = false): ?float {
  if ($nullable && ($value === null || $value === '')) return null;
  if (!is_numeric($value)) gend_json(['ok' => false, 'error' => $label . ' harus berupa angka.'], 422);
  $number = (float)$value;
  if (!is_finite($number) || $number < $min || $number > $max)
    gend_json(['ok' => false, 'error' => $label . ' harus antara ' . $min . ' dan ' . $max . '.'], 422);
  return $number;
}

function gizi_food_row(array $r): ?array {
  if (!sb_ok($r) || !is_array($r['data'])) return null;
  return $r['data'][0] ?? null;
}

$action = (string)($in['action'] ?? '');
$meals = ['sarapan', 'makan_siang', 'makan_malam', 'snack'];

if ($action === 'load') {
  $foods = sb_rest('GET', 'makanan_custom_user', $token, [
    'select' => 'id,nama_makanan,kalori_per_100g,protein_per_100g,karbo_per_100g,lemak_per_100g,serat_per_100g,sumber_data',
    'order' => 'nama_makanan.asc',
    'limit' => 500,
  ]);
  if (!sb_ok($foods) || !is_array($foods['data'])) gizi_api_error($foods, 'gizi_custom_load');

  $logs = sb_rest('GET', 'catatan_makan', $token, [
    'select' => 'id,tanggal,jenis_waktu,nama_makanan,gram,kalori_kcal,protein_g,karbo_g,lemak_g,serat_g,sumber,metode_pengolahan,id_makanan_katalog,makanan_custom_id',
    'tanggal' => 'eq.' . date('Y-m-d'),
    'order' => 'created_at.asc',
    'limit' => 500,
  ]);
  if (!sb_ok($logs) || !is_array($logs['data'])) gizi_api_error($logs, 'gizi_log_load');
  gend_json(['ok' => true, 'foods' => $foods['data'], 'logs' => $logs['data'], 'date' => date('Y-m-d')]);
}

if ($action === 'custom_add') {
  $name = trim((string)($in['name'] ?? ''));
  if ($name === '' || mb_strlen($name) > 100)
    gend_json(['ok' => false, 'error' => 'Nama makanan wajib diisi (maksimal 100 karakter).'], 422);

  $row = [
    'user_id' => $uid,
    'nama_makanan' => $name,
    'kalori_per_100g' => gizi_num($in['kcal'] ?? null, 'Kalori', 0, 1000),
    'protein_per_100g' => gizi_num($in['protein'] ?? null, 'Protein', 0, 100),
    'karbo_per_100g' => gizi_num($in['carbohydrate'] ?? null, 'Karbohidrat', 0, 100),
    'lemak_per_100g' => gizi_num($in['fat'] ?? null, 'Lemak', 0, 100),
    'serat_per_100g' => gizi_num($in['fiber'] ?? null, 'Serat', 0, 100, true),
    'sumber_data' => trim((string)($in['source'] ?? '')) ?: null,
  ];
  if ($row['sumber_data'] !== null && mb_strlen($row['sumber_data']) > 200)
    gend_json(['ok' => false, 'error' => 'Sumber data maksimal 200 karakter.'], 422);

  $r = sb_rest('POST', 'makanan_custom_user', $token, [], $row, 'return=representation');
  if (!sb_ok($r) || !is_array($r['data']) || empty($r['data'][0])) gizi_api_error($r, 'gizi_custom_add');
  gend_json(['ok' => true, 'food' => $r['data'][0]]);
}

if ($action === 'log_add') {
  $meal = (string)($in['meal'] ?? '');
  if (!in_array($meal, $meals, true)) gend_json(['ok' => false, 'error' => 'Waktu makan tidak valid.'], 422);
  $source = (string)($in['source'] ?? '');
  $foodId = trim((string)($in['food_id'] ?? ''));
  $methodId = trim((string)($in['method_id'] ?? ''));
  if (!preg_match('/^[A-Za-z0-9_-]{1,80}$/', $foodId) || !in_array($source, ['katalog', 'custom'], true))
    gend_json(['ok' => false, 'error' => 'Pilih makanan yang valid.'], 422);
  if ($source === 'custom' && !preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[1-8][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i', $foodId))
    gend_json(['ok' => false, 'error' => 'Makanan pribadi tidak valid.'], 422);
  if ($methodId !== '' && !preg_match('/^[A-Za-z0-9_-]{1,80}$/', $methodId))
    gend_json(['ok' => false, 'error' => 'Metode pengolahan tidak valid.'], 422);
  $grams = gizi_num($in['grams'] ?? null, 'Jumlah gram', 1, 3000);
  $methodName = null;

  if ($source === 'custom') {
    if ($methodId !== '') gend_json(['ok' => false, 'error' => 'Makanan pribadi tidak menggunakan metode katalog.'], 422);
    $foodResult = sb_rest('GET', 'makanan_custom_user', $token, [
      'select' => 'id,nama_makanan,kalori_per_100g,protein_per_100g,karbo_per_100g,lemak_per_100g,serat_per_100g',
      'id' => 'eq.' . $foodId,
      'limit' => 1,
    ]);
    if (!sb_ok($foodResult) || !is_array($foodResult['data'])) gizi_api_error($foodResult, 'gizi_custom_lookup');
    $food = $foodResult['data'][0] ?? null;
    if (!$food) gend_json(['ok' => false, 'error' => 'Makanan pribadi tidak ditemukan. Muat ulang halaman.'], 404);
    $ratio = $grams / 100;
    $name = (string)$food['nama_makanan'];
    $kcal = (float)$food['kalori_per_100g'] * $ratio;
    $protein = (float)$food['protein_per_100g'] * $ratio;
    $carbohydrate = (float)$food['karbo_per_100g'] * $ratio;
    $fat = (float)$food['lemak_per_100g'] * $ratio;
    $fiber = $food['serat_per_100g'] === null ? null : (float)$food['serat_per_100g'] * $ratio;
    $customId = (string)$food['id'];
    $catalogId = null;
  } else {
    $catalogResult = sb_rest('GET', 'katalog_makanan', $token, [
      'select' => 'id_makanan,nama_tampil,gram_porsi,bahan_mentah!inner(nama_bahan,kalori_per_gram,protein_per_gram,karbo_per_gram,lemak_per_gram,serat_per_gram),metode_pengolahan(id_metode,nama_metode,tambah_kkal,tambah_lemak)',
      'id_makanan' => 'eq.' . $foodId,
      'aktif' => 'eq.true',
      'limit' => 1,
    ]);
    if (!sb_ok($catalogResult) || !is_array($catalogResult['data'])) gizi_api_error($catalogResult, 'gizi_katalog_lookup');
    $food = gizi_food_row($catalogResult);
    if (!$food) gend_json(['ok' => false, 'error' => 'Makanan katalog tidak ditemukan atau sudah tidak aktif. Muat ulang halaman.'], 404);
    $ingredient = $food['bahan_mentah'] ?? null;
    if (is_array($ingredient) && isset($ingredient[0])) $ingredient = $ingredient[0];
    if (!is_array($ingredient) || (float)($food['gram_porsi'] ?? 0) <= 0)
      gend_json(['ok' => false, 'error' => 'Data gizi makanan katalog belum lengkap.'], 409);
    foreach (['kalori_per_gram', 'protein_per_gram', 'karbo_per_gram', 'lemak_per_gram'] as $nutrient) {
      if (!isset($ingredient[$nutrient]) || !is_numeric($ingredient[$nutrient]))
        gend_json(['ok' => false, 'error' => 'Data gizi makanan katalog belum lengkap.'], 409);
    }
    $method = null;
    if ($methodId !== '') {
      foreach (($food['metode_pengolahan'] ?? []) as $candidate) {
        if ((string)($candidate['id_metode'] ?? '') === $methodId) { $method = $candidate; break; }
      }
      if (!$method) gend_json(['ok' => false, 'error' => 'Metode pengolahan tidak ditemukan untuk makanan ini. Muat ulang halaman.'], 422);
      $methodName = (string)$method['nama_metode'];
    }
    $name = (string)($food['nama_tampil'] ?: $ingredient['nama_bahan']);
    $portionRatio = $grams / (float)$food['gram_porsi'];
    $kcal = (float)$ingredient['kalori_per_gram'] * $grams + ($method ? (float)$method['tambah_kkal'] * $portionRatio : 0);
    $protein = (float)$ingredient['protein_per_gram'] * $grams;
    $carbohydrate = (float)$ingredient['karbo_per_gram'] * $grams;
    $fat = (float)$ingredient['lemak_per_gram'] * $grams + ($method ? (float)$method['tambah_lemak'] * $portionRatio : 0);
    $fiber = $ingredient['serat_per_gram'] === null ? null : (float)$ingredient['serat_per_gram'] * $grams;
    $customId = null;
    $catalogId = (string)$food['id_makanan'];
  }

  $row = [
    'user_id' => $uid,
    'tanggal' => date('Y-m-d'),
    'jenis_waktu' => $meal,
    'nama_makanan' => $name,
    'gram' => round($grams, 2),
    'kalori_kcal' => round($kcal, 2),
    'protein_g' => round($protein, 2),
    'karbo_g' => round($carbohydrate, 2),
    'lemak_g' => round($fat, 2),
    'serat_g' => $fiber === null ? null : round($fiber, 2),
    'sumber' => $source,
    'metode_pengolahan' => $methodName,
    'id_makanan_katalog' => $catalogId,
    'makanan_custom_id' => $customId,
  ];
  $r = sb_rest('POST', 'catatan_makan', $token, [], $row, 'return=representation');
  if (!sb_ok($r) || !is_array($r['data']) || empty($r['data'][0])) gizi_api_error($r, 'gizi_log_add');
  gend_json(['ok' => true, 'log' => $r['data'][0]]);
}

if ($action === 'log_update') {
  $id = trim((string)($in['id'] ?? ''));
  if (!preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[1-8][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i', $id))
    gend_json(['ok' => false, 'error' => 'Catatan makan tidak valid.'], 422);
  $grams = gizi_num($in['grams'] ?? null, 'Jumlah gram', 1, 3000);
  $oldResult = sb_rest('GET', 'catatan_makan', $token, [
    'select' => 'id,gram,kalori_kcal,protein_g,karbo_g,lemak_g,serat_g',
    'id' => 'eq.' . $id,
    'limit' => 1,
  ]);
  if (!sb_ok($oldResult) || !is_array($oldResult['data'])) gizi_api_error($oldResult, 'gizi_log_lookup');
  $old = $oldResult['data'][0] ?? null;
  if (!$old || (float)$old['gram'] <= 0)
    gend_json(['ok' => false, 'error' => 'Catatan makan tidak ditemukan. Muat ulang halaman.'], 404);
  $ratio = $grams / (float)$old['gram'];
  $updated = [
    'gram' => round($grams, 2),
    'kalori_kcal' => round((float)$old['kalori_kcal'] * $ratio, 2),
    'protein_g' => round((float)$old['protein_g'] * $ratio, 2),
    'karbo_g' => round((float)$old['karbo_g'] * $ratio, 2),
    'lemak_g' => round((float)$old['lemak_g'] * $ratio, 2),
    'serat_g' => $old['serat_g'] === null ? null : round((float)$old['serat_g'] * $ratio, 2),
  ];
  $r = sb_rest('PATCH', 'catatan_makan', $token, ['id' => 'eq.' . $id], $updated, 'return=representation');
  if (!sb_ok($r) || !is_array($r['data']) || empty($r['data'][0])) gizi_api_error($r, 'gizi_log_update');
  gend_json(['ok' => true, 'log' => $r['data'][0]]);
}

if ($action === 'log_delete') {
  $id = trim((string)($in['id'] ?? ''));
  if (!preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[1-8][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i', $id))
    gend_json(['ok' => false, 'error' => 'Catatan makan tidak valid.'], 422);
  $r = sb_rest('DELETE', 'catatan_makan', $token, ['id' => 'eq.' . $id], null, 'return=representation');
  if (!sb_ok($r)) gizi_api_error($r, 'gizi_log_delete');
  if (!is_array($r['data']) || !$r['data']) gend_json(['ok' => false, 'error' => 'Catatan makan tidak ditemukan.'], 404);
  gend_json(['ok' => true]);
}

gend_json(['ok' => false, 'error' => 'Aksi tidak dikenali.'], 422);
