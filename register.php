<?php
// GEND-OFF — Halaman Daftar
// Letakkan di: htdocs/gend-off/register.php | aset di: assets/
session_start();
if (isset($_SESSION['user_id'])) { header('Location: dashboard.php'); exit; }
if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(16));

/**
 * KERANGKA: ganti isi fungsi ini dengan INSERT ke Supabase / database.
 * - Cek dulu apakah username / email sudah dipakai.
 * - Simpan kata sandi dengan password_hash($password, PASSWORD_DEFAULT), JANGAN simpan teks asli.
 * Kembalikan ['ok'=>true] jika berhasil, atau ['ok'=>false,'error'=>'pesan','field'=>'username|email'] jika gagal.
 * Contoh bawaan di bawah hanya untuk uji tampilan.
 */
function daftar_user($username, $email, $password) {
  $hash = password_hash($password, PASSWORD_DEFAULT); // simpan $hash ke database
  if (strtolower($username) === 'demo') return ['ok'=>false,'error'=>'Username sudah dipakai, coba yang lain.','field'=>'username'];
  if (strtolower($email) === 'demo@gend-off.test') return ['ok'=>false,'error'=>'Email sudah terdaftar. Silakan masuk.','field'=>'email'];
  return ['ok'=>true];
}

$error = ''; $errField = ''; $username = ''; $email = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = trim($_POST['username'] ?? '');
  $email    = trim($_POST['email'] ?? '');
  $password = $_POST['password'] ?? '';
  $confirm  = $_POST['confirm'] ?? '';
  if (!hash_equals($_SESSION['csrf'], $_POST['csrf'] ?? '')) {
    $error = 'Sesi kedaluwarsa, silakan muat ulang halaman.';
  } elseif ($username === '' || $email === '' || $password === '' || $confirm === '') {
    $error = 'Semua kolom wajib diisi.';
  } elseif (!preg_match('/^[A-Za-z0-9_]{3,20}$/', $username)) {
    $error = 'Username 3–20 karakter: huruf, angka, atau garis bawah.'; $errField = 'username';
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error = 'Format email belum benar.'; $errField = 'email';
  } elseif (strlen($password) < 8) {
    $error = 'Kata sandi minimal 8 karakter.'; $errField = 'password';
  } elseif ($password !== $confirm) {
    $error = 'Konfirmasi kata sandi tidak sama.'; $errField = 'confirm';
  } else {
    $r = daftar_user($username, $email, $password);
    if ($r['ok']) {
      $_SESSION['csrf'] = bin2hex(random_bytes(16));
      header('Location: login.php?daftar=1'); exit;
    }
    $error = $r['error']; $errField = $r['field'] ?? '';
  }
}
function ef($n){ global $error,$errField; return ($error && ($errField===$n || $errField==='')) ? 'err' : ''; }
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Daftar — GEND-OFF</title>
<link rel="icon" href="assets/logo.png">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Bebas+Neue&family=Patrick+Hand&display=swap" rel="stylesheet">
<style>
:root{--dark:#0b3b36;--dark2:#12524a;--olive:#7a9a2e;--lime:#a4c23d;--soft:#f3f7ec;--line:#e2ead3;--text:#14302c;--muted:#5d716d;--red:#d93a40}
*{box-sizing:border-box;margin:0;padding:0;-webkit-tap-highlight-color:transparent}
body{font-family:'Plus Jakarta Sans',sans-serif;background:var(--dark);color:var(--text);min-height:100vh;min-height:100svh;display:grid;place-items:center;padding:24px;overflow-x:hidden;position:relative}
.bgfx{position:fixed;inset:0;z-index:0;overflow:hidden;pointer-events:none;background:var(--dark);transition:filter .7s}
.bgfx img,.bgfx video{position:absolute;inset:0;width:100%;height:100%;object-fit:cover}
.bgfx::after{content:"";position:absolute;inset:0;background:linear-gradient(135deg,rgba(6,34,31,.93) 0%,rgba(11,59,54,.8) 50%,rgba(18,82,74,.68) 100%)}
body:has(.left.sadbg) .bgfx{filter:grayscale(.6) brightness(.9)}
a{color:inherit;text-decoration:none}
a:focus,button:focus,input:focus{outline:none}
a:focus-visible,button:focus-visible{outline:2px solid var(--lime);outline-offset:3px}

.shell{position:relative;z-index:1;width:min(1100px,100%);display:grid;grid-template-columns:1.05fr .95fr;gap:0;align-items:stretch;background:#fff;border:5px solid #fff;border-radius:36px;overflow:hidden;box-shadow:0 24px 60px rgba(11,59,54,.22)}
.left{position:relative;overflow:hidden;isolation:isolate;display:flex;flex-direction:column;align-items:center;justify-content:flex-end;padding:26px 0 0;text-align:center;min-height:620px}
.left>*:not(.scene){position:relative;z-index:1}
.left svg#mascot{display:block;width:100%;height:auto;overflow:visible;transform:scale(1.08);transform-origin:bottom center}

/* gelembung ucapan */
.cap{--bg:#fff;--ink:#14302c;position:relative;margin:6px 28px -3.5%;padding:18px 26px;max-width:290px;display:flex;flex-direction:column;align-items:center;justify-content:center;text-align:center;color:var(--ink);background:var(--bg);border:2.5px solid var(--ink);border-radius:28px;box-shadow:0 12px 26px rgba(11,59,54,.22);
  font-family:'Patrick Hand','Comic Sans MS',cursive;font-size:1.2rem;line-height:1.25;transform-origin:50% 100%;animation:popb .55s cubic-bezier(.2,1.6,.4,1) both,floaty 3.2s ease-in-out .55s infinite;z-index:2}
.cap::before,.cap::after{content:"";position:absolute;left:50%;top:100%;border:solid transparent}
.cap::before{margin:-1px 0 0 -14px;border-width:24px 14px 0;border-top-color:var(--ink)}
.cap::after{margin:-4px 0 0 -11px;border-width:20px 11px 0;border-top-color:var(--bg)}
.cap small{display:block;margin-top:3px;font-family:'Plus Jakarta Sans',sans-serif;font-weight:700;font-size:.7rem;color:var(--muted)}
.cap.pulse{animation:popb .4s cubic-bezier(.2,1.6,.4,1) both,floaty 3.2s ease-in-out .4s infinite}
.cap.sad{--bg:#ffe3e5;animation:popb .55s cubic-bezier(.2,1.6,.4,1) both,shakeb .5s ease-in-out .55s 3,floaty 3.2s ease-in-out 2.05s infinite}
@keyframes popb{from{transform:scale(.3);opacity:0}to{transform:scale(1);opacity:1}}
@keyframes floaty{0%,100%{transform:translateY(0)}50%{transform:translateY(-5px)}}
@keyframes shakeb{25%{transform:translateX(-9px)}75%{transform:translateX(9px)}}

/* LATAR: taman saat golden hour (dibuat berlapis, berkedalaman, tanpa tampilan kartun) */
.scene{position:absolute;inset:0;z-index:0;overflow:hidden;background:#d8c9a6;transition:filter .7s}
.scene svg#bg{position:absolute;inset:-14px;width:calc(100% + 28px);height:calc(100% + 28px)}
.scene .px{transition:transform .6s cubic-bezier(.2,.7,.2,1)}
.scene canvas{position:absolute;inset:0;width:100%;height:100%;pointer-events:none}
.scene::after{content:"";position:absolute;inset:0;pointer-events:none;
  background:radial-gradient(ellipse at 76% 30%,rgba(255,236,170,.30),transparent 55%),radial-gradient(ellipse at 50% 50%,transparent 55%,rgba(20,35,15,.34) 100%),linear-gradient(180deg,transparent 60%,rgba(20,40,10,.22))}
.left.sadbg .scene{filter:grayscale(.7) brightness(.86)}

.card{position:relative;overflow:hidden;background:linear-gradient(160deg,#fff 0%,#fbfdf6 60%,#f1f7e4 100%);padding:32px 44px;display:flex;flex-direction:column;justify-content:center;animation:rise .7s cubic-bezier(.2,.7,.2,1) both}
.card::before{content:"";position:absolute;top:-90px;right:-90px;width:270px;height:270px;border-radius:50%;background:radial-gradient(circle,rgba(240,138,36,.24) 0,rgba(240,138,36,0) 70%);pointer-events:none}
.card::after{content:"";position:absolute;bottom:-110px;left:-80px;width:290px;height:290px;border-radius:50%;background:radial-gradient(circle,rgba(18,82,74,.11) 0,rgba(18,82,74,0) 70%);pointer-events:none}
.card>*{position:relative;z-index:1;animation:up .6s cubic-bezier(.2,.7,.2,1) both}
.card>*:nth-child(1){animation-delay:.10s}.card>*:nth-child(2){animation-delay:.18s}.card>*:nth-child(3){animation-delay:.26s}.card>*:nth-child(5){animation-delay:.34s}.card>*:nth-child(6){animation-delay:.42s}.card>*:nth-child(7){animation-delay:.5s}.card>*:nth-child(8){animation-delay:.56s}
@keyframes up{from{opacity:0;transform:translateY(16px)}}
@keyframes rise{from{opacity:0;transform:translateY(30px)}}
.brand{display:flex;align-items:center;gap:10px;margin-bottom:14px;padding-bottom:14px;border-bottom:1px dashed rgba(240,138,36,.45)}
.brand img{width:56px;height:56px;object-fit:contain}
.brand b{font-size:1.5rem;font-weight:800;color:var(--dark);line-height:1}
.brand b span{color:var(--olive)}
.brand small{display:block;font-size:.62rem;letter-spacing:1.5px;text-transform:uppercase;color:var(--muted);font-weight:600;margin-top:4px}
h1{font-family:'Bebas Neue';font-weight:400;font-size:2.55rem;color:var(--dark);line-height:1}
h1 span{background:linear-gradient(90deg,#f08a24,#e3a21b);-webkit-background-clip:text;background-clip:text;color:transparent}
.sub{color:var(--muted);font-size:.93rem;font-weight:500;margin:6px 0 14px}
.alert{padding:12px 16px;border-radius:14px;background:#fdecee;color:var(--red);font-size:.88rem;font-weight:600;margin-bottom:16px;border:1px solid #f6c5c8;display:none}
.alert.on{display:block;animation:shakeX .5s}
@keyframes shakeX{20%,60%{transform:translateX(-7px)}40%,80%{transform:translateX(7px)}}
label{display:block;font-size:.82rem;font-weight:700;color:var(--dark);margin-bottom:6px}
.two{display:grid;grid-template-columns:1fr 1fr;gap:12px}
.field{margin-bottom:12px;position:relative}
.field .ico{position:absolute;left:15px;top:37px;width:19px;height:19px;color:#8aa06a;transition:.25s;pointer-events:none}
.field:focus-within .ico{color:var(--dark);transform:scale(1.12)}
input{width:100%;padding:13px 16px 13px 44px;border-radius:16px;border:1.5px solid var(--line);background:#f6f9ee;font:inherit;font-size:.95rem;color:var(--text);transition:.25s;box-shadow:inset 0 1px 2px rgba(11,59,54,.05)}
input:hover{border-color:#c4d79c}
input:focus{border-color:var(--olive);background:#fff;box-shadow:0 0 0 4px rgba(122,154,46,.18),0 10px 22px rgba(11,59,54,.09);transform:translateY(-1px)}
.field.err input{border-color:var(--red);background:#fff}
.field.ok input{border-color:var(--olive)}
.field.ok .ico{color:var(--olive)}
.hint{display:block;margin-top:4px;font-size:.72rem;color:var(--muted);min-height:1em}
.eye-btn{position:absolute;top:30px;right:9px;border:0;background:rgba(122,154,46,.12);color:var(--olive);font:inherit;font-size:.8rem;font-weight:700;padding:6px 10px;border-radius:10px;cursor:pointer}
.eye-btn:hover{background:rgba(122,154,46,.25)}
.has-eye input{padding-right:92px}
.meter{display:grid;grid-template-columns:repeat(4,1fr);gap:4px;margin-top:7px}
.meter i{height:5px;border-radius:99px;background:var(--line);transition:.3s}
.meter[data-s="1"] i:nth-child(-n+1){background:#e5484d}
.meter[data-s="2"] i:nth-child(-n+2){background:#f5b82e}
.meter[data-s="3"] i:nth-child(-n+3){background:#a4c23d}
.meter[data-s="4"] i:nth-child(-n+4){background:#3fa66a}
.btn{width:100%;display:flex;align-items:center;justify-content:center;gap:10px;margin-top:6px;padding:16px;border:0;border-radius:999px;background:linear-gradient(135deg,var(--dark),var(--dark2));color:#fff;font:inherit;font-weight:700;font-size:1rem;cursor:pointer;position:relative;overflow:hidden;transition:.3s;box-shadow:0 14px 26px rgba(11,59,54,.30)}
.btn:hover{transform:translateY(-3px);box-shadow:0 18px 32px rgba(11,59,54,.38)}
.btn:active{transform:translateY(0)}
.btn::after{content:"";position:absolute;top:0;left:-80%;width:50%;height:100%;background:linear-gradient(100deg,transparent,rgba(255,255,255,.35),transparent);transform:skewX(-20deg);transition:.6s}
.btn:hover::after{left:130%}
.btn .arr{display:inline-block;transition:transform .3s}
.btn:hover .arr{transform:translateX(6px)}
.perks{display:grid;grid-template-columns:repeat(3,1fr);gap:8px;margin-top:16px}
.perks span{display:flex;flex-direction:column;align-items:center;gap:3px;padding:9px 6px;border-radius:14px;background:#fff;border:1px solid var(--line);font-size:.7rem;font-weight:700;color:var(--dark);text-align:center;box-shadow:0 4px 10px rgba(11,59,54,.06)}
.perks b{font-size:1.15rem;line-height:1}
.alt{text-align:center;margin-top:14px;padding:12px;border-radius:16px;font-size:.9rem;color:var(--muted);background:rgba(240,138,36,.09);border:1px dashed rgba(240,138,36,.5)}
.alt a{color:var(--olive);font-weight:800}.alt a:hover{color:var(--dark)}
.back{display:block;text-align:center;margin-top:12px;font-size:.84rem;font-weight:600;color:var(--muted);transition:.2s}
.back:hover{color:var(--dark);transform:translateX(-4px)}

/* ===== maskot (gerakan seperti halaman login) ===== */
.pupil{transition:transform .12s ease-out}
.m-sad,.tear{display:none}
.tear{fill:#7ec8f5}
svg.sad .m-sad{display:inline}
svg.sad .tear{display:block;animation:tear 1.1s ease-in infinite}
@keyframes tear{from{transform:translateY(0);opacity:1}to{transform:translateY(20px);opacity:0}}
.hop{--h:76px;--k:1;cursor:pointer;animation:hopn 1.6s linear infinite}
.hop.w{--h:44px;animation-duration:1.35s;animation-delay:-.5s}
.hop.b{--h:30px;animation-duration:1.25s;animation-delay:-.9s}
@keyframes hopn{
  0%{transform:translateY(0) scale(1.03,.96) rotate(0deg);animation-timing-function:ease-out}
  8%{transform:translateY(0) scale(1.1,.88) rotate(0deg);animation-timing-function:cubic-bezier(.1,.7,.3,1)}
  40%{transform:translateY(calc(var(--h) * var(--k) * -1)) scale(.96,1.05) rotate(-1.5deg);animation-timing-function:cubic-bezier(.6,0,.9,.5)}
  72%{transform:translateY(0) scale(.97,1.04) rotate(0deg);animation-timing-function:ease-out}
  80%{transform:translateY(0) scale(1.12,.86) rotate(0deg);animation-timing-function:ease-out}
  90%{transform:translateY(-6px) scale(.99,1.02) rotate(0deg);animation-timing-function:ease-in}
  100%{transform:translateY(0) scale(1.03,.96) rotate(0deg)}
}
.shd,.shx{animation:shn 1.6s linear infinite}
.shx{animation-duration:1.35s;animation-delay:-.5s}
.shx.b{animation-duration:1.25s;animation-delay:-.9s}
@keyframes shn{
  0%{transform:scale(1.03);opacity:1;animation-timing-function:ease-out}
  8%{transform:scale(1.08);opacity:1;animation-timing-function:cubic-bezier(.1,.7,.3,1)}
  40%{transform:scale(.6);opacity:.45;animation-timing-function:cubic-bezier(.6,0,.9,.5)}
  72%{transform:scale(1);opacity:1;animation-timing-function:ease-out}
  80%{transform:scale(1.1);opacity:1}
  100%{transform:scale(1.03);opacity:1}
}
/* bersemangat saat mengetik / bersorak saat sandi kuat */
svg.excited .hop{--k:1.25}
svg.cheer .hop{--k:1.5;animation-duration:.95s}
svg.cheer .shd,svg.cheer .shx{animation-duration:.95s}
.hop.big{animation:bigjump .75s cubic-bezier(.3,1.4,.5,1) 1}
@keyframes bigjump{0%{transform:translateY(0) scale(1.1,.85)}40%{transform:translateY(-130px) scale(.92,1.1) rotate(-6deg)}75%{transform:translateY(0) scale(1.14,.84)}100%{transform:translateY(0) scale(1)}}
.eye{transform-box:fill-box;transform-origin:center;animation:blink 5s ease-in-out infinite}
.eye.e2{animation-delay:.6s}.eye.e3{animation-delay:1.7s}.eye.e4{animation-delay:2.4s}
@keyframes blink{0%,93%,100%{transform:scaleY(1)}96%{transform:scaleY(.08)}}
svg.sad .shx,svg.sad .shd{animation:none}
svg.sad .char{animation:headno .45s ease-in-out 6}
@keyframes headno{0%,100%{transform:rotate(0)}25%{transform:rotate(-7deg)}75%{transform:rotate(7deg)}}

@media(max-width:900px){
  .shell{grid-template-columns:1fr}
  .left{padding-top:18px;min-height:560px}
  .cap{font-size:1.05rem;padding:14px 20px;max-width:78%;margin:6px 16px -3.5%}
  .card{padding:32px 24px}
  .left svg#mascot{transform:scale(1)}
}
@media(max-width:480px){.two{grid-template-columns:1fr}}
@media(prefers-reduced-motion:reduce){*{animation:none!important;transition:none!important}}
</style>
</head>
<body>
<div class="bgfx">
  <img src="assets/buahsayur.png" alt="">
  <video autoplay muted loop playsinline poster="assets/buahsayur.png"><source src="assets/video_index3.mp4" type="video/mp4"></video>
</div>
<div class="shell">

  <!-- KIRI: maskot -->
  <div class="left <?= $error ? 'sadbg' : '' ?>">
    <div class="scene" id="scene">
      <svg id="bg" viewBox="0 0 800 700" preserveAspectRatio="xMidYMax slice" aria-hidden="true"></svg>
      <canvas id="pf"></canvas>
    </div>

    <div class="cap <?= $error ? 'sad' : '' ?>" id="cap"><span id="capTxt">Ayo gabung, kami sudah menunggumu! 🎉</span></div>

    <svg id="mascot" viewBox="0 0 800 500" class="<?= $error ? 'sad' : '' ?>" role="img" aria-label="Maskot sayuran: alpukat, wortel, dan bit">
      <defs>
        <linearGradient id="lawn" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#a7bf55"/><stop offset=".3" stop-color="#7ea341"/><stop offset="1" stop-color="#3b6329"/></linearGradient>
        <filter id="fblur"><feGaussianBlur stdDeviation="2.8"/></filter>
        <filter id="mblur"><feGaussianBlur stdDeviation=".7"/></filter>
        <filter id="soft" x="-30%" y="-30%" width="160%" height="160%"><feGaussianBlur stdDeviation="2.2"/></filter>
        <filter id="grain" x="0" y="0" width="100%" height="100%"><feTurbulence type="fractalNoise" baseFrequency=".85" numOctaves="2" seed="9"/><feColorMatrix values="0 0 0 0 .2  0 0 0 0 .3  0 0 0 0 .1  0 0 0 .9 -.35"/></filter>
      </defs>
      <g id="lawnG"></g>

      <ellipse class="shx" style="transform-origin:160px 434px" cx="160" cy="434" rx="82" ry="9" fill="rgba(10,30,5,.4)" filter="url(#mblur)"/>
      <ellipse class="shd" style="transform-origin:395px 434px" cx="395" cy="434" rx="66" ry="9" fill="rgba(10,30,5,.4)" filter="url(#mblur)"/>
      <ellipse class="shx b" style="transform-origin:655px 434px" cx="655" cy="434" rx="72" ry="9" fill="rgba(10,30,5,.4)" filter="url(#mblur)"/>

      <!-- ALPUKAT -->
      <g class="char hop" style="transform-origin:160px 430px">
        <g transform="translate(33.3 126.0) scale(0.64)"><image href="assets/maskot_alpukat.webp" width="396" height="475"/></g>
        <g class="eye" data-cx="137.6" data-cy="242.5" data-m="3.94"><ellipse cx="137.6" cy="242.5" rx="11.4" ry="11.9" fill="#fff"/><g class="pupil"><circle cx="137.6" cy="242.5" r="6.7" fill="#3a2012"/><circle cx="137.6" cy="242.5" r="4.3" fill="#0d0805"/><circle cx="140.2" cy="239.9" r="2.0" fill="#fff"/></g></g>
        <g class="eye" data-cx="187.5" data-cy="254.0" data-m="3.94"><ellipse cx="187.5" cy="254.0" rx="11.4" ry="11.9" fill="#fff"/><g class="pupil"><circle cx="187.5" cy="254.0" r="6.7" fill="#3a2012"/><circle cx="187.5" cy="254.0" r="4.3" fill="#0d0805"/><circle cx="190.1" cy="251.4" r="2.0" fill="#fff"/></g></g>
        <ellipse class="m-sad" cx="158.1" cy="273.2" rx="22.5" ry="19.1" fill="#f7d46a" filter="url(#soft)"/>
        <path class="m-sad" d="M146.8 279.0Q158.1 260.7 169.4 279.0" fill="none" stroke="#4a1c0a" stroke-width="3.2" stroke-linecap="round"/>
        <path class="tear" d="M194.9 265.4q-6 9 0 13q6-4 0-13z"/>
      </g>
      <!-- WORTEL -->
      <g class="char hop w" style="transform-origin:395px 430px">
        <g transform="translate(302.1 81.7) scale(0.57)"><image href="assets/maskot_wortel.webp" width="326" height="611"/></g>
        <g class="eye e2" data-cx="377.9" data-cy="239.6" data-m="3.9"><ellipse cx="377.9" cy="239.6" rx="11.1" ry="11.6" fill="#fff"/><g class="pupil"><circle cx="377.9" cy="239.6" r="6.6" fill="#3a2012"/><circle cx="377.9" cy="239.6" r="4.2" fill="#0d0805"/><circle cx="380.4" cy="237.1" r="2.0" fill="#fff"/></g></g>
        <g class="eye e2" data-cx="419.5" data-cy="251.0" data-m="3.9"><ellipse cx="419.5" cy="251.0" rx="11.1" ry="11.6" fill="#fff"/><g class="pupil"><circle cx="419.5" cy="251.0" r="6.6" fill="#3a2012"/><circle cx="419.5" cy="251.0" r="4.2" fill="#0d0805"/><circle cx="422.0" cy="248.5" r="2.0" fill="#fff"/></g></g>
        <ellipse class="m-sad" cx="395.0" cy="268.7" rx="22.6" ry="18.4" fill="#f97a1e" filter="url(#soft)"/>
        <path class="m-sad" d="M383.7 274.3Q395.0 256.7 406.3 274.3" fill="none" stroke="#5a2a05" stroke-width="3.2" stroke-linecap="round"/>
        <path class="tear" d="M426.7 262.2q-6 9 0 13q6-4 0-13z"/>
      </g>
      <!-- BIT -->
      <g class="char hop b" style="transform-origin:655px 430px">
        <g transform="translate(553.0 124.3) scale(0.62)"><image href="assets/maskot_bit.webp" width="329" height="493"/></g>
        <g class="eye e3" data-cx="641.7" data-cy="299.2" data-m="3.38"><ellipse cx="641.7" cy="299.2" rx="10.0" ry="10.4" fill="#fff"/><g class="pupil"><circle cx="641.7" cy="299.2" r="5.9" fill="#3a2012"/><circle cx="641.7" cy="299.2" r="3.8" fill="#0d0805"/><circle cx="643.9" cy="297.0" r="1.8" fill="#fff"/></g></g>
        <g class="eye e3" data-cx="684.5" data-cy="310.3" data-m="3.6"><ellipse cx="684.5" cy="310.3" rx="10.5" ry="11.0" fill="#fff"/><g class="pupil"><circle cx="684.5" cy="310.3" r="6.2" fill="#3a2012"/><circle cx="684.5" cy="310.3" r="4.0" fill="#0d0805"/><circle cx="686.9" cy="307.9" r="1.9" fill="#fff"/></g></g>
        <ellipse class="m-sad" cx="659.0" cy="324.0" rx="19.1" ry="15.7" fill="#98203a" filter="url(#soft)"/>
        <path class="m-sad" d="M649.5 328.8Q659.0 313.8 668.5 328.8" fill="none" stroke="#2b0c15" stroke-width="3.2" stroke-linecap="round"/>
        <path class="tear" d="M691.3 320.9q-6 9 0 13q6-4 0-13z"/>
      </g>
      <g id="fgG"></g>
    </svg>
  </div>

  <!-- KANAN: form daftar -->
  <div class="card">
    <a href="index.php" class="brand"><img src="assets/logo.png" alt="GEND-OFF"><span><b>GEND<span>-OFF</span></b><small>Gizi-On, Gendut-Off</small></span></a>
    <h1>Buat Akun <span>Gend-Off!</span></h1>
    <p class="sub">Buat akun gratis dan mulai program diet defisit kalorimu.</p>

    <div class="alert <?= $error ? 'on' : '' ?>" id="alert" role="alert"><?= htmlspecialchars($error) ?></div>

    <form method="post" action="register.php" id="form" novalidate>
      <input type="hidden" name="csrf" value="<?= htmlspecialchars($_SESSION['csrf']) ?>">
      <div class="two">
        <div class="field <?= ef('username') ?>" data-f="username">
          <label for="username">Username</label>
          <input type="text" id="username" name="username" placeholder="mis. faaiz" autocomplete="username" maxlength="20" value="<?= htmlspecialchars($username) ?>"><svg class="ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="8" r="4"/><path d="M4 21c0-4 4-6 8-6s8 2 8 6"/></svg>
          <span class="hint">3–20 huruf/angka/_</span>
        </div>
        <div class="field <?= ef('email') ?>" data-f="email">
          <label for="email">Email</label>
          <input type="email" id="email" name="email" placeholder="nama@email.com" autocomplete="email" value="<?= htmlspecialchars($email) ?>"><svg class="ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="3"/><path d="M3.5 7.5 12 13l8.5-5.5"/></svg>
          <span class="hint"></span>
        </div>
      </div>
      <div class="field has-eye <?= ef('password') ?>" data-f="password">
        <label for="pw">Kata sandi</label>
        <input type="password" id="pw" name="password" placeholder="Minimal 8 karakter" autocomplete="new-password"><svg class="ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="4" y="11" width="16" height="10" rx="2.5"/><path d="M8 11V8a4 4 0 0 1 8 0v3"/></svg>
        <button type="button" class="eye-btn" data-for="pw">Lihat</button>
        <div class="meter" id="meter" data-s="0"><i></i><i></i><i></i><i></i></div>
        <span class="hint" id="pwHint">Gunakan huruf besar, angka, dan simbol.</span>
      </div>
      <div class="field has-eye <?= ef('confirm') ?>" data-f="confirm">
        <label for="cf">Ulangi kata sandi</label>
        <input type="password" id="cf" name="confirm" placeholder="Ketik ulang kata sandi" autocomplete="new-password"><svg class="ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="m8 12.5 2.8 2.8L16 9.5"/></svg>
        <button type="button" class="eye-btn" data-for="cf">Lihat</button>
        <span class="hint" id="cfHint"></span>
      </div>
      <button type="submit" class="btn">Buat Akun <span class="arr">→</span></button>
    </form>
    <div class="perks"><span><b>🆓</b>Gratis</span><span><b>🔒</b>Data aman</span><span><b>⚡</b>Cuma 1 menit</span></div>

    <p class="alt">Sudah punya akun? <a href="login.php">Masuk</a></p>
    <a href="index.php" class="back">← Kembali ke beranda</a>
  </div>
</div>

<script>
/* =========================================================
   LATAR REALISTIS — taman saat golden hour, dibangun berlapis
   (langit → pepohonan kabur → padang → bokeh → bunga dekat)
   dengan efek kedalaman (blur), parallax kursor, dan partikel.
   ========================================================= */
(function(){
  const svg=document.getElementById('bg');
  let sd=23;const R=()=>(sd=sd*16807%2147483647)/2147483647,rr=(a,b)=>a+R()*(b-a),pk=a=>a[Math.floor(R()*a.length)];
  const defs=`<defs>
    <linearGradient id="sk" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#6fa8cf"/><stop offset=".34" stop-color="#b3d3dc"/><stop offset=".58" stop-color="#f4e3c0"/><stop offset=".78" stop-color="#f6c98e"/><stop offset="1" stop-color="#e9a86a"/></linearGradient>
    <radialGradient id="sn"><stop offset="0" stop-color="#fffdf2"/><stop offset=".16" stop-color="#fff1bd" stop-opacity=".96"/><stop offset=".45" stop-color="#ffcb7c" stop-opacity=".5"/><stop offset="1" stop-color="#ffa94d" stop-opacity="0"/></radialGradient>
    <linearGradient id="ry" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#fff3c9" stop-opacity=".75"/><stop offset="1" stop-color="#fff3c9" stop-opacity="0"/></linearGradient>
    <linearGradient id="md" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#b3b86a"/><stop offset=".3" stop-color="#7f9f48"/><stop offset="1" stop-color="#3f6a2e"/></linearGradient>
    <linearGradient id="hz" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#ffe9c2" stop-opacity="0"/><stop offset=".5" stop-color="#ffe9c2" stop-opacity=".55"/><stop offset="1" stop-color="#ffe9c2" stop-opacity="0"/></linearGradient>
    <filter id="b2" x="-10%" y="-10%" width="120%" height="120%"><feGaussianBlur stdDeviation="1.8"/></filter>
    <filter id="b6" x="-10%" y="-10%" width="120%" height="120%"><feGaussianBlur stdDeviation="6"/></filter>
    <filter id="b14" x="-10%" y="-10%" width="120%" height="120%"><feGaussianBlur stdDeviation="14"/></filter>
    <filter id="b30" x="-20%" y="-20%" width="140%" height="140%"><feGaussianBlur stdDeviation="30"/></filter>
    <filter id="gr" x="0" y="0" width="100%" height="100%"><feTurbulence type="fractalNoise" baseFrequency=".9" numOctaves="2" seed="3"/><feColorMatrix values="0 0 0 0 .5  0 0 0 0 .45  0 0 0 0 .35  0 0 0 .55 -.18"/></filter>
  </defs>`;
  const SX=610,SY=240;
  // langit
  let sky=`<rect x="-80" y="-80" width="960" height="800" fill="url(#sk)"/><g filter="url(#b30)">`;
  for(let i=0;i<8;i++)sky+=`<ellipse cx="${rr(40,760)}" cy="${rr(30,230)}" rx="${rr(80,170)}" ry="${rr(14,34)}" fill="${pk(['#fff6ea','#ffe6d0','#f7efe6'])}" opacity="${rr(.25,.5)}"/>`;
  sky+=`</g><circle cx="${SX}" cy="${SY}" r="280" fill="url(#sn)"/><circle cx="${SX}" cy="${SY}" r="30" fill="#fffdf3" filter="url(#b6)"/>`;
  sky+=`<g filter="url(#b14)" opacity=".5">`;
  [[-60,0],[120,0],[260,0],[420,0],[640,60]].forEach(([x,t])=>{sky+=`<polygon points="${SX-10},${SY} ${SX+14},${SY} ${x+110},720 ${x-40},720" fill="url(#ry)" opacity="${rr(.35,.7)}"/>`});
  sky+=`</g>`;
  // pepohonan jauh (kabur)
  const dark=['#415f3f','#4c7248','#37573a','#5a8250','#6a8d4d'];
  let tr=`<g filter="url(#b14)" opacity=".75"><path d="M-80 420Q100 300 260 380T560 350T880 390V470H-80Z" fill="#8fa98a"/></g>`;
  tr+=`<g filter="url(#b6)"><rect x="-80" y="395" width="960" height="90" fill="#46663f"/>`;
  for(let x=-70;x<=870;x+=16){const cy=rr(335,392),r=rr(26,58);tr+=`<circle cx="${x+rr(-8,8)}" cy="${cy}" r="${r}" fill="${pk(dark)}" opacity=".95"/>`;
    if(x>300){tr+=`<circle cx="${x+r*.25}" cy="${cy-r*.3}" r="${r*.55}" fill="${pk(['#d9e68e','#f2e8a6','#c6dc78'])}" opacity="${rr(.18,.38)}"/>`}}
  tr+=`</g><rect x="-80" y="350" width="960" height="120" fill="url(#hz)" opacity=".8"/>`;
  // padang
  let md=`<rect x="-80" y="395" width="960" height="340" fill="url(#md)"/><g filter="url(#b14)">`;
  for(let i=0;i<34;i++){const lit=R()>.5;md+=`<ellipse cx="${rr(-40,840)}" cy="${rr(430,690)}" rx="${rr(50,120)}" ry="${rr(14,34)}" fill="${lit?'#d9d27a':'#2f5324'}" opacity="${lit?rr(.18,.34):rr(.18,.3)}"/>`}
  md+=`</g>`;
  // bokeh bunga jauh → tengah
  const fc=['#ffffff','#fff0f4','#f7a8c4','#ffd54f','#ff9a76','#d9a6f0','#fff3b0','#ffb7d1'];
  let bk=`<g filter="url(#b6)">`;
  for(let i=0;i<130;i++){const y=rr(420,520);bk+=`<circle cx="${rr(-30,830)}" cy="${y}" r="${rr(2.5,6.5)}" fill="${pk(fc)}" opacity="${rr(.55,.95)}"/>`}
  bk+=`</g><g filter="url(#b2)">`;
  for(let i=0;i<100;i++){const y=rr(515,630),s=(y-500)/130;bk+=`<circle cx="${rr(-30,830)}" cy="${y}" r="${rr(3.5,6)+s*4}" fill="${pk(fc)}" opacity="${rr(.7,.98)}"/>`}
  bk+=`</g><g filter="url(#b2)" opacity=".75">`;
  for(let i=0;i<16;i++){bk+=`<circle cx="${rr(380,800)}" cy="${rr(120,380)}" r="${rr(12,38)}" fill="none" stroke="#fff3c4" stroke-width="${rr(1,2.5)}" opacity="${rr(.25,.6)}"/><circle cx="${rr(380,800)}" cy="${rr(150,360)}" r="${rr(8,26)}" fill="#fff0b8" opacity="${rr(.08,.2)}"/>`}
  bk+=`</g>`;
  // bunga dekat (kelopak, sedikit kabur)
  let nr=`<g filter="url(#b2)">`;
  for(let i=0;i<20;i++){
    const x=rr(-20,820),y=rr(600,700),s=rr(1.4,2.5),c=pk(fc.slice(2)),w=c;
    nr+=`<g transform="translate(${x.toFixed(0)} ${y.toFixed(0)}) scale(${s.toFixed(2)})"><path d="M0 0Q3 22 0 46" stroke="#3b6b2c" stroke-width="2.2" fill="none"/>`;
    for(let k=0;k<6;k++)nr+=`<ellipse cx="0" cy="-8" rx="3.6" ry="7.5" fill="${w}" transform="rotate(${k*60})"/>`;
    nr+=`<circle r="3.6" fill="#f2b319"/></g>`;
  }
  nr+=`</g><rect x="-80" y="-80" width="960" height="800" filter="url(#gr)" opacity=".5" style="mix-blend-mode:overlay"/>`;
  svg.innerHTML=defs+
    `<g class="px" data-d=".15">${sky}</g><g class="px" data-d=".45">${tr}</g><g class="px" data-d=".9">${md}</g><g class="px" data-d="1.3">${bk}</g><g class="px" data-d="2.4">${nr}</g>`;

  // parallax kursor
  const px=[...svg.querySelectorAll('.px')];
  if(matchMedia('(hover:hover)').matches&&!matchMedia('(prefers-reduced-motion:reduce)').matches){
    addEventListener('mousemove',e=>{const x=e.clientX/innerWidth-.5,y=e.clientY/innerHeight-.5;
      px.forEach(g=>{const d=+g.dataset.d;g.style.transform=`translate(${(-x*d*11).toFixed(1)}px,${(-y*d*5).toFixed(1)}px)`})},{passive:true});
  }

  // partikel: serbuk sari & kelopak yang melayang pelan
  const cv=document.getElementById('pf'),ctx=cv.getContext('2d');
  if(matchMedia('(prefers-reduced-motion:reduce)').matches)return;
  let W,H,P=[];
  const fit=()=>{const r=cv.parentElement.getBoundingClientRect();W=cv.width=r.width;H=cv.height=r.height};
  fit();addEventListener('resize',fit);
  for(let i=0;i<46;i++)P.push({x:Math.random()*1000,y:Math.random()*800,r:rr(.8,3.2),vx:rr(.1,.45),vy:rr(-.08,.2),a:rr(.25,.8),ph:Math.random()*6,petal:R()>.82,rot:Math.random()*6,c:pk(['255,244,200','255,255,255','255,214,150','255,190,210'])});
  let t=0;
  (function loop(){
    if(!document.hidden){
      t+=.01;ctx.clearRect(0,0,W,H);
      P.forEach(p=>{
        p.x+=p.vx+Math.sin(t+p.ph)*.25;p.y+=p.vy+Math.cos(t*.8+p.ph)*.12+(p.petal?.22:0);p.rot+=.012;
        if(p.x>W+10){p.x=-10;p.y=Math.random()*H}if(p.y>H+10)p.y=-10;if(p.y<-12)p.y=H;
        ctx.globalAlpha=p.a*(.65+.35*Math.sin(t*2+p.ph));
        if(p.petal){ctx.save();ctx.translate(p.x,p.y);ctx.rotate(p.rot);ctx.fillStyle='rgba(255,196,214,.9)';ctx.beginPath();ctx.ellipse(0,0,5,2.6,0,0,6.3);ctx.fill();ctx.restore()}
        else{const g=ctx.createRadialGradient(p.x,p.y,0,p.x,p.y,p.r*3.2);g.addColorStop(0,`rgba(${p.c},1)`);g.addColorStop(1,`rgba(${p.c},0)`);ctx.fillStyle=g;ctx.beginPath();ctx.arc(p.x,p.y,p.r*3.2,0,6.3);ctx.fill()}
      });
    }
    requestAnimationFrame(loop);
  })();
})();

/* ===== rumput & bunga untuk pijakan maskot ===== */
(function(){
  let sd=5;const R=()=>(sd=sd*16807%2147483647)/2147483647,rr=(a,b)=>a+R()*(b-a),pk=a=>a[Math.floor(R()*a.length)];
  const blade=(x,y0,h,w,c)=>{const b=rr(-h*.35,h*.35);return `<path d="M${x.toFixed(1)} ${y0}Q${(x+b*.3).toFixed(1)} ${(y0-h*.6).toFixed(1)} ${(x+b).toFixed(1)} ${(y0-h).toFixed(1)}Q${(x+b*.5+w).toFixed(1)} ${(y0-h*.5).toFixed(1)} ${(x+w).toFixed(1)} ${y0}Z" fill="${c}"/>`};
  const back=['#5f8a37','#6f9b3f','#7fae48','#4f7a2f'],front=['#6a9a3c','#85b84d','#97c657','#5b8a33'],rim=['#dfe98f','#cfe07a'];
  let h=`<rect x="-400" y="408" width="1600" height="270" fill="url(#lawn)"/><rect x="-400" y="408" width="1600" height="270" filter="url(#grain)" opacity=".5"/><g filter="url(#mblur)">`;
  for(let i=0;i<300;i++)h+=blade(rr(-30,830),414,rr(8,26),rr(2.5,5),pk(back));
  h+=`</g>`;
  for(let i=0;i<190;i++)h+=blade(rr(-30,830),419,rr(10,32),rr(2.5,5),R()>.86?pk(rim):pk(front));
  document.getElementById('lawnG').innerHTML=h;
  // latar depan (kabur) + bunga
  let f=`<g filter="url(#fblur)">`;
  for(let i=0;i<46;i++)f+=blade(rr(-20,820),506,rr(40,115),rr(4,7),pk(['#37692a','#47803a','#2f5a24','#5a9440']));
  f+=`</g><g filter="url(#fblur)">`;
  const pc=['#ffffff','#ffd54f','#f48fb1','#ce93d8','#ff8a80'];
  for(let i=0;i<11;i++){const x=rr(10,790),y=rr(470,495),s=rr(.8,1.4),c=pk(pc);
    f+=`<g transform="translate(${x.toFixed(0)} ${y.toFixed(0)}) scale(${s.toFixed(2)})"><path d="M0 40Q-3 22 0 0" stroke="#3f7a2e" stroke-width="3" fill="none"/>`;
    for(let k=0;k<6;k++)f+=`<ellipse cx="0" cy="-8" rx="5" ry="8" fill="${c}" transform="rotate(${k*60})"/>`;
    f+=`<circle r="4.5" fill="#f5b800"/></g>`}
  f+=`</g>`;
  document.getElementById('fgG').innerHTML=f;
})();

/* ===== maskot ===== */
const cap=document.getElementById('cap'),left=document.querySelector('.left'),svg=document.getElementById('mascot'),capTxt=document.getElementById('capTxt');
const HAPPY='Ayo gabung, kami sudah menunggumu! 🎉';
let cheerOn=false;
const eyes=[...svg.querySelectorAll('.eye')].map(g=>({p:g.querySelector('.pupil'),x:+g.dataset.cx,y:+g.dataset.cy,m:+g.dataset.m}));
let target=null;
const toSvg=(cx,cy)=>{const pt=svg.createSVGPoint();pt.x=cx;pt.y=cy;return pt.matrixTransform(svg.getScreenCTM().inverse())};
function look(){
  const sad=svg.classList.contains('sad');
  eyes.forEach(e=>{
    let dx=0,dy=0;
    if(sad){dy=e.m*.8}
    else if(target){const vx=target.x-e.x,vy=target.y-e.y,d=Math.hypot(vx,vy)||1,k=Math.min(1,d/90);dx=vx/d*e.m*k;dy=vy/d*e.m*k}
    e.p.style.transform=`translate(${dx}px,${dy}px)`;
  });
}
const aim=(x,y)=>{target=toSvg(x,y);look()};
addEventListener('mousemove',e=>aim(e.clientX,e.clientY));
addEventListener('touchmove',e=>{const t=e.touches[0];aim(t.clientX,t.clientY)},{passive:true});

// klik maskot = lompat tinggi
svg.querySelectorAll('.hop').forEach(c=>c.addEventListener('click',()=>{
  if(svg.classList.contains('sad'))return;
  c.classList.remove('big');void c.getBoundingClientRect();c.classList.add('big');
  setTimeout(()=>c.classList.remove('big'),800);
}));

const tips={username:'Pilih username kerenmu!',email:'Tenang, emailmu aman bersama kami.',pw:'Bikin sandi yang kuat ya!',cf:'Ketik ulang sandimu, biar yakin.'};
function say(t){
  cap.classList.remove('pulse');cap.style.animation='none';void cap.offsetWidth;cap.style.animation='';
  if(!cap.classList.contains('sad'))cap.classList.add('pulse');
  capTxt.textContent=t;
}
let typingT;
document.querySelectorAll('input').forEach(i=>{
  i.addEventListener('focus',()=>{
    const r=i.getBoundingClientRect();aim(r.left+r.width/2,r.top+r.height/2);
    if(!svg.classList.contains('sad')&&tips[i.id])say(tips[i.id]);
  });
  i.addEventListener('input',()=>{ // maskot ikut bersemangat saat mengetik
    if(svg.classList.contains('sad'))return;
    svg.classList.add('excited');clearTimeout(typingT);typingT=setTimeout(()=>svg.classList.remove('excited'),900);
  });
});

function setSad(on,msg){
  svg.classList.remove('sad','cheer','excited');cheerOn=false;void svg.getBoundingClientRect();
  if(on)svg.classList.add('sad');
  left.classList.toggle('sadbg',on);
  cap.classList.remove('sad','pulse');cap.style.animation='none';void cap.offsetWidth;cap.style.animation='';cap.classList.toggle('sad',on);
  if(on){capTxt.innerHTML='Hmm, '+msg+'<small>Coba periksa lagi ya.</small>'}
  else{capTxt.textContent=HAPPY}
  look();
}
const lc=s=>s.charAt(0).toLowerCase()+s.slice(1);
<?php if ($error): ?>setSad(true,lc(<?= json_encode($error, JSON_UNESCAPED_UNICODE) ?>).replace(/\.$/,''));<?php endif; ?>

function clearErr(){
  document.querySelectorAll('.field').forEach(f=>f.classList.remove('err'));
  document.getElementById('alert').classList.remove('on');
}
document.querySelectorAll('input').forEach(i=>i.addEventListener('input',()=>{
  if(svg.classList.contains('sad')){setSad(false);clearErr()}
}));

/* kekuatan sandi + kecocokan */
const pw=document.getElementById('pw'),cf=document.getElementById('cf'),meter=document.getElementById('meter'),pwHint=document.getElementById('pwHint'),cfHint=document.getElementById('cfHint');
const labels=['Gunakan huruf besar, angka, dan simbol.','Masih lemah','Cukup','Kuat','Sangat kuat'];
function strength(v){
  let s=0;if(v.length>=8)s++;if(/[A-Z]/.test(v)&&/[a-z]/.test(v))s++;if(/\d/.test(v))s++;if(/[^A-Za-z0-9]/.test(v))s++;
  return v.length?Math.max(1,s):0;
}
function checkPw(){const s=strength(pw.value);meter.dataset.s=s;pwHint.textContent=labels[s];checkCf()}
function checkCf(){
  const f=cf.closest('.field');
  if(!cf.value){cfHint.textContent='';f.classList.remove('ok');return}
  const same=pw.value===cf.value;
  cfHint.textContent=same?'Kata sandi cocok':'Belum sama';
  f.classList.toggle('ok',same);
}
function updateCheer(){
  if(svg.classList.contains('sad'))return;
  const match=cf.value&&pw.value===cf.value,strong=strength(pw.value)>=4,on=!!(match||strong);
  svg.classList.toggle('cheer',on);
  if(on&&!cheerOn)say(match?'Cocok! Tinggal klik Buat Akun.':'Wih, sandimu kuat banget!');
  cheerOn=on;
}
pw.addEventListener('input',()=>{checkPw();updateCheer()});cf.addEventListener('input',()=>{checkCf();updateCheer()});

document.querySelectorAll('.eye-btn').forEach(b=>b.onclick=()=>{
  const el=document.getElementById(b.dataset.for),s=el.type==='password';
  el.type=s?'text':'password';b.textContent=s?'Sembunyi':'Lihat';
});

function fail(field,msg){
  const a=document.getElementById('alert');a.textContent=msg;a.classList.remove('on');void a.offsetWidth;a.classList.add('on');
  document.querySelectorAll('.field').forEach(f=>f.classList.toggle('err',f.dataset.f===field));
  setSad(true,lc(msg).replace(/\.$/,''));
}
document.getElementById('form').addEventListener('submit',e=>{
  const u=document.getElementById('username').value.trim(),m=document.getElementById('email').value.trim(),p=pw.value,c=cf.value;
  let bad=null;
  if(!u||!m||!p||!c)bad=['','Semua kolom wajib diisi.'];
  else if(!/^[A-Za-z0-9_]{3,20}$/.test(u))bad=['username','Username 3–20 karakter: huruf, angka, atau garis bawah.'];
  else if(!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(m))bad=['email','Format email belum benar.'];
  else if(p.length<8)bad=['password','Kata sandi minimal 8 karakter.'];
  else if(p!==c)bad=['confirm','Konfirmasi kata sandi tidak sama.'];
  if(bad){e.preventDefault();fail(bad[0],bad[1])}
});
look();
</script>
</body>
</html>