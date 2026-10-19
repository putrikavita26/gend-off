<?php
// GEND-OFF — Olahraga Harian (program olahraga di rumah tanpa alat, personal sesuai hasil deteksi)
// Letakkan di: htdocs/gend-off/olahraga_planner.php | aset di: assets/
// Opsional: taruh GIF/gambar gerakan di assets/olahraga/{kode}.gif (mis. pu.gif, plank.gif). Jika tidak ada, otomatis tampil emoji.
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
$tahun    = date('Y');
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Olahraga — GEND-OFF</title>
<link rel="icon" href="assets/logo.png">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=IBM+Plex+Mono:wght@500;600&display=swap" rel="stylesheet">
<style>
:root{
  --dark:#0b3b36;--dark2:#12524a;--dark3:#06221f;--olive:#7a9a2e;--lime:#a4c23d;
  --tint:#f1f6e4;--tint2:#e6efd0;--bg:#f6f8f4;--surface:#fff;--border:#e6ebe0;--border-soft:#eef2e9;
  --text:#14302c;--muted:#5d716d;--faint:#93a29e;
  --red:#d8452e;--red-bg:#fceae6;--amber:#8a6300;--amber-bg:#fff4d6;--green:#0f7a4f;--green-bg:#e5f6ec;--blue:#2e86ab;--blue-bg:#e6f2f8;
  --radius:16px;--shadow:0 1px 2px rgba(11,59,54,.05),0 1px 1px rgba(11,59,54,.03);--shadow-md:0 10px 30px -10px rgba(11,59,54,.18);
  --sw:248px;--mono:'IBM Plex Mono',monospace;--ease:cubic-bezier(.2,.7,.2,1)
}
*{box-sizing:border-box;margin:0;padding:0;-webkit-tap-highlight-color:transparent}
body{font-family:'Plus Jakarta Sans',sans-serif;color:var(--text);-webkit-font-smoothing:antialiased;line-height:1.55;
  background:radial-gradient(900px 520px at 100% -6%,rgba(164,194,61,.17),transparent 60%),radial-gradient(760px 480px at -8% 16%,rgba(18,82,74,.10),transparent 55%),var(--bg);background-attachment:fixed}
