<?php
// GEND-OFF — Profil & Kelola Akun (halaman terpisah dari dashboard)
// Letakkan di: htdocs/gend-off/profil.php | aset di: assets/
session_start();

// Simulasi login sementara (Hapus/sesuaikan baris ini jika sudah terhubung database)
if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 1;
    $_SESSION['username'] = 'Pengguna';
}
// Aktifkan baris di bawah ini untuk proteksi halaman yang sebenarnya:
// if (!isset($_SESSION['user_id'])) { header('Location: login.php'); exit; }

$username = htmlspecialchars($_SESSION['username'] ?? 'Faaiz');
$inisial  = htmlspecialchars(mb_strtoupper(mb_substr($_SESSION['username'] ?? 'F', 0, 1)));
$email    = htmlspecialchars(strtolower($_SESSION['username'] ?? 'faaiz') . '@gend-off.com');
$tahun    = date('Y');
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Profil — GEND-OFF</title>
<link rel="icon" href="assets/logo.png">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=IBM+Plex+Mono:wght@500;600&display=swap" rel="stylesheet">
<style>
:root{
  --dark:#0b3b36;--dark2:#12524a;--dark3:#06221f;--olive:#7a9a2e;--lime:#a4c23d;
  --tint:#f1f6e4;--tint2:#e6efd0;--bg:#f6f8f4;--border:#e6ebe0;--border-soft:#eef2e9;
  --text:#14302c;--muted:#5d716d;--faint:#93a29e;--red:#d8452e;--red-bg:#fceae6;
  --radius:16px;--shadow:0 1px 2px rgba(11,59,54,.05),0 1px 1px rgba(11,59,54,.03);--shadow-md:0 10px 30px -10px rgba(11,59,54,.18);
  --sw:248px;--mono:'IBM Plex Mono',monospace;--ease:cubic-bezier(.2,.7,.2,1)
}
*{box-sizing:border-box;margin:0;padding:0;-webkit-tap-highlight-color:transparent}
body{font-family:'Plus Jakarta Sans',sans-serif;color:var(--text);-webkit-font-smoothing:antialiased;line-height:1.55;
  background:radial-gradient(900px 520px at 100% -6%,rgba(164,194,61,.17),transparent 60%),radial-gradient(760px 480px at -8% 16%,rgba(18,82,74,.10),transparent 55%),var(--bg);background-attachment:fixed}
