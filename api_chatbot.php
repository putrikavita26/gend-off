<?php
require_once __DIR__ . '/gend_core.php';
[, , $in] = gend_api_awal();

$messages = $in['messages'] ?? null;
if (!is_array($messages) || !$messages || count($messages) > 12) {
  gend_json(['ok' => false, 'error' => 'Riwayat percakapan tidak valid.'], 422);
}

$validMessages = [];
foreach ($messages as $message) {
  if (!is_array($message) || !in_array($message['role'] ?? '', ['user', 'assistant'], true)
      || !isset($message['content']) || !is_string($message['content'])) {
    gend_json(['ok' => false, 'error' => 'Format pesan tidak valid.'], 422);
  }
  $content = trim($message['content']);
  if ($content === '' || mb_strlen($content) > 2000) {
    gend_json(['ok' => false, 'error' => 'Pesan harus berisi 1-2000 karakter.'], 422);
  }
  $validMessages[] = ['role' => $message['role'], 'content' => $content];
}
if (end($validMessages)['role'] !== 'user') {
  gend_json(['ok' => false, 'error' => 'Pesan terakhir harus dari pengguna.'], 422);
}

$configFile = __DIR__ . '/ai_config.local.php';
$config = is_file($configFile) ? require $configFile : [];
if (!is_array($config)) {
  error_log('Konfigurasi Groq harus mengembalikan array.');
  gend_json(['ok' => false, 'error' => 'Konfigurasi AI tidak valid.'], 500);
}
$apiKey = trim((string)(getenv('GROQ_API_KEY') ?: ($config['api_key'] ?? '')));
$model = trim((string)($config['model'] ?? 'llama-3.3-70b-versatile'));
if ($apiKey === '') {
  gend_json(['ok' => false, 'error' => 'API key Groq belum dikonfigurasi. Isi GROQ_API_KEY atau api_key di ai_config.local.php.'], 503);
}
if (!preg_match('/^[A-Za-z0-9._-]+$/', $model)) {
  gend_json(['ok' => false, 'error' => 'Model Groq tidak valid.'], 500);
}
if (!function_exists('curl_init')) {
  gend_json(['ok' => false, 'error' => 'Ekstensi cURL PHP belum aktif.'], 503);
}

$payload = [
  'model' => $model,
  'messages' => array_merge([[
    'role' => 'system',
    'content' => 'Kamu adalah asisten edukasi gizi berbahasa Indonesia. Berikan informasi umum yang jelas, ramah, dan tidak menghakimi. Jangan mendiagnosis penyakit, meresepkan obat, atau mengklaim menggantikan dokter/ahli gizi. Untuk gejala berat, kondisi medis, kehamilan, gangguan makan, atau diet ekstrem, anjurkan berkonsultasi dengan tenaga kesehatan. Jika tidak yakin, katakan terus terang.',
  ]], $validMessages),
  'max_tokens' => 512,
  'temperature' => 0.6,
];

$url = 'https://api.groq.com/openai/v1/chat/completions';
$attempt = 0;
do {
  $curl = curl_init($url);
  curl_setopt_array($curl, [
    CURLOPT_POST => true,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => ['Content-Type: application/json', 'Authorization: Bearer ' . $apiKey],
    CURLOPT_POSTFIELDS => json_encode($payload, JSON_UNESCAPED_UNICODE),
    CURLOPT_CONNECTTIMEOUT => 10,
    CURLOPT_TIMEOUT => 120,
  ]);
  $body = curl_exec($curl);
  $curlErrorNumber = curl_errno($curl);
  $status = (int)curl_getinfo($curl, CURLINFO_HTTP_CODE);
  curl_close($curl);
  $attempt++;
  if ($body !== false && $status === 503 && $attempt < 2) usleep(800000);
} while ($body !== false && $status === 503 && $attempt < 2);

if ($body === false) {
  error_log('Permintaan Groq gagal dengan cURL error ' . $curlErrorNumber . '.');
  gend_json(['ok' => false, 'error' => 'Tidak dapat menghubungi Groq. Periksa koneksi internet server.'], 502);
}

$result = json_decode($body, true);
if ($status < 200 || $status >= 300 || !is_array($result)) {
  error_log('Groq mengembalikan HTTP ' . $status . '.');
  if ($status === 401 || $status === 403) {
    $error = 'API key Groq tidak valid atau tidak memiliki akses.';
  } elseif ($status === 404) {
    $error = 'Model Groq tidak ditemukan atau tidak tersedia. Periksa nama model di ai_config.local.php.';
  } elseif ($status === 429) {
    $error = 'Batas penggunaan Groq tercapai. Coba lagi nanti atau periksa kuota API.';
  } elseif ($status === 400) {
    $detail = (string)($result['error']['message'] ?? '');
    $detail = str_replace($apiKey, '[disembunyikan]', $detail);
    $detail = trim(preg_replace('/[\r\n\t]+/', ' ', $detail) ?? '');
    $detail = mb_substr($detail, 0, 240);
    $error = $detail !== ''
      ? 'Groq menolak permintaan: ' . $detail
      : 'Groq menolak permintaan. Periksa API key dan nama model.';
  } elseif ($status >= 500) {
    $detail = (string)($result['error']['message'] ?? '');
    $detail = str_replace($apiKey, '[disembunyikan]', $detail);
    $detail = trim(preg_replace('/[\r\n\t]+/', ' ', $detail) ?? '');
    $detail = mb_substr($detail, 0, 240);
    $error = $detail !== ''
      ? 'Layanan Groq gagal (HTTP ' . $status . '): ' . $detail
      : 'Layanan Groq sedang mengalami gangguan (HTTP ' . $status . '). Coba lagi nanti.';
  } else {
    $error = 'Groq gagal memproses pesan (HTTP ' . $status . '). Coba lagi.';
  }
  gend_json(['ok' => false, 'error' => $error], 502);
}

$reply = trim((string)($result['choices'][0]['message']['content'] ?? ''));
if ($reply === '') {
  error_log('Groq tidak mengembalikan teks jawaban.');
  gend_json(['ok' => false, 'error' => 'Groq belum memberikan jawaban. Coba ubah pertanyaanmu.'], 502);
}
gend_json(['ok' => true, 'reply' => $reply]);
