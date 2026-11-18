<?php
/**
 * POST JSON: bb (opsional, kg) dan/atau wa (opsional, cm). Mencatat progres hari ini.
 * Bila berat berubah, IMT/status/BMR/TDEE/target kalori dihitung ulang oleh sistem pakar dan profil diperbarui.
 */
require_once __DIR__ . '/gend_core.php';
[$token, $uid, $in] = gend_api_awal();

$bb = isset($in['bb']) && $in['bb'] !== '' ? (float)$in['bb'] : null;
$wa = isset($in['wa']) && $in['wa'] !== '' ? (float)$in['wa'] : null;
if ($bb === null && $wa === null) gend_json(['ok' => false, 'error' => 'Tidak ada data yang dicatat.'], 422);
if ($bb !== null && ($bb < 20 || $bb > 300)) gend_json(['ok' => false, 'error' => 'Berat badan harus 20-300 kg.'], 422);
if ($wa !== null && ($wa < 40 || $wa > 200)) gend_json(['ok' => false, 'error' => 'Lingkar perut harus 40-200 cm.'], 422);

$st = gend_state($token, $uid);
if (!$st || !$st['profil']) gend_json(['ok' => false, 'error' => 'Isi data tubuh di Deteksi Obesitas lebih dulu.'], 409);
$p = $st['profil'];
$ref = gend_ref();
if (!$ref) gend_json(['ok' => false, 'error' => 'Aturan sistem pakar tidak dapat dimuat dari database.'], 503);

$bbBaru = $bb ?? $p['bb'];
$hit = gend_hitung(['jk' => $p['jk'], 'tgl_lahir' => $p['tgl_lahir'], 'tb' => $p['tb'], 'bb' => $bbBaru, 'id_aktivitas' => $p['id_aktivitas']], $ref);
if (!$hit['ok']) gend_json(['ok' => false, 'error' => $hit['error']], 422);
$h = $hit['hasil'];

$ubah = ['berat_badan' => $bbBaru, 'bmi' => $h['bmi'], 'bmr' => $h['bmr'], 'tdee' => $h['tdee'], 'target_kalori_harian' => $h['target'], 'id_status' => $h['status']['id']];
if ($wa !== null) { $ubah['lingkar_perut'] = $wa; $ubah['lingkar_perut_estimasi'] = false; }
$r = sb_rest('PATCH', 'profil_user', $token, ['user_id' => 'eq.' . $uid], $ubah, 'return=minimal');
if (!sb_ok($r)) { sb_log('progres_profil', $r); gend_json(['ok' => false, 'error' => 'Gagal memperbarui profil.'], 502); }

$kolom = ['bb_terbaru' => $bbBaru, 'bmi' => $h['bmi'], 'bmr_recalculated' => $h['bmr'], 'tdee_recalculated' => $h['tdee'], 'id_status' => $h['status']['id']];
if ($wa !== null) $kolom['lingkar_perut'] = $wa;
$c = gend_catat_progres($token, $uid, $kolom);
if (!$c['ok']) gend_json(['ok' => false, 'error' => $c['error']], 502);

$state = gend_state($token, $uid);
if (!$state) gend_json(['ok' => false, 'error' => 'Data tersimpan, tetapi gagal dimuat ulang. Muat ulang halaman.'], 502);
gend_json(['ok' => true, 'hasil' => $h, 'state' => $state]);