a{color:inherit;text-decoration:none}
button,input{font-family:inherit;color:inherit}
button{cursor:pointer;border:0;background:none}
::-webkit-scrollbar{width:9px;height:9px}::-webkit-scrollbar-thumb{background:#cfd9c4;border-radius:8px}
:focus-visible{outline:2px solid var(--lime);outline-offset:2px}
h1,h2,h3{font-weight:800;color:var(--dark);line-height:1.25}

/* ===== LAYOUT ===== */
.app{display:flex;min-height:100vh}
.sidebar{width:var(--sw);flex-shrink:0;position:sticky;top:0;height:100vh;display:flex;flex-direction:column;z-index:80;color:#fff;overflow:hidden;
  background:linear-gradient(200deg,rgba(164,194,61,.18),transparent 38%),linear-gradient(165deg,var(--dark3) 0%,var(--dark) 52%,var(--dark2) 100%);
  box-shadow:12px 0 40px -20px rgba(6,34,31,.55);transition:transform .3s var(--ease)}
.sidebar::after{content:"";position:absolute;left:-60px;bottom:-80px;width:240px;height:240px;border-radius:50%;pointer-events:none;background:radial-gradient(circle,rgba(164,194,61,.32),transparent 68%);animation:breathe 6s ease-in-out infinite}
@keyframes breathe{50%{transform:scale(1.18);opacity:.7}}
.sidebar>*{position:relative;z-index:1}
.brand{display:flex;align-items:center;gap:12px;padding:20px 18px 18px;border-bottom:1px solid rgba(255,255,255,.1)}
.brand-mark{width:50px;height:50px;padding:4px;border-radius:14px;flex-shrink:0;display:grid;place-items:center;background:rgba(255,255,255,.96);box-shadow:0 8px 18px rgba(0,0,0,.25);transition:transform .35s var(--ease)}
.brand:hover .brand-mark{transform:rotate(-6deg) scale(1.06)}
.brand-mark img{width:100%;height:100%;object-fit:contain}
.brand b{display:block;font-size:1.2rem;font-weight:800;letter-spacing:.3px;color:#fff;line-height:1}
.brand b span{color:var(--lime)}
.brand small{display:block;font-size:.62rem;font-weight:600;letter-spacing:1.4px;text-transform:uppercase;color:rgba(255,255,255,.6);margin-top:5px}
.nav{flex:1;overflow-y:auto;padding:10px 12px 14px}
.nav::-webkit-scrollbar-thumb{background:rgba(255,255,255,.18)}
.nav-label{font-size:.66rem;text-transform:uppercase;letter-spacing:.9px;color:rgba(255,255,255,.4);font-weight:800;padding:16px 10px 6px}
.nav-item{position:relative;display:flex;align-items:center;gap:11px;width:100%;padding:10px 11px;margin:2px 0;border-radius:10px;font-size:.88rem;font-weight:600;color:rgba(255,255,255,.78);transition:background .15s,color .15s,transform .15s}
.nav-item:hover{background:rgba(255,255,255,.09);color:#fff;transform:translateX(3px)}
.nav-item.active{background:linear-gradient(90deg,rgba(164,194,61,.26),rgba(164,194,61,.07));color:#fff;font-weight:700;box-shadow:inset 0 0 0 1px rgba(164,194,61,.24)}
.nav-item.active::before{content:"";position:absolute;left:-12px;top:8px;bottom:8px;width:3px;border-radius:0 4px 4px 0;background:linear-gradient(180deg,var(--lime),var(--olive))}
.nav-item .ic{width:18px;height:18px;flex-shrink:0;display:flex}.nav-item .ic svg{width:100%;height:100%}
.nav-item.active .ic{color:var(--lime)}
.side-foot{padding:12px 14px 16px;border-top:1px solid rgba(255,255,255,.1)}
.logout{display:flex;align-items:center;gap:10px;padding:10px 12px;border-radius:10px;font-size:.86rem;font-weight:700;color:rgba(255,255,255,.85);border:1px solid rgba(255,255,255,.18);background:rgba(255,255,255,.06);transition:.15s}
.logout:hover{color:#ffd9d6;border-color:rgba(229,72,77,.5);background:rgba(229,72,77,.18)}
.logout svg{width:16px;height:16px}
.side-ver{font-family:var(--mono);font-size:.64rem;color:rgba(255,255,255,.4);margin:10px 4px 0;line-height:1.6}
.scrim{position:fixed;inset:0;background:rgba(11,59,54,.35);z-index:70;opacity:0;pointer-events:none;transition:.25s}

.main{flex:1;min-width:0;display:flex;flex-direction:column}
.topbar{position:sticky;top:0;z-index:50;display:flex;align-items:center;justify-content:space-between;gap:16px;padding:14px 30px;background:rgba(255,255,255,.82);backdrop-filter:blur(14px) saturate(160%);-webkit-backdrop-filter:blur(14px) saturate(160%);border-bottom:1px solid var(--border)}
.tb-l{display:flex;align-items:center;gap:12px}
.burger{display:none;width:40px;height:40px;border-radius:10px;border:1px solid var(--border);align-items:center;justify-content:center;background:#fff}
.burger svg{width:18px;height:18px}
.tb-title{font-size:1.15rem;font-weight:800;color:var(--dark)}
.tb-sub{font-size:.78rem;color:var(--muted)}
.tb-r{display:flex;align-items:center;gap:12px}
.clk{display:flex;align-items:center;gap:9px;padding:5px 13px 5px 6px;border-radius:11px;color:#fff;background:linear-gradient(150deg,var(--dark2),var(--dark3));border:1px solid rgba(255,255,255,.08);box-shadow:0 10px 20px -12px rgba(6,34,31,.75)}
.clk-ic{width:26px;height:26px;border-radius:8px;display:grid;place-items:center;flex-shrink:0;background:rgba(255,255,255,.12);color:var(--lime)}
.clk-ic svg{width:14px;height:14px}
.clk b{display:block;font-family:var(--mono);font-size:.8rem;font-weight:600;letter-spacing:.4px;line-height:1.2;color:#fff}
.clk small{display:block;font-size:.62rem;font-weight:600;color:rgba(255,255,255,.7);line-height:1.25}
.chip-user{display:flex;align-items:center;gap:10px;padding:5px 14px 5px 5px;border:1px solid var(--border);border-radius:99px;background:#fff;box-shadow:var(--shadow)}
.av{position:relative;width:32px;height:32px;border-radius:50%;display:grid;place-items:center;font-weight:800;font-size:.85rem;color:#fff;background:linear-gradient(155deg,var(--dark2),var(--dark))}
.av::after{content:"";position:absolute;right:-1px;bottom:-1px;width:10px;height:10px;border-radius:50%;background:#3fe08a;border:2px solid #fff}
.chip-user b{display:block;font-size:.82rem;color:var(--dark);line-height:1.2}
.chip-user small{font-size:.7rem;color:var(--faint)}

/* konten dipusatkan, satu kolom */
.content{padding:30px 24px 70px;max-width:760px;width:100%;margin:0 auto;display:flex;flex-direction:column;gap:18px}
.sec-h{text-align:center;margin-bottom:2px}
.sec-h h1{font-size:1.6rem;letter-spacing:-.3px}
.sec-h p{font-size:.88rem;color:var(--muted);margin-top:6px}
@keyframes rise{from{opacity:0;transform:translateY(14px)}}
.rise{animation:rise .55s var(--ease) backwards;animation-delay:calc(var(--i,0)*70ms)}

/* ===== KARTU ===== */
.card{background:linear-gradient(165deg,#fff 0%,#fbfdf8 100%);border:1px solid var(--border);border-radius:var(--radius);box-shadow:var(--shadow);padding:24px}
.sh{display:flex;align-items:center;justify-content:space-between;gap:12px;margin-bottom:8px}
.sh h2{display:flex;align-items:center;gap:10px;font-size:1.02rem}
.sh h2::before{content:"";width:4px;height:18px;border-radius:4px;background:linear-gradient(180deg,var(--lime),var(--olive))}
.sh a{font-size:.78rem;font-weight:800;color:var(--olive);transition:color .15s}.sh a:hover{color:var(--dark)}

/* header profil */
.hero{position:relative;overflow:hidden;display:flex;align-items:center;gap:20px;padding:26px 28px;border-radius:22px;color:#fff;
  background:radial-gradient(420px 220px at 100% 0%,rgba(164,194,61,.22),transparent 70%),linear-gradient(135deg,var(--dark2) 0%,var(--dark) 60%,var(--dark3) 100%);
  border:1px solid rgba(255,255,255,.08);box-shadow:0 26px 50px -28px rgba(6,34,31,.65)}
.hero .big-av{width:76px;height:76px;border-radius:50%;flex-shrink:0;display:grid;place-items:center;font-size:2rem;font-weight:800;color:var(--dark);
  background:linear-gradient(135deg,var(--lime),#d4e58a);border:3px solid rgba(255,255,255,.35);box-shadow:0 10px 24px rgba(0,0,0,.28)}
.hero h2{font-size:1.4rem;color:#fff;letter-spacing:-.2px;overflow-wrap:anywhere}
.hero .em{font-size:.84rem;color:rgba(255,255,255,.72);overflow-wrap:anywhere}
.hero .tags{display:flex;gap:8px;flex-wrap:wrap;margin-top:10px}
.htag{display:inline-flex;align-items:center;gap:6px;padding:4px 12px;border-radius:99px;font-size:.74rem;font-weight:700;background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.18);color:#fff}
.htag i{width:7px;height:7px;border-radius:50%;background:#3fe08a}

/* daftar data seperti aplikasi: ikon, label, nilai */
.rows{display:flex;flex-direction:column}
.row{display:flex;align-items:center;gap:14px;padding:13px 2px;border-bottom:1px solid var(--border-soft)}
.row:last-child{border-bottom:0}
.ri{width:42px;height:42px;border-radius:12px;flex-shrink:0;display:grid;place-items:center;background:var(--tint);color:var(--olive)}
.ri svg{width:21px;height:21px}
.rl{flex:1;min-width:0;font-weight:700;font-size:.94rem;color:var(--dark)}
.pill{padding:5px 14px;border-radius:99px;font-weight:800;font-size:.86rem;background:#f0f3ee;color:var(--dark);text-align:right;max-width:55%;overflow-wrap:anywhere}
.pill.mono{font-family:var(--mono);font-weight:600}
.hint{margin-top:14px;padding:12px 16px;border-radius:12px;background:var(--tint);border:1px solid var(--tint2);font-size:.8rem;color:var(--muted);display:none}
.hint a{font-weight:800;color:var(--dark);text-decoration:underline}
.hint.show{display:block}

/* form */
.field{margin-bottom:16px}
.field label{display:block;font-size:.84rem;font-weight:700;color:var(--dark);margin-bottom:7px}
.inp{position:relative}
.inp input{width:100%;padding:13px 16px;border-radius:12px;border:1.5px solid var(--border);background:#fff;font-size:.92rem;outline:none;transition:.15s}
.inp input:focus{border-color:var(--olive);box-shadow:0 0 0 3px rgba(122,154,46,.14)}
.inp input.bad{border-color:var(--red);box-shadow:0 0 0 3px rgba(216,69,46,.12)}
.inp .eye{position:absolute;right:8px;top:50%;transform:translateY(-50%);width:36px;height:36px;border-radius:8px;display:grid;place-items:center;color:var(--faint)}
.inp .eye:hover{color:var(--dark);background:var(--tint)}
.inp .eye svg{width:18px;height:18px}
.inp.pw input{padding-right:48px}
.err{display:none;margin-top:6px;font-size:.78rem;font-weight:600;color:var(--red)}.err.show{display:block}
.btn{display:flex;align-items:center;justify-content:center;gap:8px;width:100%;padding:14px 20px;border-radius:12px;font-size:.92rem;font-weight:800;color:#fff;
  background:linear-gradient(135deg,var(--dark),var(--dark2));box-shadow:0 6px 16px -6px rgba(11,59,54,.45);transition:.15s}
.btn:hover{transform:translateY(-1px);filter:brightness(1.1)}.btn:active{transform:scale(.99)}

.toasts{position:fixed;right:22px;bottom:22px;z-index:500;display:grid;gap:10px}
.toast{display:flex;align-items:center;gap:10px;min-width:250px;max-width:360px;padding:12px 18px;border-radius:12px;font-size:.84rem;font-weight:600;color:#fff;background:var(--dark);border-left:4px solid var(--lime);box-shadow:0 14px 34px rgba(11,59,54,.3);animation:tin .3s var(--ease)}
.toast.out{opacity:0;transform:translateX(30px);transition:.4s}
@keyframes tin{from{opacity:0;transform:translateX(30px)}}

@media(max-width:900px){
  .sidebar{position:fixed;left:0;top:0;transform:translateX(-100%)}.sidebar.open{transform:none;box-shadow:0 0 40px rgba(11,59,54,.2)}
  .scrim.show{opacity:1;pointer-events:auto}
  .burger{display:flex}.chip-user div,.clk small,.clk-ic{display:none}.chip-user{padding:5px}.clk{padding:8px 12px}
  .topbar{padding:12px 16px}.content{padding:20px 16px 50px}.sec-h h1{font-size:1.4rem}
}
@media(max-width:520px){.hero{flex-direction:column;text-align:center;padding:24px 20px}.hero .tags{justify-content:center}.card{padding:20px 18px}.pill{max-width:50%}}
@media(prefers-reduced-motion:reduce){*{animation:none!important;transition:none!important}}
</style>
</head>
<body>
<div class="scrim" id="scrim"></div>
<div class="app">

  <aside class="sidebar" id="sidebar">
    <a href="index.php" class="brand"><span class="brand-mark"><img src="assets/logo.png" alt="Logo"></span><span><b>GEND<span>-OFF</span></b><small>Gizi-On, Gendut-Off</small></span></a>
    <nav class="nav" id="nav"></nav>
    <div class="side-foot">
      <a href="login.php" class="logout" onclick="return confirm('Yakin ingin keluar?')"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><path d="M16 17l5-5-5-5"/><path d="M21 12H9"/></svg> Keluar dari akun</a>
      <div class="side-ver">GEND-OFF v2.0<br>© <?= $tahun ?> Sistem Pakar Gizi</div>
    </div>
  </aside>

  <div class="main">
    <header class="topbar">
      <div class="tb-l">
        <button class="burger" id="burger" aria-label="Menu"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M4 7h16M4 12h16M4 17h16"/></svg></button>
        <div><div class="tb-title">Kelola Profil</div><div class="tb-sub">Data akun dan preferensi</div></div>
      </div>
      <div class="tb-r">
        <div class="clk"><span class="clk-ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 3"/></svg></span><div><b id="clkT">--:--:--</b><small id="clkD">-- --- ----</small></div></div>
        <div class="chip-user"><div class="av" id="uAv"><?= $inisial ?></div><div><b id="uName"><?= $username ?></b><small>Pengguna Aktif</small></div></div>
      </div>
    </header>

    <main class="content">
      <div class="sec-h rise"><h1>Kelola Profil</h1><p>Lihat data tubuhmu dan perbarui informasi akun.</p></div>

      <!-- Header profil -->
      <section class="hero rise" style="--i:1">
        <div class="big-av" id="bigAv"><?= $inisial ?></div>
        <div>
          <h2 id="hName"><?= $username ?></h2>
          <div class="em" id="hMail"><?= $email ?></div>
          <div class="tags"><span class="htag"><i></i>Pengguna Aktif</span><span class="htag" id="hCat" hidden></span></div>
        </div>
      </section>

      <!-- Informasi pengguna -->
      <section class="card rise" style="--i:2">
        <div class="sh"><h2>Profil Saya</h2><a href="deteksi_obesitas.php">Perbarui data →</a></div>
        <div class="rows" id="rows"></div>
        <div class="hint" id="hint">Data usia, jenis kelamin, dan aktivitas akan terisi setelah kamu melakukan analisis di <a href="deteksi_obesitas.php">Deteksi Obesitas</a>.</div>
      </section>

      <!-- Kelola akun -->
      <section class="card rise" style="--i:3">
        <div class="sh"><h2>Data Akun</h2></div>
        <form id="fP" novalidate style="margin-top:14px">
          <div class="field"><label for="pU">Username</label><div class="inp"><input type="text" id="pU" value="<?= $username ?>" autocomplete="username"></div><div class="err" id="eU">Username tidak boleh kosong.</div></div>
          <div class="field"><label for="pE">Email</label><div class="inp"><input type="email" id="pE" value="<?= $email ?>" autocomplete="email"></div><div class="err" id="eE">Format email belum benar.</div></div>
          <div class="field"><label for="pW">Kata Sandi Baru</label>
            <div class="inp pw"><input type="password" id="pW" placeholder="Biarkan kosong jika tidak ingin ganti" autocomplete="new-password"><button type="button" class="eye" id="eye" aria-label="Tampilkan sandi"></button></div>
            <div class="err" id="eW">Kata sandi minimal 6 karakter.</div></div>
          <button type="submit" class="btn">Simpan Perubahan</button>
        </form>
      </section>
    </main>
  </div>
</div>
<div class="toasts" id="toasts"></div>

<script>
const $=s=>document.querySelector(s),$$=s=>[...document.querySelectorAll(s)];
const fmt=n=>Math.round(n).toLocaleString('id-ID');
const dec=(n,d=1)=>Number(n).toFixed(d).replace('.',',');
const sv=p=>`<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">${p}</svg>`;
const ICON={
 grid:sv('<rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/>'),
 pulse:sv('<path d="M3 12h4l3-8 4 16 3-8h4"/>'),
 salad:sv('<path d="M4 11h16a8 8 0 01-16 0z"/><path d="M8 11c0-3 2-5 4-5s4 2 4 5"/>'),
 dumb:sv('<path d="M6.5 6.5v11M17.5 6.5v11M3 9v6M21 9v6M6.5 12h11"/>'),
 chat:sv('<path d="M21 12a8 8 0 01-11.6 7.1L3 21l1.9-5.6A8 8 0 1121 12z"/>'),
 book:sv('<path d="M4 4h6a3 3 0 013 3v13a2 2 0 00-2-2H4z"/><path d="M20 4h-6a3 3 0 00-3 3v13a2 2 0 012-2h7z"/>'),
 file:sv('<path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6M9 13h6M9 17h6"/>'),
 user:sv('<circle cx="12" cy="8" r="4"/><path d="M4 21c0-4.4 3.6-8 8-8s8 3.6 8 8"/>'),
 /* ikon baris profil */
 ruler:sv('<path d="M8 3v18M8 7h4M8 11h2M8 15h4M8 19h2"/><path d="M16 4l-2 3h4zM16 20l-2-3h4zM16 7v10"/>'),
 kg:sv('<path d="M6.5 8h11l2 12h-15z"/><circle cx="12" cy="5" r="2"/><path d="M10 14h4"/>'),
 flag:sv('<path d="M5 21V4M5 4h11l-2 4 2 4H5"/>'),
 cake:sv('<path d="M4 20h16M5 20v-6a2 2 0 012-2h10a2 2 0 012 2v6M12 12V9M12 5c1 1 1 2 0 3-1-1-1-2 0-3z"/>'),
 gender:sv('<circle cx="12" cy="5" r="2"/><path d="M12 9v6M9 21l1.5-6h3L15 21M8 11l4-2 4 2"/>'),
 bmi:sv('<path d="M4 17a8 8 0 0116 0"/><path d="M12 17l3-5"/><path d="M4 21h16"/>'),
 run:sv('<circle cx="14" cy="4.5" r="1.8"/><path d="M8 21l3-6 3 2v4M11 15l-1-5 4-1 2 3 3 1M10 10L7 13"/>'),
 heart:sv('<path d="M12 20s-7-4.5-7-10a4 4 0 017-2.6A4 4 0 0119 10c0 5.500-7 10-7 10z"/>'),
 fire:sv('<path d="M12 2.500s-6 5.500-6 11a6 6 0 0012 0c0-3-2-5-2-5s-1 2-2.500 2C13 8 12 2.500 12 2.500z"/>'),
 eye:sv('<path d="M2 12s4-7 10-7 10 7 10 7-4 7-10 7S2 12 2 12z"/><circle cx="12" cy="12" r="3"/>'),
 eyeoff:sv('<path d="M3 3l18 18M10.600 6.100A9.500 9.500 0 0112 6c6 0 10 6 10 6a17 17 0 01-3.200 3.800M6.700 7.700A17 17 0 002 12s4 6 10 6c1.500 0 2.900-.4 4.100-1"/><path d="M9.900 9.900a3 3 0 004.200 4.200"/>')
};
const NAV=[
 {g:'Utama',items:[['dashboard','Dashboard','grid','dashboard.php']]},
 {g:'Pemeriksaan & Menu',items:[['deteksi','Deteksi Obesitas','pulse','deteksi_obesitas.php'],['gizi','Gizi Planner','salad','gizi_planner.php'],['olahraga','Olahraga','dumb','olahraga_planner.php']]},
 {g:'Konsultasi & Belajar',items:[['chatbot','Konsultasi AI','chat','chatbot.php'],['edukasi','Edukasi Pengguna','book','edukasi_pengguna.php']]},
 {g:'Laporan & Akun',items:[['ekspor','Ekspor Laporan','file','dashboard.php#ekspor'],['profil','Kelola Profil','user','profil.php']]}
];
$('#nav').innerHTML=NAV.map(n=>`<div class="nav-label">${n.g}</div>`+n.items.map(i=>`<a class="nav-item${i[0]==='profil'?' active':''}" href="${i[3]}"><span class="ic">${ICON[i[2]]}</span>${i[1]}</a>`).join('')).join('');
const side=$('#sidebar'),scrim=$('#scrim');
function closeSide(){side.classList.remove('open');scrim.classList.remove('show')}
$('#burger').onclick=()=>{side.classList.add('open');scrim.classList.add('show')};scrim.onclick=closeSide;
function toast(msg){const t=document.createElement('div');t.className='toast';t.textContent=msg;$('#toasts').appendChild(t);setTimeout(()=>t.classList.add('out'),3200);setTimeout(()=>t.remove(),3700)}

/* ===== DATA PENGGUNA (tersinkron dengan Deteksi Obesitas & dashboard) ===== */
let S={};
try{S=JSON.parse(localStorage.getItem('gendoff_dash_v3')||'{}')||{}}catch(e){S={}}
const L=S.last||null;
const tb=+S.tb||0,bb=+S.bb||0,goal=+S.goal||0;
const bmi=L&&L.bmi!=null?+L.bmi:(tb&&bb?bb/Math.pow(tb/100,2):null);
const catOf=b=>b<18.5?'Kurus':b<23?'Normal':b<25?'Overweight':b<30?'Obesitas I':'Obesitas II';
const ACT={'1.2':'Sedentari','1.375':'Ringan','1.55':'Sedang','1.725':'Berat'};
const rows=[
 ['ruler','Tinggi Badan',tb?`${fmt(tb)} cm`:'–'],
 ['kg','Berat Badan',bb?`${dec(bb,1)} kg`:'–'],
 ['flag','Berat Badan Target',goal?`${dec(goal,1)} kg`:'–'],
 ['cake','Usia',L&&L.age?`${L.age} tahun`:'–'],
 ['gender','Jenis Kelamin',L&&L.g?(L.g==='P'?'Perempuan':'Laki-laki'):'–'],
 ['bmi','BMI',bmi!=null?`${dec(bmi,1)} · ${L&&L.cat?L.cat:catOf(bmi)}`:'–','mono'],
 ['run','Tingkat Aktivitas',L&&L.af?(ACT[String(L.af)]||'–'):'–'],
 ['fire','Target Kalori',L&&L.tgt?`${fmt(L.tgt)} kkal/hari`:(S.target?`${fmt(S.target)} kkal/hari`:'–'),'mono'],
 ['heart','Riwayat Penyakit',L&&L.pen&&String(L.pen).trim()?String(L.pen).trim():'Tidak ada']
];
const rowsEl=$('#rows');
rows.forEach((r,i)=>{
  const d=document.createElement('div');d.className='row';
  d.innerHTML=`<span class="ri">${ICON[r[0]]}</span><span class="rl"></span><span class="pill${r[3]?' '+r[3]:''}"></span>`;
  d.querySelector('.rl').textContent=r[1];d.querySelector('.pill').textContent=r[2];
  rowsEl.appendChild(d);
});
if(!L)$('#hint').classList.add('show');
if(bmi!=null){const h=$('#hCat');h.hidden=false;h.textContent=`BMI ${dec(bmi,1)} · ${L&&L.cat?L.cat:catOf(bmi)}`}

/* ===== PROFIL (username & email disimpan di browser; hubungkan ke database agar permanen) ===== */
const PK='gendoff_profil_v1';
let P={};try{P=JSON.parse(localStorage.getItem(PK)||'{}')||{}}catch(e){P={}}
function applyProfile(u,m){
  $('#uName').textContent=u;$('#hName').textContent=u;$('#hMail').textContent=m;
  const c=(u.trim().charAt(0)||'?').toUpperCase();$('#uAv').textContent=c;$('#bigAv').textContent=c;
}
if(P.u){$('#pU').value=P.u;}
if(P.m){$('#pE').value=P.m;}
if(P.u||P.m)applyProfile($('#pU').value,$('#pE').value);

$('#eye').innerHTML=ICON.eye;
$('#eye').onclick=()=>{const i=$('#pW'),s=i.type==='password';i.type=s?'text':'password';$('#eye').innerHTML=s?ICON.eyeoff:ICON.eye};
$('#fP').onsubmit=e=>{
  e.preventDefault();
  const u=$('#pU').value.trim(),m=$('#pE').value.trim(),w=$('#pW').value;
  const okU=!!u,okE=/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(m),okW=!w||w.length>=6;
  [['pU','eU',okU],['pE','eE',okE],['pW','eW',okW]].forEach(([i,er,ok])=>{$('#'+i).classList.toggle('bad',!ok);$('#'+er).classList.toggle('show',!ok)});
  if(!(okU&&okE&&okW)){toast('Periksa kembali isian yang ditandai');return}
  try{localStorage.setItem(PK,JSON.stringify({u,m}))}catch(x){}
  applyProfile(u,m);$('#pW').value='';
  toast(w?'Username dan email disimpan. Kata sandi butuh koneksi database agar tersimpan.':'Perubahan disimpan (hubungkan ke database agar permanen)');
};
$$('#fP input').forEach(i=>i.addEventListener('input',()=>{i.classList.remove('bad');const er=$('#e'+i.id.slice(1));if(er)er.classList.remove('show')}));

function tick(){const n=new Date();
  $('#clkT').textContent=n.toLocaleTimeString('id-ID',{hour:'2-digit',minute:'2-digit',second:'2-digit',hour12:false}).replace(/\./g,':');
  $('#clkD').textContent=n.toLocaleDateString('id-ID',{weekday:'long',day:'2-digit',month:'long',year:'numeric'})}
tick();setInterval(tick,1000);
</script>
</body>
</html>