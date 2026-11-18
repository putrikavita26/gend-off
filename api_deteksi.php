<?php
/** POST JSON: jk, tgl_lahir, tb, bb, goal, wa, we, id_aktivitas, pen. Menghitung (sistem pakar) lalu menyimpan ke database. */
require_once __DIR__ . '/gend_core.php';
[$token, $uid, $in] = gend_api_awal();

$p = [
  'jk' => (string)($in['jk'] ?? ''), 'tgl_lahir' => (string)($in['tgl_lahir'] ?? ''),
  'tb' => (float)($in['tb'] ?? 0), 'bb' => (float)($in['bb'] ?? 0), 'goal' => (float)($in['goal'] ?? 0),
  'wa' => (float)($in['wa'] ?? 0), 'we' => !empty($in['we']), 'id_aktivitas' => (int)($in['id_aktivitas'] ?? 0),
  'pen' => trim((string)($in['pen'] ?? '')),
];
if ($p['goal'] < 30 || $p['goal'] > 200) gend_json(['ok' => false, 'error' => 'Target berat badan harus 30-200 kg.'], 422);
if ($p['wa'] < 40 || $p['wa'] > 200)     gend_json(['ok' => false, 'error' => 'Lingkar perut harus 40-200 cm.'], 422);
if (mb_strlen($p['pen']) > 200)          gend_json(['ok' => false, 'error' => 'Riwayat penyakit maksimal 200 karakter.'], 422);

$ref = gend_ref();
if (!$ref) gend_json(['ok' => false, 'error' => 'Aturan sistem pakar tidak dapat dimuat dari database.'], 503);
$hit = gend_hitung($p, $ref);
if (!$hit['ok']) gend_json(['ok' => false, 'error' => $hit['error']], 422);

$simpan = gend_simpan_deteksi($token, $uid, $p, $hit['hasil']);
if (!$simpan['ok']) gend_json(['ok' => false, 'error' => $simpan['error']], 502);
$state = gend_state($token, $uid);
if (!$state) gend_json(['ok' => false, 'error' => 'Data tersimpan, tetapi gagal dimuat ulang. Muat ulang halaman.'], 502);
gend_json(['ok' => true, 'hasil' => $hit['hasil'], 'state' => $state]);