a{color:inherit;text-decoration:none}
button{font-family:inherit;cursor:pointer;border:0;background:none;color:inherit}
::-webkit-scrollbar{width:9px;height:9px}::-webkit-scrollbar-thumb{background:#cfd9c4;border-radius:8px}
:focus-visible{outline:2px solid var(--lime);outline-offset:2px}
h1,h2,h3,h4{font-weight:800;color:var(--dark);line-height:1.2}

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
.content{padding:26px 30px 60px;max-width:1360px;width:100%;margin:0 auto}
.sec-h{margin-bottom:20px}
.sec-h h1{font-size:1.6rem;letter-spacing:-.3px;margin-bottom:4px}
.sec-h p{font-size:.88rem;color:var(--muted);max-width:660px;margin-top:8px}
@keyframes rise{from{opacity:0;transform:translateY(14px)}}
.rise{animation:rise .55s var(--ease) backwards;animation-delay:calc(var(--i,0)*60ms)}
@keyframes fade{from{opacity:0;transform:translateY(6px)}}

/* ===== KOMPONEN ===== */
.card{background:linear-gradient(165deg,#fff 0%,#fbfdf8 100%);border:1px solid var(--border);border-radius:var(--radius);box-shadow:var(--shadow);padding:22px}
.mb{margin-bottom:18px}
.card-h{display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;margin-bottom:16px}
.card-t{font-size:.98rem;font-weight:800;color:var(--dark)}
.card-s{font-size:.76rem;color:var(--faint);font-weight:500;margin-top:2px}
.tag{display:inline-block;font-size:.72rem;font-weight:700;padding:4px 11px;border-radius:99px;background:var(--tint);color:var(--dark)}
.btn{display:inline-flex;align-items:center;justify-content:center;gap:8px;padding:10px 18px;border-radius:10px;font-size:.86rem;font-weight:700;border:1px solid transparent;transition:.15s;white-space:nowrap}
.btn:active{transform:scale(.98)}
.btn-p{position:relative;overflow:hidden;background:linear-gradient(135deg,var(--dark),var(--dark2));color:#fff;box-shadow:0 4px 12px rgba(11,59,54,.2)}
.btn-p:hover{transform:translateY(-1px)}
.btn-o{background:#fff;border-color:var(--border);color:var(--text)}.btn-o:hover{border-color:var(--olive);color:var(--dark)}
.btn-l{background:linear-gradient(135deg,var(--olive),var(--lime));color:var(--dark);box-shadow:0 10px 22px rgba(0,0,0,.25)}
.btn-l:hover{transform:translateY(-2px)}
.btn-g{background:rgba(255,255,255,.1);border-color:rgba(255,255,255,.3);color:#fff}.btn-g:hover{background:rgba(255,255,255,.2)}

/* hero */
.hero-card{position:relative;overflow:hidden;display:flex;align-items:center;justify-content:space-between;gap:24px;flex-wrap:wrap;padding:30px 36px;margin-bottom:18px;border-radius:24px;color:#fff;
  background:radial-gradient(480px 240px at 100% 0%,rgba(164,194,61,.2),transparent 70%),linear-gradient(135deg,var(--dark2) 0%,var(--dark) 60%,var(--dark3) 100%);
  border:1px solid rgba(255,255,255,.08);box-shadow:0 26px 50px -26px rgba(6,34,31,.65)}
.hero-card h2{font-size:clamp(1.4rem,2.6vw,2rem);letter-spacing:-.4px;color:#fff;margin-bottom:8px}
.hero-card p{color:rgba(255,255,255,.8);font-size:.9rem;max-width:520px;margin-bottom:16px}
.hmeta{display:flex;gap:8px;flex-wrap:wrap;margin-bottom:16px}
.hm{display:inline-flex;align-items:center;gap:5px;padding:5px 12px;border-radius:99px;background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.16);font-size:.76rem;font-weight:600;color:rgba(255,255,255,.85)}
.hm b{font-family:var(--mono);font-weight:600;color:var(--lime)}
.hero-btns{display:flex;gap:10px;flex-wrap:wrap}
.hero-big{font-size:5.5rem;line-height:1;filter:drop-shadow(0 12px 24px rgba(0,0,0,.35))}

/* stat */
.g4{display:grid;grid-template-columns:repeat(4,1fr);gap:18px;margin-bottom:18px}
.stat{position:relative;overflow:hidden;background:radial-gradient(170px 120px at 100% 0%,var(--tint),transparent 70%),#fff}
.stat-top{display:flex;align-items:center;justify-content:space-between;margin-bottom:10px}
.stat-l{font-size:.8rem;font-weight:700;color:var(--muted)}
.stat-ic{width:36px;height:36px;border-radius:10px;display:grid;place-items:center;font-size:1.1rem;background:var(--tint)}
.stat-v{font-family:var(--mono);font-size:1.8rem;font-weight:600;color:var(--dark);line-height:1.1}
.stat-v small{font-family:'Plus Jakarta Sans';font-size:.8rem;color:var(--faint);font-weight:600}
.stat-n{font-size:.76rem;color:var(--muted);margin-top:8px}
.bar{height:7px;border-radius:99px;background:var(--border-soft);overflow:hidden;margin-top:12px}
.bar i{display:block;height:100%;width:0;border-radius:99px;background:linear-gradient(90deg,var(--olive),var(--lime));transition:width 1s var(--ease)}

/* tabs hari */
.tabs{display:flex;gap:8px;flex-wrap:wrap;margin-bottom:18px}
.tab{padding:9px 16px;border-radius:99px;border:1.5px solid var(--border);background:#fff;font-size:.84rem;font-weight:700;color:var(--muted);transition:.15s;display:inline-flex;align-items:center;gap:6px}
.tab:hover{border-color:var(--olive);color:var(--dark)}
.tab.on{background:linear-gradient(135deg,var(--dark),var(--dark2));border-color:var(--dark);color:#fff;box-shadow:0 6px 14px -6px rgba(11,59,54,.5)}
.tab .st{font-size:.72rem}
.tab.full:not(.on){background:var(--green-bg);border-color:#bfe6cf;color:var(--green)}

/* detail hari */
.dhead{display:flex;align-items:flex-start;justify-content:space-between;gap:14px;flex-wrap:wrap;margin-bottom:18px}
.dhead h2{font-size:2rem;margin:8px 0 2px}
.dhead span.sub{font-size:.9rem;color:var(--muted);font-weight:600}
.trio{display:grid;grid-template-columns:repeat(3,1fr);gap:10px;margin-bottom:16px}
.trio div{padding:14px 10px;border-radius:12px;background:var(--bg);border:1px solid var(--border-soft);text-align:center}
.trio b{display:block;font-family:var(--mono);font-size:1.05rem;font-weight:600;color:var(--dark)}
.trio small{font-size:.72rem;font-weight:700;color:var(--faint)}
.ptop{display:flex;justify-content:space-between;align-items:center;margin:4px 0 2px}
.ptop b{font-size:.9rem;color:var(--dark)}.ptop span{font-family:var(--mono);font-size:.8rem;color:var(--muted)}
.ck-list{display:flex;flex-direction:column;gap:8px;margin-top:14px}
.ck{display:flex;align-items:center;gap:12px;width:100%;padding:12px 14px;border-radius:12px;border:1px solid var(--border-soft);background:#fff;text-align:left;transition:.15s}
.ck:hover{background:var(--bg)}
.ck .bx{width:24px;height:24px;border-radius:7px;border:2px solid #c3d1ac;display:grid;place-items:center;flex-shrink:0;color:transparent;font-size:.8rem;font-weight:800;transition:.2s}
.ck.on{background:var(--tint);border-color:#cfdcbb}
.ck.on .bx{background:var(--olive);border-color:var(--olive);color:#fff}
.ck .em{font-size:1.7rem;width:40px;text-align:center;flex-shrink:0}
.ck .tx{flex:1;min-width:0}
.ck .tx b{display:block;font-size:.92rem;color:var(--dark)}
.ck .tx small{display:block;font-size:.8rem;font-weight:700;color:var(--blue);margin:1px 0 2px}
.ck .tx em{display:block;font-style:normal;font-size:.76rem;color:var(--muted);line-height:1.45}
.ck.on .tx b{text-decoration:line-through;color:var(--faint)}

.rlist{list-style:none;display:grid;gap:9px}
.rlist li{padding:12px 14px;border-radius:12px;background:#fff;border:1px solid var(--border-soft);border-left:3px solid var(--lime);font-size:.83rem;color:var(--muted)}
.rlist li strong{display:block;color:var(--dark);font-size:.86rem;margin-bottom:1px}
.rlist li.w{border-left-color:#f5b82e}
.g2e{display:grid;grid-template-columns:1.4fr 1fr;gap:18px}

/* pemutar latihan */
#player{position:fixed;inset:0;z-index:400;background:#fff;display:none;flex-direction:column;align-items:center;justify-content:flex-start;gap:0;padding:16px 20px 20px;text-align:center;overflow:hidden}
#player.show{display:flex}
.pt{width:100%;max-width:560px;display:flex;align-items:center;gap:12px}
.pt .x{width:38px;height:38px;border-radius:50%;background:var(--bg);display:grid;place-items:center;font-size:1.1rem;color:var(--muted);flex-shrink:0}
.pt .pb{flex:1;height:8px;border-radius:99px;background:var(--border-soft);overflow:hidden}
.pt .pb i{display:block;height:100%;border-radius:99px;background:linear-gradient(90deg,var(--olive),var(--lime));transition:width .5s var(--ease)}
.pt span{font-family:var(--mono);font-size:.78rem;color:var(--muted);font-weight:600}
.pbody{width:100%;max-width:560px;flex:1;display:flex;flex-direction:column;align-items:center;justify-content:center;min-height:0}
.pvis{height:clamp(150px,30vh,280px);width:100%;flex:none;display:grid;place-items:center;font-size:clamp(5rem,16vh,8rem);line-height:1;overflow:visible}
.stk{display:inline-block;line-height:1}
.pvis img{max-width:100%;max-height:clamp(150px,30vh,280px);object-fit:contain}
.pvis.move .stk{animation:bob 1.2s ease-in-out infinite}
@keyframes bob{50%{transform:translateY(-10px) scale(1.04)}}
.pst{font-size:1.25rem;font-weight:700;letter-spacing:.5px;color:var(--dark);margin-top:8px;height:1.6em;line-height:1.6em}
.pnm{font-size:1.6rem;font-weight:800;color:var(--dark);margin:6px 0 2px;height:1.3em;line-height:1.3em;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:100%}
.psub{font-size:.9rem;color:var(--muted);font-weight:600;height:1.4em;line-height:1.4em}
.phow{font-size:.82rem;color:var(--muted);max-width:420px;height:4.5em;margin:8px auto 0;line-height:1.5;overflow:hidden}
.pring{position:relative;width:150px;height:150px;margin:10px auto 10px;flex:none}
.pring svg{width:100%;height:100%;transform:rotate(-90deg)}
.pring circle{fill:none;stroke-width:7}
.pring .pb{stroke:#c9dcf8}.pring .pv{stroke:#3b82f6;stroke-linecap:round;stroke-dasharray:276.46;stroke-dashoffset:0;transition:stroke-dashoffset 1s linear}
.pring.rest .pv{stroke:var(--olive)}.pring.rest .pb{stroke:var(--tint2)}
.pring b{position:absolute;inset:0;display:grid;place-items:center;font-family:var(--mono);font-size:2.6rem;font-weight:600;color:var(--dark)}
.pbtn{display:flex;gap:12px;justify-content:center;margin-top:8px}
.pbtn button{min-width:120px;padding:12px 26px;border-radius:99px;font-weight:800;font-size:.9rem;letter-spacing:.5px}
.pbtn .a{background:linear-gradient(135deg,var(--dark),var(--dark2));color:#fff}
.pbtn .b{color:var(--muted);background:var(--bg)}
.pdone{margin:auto;max-width:420px}
.pdone .big{font-size:5rem}
.pdone h2{font-size:1.7rem;margin:8px 0}
.pdone p{color:var(--muted);font-size:.9rem;margin-bottom:18px}

.toasts{position:fixed;right:22px;bottom:22px;z-index:500;display:grid;gap:10px}
.toast{display:flex;align-items:center;gap:10px;min-width:250px;padding:12px 18px;border-radius:12px;font-size:.84rem;font-weight:600;color:#fff;background:var(--dark);border-left:4px solid var(--lime);box-shadow:0 14px 34px rgba(11,59,54,.3);animation:tin .3s var(--ease)}
.toast.out{opacity:0;transform:translateX(30px);transition:.4s}
@keyframes tin{from{opacity:0;transform:translateX(30px)}}
.empty{text-align:center;padding:40px 22px}
.empty .big{font-size:3.4rem}
.empty h3{margin:10px 0 6px;font-size:1.2rem}
.empty p{font-size:.88rem;color:var(--muted);max-width:440px;margin:0 auto 18px}

@media(max-width:1180px){.g4{grid-template-columns:1fr 1fr}.g2e{grid-template-columns:1fr}}
@media(max-width:900px){
  .sidebar{position:fixed;left:0;top:0;transform:translateX(-100%)}.sidebar.open{transform:none}
  .scrim.show{opacity:1;pointer-events:auto}
  .burger{display:flex}.chip-user div,.clk small,.clk-ic{display:none}.chip-user{padding:5px}.clk{padding:8px 12px}
  .topbar{padding:12px 16px}.content{padding:18px 16px 50px}
  .hero-card{padding:24px 20px}.hero-big{display:none}.sec-h h1{font-size:1.4rem}
}
@media(max-width:560px){.g4{grid-template-columns:1fr}.dhead h2{font-size:1.6rem}.ck .em{font-size:1.4rem;width:32px}}
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
        <div><div class="tb-title">Olahraga</div><div class="tb-sub">Latihan di rumah tanpa alat, sesuai kondisimu</div></div>
      </div>
      <div class="tb-r">
        <div class="clk"><span class="clk-ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 3"/></svg></span><div><b id="clkT">--:--:--</b><small id="clkD">-- --- ----</small></div></div>
        <div class="chip-user"><div class="av"><?= $inisial ?></div><div><b><?= $username ?></b><small>Pengguna Aktif</small></div></div>
      </div>
    </header>

    <main class="content">
      <section id="page-olahraga">
        <div class="sec-h"><h1>Olahraga Harian</h1><p>Program 7 hari yang disesuaikan dengan jenis kelamin, kategori BMI, usia, tingkat aktivitas, dan riwayat penyakitmu. Semua gerakan bisa dilakukan di rumah tanpa alat.</p></div>
        <div id="app"></div>
      </section>
    </main>
  </div>
</div>

<!-- Pemutar latihan layar penuh -->
<div id="player"></div>
<div class="toasts" id="toasts"></div>

<script>
const $=s=>document.querySelector(s),$$=s=>[...document.querySelectorAll(s)];
const fmt=n=>Math.round(n).toLocaleString('id-ID');
const esc=s=>String(s).replace(/[&<>"]/g,c=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;'}[c]));
const sv=p=>`<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">${p}</svg>`;
const ICON={
 grid:sv('<rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/>'),
 pulse:sv('<path d="M3 12h4l3-8 4 16 3-8h4"/>'),
 salad:sv('<path d="M4 11h16a8 8 0 01-16 0z"/><path d="M8 11c0-3 2-5 4-5s4 2 4 5"/>'),
 dumb:sv('<path d="M6.5 6.5v11M17.5 6.5v11M3 9v6M21 9v6M6.5 12h11"/>'),
 chat:sv('<path d="M21 12a8 8 0 01-11.6 7.1L3 21l1.9-5.6A8 8 0 1121 12z"/>'),
 book:sv('<path d="M4 4h6a3 3 0 013 3v13a2 2 0 00-2-2H4z"/><path d="M20 4h-6a3 3 0 00-3 3v13a2 2 0 012-2h7z"/>'),
 file:sv('<path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6M9 13h6M9 17h6"/>'),
 user:sv('<circle cx="12" cy="8" r="4"/><path d="M4 21c0-4.4 3.6-8 8-8s8 3.6 8 8"/>')
};
const NAV=[
 {g:'Utama',items:[['dashboard','Dashboard','grid','dashboard.php']]},
 {g:'Pemeriksaan & Menu',items:[['deteksi','Deteksi Obesitas','pulse','deteksi_obesitas.php'],['gizi','Gizi Planner','salad','gizi_planner.php'],['olahraga','Olahraga','dumb','olahraga_planner.php']]},
 {g:'Konsultasi & Belajar',items:[['chatbot','Konsultasi AI','chat','dashboard.php#chatbot'],['edukasi','Edukasi Pengguna','book','dashboard.php#edukasi']]},
 {g:'Laporan & Akun',items:[['ekspor','Ekspor Laporan','file','dashboard.php#ekspor'],['profil','Kelola Profil','user','dashboard.php#profil']]}
];
$('#nav').innerHTML=NAV.map(n=>`<div class="nav-label">${n.g}</div>`+n.items.map(i=>`<a class="nav-item${i[0]==='olahraga'?' active':''}" href="${i[3]}"><span class="ic">${ICON[i[2]]}</span>${i[1]}</a>`).join('')).join('');
const side=$('#sidebar'),scrim=$('#scrim');
function closeSide(){side.classList.remove('open');scrim.classList.remove('show')}
$('#burger').onclick=()=>{side.classList.add('open');scrim.classList.add('show')};scrim.onclick=closeSide;
function toast(msg){const t=document.createElement('div');t.className='toast';t.textContent=msg;$('#toasts').appendChild(t);setTimeout(()=>t.classList.add('out'),2600);setTimeout(()=>t.remove(),3100)}

/* ===== STATE ===== */
const KEY='gendoff_dash_v3',OKEY='gendoff_olahraga_v1';
let S={},O=null,P=null,sel=null,PL=null;
const isoDate=d=>`${d.getFullYear()}-${String(d.getMonth()+1).padStart(2,'0')}-${String(d.getDate()).padStart(2,'0')}`;
function loadS(){try{S=JSON.parse(localStorage.getItem(KEY)||'{}')||{}}catch(e){S={}}}
function saveO(){try{localStorage.setItem(OKEY,JSON.stringify(O))}catch(e){}}
function loadO(){
  try{O=JSON.parse(localStorage.getItem(OKEY)||'null')}catch(e){O=null}
  if(!O||!O.start)O={start:isoDate(new Date()),done:{}};
  if(!O.done)O.done={};
  saveO();
}
function dayIndex(){
  const [y,m,d]=O.start.split('-').map(Number),a=new Date(y,m-1,d),n=new Date(),b=new Date(n.getFullYear(),n.getMonth(),n.getDate());
  return Math.max(0,Math.round((b-a)/864e5));
}

/* ===== PUSTAKA GERAKAN (tanpa alat) =====
   tg = tag risiko: jump (lompat) | hi (intensitas tinggi) | knee (tekuk lutut dalam) | prone (menekan perut)
        flex (menekuk tubuh/perut) | iso (tahan posisi) | inv (kepala di bawah) | back (beban punggung)
   lv = level minimal (0 pemula, 1 menengah) | met = intensitas untuk estimasi kalori */
const EX={
 arm:{n:'Putar Lengan',e:'🔄',met:2.5,lv:0,tg:[],how:'Berdiri tegak, putar kedua lengan lebar ke depan lalu ke belakang secara perlahan.'},
 march:{n:'Jalan di Tempat',e:'🚶',met:3.5,lv:0,tg:[],how:'Angkat lutut bergantian setinggi pinggang sambil mengayun lengan.'},
 sidestep:{n:'Side Step',e:'↔️',met:4,lv:0,tg:[],how:'Melangkah ke samping kanan-kiri bergantian dengan ayunan lengan, tanpa melompat.'},
 box:{n:'Shadow Boxing',e:'🥊',met:5.5,lv:0,tg:[],how:'Kuda-kuda ringan, pukul ke depan bergantian dengan tempo stabil sambil menjaga napas.'},
 stj:{n:'Step Jack',e:'🙆',met:5,lv:0,tg:[],how:'Seperti jumping jack tetapi dengan melangkah: satu kaki ke samping sambil mengangkat lengan, tanpa melompat.'},
 bk:{n:'Butt Kicks',e:'🏃',met:6,lv:0,tg:['jump'],how:'Jogging di tempat, tumit mengarah ke bokong.'},
 jj:{n:'Jumping Jack',e:'🤸',met:8,lv:0,tg:['jump','hi'],how:'Lompat sambil membuka kaki dan mengangkat lengan di atas kepala, lalu kembali.'},
 hk:{n:'High Knees',e:'🏃‍♂️',met:8,lv:0,tg:['jump','hi'],how:'Berlari di tempat dengan lutut diangkat setinggi pinggang.'},
 mc:{n:'Mountain Climber',e:'🧗',met:8,lv:0,tg:['prone','hi'],how:'Posisi plank tinggi, tarik lutut ke dada bergantian dengan cepat.'},
 bp:{n:'Burpee',e:'🔥',met:8,lv:1,tg:['hi','jump','knee','prone'],how:'Jongkok, lempar kaki ke belakang (plank), kembali jongkok lalu melompat ke atas.'},
 sq:{n:'Squat',e:'🏋️',met:5,lv:0,tg:['knee'],how:'Kaki selebar bahu, turunkan pinggul seperti duduk, punggung lurus, lutut sejajar jari kaki.'},
 ssq:{n:'Squat Dangkal',e:'🪑',met:4,lv:0,tg:[],how:'Turun hanya setengah dari squat biasa, lutut tidak melewati ujung jari kaki. Ramah untuk sendi.'},
 jsq:{n:'Jump Squat',e:'⬆️',met:8,lv:1,tg:['jump','hi','knee'],how:'Squat lalu melompat ke atas dan mendarat lembut.'},
 sumo:{n:'Sumo Squat',e:'🦵',met:4.5,lv:0,tg:['knee'],how:'Kaki dibuka lebar, ujung kaki menghadap keluar, turun perlahan sambil menjaga dada tegak.'},
 rl:{n:'Reverse Lunge',e:'🚶‍♂️',met:4,lv:0,tg:['knee'],how:'Langkahkan satu kaki ke belakang, turunkan lutut belakang mendekati lantai, kembali berdiri. Ganti sisi.'},
 wsit:{n:'Wall Sit',e:'🧱',met:3.5,lv:0,tg:['iso','knee'],how:'Sandarkan punggung ke tembok, turun hingga paha sejajar lantai dan tahan. Bernapas normal.'},
 cr:{n:'Calf Raise',e:'🦶',met:3,lv:0,tg:[],how:'Berdiri, angkat tumit setinggi mungkin lalu turun perlahan. Pegang dinding jika perlu.'},
 gb:{n:'Glute Bridge',e:'🍑',met:3.5,lv:0,tg:[],how:'Berbaring, lutut ditekuk, angkat pinggul hingga tubuh lurus lalu kencangkan bokong 1 detik.'},
 dk:{n:'Donkey Kick',e:'🐴',met:3.5,lv:0,tg:[],how:'Posisi merangkak, tendang satu kaki ke atas dengan lutut tetap ditekuk. Ganti sisi.'},
 fh:{n:'Fire Hydrant',e:'🐕',met:3.5,lv:0,tg:[],how:'Posisi merangkak, angkat satu lutut ke samping seperti hewan buang air. Ganti sisi.'},
 sla:{n:'Side Lying Hip Abduction',e:'🛌',met:3.5,lv:0,tg:[],how:'Berbaring miring, angkat kaki atas ke samping secara perlahan lalu turunkan. Ganti sisi.'},
 pu:{n:'Push-Up',e:'💪',met:4,lv:0,tg:[],how:'Tangan selebar bahu, turunkan dada mendekati lantai dengan tubuh lurus, lalu dorong naik.'},
 dpu:{n:'Diamond Push-Up',e:'💎',met:4.5,lv:1,tg:[],how:'Kedua tangan membentuk berlian di bawah dada, turun dan dorong naik. Melatih trisep.'},
 pike:{n:'Pike Push-Up',e:'🔺',met:4,lv:1,tg:['inv'],how:'Pinggul diangkat membentuk huruf V terbalik, tekuk siku menurunkan kepala ke lantai, dorong naik.'},
 kpu:{n:'Knee Push-Up',e:'🙇',met:3.8,lv:0,tg:[],how:'Push-up dengan lutut menempel lantai. Badan tetap lurus dari lutut sampai kepala.'},
 wpu:{n:'Wall Push-Up',e:'🧍',met:3,lv:0,tg:[],how:'Berdiri menghadap tembok, tangan di dinding, tekuk siku mendekatkan dada ke tembok lalu dorong.'},
 sup:{n:'Superman',e:'🦸',met:3,lv:0,tg:['prone','back'],how:'Telungkup, angkat tangan dan kaki bersamaan 1–2 detik, turunkan perlahan.'},
 plank:{n:'Plank',e:'📏',met:3.5,lv:0,tg:['iso','prone'],how:'Tumpu pada siku dan ujung kaki, tubuh lurus, kencangkan perut. Jangan menahan napas.'},
 sidep:{n:'Side Plank (Lutut)',e:'📐',met:3,lv:0,tg:['iso'],how:'Berbaring miring, tumpu pada siku dan lutut bawah, angkat pinggul hingga tubuh lurus. Ganti sisi.'},
 dead:{n:'Dead Bug',e:'🪲',met:3,lv:0,tg:[],how:'Berbaring, lengan ke atas dan lutut 90°, turunkan lengan & kaki berlawanan perlahan sambil punggung menempel lantai.'},
 bird:{n:'Bird Dog',e:'🐦',met:3,lv:0,tg:[],how:'Posisi merangkak, luruskan lengan kanan dan kaki kiri bersamaan, tahan sebentar, ganti sisi.'},
 bic:{n:'Bicycle Crunch',e:'🚴',met:4,lv:0,tg:['flex'],how:'Berbaring, kayuh kaki bergantian sambil siku menyentuh lutut yang berlawanan.'},
 lr:{n:'Lying Leg Raise',e:'🦿',met:3.5,lv:0,tg:['flex','back'],how:'Berbaring, angkat kedua kaki lurus hingga 90° lalu turunkan perlahan tanpa menyentuh lantai.'},
 sc:{n:'Standing Oblique Crunch',e:'🧍‍♂️',met:3.5,lv:0,tg:[],how:'Berdiri, tangan di belakang kepala, tekuk badan ke samping sambil mengangkat lutut yang sama ke arah siku.'},
 cat:{n:'Cat-Cow',e:'🐱',met:2.3,lv:0,tg:[],how:'Merangkak, lengkungkan punggung ke atas lalu turunkan perlahan mengikuti napas.'},
 ham:{n:'Peregangan Paha Belakang',e:'🧘',met:2.3,lv:0,tg:[],how:'Duduk, luruskan satu kaki, condongkan badan ke depan hingga terasa tarikan. Ganti sisi.'},
 shd:{n:'Peregangan Bahu & Dada',e:'🤗',met:2.3,lv:0,tg:[],how:'Satukan kedua tangan di belakang punggung, buka dada, tarik lengan ke bawah dan tahan.'},
 quad:{n:'Peregangan Paha Depan',e:'🧎',met:2.3,lv:0,tg:[],how:'Berdiri, tarik satu tumit ke bokong, tahan sambil berpegangan dinding. Ganti sisi.'},
 chd:{n:'Child Pose',e:'🙏',met:2.3,lv:0,tg:['flex'],how:'Duduk di atas tumit, rebahkan badan ke depan dengan lengan lurus, bernapas dalam.'},
 walk:{n:'Jalan Santai',e:'🌳',met:3.5,lv:0,tg:[],how:'Berjalan dengan kecepatan nyaman (masih bisa mengobrol). Bisa di sekitar rumah atau dalam ruangan.'}
};
const POOL_DEF={
 L:{warm:['arm','march','sidestep'],
    cardio:['jj','hk','mc','bp','box','stj','bk','sidestep','march'],
    upper:['pu','dpu','pike','kpu','wpu'],
    lower:['sq','jsq','rl','wsit','ssq','cr'],
    core:['plank','bic','dead','sidep','sup','bird','sc','lr']},
 P:{warm:['arm','march','sidestep'],
    cardio:['jj','stj','box','mc','hk','bk','sidestep','march'],
    upper:['kpu','wpu','pu','dpu','sup'],
    lower:['gb','sq','dk','fh','sla','sumo','rl','ssq','cr'],
    core:['dead','bird','plank','sc','bic','sidep','lr']}
};
const STRE=['cat','ham','shd','quad','chd'];
const DAYS=[
 {t:'Kardio + Core',mix:['cardio','core','cardio','lower','cardio','core','upper','cardio']},
 {t:'Tubuh Atas + Core',mix:['upper','core','upper','lower','core','upper','cardio','core']},
 {t:'Kardio + Kaki & Glute',mix:['cardio','lower','cardio','lower','core','cardio','lower','upper']},
 {t:'Istirahat Aktif',rest:1},
 {t:'Full Body',mix:['lower','upper','cardio','core','lower','upper','cardio','core']},
 {t:'Kardio Interval',mix:['cardio','cardio','lower','cardio','upper','cardio','core','lower']},
 {t:'Jalan Santai & Peregangan',walk:1}
];

/* ===== MESIN ATURAN (forward chaining) ===== */
function buildPlan(){
  loadS();
  const L=S.last;
  if(!L||!L.g||!L.age||L.bmi==null)return null;
  const g=L.g==='P'?'P':'L',age=+L.age,bmi=+L.bmi,bb=+S.bb||70,af=+L.af||1.375,pen=String(L.pen||'').toLowerCase(),cat=L.cat||'Normal';
  const idx=dayIndex(),week=Math.floor(idx/7)+1,today=idx%7+1,prog=Math.min(3,week-1);
  const F={
    lambung:/lambung|maag|gerd/.test(pen),
    hipertensi:/hipertensi|darah tinggi|tekanan darah/.test(pen),
    diabetes:/diabetes|gula|\bdm\b|kencing manis/.test(pen),
    kolesterol:/kolesterol/.test(pen),
    sendi:/lutut|sendi|asam urat|rematik|arthritis|encok|osteo|pengapuran/.test(pen),
    asma:/asma|sesak/.test(pen),
    jantung:/jantung/.test(pen),
    punggung:/punggung|hernia|saraf|hnp|skoliosis|pinggang/.test(pen)
  };
  const ban=new Set(),rules=[],warn=[];
  const add=(tags,t,m)=>{tags.forEach(x=>ban.add(x));rules.push([t,m])};
  /* 1. jenis kelamin */
  rules.push(g==='L'
    ?['Laki-laki','Fokus penguatan tubuh atas dan core (push-up, plank) dipadukan kardio untuk membakar lemak dan menjaga massa otot.']
    :['Perempuan','Fokus penguatan kaki, glute, dan core (squat, glute bridge, donkey kick) dengan kardio; push-up dimulai dari versi lutut/dinding.']);
  /* 2. kategori BMI */
  const over=bmi>=23,kurus=bmi<18.5;
  rules.push([`${cat} (BMI ${bmi.toFixed(1).replace('.',',')})`,
    kurus?'Porsi penguatan otot diperbanyak dan kardio dibatasi agar berat badan tidak turun lebih jauh.'
    :over?'Kardio dijadikan menu utama untuk mendukung defisit kalori, dipadukan penguatan otot agar massa otot terjaga.'
    :'Kombinasi seimbang kardio dan penguatan otot untuk menjaga kebugaran.']);
  /* 3. usia, BMI tinggi */
  if(age>=50)add(['hi','jump'],`Usia ${age} tahun`,'Gerakan lompat dan intensitas tinggi dihilangkan, durasi dikurangi.');
  if(bmi>=30)add(['hi','jump'],'Obesitas II','Semua gerakan dibuat low-impact (tanpa lompat) agar sendi lutut dan pergelangan tidak terbebani.');
  /* 4. riwayat penyakit */
  if(F.lambung)add(['prone','flex'],'Asam lambung','Gerakan yang menekan perut (plank, mountain climber, crunch, child pose) dihilangkan. Berolahraga minimal 1–2 jam setelah makan.');
  if(F.hipertensi)add(['iso','inv','hi'],'Hipertensi','Gerakan tahan posisi lama (plank, wall sit), kepala di bawah (pike push-up), dan intensitas tinggi dihilangkan. Napas teratur, jangan menahan napas.');
  if(F.jantung){add(['iso','inv','hi','jump'],'Penyakit jantung','Hanya gerakan ringan tanpa lompat dan tahan napas.');warn.push('Karena ada riwayat jantung, konsultasikan dulu dengan dokter sebelum memulai program ini. Berhenti segera jika nyeri dada atau sesak.')}
  if(F.sendi)add(['jump','hi','knee'],'Nyeri sendi / asam urat','Lompat dan tekukan lutut dalam (lunge, wall sit, sumo squat) dihilangkan. Hindari olahraga saat sedang kambuh.');
  if(F.asma){add(['hi'],'Asma','Gerakan intensitas tinggi dihilangkan dan waktu istirahat lebih panjang. Siapkan inhaler.')}
  if(F.punggung)add(['flex','back','inv'],'Masalah punggung / pinggang','Gerakan menekuk tulang belakang (crunch, leg raise, superman) dan kepala di bawah dihilangkan.');
  if(F.diabetes){rules.push(['Diabetes / gula darah','Intensitas dijaga sedang dan konsisten. Cek gula darah sebelum dan sesudah olahraga, bawa camilan cepat serap, dan jangan berolahraga saat gula darah terlalu rendah.'])}
  if(F.kolesterol){rules.push(['Kolesterol','Kardio tetap ada di setiap sesi. Usahakan konsisten minimal 150 menit per minggu.'])}
  /* 5. level, durasi, set */
  const frail=F.hipertensi||F.jantung||F.asma||F.sendi||F.punggung;
  const lv=(af>=1.55&&!frail&&age<50&&bmi<30)?1:0;
  const safe=frail||age>=50||bmi>=30;
  let T={'Kurus':20,'Normal':25,'Overweight':30,'Obesitas I':30,'Obesitas II':25}[cat]||25;
  if(lv)T+=5;if(safe)T-=5;if(age>=50)T-=3;if(af<=1.2)T-=3;
  T=Math.max(15,Math.min(40,T))+prog*2;
  const W=(lv?40:(safe?25:30))+prog*5,R=lv?15:(safe?25:20);
  const maxSets=lv?5:4;
  rules.push([lv?'Level menengah':(safe?'Level ringan':'Level pemula'),`${W} detik latihan, ${R} detik istirahat, target ±${T} menit per hari. Durasi dan intensitas naik bertahap tiap minggu${prog?` (kamu di minggu ${week})`:''}.`]);
  /* pool gerakan terfilter */
  const ok=id=>EX[id].lv<=lv&&!EX[id].tg.some(t=>ban.has(t));
  const pools={};
  ['warm','cardio','upper','lower','core'].forEach(k=>pools[k]=POOL_DEF[g][k].filter(ok));
  pools.stre=STRE.filter(ok);
  if(!pools.cardio.length)pools.cardio=['march'];
  if(!pools.warm.length)pools.warm=['arm'];
  const plan={L,g,age,bmi,bb,af,cat,F,ban,rules,warn,lv,safe,W,R,T,week,today,prog,pools,kurus,over,maxSets,days:[]};
  for(let d=1;d<=7;d++)plan.days.push(buildDay(plan,d));
  return plan;
}
function buildSteps(items){
  const st=[];
  items.forEach((it,i)=>{for(let s=1;s<=it.sets;s++){st.push({t:'work',i,s,sec:it.sec});st.push({t:'rest',i,sec:it.rest})}});
  st.pop();return st;
}
function buildDay(P,d){
  const D=DAYS[d-1],items=[],off=d+P.week;
  const mk=(id,sec,kind,sets=1,rest=10)=>({id,sec,sets,kind,rest,k:EX[id].met*P.bb*(sec*sets)/3600});
  const pickN=(pool,n)=>{const o=[];for(let i=0;i<pool.length&&o.length<n;i++)o.push(pool[(off+i)%pool.length]);return o};
  let title=D.t;
  if(D.rest||D.walk){
    items.push(mk('arm',40,'warm'));
    const mins=D.rest?10:(P.lv?20:15)+P.prog*2;
    items.push(mk('walk',mins*60,'main'));
    pickN(P.pools.stre,D.rest?4:3).forEach(id=>items.push(mk(id,45,'cool')));
  }else{
    if(d===6&&P.safe)title='Kardio Ringan';
    const warm=pickN(P.pools.warm,2);
    warm.forEach(id=>items.push(mk(id,40,'warm')));
    const used=new Set(warm);
    let mix=D.mix;
    if(P.kurus){let c=0,a=0;mix=mix.map(k=>k==='cardio'?(c++===0?'cardio':(a++%2?'upper':'lower')):k)}
    const n0=P.T<=20?5:(P.T<=30?6:7);
    const ptr={cardio:off,upper:off,lower:off,core:off},main=[];
    for(let i=0;main.length<n0&&i<60;i++){
      const key=mix[i%mix.length],pool=P.pools[key];
      if(!pool||!pool.length)continue;
      for(let k=0;k<pool.length;k++){
        const id=pool[(ptr[key]+k)%pool.length];
        if(!used.has(id)){used.add(id);ptr[key]=(ptr[key]+k+1)%pool.length;main.push(id);break}
      }
    }
    const fixed=2*40+2*40+4*10;
    const sets=Math.max(1,Math.min(P.maxSets,Math.round((P.T*60-fixed)/(Math.max(1,main.length)*(P.W+P.R)))));
    main.forEach(id=>items.push(mk(id,P.W,'main',sets,P.R)));
    pickN(P.pools.stre,2).forEach(id=>items.push(mk(id,40,'cool')));
  }
  const steps=buildSteps(items);
  const dur=steps.reduce((a,s)=>a+s.sec,0),kcal=items.reduce((a,it)=>a+it.k,0);
  return {d,title,items,steps,dur,kcal};
}

/* ===== CHECKLIST ===== */
const dkey=(w,d)=>`w${w}d${d}`;
function getChecks(w,d,items){
  const sig=items.map(i=>i.id+i.sets+'x'+i.sec).join(',');
  let r=O.done[dkey(w,d)];
  if(!r||r.sig!==sig){r={sig,c:items.map(()=>false)};O.done[dkey(w,d)]=r}
  return r.c;
}
const cnt=c=>c.filter(Boolean).length;
const kDone=(items,c)=>items.reduce((a,it,i)=>a+(c[i]?it.k:0),0);
const durTxt=s=>Math.max(1,Math.round(s/60))+' mnt';
const secTxt=it=>{const t=it.sec>=120?Math.round(it.sec/60)+' menit':it.sec+' detik';return it.sets>1?`${it.sets} set × ${t}`:t};
const kRange=k=>`~${Math.round(k*.85)}–${Math.round(k*1.15)}`;
const mmss=s=>{s=Math.max(0,s);const m=Math.floor(s/60),x=s%60;return m?`${m}:${String(x).padStart(2,'0')}`:String(x)};

/* ===== RENDER ===== */
function render(){
  P=buildPlan();
  const app=$('#app');
  if(!P){
    app.innerHTML=`<div class="card empty"><div class="big">🏃</div><h3>Analisis dulu datamu</h3><p>Program olahraga dibuat dari hasil deteksi obesitas (jenis kelamin, usia, BMI, aktivitas, dan riwayat penyakit). Lakukan analisis terlebih dahulu.</p><a class="btn btn-p" href="deteksi_obesitas.php">Ke Deteksi Obesitas →</a></div>`;
    return;
  }
  if(sel==null)sel=P.today;
  const week=P.week,D=P.days[sel-1],c=getChecks(week,sel,D.items);
  const Dt=P.days[P.today-1],ct=getChecks(week,P.today,Dt.items);
  /* ringkasan minggu */
  let daysDone=0,wItems=0,wDone=0,wK=0;
  const full=[];
  P.days.forEach(x=>{const cc=getChecks(week,x.d,x.items),n=cnt(cc);full[x.d]=n===x.items.length;if(full[x.d])daysDone++;wItems+=x.items.length;wDone+=n;wK+=kDone(x.items,cc)});
  saveO();
  const gLbl=P.g==='L'?'Laki-laki':'Perempuan';
  const lvLbl=P.lv?'Menengah':(P.safe?'Ringan':'Pemula');
  const todayFull=cnt(ct)===Dt.items.length;
  const pct=Math.round(cnt(c)/D.items.length*100);
  app.innerHTML=`
  <div class="hero-card rise">
    <div>
      <h2>Program ${gLbl}: ${esc(P.cat)}</h2>
      <p>Hari ini <b>Day ${P.today}</b> · Minggu ${week}. ${esc(Dt.title)} — ${durTxt(Dt.dur)}, ${Dt.items.length} gerakan.</p>
      <div class="hmeta"><span class="hm">Usia <b>${P.age}</b></span><span class="hm">BMI <b>${P.bmi.toFixed(1).replace('.',',')}</b></span><span class="hm">Level <b>${lvLbl}</b></span><span class="hm">Target <b>±${P.T} mnt</b></span></div>
      <div class="hero-btns"><button class="btn btn-l" data-act="start" data-d="${P.today}">${todayFull?'Ulangi latihan hari ini':'Mulai olahraga hari ini'}</button><button class="btn btn-g" data-act="tab" data-d="${P.today}">Lihat daftar gerakan</button></div>
    </div>
    <div class="hero-big">${P.g==='L'?'🏋️‍♂️':'🧘‍♀️'}</div>
  </div>
  ${P.warn.map(w=>`<div class="card mb" style="border-color:#f0dc9a;background:var(--amber-bg)"><b style="color:var(--amber)">⚠ Perhatian</b><p style="font-size:.85rem;color:var(--text);margin-top:4px">${esc(w)}</p></div>`).join('')}
  <div class="g4">
    <div class="card stat rise" style="--i:1"><div class="stat-top"><span class="stat-l">Hari selesai minggu ini</span><span class="stat-ic">📅</span></div><div class="stat-v">${daysDone}<small> / 7 hari</small></div><div class="bar"><i style="width:${daysDone/7*100}%"></i></div><div class="stat-n">${wDone} dari ${wItems} gerakan tercentang</div></div>
    <div class="card stat rise" style="--i:2"><div class="stat-top"><span class="stat-l">Hari ini · Day ${P.today}</span><span class="stat-ic">✅</span></div><div class="stat-v">${cnt(ct)}<small> / ${Dt.items.length} gerakan</small></div><div class="bar"><i style="width:${cnt(ct)/Dt.items.length*100}%"></i></div><div class="stat-n">${todayFull?'Selesai, kerja bagus! 🎉':'Lanjutkan sampai semua tercentang'}</div></div>
    <div class="card stat rise" style="--i:3"><div class="stat-top"><span class="stat-l">Kalori terbakar hari ini</span><span class="stat-ic">🔥</span></div><div class="stat-v">${fmt(kDone(Dt.items,ct))}<small> kkal</small></div><div class="bar"><i style="width:${Dt.kcal?kDone(Dt.items,ct)/Dt.kcal*100:0}%"></i></div><div class="stat-n">Target sesi ±${fmt(Dt.kcal)} kkal</div></div>
    <div class="card stat rise" style="--i:4"><div class="stat-top"><span class="stat-l">Kalori minggu ini</span><span class="stat-ic">⚡</span></div><div class="stat-v">${fmt(wK)}<small> kkal</small></div><div class="stat-n" style="margin-top:14px">Estimasi dari gerakan yang sudah dicentang</div></div>
  </div>

  <div class="tabs" id="dtabs">${P.days.map(x=>`<button class="tab${x.d===sel?' on':''}${full[x.d]?' full':''}" data-act="tab" data-d="${x.d}">Day ${x.d}${full[x.d]?' <span class="st">✓</span>':(x.d===P.today?' <span class="st">●</span>':'')}</button>`).join('')}</div>

  <div class="g2e">
    <div class="card rise">
      <div class="dhead"><div><span class="tag">Minggu ${week}${sel===P.today?' · Hari ini':''}</span><h2>Day ${sel}</h2><span class="sub">${esc(D.title)} · ${D.items.length} gerakan</span></div><button class="btn btn-p" data-act="start" data-d="${sel}">▶ Mulai</button></div>
      <div class="trio"><div><b>${lvLbl}</b><small>Level</small></div><div><b>${kRange(D.kcal)}</b><small>Kkal (~)</small></div><div><b>${durTxt(D.dur)}</b><small>Durasi</small></div></div>
      <div class="ptop"><b>Progres hari ini</b><span>${cnt(c)}/${D.items.length} · ${pct}%</span></div>
      <div class="bar"><i style="width:${pct}%"></i></div>
      <div class="ck-list">${D.items.map((it,i)=>{const x=EX[it.id];return `<button type="button" class="ck${c[i]?' on':''}" data-act="chk" data-i="${i}"><span class="bx">✓</span><span class="em">${x.e}</span><span class="tx"><b>${esc(x.n)}</b><small>${secTxt(it)}</small><em>${esc(x.how)}</em></span></button>`}).join('')}</div>
    </div>
    <div style="display:grid;gap:18px;align-content:start">
      <div class="card rise" style="--i:1"><div class="card-h"><div><div class="card-t">Penyesuaian untukmu</div><div class="card-s">Aturan yang diterapkan sistem pakar</div></div></div>
        <ul class="rlist">${P.rules.map(r=>`<li><strong>${esc(r[0])}</strong>${esc(r[1])}</li>`).join('')}</ul></div>
      <div class="card rise" style="--i:2"><div class="card-h"><div><div class="card-t">Catatan keselamatan</div></div></div>
        <ul class="rlist"><li class="w"><strong>Pemanasan & pendinginan</strong>Sudah termasuk di awal dan akhir setiap sesi. Jangan dilewati.</li>
        <li class="w"><strong>Minum air</strong>Minum 200–250 ml sebelum dan sesudah olahraga.</li>
        <li class="w"><strong>Berhenti jika</strong>Nyeri dada, pusing berat, sesak, atau nyeri tajam di sendi.</li>
        <li class="w"><strong>Kondisi khusus</strong>Jika hamil, menyusui, atau punya penyakit tertentu, konsultasikan dulu dengan dokter.</li></ul>
        <button class="btn btn-o" style="margin-top:14px;width:100%" data-act="reset">Mulai ulang program dari Day 1</button></div>
    </div>
  </div>`;
}

/* ===== EVENT ===== */
$('#app').addEventListener('click',e=>{
  const b=e.target.closest('[data-act]');if(!b)return;
  const a=b.dataset.act;
  if(a==='tab'){sel=+b.dataset.d;render();if(b.closest('.hero-btns'))window.scrollTo({top:$('#dtabs').offsetTop-90,behavior:'smooth'})}
  else if(a==='chk'){
    const D=P.days[sel-1],c=getChecks(P.week,sel,D.items),i=+b.dataset.i;
    c[i]=!c[i];saveO();render();
    if(c.every(Boolean))toast(`Day ${sel} selesai! Kerja bagus 🎉`);
  }
  else if(a==='start')openPlayer(+b.dataset.d);
  else if(a==='reset'){if(confirm('Mulai ulang program dari Day 1? Centang yang sudah ada akan dihapus.')){O={start:isoDate(new Date()),done:{}};saveO();sel=null;render();toast('Program dimulai dari Day 1')}}
});

/* ===== PEMUTAR LATIHAN ===== */
function openPlayer(d){
  const D=P.days[d-1];
  PL={d,week:P.week,items:D.items,steps:D.steps,k:-1,ph:'ready',t:10,total:10,paused:false,fail:{},done:0,timer:null};
  document.body.style.overflow='hidden';$('#player').classList.add('show');
  drawPl();PL.timer=setInterval(tickPl,1000);
}
function tickPl(){
  if(!PL||PL.paused||PL.ph==='done')return;
  PL.t--;
  if(PL.t<=0){stepDone(true)}else updRing();
}
function stepDone(natural){
  const st=PL.steps[PL.k];
  if(PL.ph==='ready'){go(0);return}
  if(PL.ph==='work'){
    const it=PL.items[st.i];
    if(!natural)PL.fail[st.i]=true;
    if(st.s===it.sets&&!PL.fail[st.i])markDone(st.i);
  }
  if(PL.k+1>=PL.steps.length){finishPl();return}
  go(PL.k+1);
}
function go(k){const st=PL.steps[k];PL.k=k;PL.ph=st.t;PL.t=PL.total=st.sec;drawPl()}
function markDone(i){
  const c=getChecks(PL.week,PL.d,PL.items);
  if(!c[i]){c[i]=true;PL.done++;saveO();render()}
}
function finishPl(){
  clearInterval(PL.timer);PL.ph='done';
  const c=getChecks(PL.week,PL.d,PL.items);
  drawPl();
  if(c.every(Boolean))toast(`Day ${PL.d} selesai! 🎉`);
}
function plPause(){if(!PL)return;PL.paused=!PL.paused;const b=$('#plP');if(b)b.textContent=PL.paused?'LANJUT':'JEDA'}
function plSkip(){if(PL&&PL.ph!=='done')stepDone(false)}
function plClose(){
  if(!PL)return;
  if(PL.ph!=='done'&&!confirm('Keluar dari latihan? Gerakan yang sudah selesai tetap tercentang.'))return;
  clearInterval(PL.timer);PL=null;
  $('#player').classList.remove('show');document.body.style.overflow='';render();
}
function updRing(){
  const r=$('#plR'),n=$('#plN');if(!r||!n)return;
  n.textContent=mmss(PL.t);r.style.strokeDashoffset=276.46*(1-PL.t/PL.total);
}
function visual(id){const x=EX[id];return `<span class="stk"><img src="assets/olahraga/${id}.gif" alt="${esc(x.n)}" onerror="this.replaceWith(document.createTextNode('${x.e}'))"></span>`}
function drawPl(){
  const el=$('#player');if(!PL)return;
  if(PL.ph==='done'){
    const c=getChecks(PL.week,PL.d,PL.items),k=kDone(PL.items,c);
    el.innerHTML=`<div class="pt"><button class="x" onclick="plClose()" aria-label="Tutup">✕</button><div class="pb"><i style="width:100%"></i></div></div>
     <div class="pdone"><div class="big">🎉</div><h2>Latihan selesai!</h2><p>${cnt(c)} dari ${PL.items.length} gerakan tercentang · ±${fmt(k)} kkal terbakar. Jangan lupa minum air dan istirahat.</p><button class="btn btn-p" style="width:100%;padding:14px" onclick="plClose()">Selesai</button></div><div></div>`;
    return;
  }
  const st=PL.steps[PL.k],isReady=PL.ph==='ready',isRest=PL.ph==='rest';
  const nx=isReady?PL.steps[0]:(isRest?PL.steps[PL.k+1]:st);
  const it=PL.items[nx.i],x=EX[it.id];
  const label=isReady?'SIAP-SIAP!':(isRest?'ISTIRAHAT':'KERJAKAN!');
  const sub=isRest?'Berikutnya':(PL.ph==='work'&&it.sets>1?`Set ${st.s} dari ${it.sets}`:(isReady?'Gerakan pertama':''));
  const prog=Math.round(nx.i/PL.items.length*100);
  el.innerHTML=`<div class="pt"><button class="x" onclick="plClose()" aria-label="Tutup">✕</button><div class="pb"><i style="width:${prog}%"></i></div><span>${nx.i+1}/${PL.items.length}</span></div>
   <div class="pbody"><div class="pvis ${PL.ph==='work'?'move':''}">${visual(it.id)}</div>
   <div><div class="pst">${label}</div><div class="pnm">${esc(x.n)}</div><div class="psub">${sub||'&nbsp;'}</div><div class="phow">${esc(x.how)}</div>
   <div class="pring ${isRest?'rest':''}"><svg viewBox="0 0 100 100"><circle class="pb" cx="50" cy="50" r="44"/><circle class="pv" id="plR" cx="50" cy="50" r="44"/></svg><b id="plN">${mmss(PL.t)}</b></div>
   <div class="pbtn"><button class="a" id="plP" onclick="plPause()">${PL.paused?'LANJUT':'JEDA'}</button><button class="b" onclick="plSkip()">LEWATI</button></div></div></div>`;
  updRing();
}
document.addEventListener('keydown',e=>{if(e.key==='Escape'&&PL)plClose()});

/* ===== INIT ===== */
function tick(){const n=new Date();
  $('#clkT').textContent=n.toLocaleTimeString('id-ID',{hour:'2-digit',minute:'2-digit',second:'2-digit',hour12:false}).replace(/\./g,':');
  $('#clkD').textContent=n.toLocaleDateString('id-ID',{weekday:'long',day:'2-digit',month:'long',year:'numeric'})}
tick();setInterval(tick,1000);
loadO();render();
</script>
</body>
</html>