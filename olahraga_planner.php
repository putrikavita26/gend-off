<?php
// GEND-OFF — Olahraga Harian (program olahraga di rumah tanpa alat, personal sesuai hasil deteksi)
// Letakkan di: htdocs/gend-off/olahraga_planner.php | aset di: assets/
// Peraga gerakan berupa animasi SVG (pria/wanita sesuai hasil deteksi), tidak butuh file gambar tambahan.
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
.pvis{height:clamp(170px,34vh,300px);width:100%;flex:none;display:flex;justify-content:center}
.pvis svg{height:100%;width:100%;display:block}
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

/* figur peraga */
.ck .em{width:54px;height:54px;border-radius:12px;background:var(--bg);overflow:hidden;font-size:0;padding:2px}
.ck .em svg{width:100%;height:100%;display:block}
/* perjalanan menuju target */
.jr{padding:26px 28px;margin-bottom:18px}
.jr-top{display:flex;justify-content:space-between;align-items:flex-start;gap:14px;flex-wrap:wrap}
.jr h2{font-size:1.5rem;margin:8px 0 4px}
.jr p.s{font-size:.86rem;color:var(--muted);max-width:640px}
.jst{padding:6px 14px;border-radius:99px;font-size:.78rem;font-weight:800;white-space:nowrap}
.jst.ok{background:var(--green-bg);color:var(--green)}.jst.warn{background:var(--amber-bg);color:var(--amber)}.jst.info{background:var(--blue-bg);color:var(--blue)}
.jgrid{display:grid;grid-template-columns:repeat(5,1fr);gap:10px;margin:18px 0 8px}
.jgrid div{padding:12px 8px;border-radius:12px;background:var(--bg);border:1px solid var(--border-soft);text-align:center}
.jgrid b{display:block;font-family:var(--mono);font-size:1rem;color:var(--dark);font-weight:600;line-height:1.3}
.jgrid small{font-size:.7rem;font-weight:700;color:var(--faint)}
.tl-w{margin-top:34px}
.tl{position:relative;display:flex;gap:3px;height:34px}
.sg{display:grid;place-items:center;font-size:.7rem;font-weight:800;border-radius:8px;overflow:hidden;white-space:nowrap;padding:0 4px;min-width:6px}
.sg-a{background:#d3e8f2;color:#1d5a78}.sg-m{background:#e2edb9;color:#4a6414}.sg-f{background:#c9de93;color:#3b5210}.sg-p{background:#d6efe2;color:#0f7a4f}
.tlm{position:absolute;top:-27px;transform:translateX(-50%);padding:2px 8px;border-radius:99px;background:var(--dark);color:#fff;font-size:.66rem;font-weight:800;white-space:nowrap}
.tlm::after{content:"";position:absolute;left:50%;bottom:-4px;margin-left:-4px;border:4px solid transparent;border-bottom:0;border-top-color:var(--dark)}
.tl-n{font-size:.74rem;color:var(--faint);margin-top:6px;font-weight:600}
.jlog{display:flex;gap:10px;margin-top:16px;flex-wrap:wrap;align-items:center}
.jlog label{font-size:.84rem;font-weight:700;color:var(--dark)}
.jlog input{width:110px;padding:10px 12px;border-radius:10px;border:1.5px solid var(--border);font:600 .9rem var(--mono);color:var(--text);background:#fff}
.jlog input:focus{outline:none;border-color:var(--olive)}
.jhint{font-size:.74rem;color:var(--faint)}
/* deretan minggu */
.wk-h{display:flex;justify-content:space-between;align-items:baseline;gap:10px;flex-wrap:wrap;margin:6px 2px 8px}
.wk-h b{font-size:.98rem;color:var(--dark)}.wk-h span{font-size:.74rem;color:var(--faint)}
.wks{display:flex;gap:8px;overflow-x:auto;padding:4px 2px 12px;margin-bottom:6px}
.wk{position:relative;flex:0 0 auto;width:60px;padding:11px 4px 8px;border-radius:12px;border:1.5px solid var(--border);background:#fff;text-align:center;overflow:hidden;transition:.15s}
.wk:hover{border-color:var(--olive)}
.wk b{display:block;font-family:var(--mono);font-size:1rem;font-weight:600;color:var(--dark);line-height:1.2}
.wk small{display:block;font-size:.68rem;font-weight:700;color:var(--faint);margin-top:2px;min-height:1em}
.wk .pc{position:absolute;left:0;right:0;top:0;height:4px}
.pc-a{background:#8cc3dc}.pc-m{background:var(--lime)}.pc-f{background:var(--olive)}.pc-p{background:#2fa874}
.wk.fut{opacity:.72}
.wk.ok{background:var(--green-bg);border-color:#bfe6cf}.wk.ok small{color:var(--green)}
.wk.part small{color:var(--blue)}.wk.miss small{color:#c4cdc8}
.wk.cur{box-shadow:0 0 0 2px var(--lime)}
.wk.on{background:linear-gradient(135deg,var(--dark),var(--dark2));border-color:var(--dark);opacity:1}
.wk.on b,.wk.on small{color:#fff}
.wkh{display:flex;justify-content:space-between;align-items:center;gap:16px;flex-wrap:wrap}
.wkh h2{font-size:1.35rem;margin:8px 0 4px}
.wkh p{font-size:.84rem;color:var(--muted);max-width:560px}
.hmeta2{display:flex;gap:8px;flex-wrap:wrap}
.hmeta2 span{padding:6px 12px;border-radius:99px;background:var(--bg);border:1px solid var(--border-soft);font-size:.76rem;font-weight:600;color:var(--muted)}
.hmeta2 b{font-family:var(--mono);font-weight:600;color:var(--dark)}
@media(max-width:900px){.jgrid{grid-template-columns:repeat(2,1fr)}.jr{padding:22px 18px}}

@media(max-width:1180px){.g4{grid-template-columns:1fr 1fr}.g2e{grid-template-columns:1fr}}
@media(max-width:900px){
  .sidebar{position:fixed;left:0;top:0;transform:translateX(-100%)}.sidebar.open{transform:none}
  .scrim.show{opacity:1;pointer-events:auto}
  .burger{display:flex}.chip-user div,.clk small,.clk-ic{display:none}.chip-user{padding:5px}.clk{padding:8px 12px}
  .topbar{padding:12px 16px}.content{padding:18px 16px 50px}
  .hero-card{padding:24px 20px}.hero-big{display:none}.sec-h h1{font-size:1.4rem}
}
@media(max-width:560px){.g4{grid-template-columns:1fr}.dhead h2{font-size:1.6rem}.ck .em{width:44px;height:44px}}
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
        <div class="sec-h"><h1>Olahraga Harian</h1><p>Jadwal olahraga berminggu-minggu yang mengikuti estimasi waktu capai target berat badanmu, disesuaikan dengan jenis kelamin, BMI, usia, aktivitas, dan riwayat penyakit. Semua gerakan bisa dilakukan di rumah tanpa alat.</p></div>
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
  loadS();
  if(!O||!O.start)O={start:isoDate(new Date()),done:{}};
  if(!O.done)O.done={};
  if(!O.fz)O.fz={};
  if(!O.w0)O.w0=+S.bb||0;
  saveO();
}
function dayIndex(){
  const [y,m,d]=O.start.split('-').map(Number),a=new Date(y,m-1,d),n=new Date(),b=new Date(n.getFullYear(),n.getMonth(),n.getDate());
  return Math.max(0,Math.round((b-a)/864e5));
}

/* ===== FIGUR ANIMASI (rig 2D: sudut sendi → SVG) ===== */
const FG={G:178,T:46,NK:8,HR:12,UA:27,FA:25,TH:37,SH:36,FT:11};
const D2R=Math.PI/180,dr=a=>[Math.sin(a*D2R),-Math.cos(a*D2R)];
const vAdd=(p,v,l)=>[p[0]+v[0]*l,p[1]+v[1]*l];
const lerp=(a,b,t)=>a+(b-a)*t;
const lerpA=(a,b,t)=>a.map((x,i)=>lerp(x,b[i],t));
const POSE0={ta:0,hd:0,bw:0,an:[180,180],af:[180,180],ln:[180,180],lf:[180,180],lift:0,dx:0,anc:'ln',fn:null,ff:null};
const COL={
 L:{skin:'#d28a4e',skinS:'#b8763f',top:'#2c2e35',topS:'#202228',pants:'#e3a712',pantsS:'#c48d0b',hair:'#241f1d',hairS:'#241f1d',sh:'#e7e8ea'},
 P:{skin:'#e6a67d',skinS:'#cf9168',top:'#f5c45a',topS:'#dcab42',pants:'#2b2f57',pantsS:'#1f2244',hair:'#7a4936',hairS:'#7a4936',sh:'#e7e8ea'}
};
function poseMix(A,B,t){
  const a={...POSE0,...A},b={...POSE0,...B},o={};
  ['ta','hd','bw','lift','dx'].forEach(k=>o[k]=lerp(a[k],b[k],t));
  ['an','af','ln','lf'].forEach(k=>o[k]=lerpA(a[k],b[k],t));
  ['fn','ff'].forEach(k=>o[k]=(a[k]==null&&b[k]==null)?null:lerp(a[k]==null?NaN:a[k],b[k]==null?NaN:b[k],t));
  o.anc=t<.5?a.anc:b.anc;
  return o;
}
function limbPts(root,ang,l1,l2){const m=vAdd(root,dr(ang[0]),l1);return [root,m,vAdd(m,dr(ang[1]),l2)]}
/* hitung semua titik tubuh; front=tampak depan */
function fig(p,g,front){
  const F=g==='P',SW=front?(F?12:14):0,HW=front?(F?10:8.5):0;
  const H=[0,0],tv=dr(p.ta),S=vAdd(H,tv,FG.T),pp=dr(p.ta+90);
  const sN=vAdd(S,pp,SW),sF=vAdd(S,pp,-SW),hN=vAdd(H,pp,HW),hF=vAdd(H,pp,-HW);
  const arm=(r,a)=>limbPts(r,a,FG.UA,FG.FA),leg=(r,a)=>limbPts(r,a,FG.TH,FG.SH);
  const o={H,S,sN,sF,hN,hF,an:arm(sN,p.an),af:arm(sF,p.af),ln:leg(hN,p.ln),lf:leg(hF,p.lf),pp,tv};
  const foot=(l,a,ov,side)=>{
    let fa;
    if(ov!=null&&!isNaN(ov))fa=ov;else fa=front?(side>0?100:-100):a[1]-90;
    return vAdd(l[2],dr(fa),FG.FT);
  };
  o.tn=foot(o.ln,p.ln,p.fn,1);o.tf=foot(o.lf,p.lf,p.ff,-1);
  o.hv=dr(p.ta+p.hd);
  o.C=vAdd(S,o.hv,FG.NK+FG.HR);
  /* tinggi kontak lantai */
  const pts=[[H,9],[S,8],[o.C,FG.HR]];
  [o.an,o.af].forEach(a=>{pts.push([a[1],5.5],[a[2],5.5])});
  [o.ln,o.lf].forEach(l=>{pts.push([l[1],7],[l[2],4.5])});
  pts.push([o.tn,4],[o.tf,4]);
  let maxY=-1e9;pts.forEach(([q,r])=>{if(q[1]+r>maxY)maxY=q[1]+r});
  const ancMap={ln:o.ln[2],lf:o.lf[2],an:o.an[2],af:o.af[2],hip:H,sh:S};
  const ax=(ancMap[p.anc]||H)[0];
  const sx=120-ax+p.dx,sy=FG.G-p.lift-maxY;
  const sh=q=>[q[0]+sx,q[1]+sy];
  ['H','S','sN','sF','hN','hF','C','tn','tf'].forEach(k=>o[k]=sh(o[k]));
  ['an','af','ln','lf'].forEach(k=>o[k]=o[k].map(sh));
  o.lift=p.lift;o.p=p;
  return o;
}
function allPts(o){return [o.H,o.S,o.C,...o.an,...o.af,...o.ln,...o.lf,o.tn,o.tf]}
/* gambar */
const ln_=(a,b,w,c,cap)=>`<line x1="${a[0].toFixed(1)}" y1="${a[1].toFixed(1)}" x2="${b[0].toFixed(1)}" y2="${b[1].toFixed(1)}" stroke="${c}" stroke-width="${w}" stroke-linecap="${cap||'round'}"/>`;
const circ_=(c,r,f)=>`<circle cx="${c[0].toFixed(1)}" cy="${c[1].toFixed(1)}" r="${r}" fill="${f}"/>`;
const poly_=(pts,f)=>`<polygon points="${pts.map(q=>q[0].toFixed(1)+','+q[1].toFixed(1)).join(' ')}" fill="${f}"/>`;
const mixP=(a,b,t)=>[lerp(a[0],b[0],t),lerp(a[1],b[1],t)];
function figSVG(o,g,front,far){
  const c=COL[g],F=g==='P',p=o.p;let s='';
  const legW1=F?17:17,legW2=13;
  const drawLeg=(l,toe,shade)=>{
    const pc=shade?c.pantsS:c.pants,sk=shade?c.skinS:c.skin;
    return ln_(l[0],l[1],legW1,pc)+ln_(l[1],l[2],legW2,pc)+ln_(l[2],toe,8,sk)+circ_(l[2],4.6,sk);
  };
  const drawArm=(a,shade)=>{
    const sk=shade?c.skinS:c.skin,tp=shade?c.topS:c.top;
    return circ_(a[0],front?5.5:6,tp)+ln_(a[0],a[1],10,sk)+ln_(a[1],a[2],8.5,sk)+circ_(a[2],5,sk);
  };
  const base=far.base;
  s+=far.shadow;
  s+=drawArm(o.af,1)+drawLeg(o.lf,o.tf,1);
  /* batang tubuh */
  const H=front?mixP(o.hN,o.hF,.5):o.H,S=front?mixP(o.sN,o.sF,.5):o.S;
  const bw=p.bw||0,pv=dr(p.ta-90);
  const ctrl=[ (H[0]+S[0])/2+pv[0]*bw*2,(H[1]+S[1])/2+pv[1]*bw*2 ];
  const bez=t=>[(1-t)*(1-t)*H[0]+2*t*(1-t)*ctrl[0]+t*t*S[0],(1-t)*(1-t)*H[1]+2*t*(1-t)*ctrl[1]+t*t*S[1]];
  const seg=(t0,t1,w,col,cap)=>{const n=Math.max(2,Math.round((t1-t0)*14));let d='';for(let i=0;i<=n;i++){const q=bez(t0+(t1-t0)*i/n);d+=(i?'L':'M')+q[0].toFixed(1)+' '+q[1].toFixed(1)}return `<path d="${d}" fill="none" stroke="${col}" stroke-width="${w}" stroke-linejoin="round" stroke-linecap="${cap||'butt'}"/>`};
  if(front){
    const q=(t,side)=>side>0?[lerp(o.hN[0],o.sN[0],t),lerp(o.hN[1],o.sN[1],t)]:[lerp(o.hF[0],o.sF[0],t),lerp(o.hF[1],o.sF[1],t)];
    const band=(t0,t1,col)=>poly_([q(t0,1),q(t1,1),q(t1,-1),q(t0,-1)],col);
    s+=band(0,.22,c.pants);
    if(F){s+=band(.2,.52,c.skin);s+=band(.5,1,c.top)}else s+=band(.2,1,c.top);
    s+=circ_(o.sN,F?5:6.5,c.top)+circ_(o.sF,F?5:6.5,c.top)+circ_(o.hN,5,c.pants)+circ_(o.hF,5,c.pants);
  }else{
    const tw=F?19:23;
    s+=circ_(H,F?10:10.5,c.pants);
    s+=seg(0,.22,tw,c.pants);
    if(F){s+=seg(.2,.52,tw-3,c.skin);s+=seg(.5,1,tw,c.top)}else s+=seg(.2,1,tw,c.top);
    s+=circ_(S,F?9:11,c.top);
  }
  s+=drawLeg(o.ln,o.tn,0);
  /* leher & kepala */
  s+=ln_(S,o.C,F?6.5:8,c.skin);
  const e=dr(p.ta+p.hd+90),u=o.hv,C=o.C;
  if(front){
    s+=circ_(C,12.6,c.hair);
    s+=circ_([C[0]-u[0]*-1.8,C[1]-u[1]*-1.8],10.6,c.skin);
    if(F)s+=circ_(vAdd(C,u,12),5.6,c.hair);
    else s+=circ_(vAdd(C,u,-0.3),0.01,c.hair);
  }else{
    if(F)s+=circ_(vAdd(vAdd(C,e,-9),u,8),6.2,c.hair);
    s+=circ_(vAdd(vAdd(C,e,-2.2),u,1.2),12.6,c.hair);
    s+=circ_(vAdd(vAdd(C,e,1.8),u,-0.8),10.4,c.skin);
  }
  s+=drawArm(o.an,0);
  return s;
}

/* ===== DATA GERAKAN ===== */
const ang=v=>Math.atan2(v[0],-v[1])/D2R;
function ikA(r,t,l1,l2,b){
  let dx=t[0]-r[0],dy=t[1]-r[1];const dd=Math.hypot(dx,dy)||1,ux=dx/dd,uy=dy/dd;
  const d=Math.min(l1+l2-.01,Math.max(Math.abs(l1-l2)+.01,dd));
  const a=(l1*l1-l2*l2+d*d)/(2*d),h=Math.sqrt(Math.max(0,l1*l1-a*a));
  const e=[r[0]+ux*a-uy*h*b,r[1]+uy*a+ux*h*b],tt=[r[0]+ux*d,r[1]+uy*d];
  return [ang([e[0]-r[0],e[1]-r[1]]),ang([tt[0]-e[0],tt[1]-e[1]])];
}
const neg=a=>a.map(x=>-x);
const FRp=(an,ln,o)=>({an,af:neg(an),ln,lf:neg(ln),...(o||{})});
const LB=FG.T+FG.TH+FG.SH;
/* tumpuan tangan & kaki (push-up / mountain climber): pergelangan kaki di (0,0), tinggi bahu sy (negatif = di atas lantai) */
const TOE=160,WY=FG.FT*(-Math.cos(TOE*D2R))+4-5.5;
function pushPose(sy,Wx,o){
  o=o||{};const sx=Math.sqrt(LB*LB-sy*sy),S=[sx,sy],ta=ang(S),la=ta-180;
  const W=[Wx,WY];
  return {ta,hd:o.hd==null?8:o.hd,ln:[la,la],lf:[la,la],an:ikA(S,W,FG.UA,FG.FA,1),af:ikA(S,[Wx-3,WY],FG.UA,FG.FA,1),fn:TOE,ff:TOE,anc:'an'};
}
const PX=Math.sqrt(LB*LB-(WY-52)*(WY-52));
const pkA=pushPose(WY-51,PX),pkB=pushPose(WY-24,PX);
/* push-up dari lutut */
function kneePose(sy,Wx){
  const KL=FG.TH+FG.T,sx=Math.sqrt(KL*KL-sy*sy),S=[sx,sy],ta=ang(S),la=ta-180,W=[Wx,1.5];
  return {ta,hd:8,ln:[la,-60],lf:[la,-62],an:ikA(S,W,FG.UA,FG.FA,1),af:ikA(S,[Wx-3,1.5],FG.UA,FG.FA,1),fn:-150,ff:-150,anc:'an'};
}
const KX=Math.sqrt((FG.TH+FG.T)**2-(1.5-51)**2);
/* plank siku */
function elbPose(sy,o){
  o=o||{};const sx=Math.sqrt(LB*LB-sy*sy),S=[sx,sy],ta=ang(S),la=ta-180;
  const E=[sx,sy+FG.UA];
  return {ta,hd:8,ln:[la,la],lf:[la,la],an:[180,90],af:[180,92],fn:TOE,ff:TOE,anc:'an',...(o||{})};
}
/* pike: pergelangan kaki (0,0), tangan di (Wx,WY), bahu S; pinggul = perpotongan lingkaran */
function pikePose(Wx,S,fo){
  const A=[0,0],r1=FG.TH+FG.SH,r2=FG.T,dx=S[0]-A[0],dy=S[1]-A[1],d=Math.hypot(dx,dy);
  const a=(r1*r1-r2*r2+d*d)/(2*d),h=Math.sqrt(Math.max(0,r1*r1-a*a)),ux=dx/d,uy=dy/d;
  const Hh=[ux*a+uy*h,uy*a-ux*h];
  const la=ang([Hh[0]-A[0],Hh[1]-A[1]]);       /* arah dari pergelangan ke pinggul */
  const lg=la-180;                               /* arah dari pinggul ke kaki */
  const ta=ang([S[0]-Hh[0],S[1]-Hh[1]]);
  return {ta,hd:-40,ln:[lg,lg],lf:[lg,lg],an:ikA(S,[Wx,WY],FG.UA,FG.FA,1),af:ikA(S,[Wx-3,WY],FG.UA,FG.FA,1),fn:150,ff:150,anc:'an'};
}
const XD={};
/* ---------- BERDIRI ---------- */
XD.arm={v:'s',sp:2.2,A:{an:[15,15],af:[40,40],ln:[177,182],lf:[184,180],anc:'ln'},B:{an:[200,200],af:[228,228],ln:[177,182],lf:[184,180],anc:'ln'}};
XD.march={v:'s',sp:1.4,A:{ta:2,ln:[88,178],lf:[180,180],an:[205,130],af:[140,60],anc:'hip'},B:{ta:2,ln:[180,180],lf:[88,178],an:[140,60],af:[205,130],anc:'hip'}};
XD.sidestep={v:'f',sp:1.6,A:{...FRp([160,-40],[155,170]),lf:[-178,-180],dx:12,ta:3},B:{...FRp([172,-30],[178,180]),dx:-12,ta:-3}};
XD.box={v:'s',sp:1.0,A:{ta:8,hd:-8,ln:[196,184],lf:[158,186],an:[90,90],af:[150,25],anc:'lf'},B:{ta:8,hd:-8,ln:[196,184],lf:[158,186],an:[150,25],af:[90,90],anc:'lf'}};
XD.stj={v:'f',sp:1.4,A:FRp([166,166],[178,180]),B:{...FRp([32,28],[150,172]),lf:[-178,-180],dx:10}};
XD.bk={v:'s',sp:1.0,A:{ta:6,hd:-6,ln:[194,-22],lf:[180,180],an:[140,60],af:[205,130],anc:'hip'},B:{ta:6,hd:-6,ln:[180,180],lf:[194,-22],an:[205,130],af:[140,60],anc:'hip'}};
XD.jj={v:'f',sp:1.3,A:FRp([166,166],[178,180]),B:{...FRp([34,28],[152,156]),lift:7}};
XD.hk={v:'s',sp:1.0,A:{ta:4,ln:[72,176],lf:[182,182],an:[210,120],af:[135,50],anc:'hip',lift:3},B:{ta:4,ln:[182,182],lf:[72,176],an:[135,50],af:[210,120],anc:'hip',lift:3}};
XD.mc={v:'s',sp:0.9,A:{...pkA,ln:[96,196],lf:[pkA.ln[0],pkA.ln[0]]},B:{...pkA,lf:[96,196],ln:[pkA.ln[0],pkA.ln[0]]}};
XD.bp={v:'s',sp:2.0,A:{ta:0,ln:[175,185],lf:[178,186],an:[8,8],af:[22,22],lift:12,anc:'ln'},B:{ta:58,hd:-38,ln:[100,200],lf:[102,202],an:[172,168],af:[176,172],anc:'ln'}};
XD.sq={v:'s',sp:2.2,A:{ta:0,ln:[180,180],lf:[182,182],an:[180,180],af:[184,184],anc:'ln'},B:{ta:38,hd:-30,ln:[100,195],lf:[104,196],an:[88,88],af:[94,94],anc:'ln'}};
XD.ssq={v:'s',sp:2.0,A:{ta:0,ln:[180,180],lf:[182,182],an:[180,180],af:[184,184],anc:'ln'},B:{ta:22,hd:-18,ln:[125,190],lf:[128,191],an:[95,95],af:[100,100],anc:'ln'}};
XD.jsq={v:'s',sp:1.6,A:{ta:38,hd:-30,ln:[100,195],lf:[104,196],an:[205,205],af:[210,210],anc:'ln'},B:{ta:0,ln:[177,183],lf:[181,185],an:[15,15],af:[28,28],lift:15,anc:'ln'}};
XD.sumo={v:'f',sp:2.2,A:FRp([172,170],[158,160]),B:FRp([165,-35],[118,188])};
XD.rl={v:'s',sp:2.2,A:{ta:0,ln:[180,180],lf:[182,182],an:[190,190],af:[195,195],anc:'ln'},B:{ta:2,ln:[88,180],lf:[188,256],an:[200,150],af:[205,150],anc:'ln'}};
XD.wsit={v:'s',sp:2.6,wall:1,A:{ta:0,ln:[168,176],lf:[170,178],an:[180,180],af:[183,183],anc:'hip'},B:{ta:0,ln:[90,180],lf:[92,182],an:[110,100],af:[112,102],anc:'hip'}};
XD.cr={v:'s',sp:1.4,A:{ta:0,ln:[180,180],lf:[182,182],an:[200,155],af:[204,158],anc:'ln',fn:90,ff:90},B:{ta:0,ln:[180,180],lf:[182,182],an:[200,155],af:[204,158],anc:'ln',fn:128,ff:128}};
/* ---------- BERBARING / MERANGKAK ---------- */
const AF={ta:76,hd:-10,an:[180,180],af:[182,182],ln:[180,-90],lf:[182,-90],fn:-90,ff:-90,anc:'hip'};
XD.gb={v:'s',sp:2.0,A:{ta:90,hd:0,an:[-95,-95],af:[-100,-100],ln:[-50,-150],lf:[-54,-152],anc:'ln'},B:{ta:112,hd:-22,an:[-95,-95],af:[-100,-100],ln:[-112,-165],lf:[-114,-167],anc:'ln'}};
XD.dk={v:'s',sp:1.4,A:{...AF},B:{...AF,ln:[-68,22],fn:-68}};
XD.fh={v:'s',sp:1.6,A:{...AF},B:{...AF,ln:[-82,-165],fn:-120}};
XD.sla={v:'s',sp:1.6,A:{ta:90,hd:0,an:[160,100],af:[170,100],ln:[-92,-92],lf:[-90,-90],anc:'hip'},B:{ta:90,hd:0,an:[160,100],af:[170,100],ln:[-92,-92],lf:[-62,-62],anc:'hip'}};
XD.pu={v:'s',sp:1.8,A:pkA,B:pkB};
XD.dpu={v:'s',sp:1.8,A:pkA,B:pkB};
XD.kpu={v:'s',sp:1.8,A:kneePose(1.5-51,KX),B:kneePose(1.5-24,KX)};
XD.pike={v:'s',sp:2.0,A:pikePose(70,[70,WY-52]),B:pikePose(70,[84,WY-26])};
XD.plank={v:'s',sp:2.0,A:{...elbPose(1.5-FG.UA),bw:-4},B:{...elbPose(1.5-FG.UA),bw:5,hd:14}};
XD.sidep={v:'s',sp:2.0,A:{...kneePose(-14,0),an:[180,90],af:[0,0],hd:-5},B:{...kneePose(-27,0),an:[180,90],af:[0,0],hd:-5}};
XD.sup={v:'s',sp:1.6,A:{ta:90,hd:0,an:[92,92],af:[96,96],ln:[-90,-90],lf:[-92,-92],anc:'hip'},B:{ta:82,hd:-12,bw:6,an:[58,58],af:[62,62],ln:[-60,-60],lf:[-64,-64],anc:'hip'}};
XD.dead={v:'s',sp:2.6,A:{ta:90,an:[0,0],af:[4,4],ln:[0,-90],lf:[4,-88],anc:'hip'},B:{ta:90,an:[96,96],af:[4,4],ln:[0,-90],lf:[-94,-94],anc:'hip'}};
XD.bird={v:'s',sp:2.4,A:{...AF,ta:76},B:{...AF,ta:76,an:[88,88],lf:[-86,-86],ff:-60}};
XD.bic={v:'s',sp:1.4,A:{ta:72,hd:-14,an:[38,-130],af:[44,-128],ln:[18,-82],lf:[-74,-74],anc:'hip'},B:{ta:72,hd:-14,an:[38,-130],af:[44,-128],ln:[-74,-74],lf:[18,-82],anc:'hip'}};
XD.lr={v:'s',sp:2.2,A:{ta:90,an:[-92,-92],af:[-96,-96],ln:[-88,-88],lf:[-90,-90],anc:'hip'},B:{ta:90,an:[-92,-92],af:[-96,-96],ln:[-4,-4],lf:[0,0],anc:'hip'}};
XD.sc={v:'f',sp:1.8,A:{...FRp([75,-30],[178,180]),ta:-10,hd:10,lf:[-118,-178]},B:{...FRp([75,-30],[118,178]),ta:10,hd:-10,lf:[-178,-180]}};
XD.cat={v:'s',sp:2.6,A:{...AF,ta:76,bw:-9,hd:-28},B:{...AF,ta:76,bw:13,hd:34}};
XD.ham={v:'s',sp:3.0,A:{ta:0,hd:0,ln:[90,90],lf:[60,-130],an:[170,175],af:[175,178],anc:'hip',fn:null},B:{ta:62,hd:-30,ln:[90,90],lf:[60,-130],an:[92,92],af:[96,96],anc:'hip'}};
XD.shd={v:'s',sp:2.6,A:{ta:0,ln:[180,180],lf:[182,182],an:[195,195],af:[198,198],anc:'ln'},B:{ta:-4,hd:-8,ln:[180,180],lf:[182,182],an:[232,232],af:[236,236],anc:'ln'}};
XD.quad={v:'s',sp:3.0,A:{ta:0,ln:[180,180],lf:[182,182],an:[180,180],af:[100,100],anc:'ln'},B:{ta:0,ln:[180,180],lf:[196,-18],an:ikA([0,-FG.T],[-21,1],FG.UA,FG.FA,1),af:[100,100],anc:'ln'}};
XD.chd={v:'s',sp:3.2,A:{ta:0,ln:[180,-90],lf:[182,-90],an:[20,20],af:[26,26],fn:-90,ff:-90,anc:'hip'},B:{ta:78,hd:-30,ln:[108,-90],lf:[110,-90],an:[90,90],af:[94,94],fn:-90,ff:-90,anc:'hip'}};
XD.walk={v:'s',sp:1.6,A:{ta:2,ln:[154,170],lf:[204,196],an:[205,180],af:[150,130],anc:'hip'},B:{ta:2,ln:[204,196],lf:[154,170],an:[150,130],af:[205,180],anc:'hip'}};
/* push-up dinding */
(function(){
  const p1=14,S1=[LB*Math.sin(p1*D2R),-LB*Math.cos(p1*D2R)],W=[S1[0]+FG.UA+FG.FA-.5,S1[1]];
  let p2=p1;for(let a=p1;a<60;a+=.25){const S=[LB*Math.sin(a*D2R),-LB*Math.cos(a*D2R)];if(Math.hypot(S[0]-W[0],S[1]-W[1])<=31){p2=a;break}}
  const mk=phi=>{const S=[LB*Math.sin(phi*D2R),-LB*Math.cos(phi*D2R)];return {ta:phi,hd:-phi*.6,ln:[180+phi,180+phi],lf:[180+phi,180+phi],an:ikA(S,W,FG.UA,FG.FA,1),af:ikA(S,[W[0]-1,W[1]],FG.UA,FG.FA,1),fn:90,ff:90,anc:'ln'}};
  XD.wpu={v:'s',sp:2.0,wallR:120+W[0]+4,A:mk(p1),B:mk(p2)};
})();

/* bbox gabungan & render per gerakan */
const BB={};
function exBox(id){
  if(BB[id])return BB[id];
  const X=XD[id];let x0=1e9,y0=1e9,x1=-1e9,y1=-1e9;
  ['L','P'].forEach(g=>[0,.25,.5,.75,1].forEach(t=>{
    const o=fig(poseMix(X.A,X.B,t),g,X.v==='f');
    allPts(o).forEach(q=>{x0=Math.min(x0,q[0]);x1=Math.max(x1,q[0]);y0=Math.min(y0,q[1]);y1=Math.max(y1,q[1])});
  }));
  x0-=16;x1+=16;y0-=32;y1=FG.G+12;
  let w=x1-x0,h=y1-y0;const s=Math.max(w,h*1.0,120);
  const cx=(x0+x1)/2;
  BB[id]={x:cx-s/2,y:y1-s,w:s,h:s,cx,w0:x1-x0};
  return BB[id];
}
function exInner(id,g,t){
  const X=XD[id],b=exBox(id),front=X.v==='f',p=poseMix(X.A,X.B,t),o=fig(p,g,front);
  const sc=COL[g].sh,k=1-Math.min(.5,(o.lift||0)/40);
  const rx=Math.max(26,Math.min(95,b.w0*.5))*k;
  const shadow=`<ellipse cx="${b.cx.toFixed(1)}" cy="${FG.G+1}" rx="${rx.toFixed(1)}" ry="${(7*k).toFixed(1)}" fill="${sc}"/>`;
  const wall=X.wall?`<rect x="${(o.H[0]-24).toFixed(1)}" y="20" width="9" height="${FG.G-20}" fill="#d6dad2"/>`:'';
  const extra=X.wallR?`<rect x="${X.wallR.toFixed(1)}" y="20" width="9" height="${FG.G-20}" fill="#d6dad2"/>`:'';
  return `${wall}${extra}${figSVG(o,g,front,{shadow})}`;
}
const exVB=id=>{const b=exBox(id);return `${b.x.toFixed(1)} ${b.y.toFixed(1)} ${b.w.toFixed(1)} ${b.h.toFixed(1)}`};
function exSVG(id,g,t,style){return `<svg viewBox="${exVB(id)}" style="${style||''}" xmlns="http://www.w3.org/2000/svg">${exInner(id,g,t)}</svg>`}

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

/* ===== PERJALANAN MENUJU TARGET ===== */
const MAXW=104;
const f1=n=>(Math.round(n*10)/10).toFixed(1).replace('.',',');
function journey(week,idx){
  const bb=+S.bb||0,goal=+S.goal||bb,diff=bb-goal;
  const mode=diff>.25?'lose':(diff<-.25?'gain':'keep');
  const rate=mode==='gain'?.4:.5;                       /* kg per minggu (sama dengan estimasi di Deteksi Obesitas) */
  const rem=mode==='keep'?0:Math.min(MAXW,Math.max(1,Math.ceil(Math.abs(diff)/rate)));
  const w0=+O.w0||bb,tot=Math.abs(w0-goal),moved=mode==='gain'?bb-w0:w0-bb;
  const pct=mode==='keep'?100:(tot>.05?Math.max(0,Math.min(100,moved/tot*100)):0);
  const N=mode==='keep'?week-1:Math.min(MAXW,week-1+rem);
  const e=idx/7,exp=mode==='gain'?Math.min(goal,w0+rate*e):Math.max(goal,w0-rate*e);
  let st=null;
  if(mode!=='keep'){
    const dl=mode==='gain'?exp-bb:bb-exp;
    st=idx<7?['Baru mulai','info']:(dl<=-.4?['Lebih cepat dari rencana','ok']:(dl<=.4?['Sesuai jalur','ok']:['Sedikit tertinggal','warn']));
  }
  const eta=new Date();eta.setDate(eta.getDate()+rem*7);
  return {mode,rate,bb,goal,diff,rem,w0,pct,N,exp,st,eta,week,idx};
}
function phaseOf(w,J){
  const N=J.N;
  if(J.mode==='keep'||w>N)return 'maint';
  const ad=N<=4?1:(N<=12?2:4),fin=N<=6?0:(N<=16?2:4);
  return w<=ad?'adapt':(w>N-fin?'finish':'main');
}
const PHN=(k,mode)=>({
  adapt:['Adaptasi','Membangun kebiasaan dan membiasakan sendi serta otot. Intensitas ringan, fokus pada teknik gerakan.'],
  main:mode==='gain'?['Pembentukan Otot','Fokus penguatan otot agar berat badan naik secara sehat. Kardio dibatasi dan beban naik bertahap tiap 4 minggu.']
      :['Pembakaran Lemak','Fase utama penurunan berat badan: kardio dan penguatan otot, intensitas naik bertahap tiap 4 minggu.'],
  finish:['Finishing','Mendekati target. Porsi penguatan otot ditambah untuk menjaga massa otot dan bentuk tubuh.'],
  maint:['Pemeliharaan','Target berat badan tercapai. Pertahankan kebugaran dan berat idealmu dengan olahraga rutin.']}[k]);

/* ===== MESIN ATURAN (forward chaining) ===== */
function buildPlan(){
  loadS();
  const L=S.last;
  if(!L||!L.g||!L.age||L.bmi==null)return null;
  const g=L.g==='P'?'P':'L',age=+L.age,bmi=+L.bmi,bb=+S.bb||70,af=+L.af||1.375,pen=String(L.pen||'').toLowerCase(),cat=L.cat||'Normal';
  const idx=dayIndex(),week=Math.floor(idx/7)+1,today=idx%7+1;
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
  rules.push(g==='L'
    ?['Laki-laki','Fokus penguatan tubuh atas dan core (push-up, plank) dipadukan kardio untuk membakar lemak dan menjaga massa otot.']
    :['Perempuan','Fokus penguatan kaki, glute, dan core (squat, glute bridge, donkey kick) dengan kardio; push-up dimulai dari versi lutut/dinding.']);
  const over=bmi>=23,kurus=bmi<18.5;
  rules.push([`${cat} (BMI ${bmi.toFixed(1).replace('.',',')})`,
    kurus?'Porsi penguatan otot diperbanyak dan kardio dibatasi agar berat badan tidak turun lebih jauh.'
    :over?'Kardio dijadikan menu utama untuk mendukung defisit kalori, dipadukan penguatan otot agar massa otot terjaga.'
    :'Kombinasi seimbang kardio dan penguatan otot untuk menjaga kebugaran.']);
  if(age>=50)add(['hi','jump'],`Usia ${age} tahun`,'Gerakan lompat dan intensitas tinggi dihilangkan, durasi dikurangi.');
  if(bmi>=30)add(['hi','jump'],'Obesitas II','Semua gerakan dibuat low-impact (tanpa lompat) agar sendi lutut dan pergelangan tidak terbebani.');
  if(F.lambung)add(['prone','flex'],'Asam lambung','Gerakan yang menekan perut (plank, mountain climber, crunch, child pose) dihilangkan. Berolahraga minimal 1–2 jam setelah makan.');
  if(F.hipertensi)add(['iso','inv','hi'],'Hipertensi','Gerakan tahan posisi lama (plank, wall sit), kepala di bawah (pike push-up), dan intensitas tinggi dihilangkan. Napas teratur, jangan menahan napas.');
  if(F.jantung){add(['iso','inv','hi','jump'],'Penyakit jantung','Hanya gerakan ringan tanpa lompat dan tahan napas.');warn.push('Karena ada riwayat jantung, konsultasikan dulu dengan dokter sebelum memulai program ini. Berhenti segera jika nyeri dada atau sesak.')}
  if(F.sendi)add(['jump','hi','knee'],'Nyeri sendi / asam urat','Lompat dan tekukan lutut dalam (lunge, wall sit, sumo squat) dihilangkan. Hindari olahraga saat sedang kambuh.');
  if(F.asma){add(['hi'],'Asma','Gerakan intensitas tinggi dihilangkan dan waktu istirahat lebih panjang. Siapkan inhaler.')}
  if(F.punggung)add(['flex','back','inv'],'Masalah punggung / pinggang','Gerakan menekuk tulang belakang (crunch, leg raise, superman) dan kepala di bawah dihilangkan.');
  if(F.diabetes){rules.push(['Diabetes / gula darah','Intensitas dijaga sedang dan konsisten. Cek gula darah sebelum dan sesudah olahraga, bawa camilan cepat serap, dan jangan berolahraga saat gula darah terlalu rendah.'])}
  if(F.kolesterol){rules.push(['Kolesterol','Kardio tetap ada di setiap sesi. Usahakan konsisten minimal 150 menit per minggu.'])}
  const frail=F.hipertensi||F.jantung||F.asma||F.sendi||F.punggung;
  const lv=(af>=1.55&&!frail&&age<50&&bmi<30)?1:0;
  const safe=frail||age>=50||bmi>=30;
  let T={'Kurus':20,'Normal':25,'Overweight':30,'Obesitas I':30,'Obesitas II':25}[cat]||25;
  if(lv)T+=5;if(safe)T-=5;if(age>=50)T-=3;if(af<=1.2)T-=3;
  T=Math.max(15,Math.min(40,T));
  const W=(lv?40:(safe?25:30)),R=lv?15:(safe?25:20);
  rules.push([lv?'Level menengah':(safe?'Level ringan':'Level pemula'),`${W} detik latihan, ${R} detik istirahat, target ±${T} menit per hari di awal program. Durasi dan intensitas naik bertahap tiap 4 minggu, dengan minggu pemulihan yang lebih ringan.`]);
  const J=journey(week,idx);
  return {L,g,age,bmi,bb,af,cat,F,ban,rules,warn,lv,safe,W0:W,R,T0:T,week,today,idx,J,kurus,over,wkc:{},pc:{}};
}
function mkPools(P,lv){
  if(P.pc[lv])return P.pc[lv];
  const ok=id=>EX[id].lv<=lv&&!EX[id].tg.some(t=>P.ban.has(t)),pl={};
  ['warm','cardio','upper','lower','core'].forEach(k=>pl[k]=POOL_DEF[P.g][k].filter(ok));
  pl.stre=STRE.filter(ok);
  if(!pl.cardio.length)pl.cardio=['march'];
  if(!pl.warm.length)pl.warm=['arm'];
  return P.pc[lv]=pl;
}
/* rencana satu minggu: fase ditentukan dari estimasi waktu capai target, dibekukan setelah minggu itu berjalan */
function weekPlan(P,w){
  if(P.wkc[w])return P.wkc[w];
  const J=P.J;let k;
  if(w<=P.week){if(!O.fz[w])O.fz[w]=phaseOf(w,J);k=O.fz[w]}else k=phaseOf(w,J);
  let prog=k==='adapt'?0:(k==='maint'?2:Math.min(4,Math.floor((w-1)/4)));
  const deload=(k==='main'||k==='finish')&&w%4===0;
  if(deload)prog=Math.max(0,prog-1);
  const lv=(P.lv||(!P.safe&&prog>=3))?1:0;
  const K={w,k,ph:PHN(k,J.mode),prog,deload,lv,
    T:Math.max(15,Math.min(50,P.T0+prog*2-(deload?4:0))),W:Math.min(60,P.W0+prog*5-(deload?5:0)),R:P.R,maxSets:lv?5:4,pools:mkPools(P,lv),days:[]};
  for(let d=1;d<=7;d++)K.days.push(buildDay(P,d,K));
  return P.wkc[w]=K;
}
function buildSteps(items){
  const st=[];
  items.forEach((it,i)=>{for(let s=1;s<=it.sets;s++){st.push({t:'work',i,s,sec:it.sec});st.push({t:'rest',i,sec:it.rest})}});
  st.pop();return st;
}
function buildDay(P,d,K){
  const D=DAYS[d-1],items=[],off=d+K.w,pools=K.pools;
  const mk=(id,sec,kind,sets=1,rest=10)=>({id,sec,sets,kind,rest,k:EX[id].met*P.bb*(sec*sets)/3600});
  const pickN=(pool,n)=>{const o=[];for(let i=0;i<pool.length&&o.length<n;i++)o.push(pool[(off+i)%pool.length]);return o};
  let title=D.t;
  if(D.rest||D.walk){
    items.push(mk('arm',40,'warm'));
    const mins=D.rest?10:(K.lv?20:15)+K.prog*2;
    items.push(mk('walk',mins*60,'main'));
    pickN(pools.stre,D.rest?4:3).forEach(id=>items.push(mk(id,45,'cool')));
  }else{
    if(d===6&&P.safe)title='Kardio Ringan';
    const warm=pickN(pools.warm,2);
    warm.forEach(id=>items.push(mk(id,40,'warm')));
    const used=new Set(warm);
    let mix=D.mix;
    if(P.kurus||P.J.mode==='gain'){let c=0,a=0;mix=mix.map(k=>k==='cardio'?(c++===0?'cardio':(a++%2?'upper':'lower')):k)}
    else if(K.k==='finish')mix=mix.map((k,i)=>k==='cardio'&&i%2?(i%4===1?'lower':'upper'):k);
    const n0=K.T<=20?5:(K.T<=30?6:7);
    const ptr={cardio:off,upper:off,lower:off,core:off},main=[];
    for(let i=0;main.length<n0&&i<60;i++){
      const key=mix[i%mix.length],pool=pools[key];
      if(!pool||!pool.length)continue;
      for(let k=0;k<pool.length;k++){
        const id=pool[(ptr[key]+k)%pool.length];
        if(!used.has(id)){used.add(id);ptr[key]=(ptr[key]+k+1)%pool.length;main.push(id);break}
      }
    }
    const fixed=2*40+2*40+4*10;
    const sets=Math.max(1,Math.min(K.maxSets,Math.round((K.T*60-fixed)/(Math.max(1,main.length)*(K.W+K.R)))));
    main.forEach(id=>items.push(mk(id,K.W,'main',sets,K.R)));
    pickN(pools.stre,2).forEach(id=>items.push(mk(id,40,'cool')));
  }
  const steps=buildSteps(items);
  const dur=steps.reduce((a,s)=>a+s.sec,0),kcal=items.reduce((a,it)=>a+it.k,0);
  return {d,title,items,steps,dur,kcal};
}

/* ===== CHECKLIST ===== */
const dkey=(w,d)=>`w${w}d${d}`;
const sigOf=items=>items.map(i=>i.id+i.sets+'x'+i.sec).join(',');
function getChecks(w,d,items){
  const sig=sigOf(items);
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
/* ringkasan minggu tanpa membuat entri baru */
function weekPeek(w){
  const K=weekPlan(P,w);let n=0,done=0;
  K.days.forEach(x=>{const r=O.done[dkey(w,x.d)],ok=r&&r.sig===sigOf(x.items),c=ok?cnt(r.c):0;done+=c;if(ok&&c===x.items.length)n++});
  return {n,done};
}
function logWeight(v){
  v=parseFloat(String(v).replace(',','.'));
  if(!(v>20&&v<300)){toast('Masukkan berat badan yang valid (kg)');return}
  loadS();
  const td=new Date().toDateString();
  if(!Array.isArray(S.weights)||!S.weights.length)S.weights=[{d:td,w:v}];
  else{const l=S.weights[S.weights.length-1];if(l.d===td)l.w=v;else S.weights.push({d:td,w:v})}
  const before=+S.bb;S.bb=v;
  try{localStorage.setItem(KEY,JSON.stringify(S))}catch(e){}
  render();
  const d=v-before;
  toast(`Berat ${f1(v)} kg tercatat${Math.abs(d)>=.05?` (${d<0?'turun':'naik'} ${f1(Math.abs(d))} kg)`:''}`);
}

/* ===== RENDER ===== */
let vw=null;
const PHC={adapt:'a',main:'m',finish:'f',maint:'p'};
function segsOf(J){
  const N=Math.max(J.N,0);
  if(J.mode==='keep'||N===0)return [{k:'maint',n:'Pemeliharaan',w:1}];
  const list=[];let cur=null;
  for(let w=1;w<=N;w++){const k=phaseOf(w,J);if(cur&&cur.k===k)cur.w++;else{cur={k,n:PHN(k,J.mode)[0],w:1};list.push(cur)}}
  list.push({k:'maint',n:'Pemeliharaan',w:Math.max(2,Math.round(N*.15))});
  return list;
}
function render(){
  const oldS=$('#wks'),keep=oldS?oldS.scrollLeft:null;
  P=buildPlan();
  const app=$('#app');
  if(!P){
    app.innerHTML=`<div class="card empty"><div class="big">🏃</div><h3>Analisis dulu datamu</h3><p>Program olahraga dibuat dari hasil deteksi obesitas (jenis kelamin, usia, BMI, aktivitas, dan riwayat penyakit). Lakukan analisis terlebih dahulu.</p><a class="btn btn-p" href="deteksi_obesitas.php">Ke Deteksi Obesitas →</a></div>`;
    return;
  }
  const J=P.J,chips=Math.min(MAXW+1,J.N>=P.week?J.N+1:P.week+3);
  if(vw==null||vw<1)vw=P.week;
  vw=Math.min(vw,chips);
  const K=weekPlan(P,vw),days=K.days;
  if(sel==null)sel=vw===P.week?P.today:1;
  const D=days[sel-1],c=getChecks(vw,sel,D.items);
  const KT=weekPlan(P,P.week),Dt=KT.days[P.today-1],ct=getChecks(P.week,P.today,Dt.items);
  /* ringkasan minggu yang dilihat */
  let daysDone=0,wItems=0,wDone=0,wK=0;const full=[];
  days.forEach(x=>{const cc=getChecks(vw,x.d,x.items),n=cnt(cc);full[x.d]=n===x.items.length;if(full[x.d])daysDone++;wItems+=x.items.length;wDone+=n;wK+=kDone(x.items,cc)});
  saveO();
  const gLbl=P.g==='L'?'Laki-laki':'Perempuan';
  const lvLbl=K.lv?'Menengah':(P.safe?'Ringan':'Pemula');
  const todayFull=cnt(ct)===Dt.items.length,pct=Math.round(cnt(c)/D.items.length*100);
  const curPh=KT.ph[0],isCur=vw===P.week,future=vw>P.week;
  /* kartu perjalanan */
  const segs=segsOf(J),tot=segs.reduce((a,s)=>a+s.w,0);
  let pos;
  if(J.mode==='keep'||J.N===0)pos=.5;
  else if(P.week<=J.N)pos=(P.week-1+(P.today-.5)/7)/tot;
  else pos=(J.N+Math.min(segs[segs.length-1].w*.5,(P.week-J.N)*.4))/tot;
  const etaTxt=J.eta.toLocaleDateString('id-ID',{day:'numeric',month:'long',year:'numeric'});
  const title=J.mode==='keep'?'Berat badanmu sudah di target 🎉':(J.mode==='gain'?`Naik ${f1(Math.abs(J.diff))} kg lagi menuju ${f1(J.goal)} kg`:`Turun ${f1(J.diff)} kg lagi menuju ${f1(J.goal)} kg`);
  const sub=J.mode==='keep'?'Program masuk fase Pemeliharaan: olahraga rutin untuk mempertahankan berat dan kebugaranmu. Jika targetmu berubah, atur ulang di Deteksi Obesitas.'
    :`Estimasi ±${J.rem} minggu (${J.mode==='gain'?'naik':'turun'} ±${String(J.rate).replace('.',',')} kg/minggu). Jadwal olahraga berjalan terus sampai target tercapai, lalu masuk fase pemeliharaan.${J.rem>=MAXW?' Target ini sangat jauh, pertimbangkan target bertahap.':''}`;
  const jr=`<div class="card jr rise">
    <div class="jr-top"><div><span class="tag">Perjalanan menuju target</span><h2>${title}</h2><p class="s">${sub}</p></div>${J.st?`<div class="jst ${J.st[1]}">${J.st[0]}</div>`:''}</div>
    <div class="jgrid">
      <div><b>${f1(J.w0)}</b><small>Berat awal (kg)</small></div>
      <div><b>${f1(J.bb)}</b><small>Sekarang (kg)</small></div>
      <div><b>${f1(J.goal)}</b><small>Target (kg)</small></div>
      <div><b>${J.mode==='keep'?'0':f1(Math.abs(J.diff))}</b><small>Sisa (kg)</small></div>
      <div><b>${J.mode==='keep'?'Tercapai':etaTxt}</b><small>Perkiraan tercapai</small></div>
    </div>
    <div class="ptop"><b>Progres berat badan</b><span>${Math.round(J.pct)}%${J.mode!=='keep'&&P.idx>=7?` · seharusnya ±${f1(J.exp)} kg sekarang`:''}</span></div>
    <div class="bar"><i style="width:${J.pct}%"></i></div>
    <div class="tl-w"><div class="tl">${segs.map(s=>`<div class="sg sg-${PHC[s.k]}" style="flex:${s.w}" title="${esc(s.n)}">${s.w>=2||segs.length===1?esc(s.n):''}</div>`).join('')}<span class="tlm" style="left:${(pos*100).toFixed(1)}%">Kamu di sini</span></div>
    <div class="tl-n">${J.mode==='keep'?'Pemeliharaan':`${J.N} minggu menuju target, lalu Pemeliharaan`}</div></div>
    <div class="jlog"><label for="jw">Catat berat hari ini</label><input id="jw" type="text" inputmode="decimal" placeholder="${f1(J.bb)}" autocomplete="off"><button class="btn btn-p" data-act="log">Simpan</button><span class="jhint">Jadwal dan estimasi menyesuaikan setiap kamu mencatat berat.</span></div>
  </div>`;
  /* deretan minggu */
  let chipH='';
  for(let w=1;w<=chips;w++){
    const kk=w<=P.week?weekPlan(P,w).k:phaseOf(w,J);let st='&nbsp;',cl='';
    if(w<=P.week){const pk=weekPeek(w);if(pk.n>=6){st='✓';cl=' ok'}else if(pk.done>0){st=pk.n+'/7';cl=' part'}else if(w<P.week){st='–';cl=' miss'}else st='●'}
    chipH+=`<button class="wk${cl}${w===vw?' on':''}${w===P.week?' cur':''}${w>P.week?' fut':''}" data-act="week" data-w="${w}" title="Minggu ${w} · ${esc(PHN(kk,J.mode)[0])}"><i class="pc pc-${PHC[kk]}"></i><b>${w}</b><small>${st}</small></button>`;
  }
  const wkHead=`<div class="card wkh mb rise"><div><span class="tag">Minggu ${vw}${J.mode!=='keep'&&vw<=J.N?` dari ±${J.N}`:''}${isCur?' · Minggu ini':''}</span><h2>${esc(K.ph[0])}${K.deload?' · Minggu pemulihan':''}</h2><p>${esc(K.ph[1])}${K.deload?' Minggu ini sengaja dibuat lebih ringan agar tubuh pulih sebelum beban naik lagi.':''}${future?' Ini pratinjau, jadwal bisa berubah mengikuti kemajuan berat badanmu.':''}</p></div>
    <div class="hmeta2"><span>Latihan <b>${K.W} dtk</b></span><span>Istirahat <b>${K.R} dtk</b></span><span>Target <b>±${K.T} mnt/hari</b></span><span>Level <b>${lvLbl}</b></span></div></div>`;
  const canStart=vw<=P.week;
  app.innerHTML=`
  <div class="hero-card rise">
    <div>
      <h2>Program ${gLbl}: ${esc(P.cat)}</h2>
      <p>Hari ini <b>Day ${P.today}</b> · Minggu ${P.week}${J.mode!=='keep'&&P.week<=J.N?` dari ±${J.N}`:''} · Fase ${esc(curPh)}. ${esc(Dt.title)} — ${durTxt(Dt.dur)}, ${Dt.items.length} gerakan.</p>
      <div class="hmeta"><span class="hm">Usia <b>${P.age}</b></span><span class="hm">BMI <b>${P.bmi.toFixed(1).replace('.',',')}</b></span><span class="hm">Level <b>${P.lv?'Menengah':(P.safe?'Ringan':'Pemula')}</b></span><span class="hm">Target <b>${f1(J.goal)} kg</b></span></div>
      <div class="hero-btns"><button class="btn btn-l" data-act="start" data-w="${P.week}" data-d="${P.today}">${todayFull?'Ulangi latihan hari ini':'Mulai olahraga hari ini'}</button><button class="btn btn-g" data-act="today">Lihat daftar gerakan</button></div>
    </div>
    <div class="hero-big">${P.g==='L'?'🏋️‍♂️':'🧘‍♀️'}</div>
  </div>
  ${P.warn.map(w=>`<div class="card mb" style="border-color:#f0dc9a;background:var(--amber-bg)"><b style="color:var(--amber)">⚠ Perhatian</b><p style="font-size:.85rem;color:var(--text);margin-top:4px">${esc(w)}</p></div>`).join('')}
  ${jr}
  <div class="wk-h"><b>Jadwal sampai target</b><span>Geser untuk melihat semua minggu · ✓ = 6 hari atau lebih selesai</span></div>
  <div class="wks" id="wks">${chipH}</div>
  ${wkHead}
  <div class="g4">
    <div class="card stat rise" style="--i:1"><div class="stat-top"><span class="stat-l">Hari selesai · Minggu ${vw}</span><span class="stat-ic">📅</span></div><div class="stat-v">${daysDone}<small> / 7 hari</small></div><div class="bar"><i style="width:${daysDone/7*100}%"></i></div><div class="stat-n">${wDone} dari ${wItems} gerakan tercentang</div></div>
    <div class="card stat rise" style="--i:2"><div class="stat-top"><span class="stat-l">${isCur&&sel===P.today?'Hari ini · ':''}Day ${sel}</span><span class="stat-ic">✅</span></div><div class="stat-v">${cnt(c)}<small> / ${D.items.length} gerakan</small></div><div class="bar"><i style="width:${cnt(c)/D.items.length*100}%"></i></div><div class="stat-n">${cnt(c)===D.items.length?'Selesai, kerja bagus! 🎉':'Lanjutkan sampai semua tercentang'}</div></div>
    <div class="card stat rise" style="--i:3"><div class="stat-top"><span class="stat-l">Kalori terbakar · Day ${sel}</span><span class="stat-ic">🔥</span></div><div class="stat-v">${fmt(kDone(D.items,c))}<small> kkal</small></div><div class="bar"><i style="width:${D.kcal?kDone(D.items,c)/D.kcal*100:0}%"></i></div><div class="stat-n">Target sesi ±${fmt(D.kcal)} kkal</div></div>
    <div class="card stat rise" style="--i:4"><div class="stat-top"><span class="stat-l">Kalori minggu ${vw}</span><span class="stat-ic">⚡</span></div><div class="stat-v">${fmt(wK)}<small> kkal</small></div><div class="stat-n" style="margin-top:14px">Estimasi dari gerakan yang sudah dicentang</div></div>
  </div>

  <div class="tabs" id="dtabs">${days.map(x=>`<button class="tab${x.d===sel?' on':''}${full[x.d]?' full':''}" data-act="tab" data-d="${x.d}">Day ${x.d}${full[x.d]?' <span class="st">✓</span>':(isCur&&x.d===P.today?' <span class="st">●</span>':'')}</button>`).join('')}</div>

  <div class="g2e">
    <div class="card rise">
      <div class="dhead"><div><span class="tag">Minggu ${vw}${isCur&&sel===P.today?' · Hari ini':''}</span><h2>Day ${sel}</h2><span class="sub">${esc(D.title)} · ${D.items.length} gerakan</span></div>${canStart?`<button class="btn btn-p" data-act="start" data-w="${vw}" data-d="${sel}">▶ Mulai</button>`:`<span class="tag" style="background:var(--bg);color:var(--muted)">Terbuka di Minggu ${vw}</span>`}</div>
      <div class="trio"><div><b>${lvLbl}</b><small>Level</small></div><div><b>${kRange(D.kcal)}</b><small>Kkal (~)</small></div><div><b>${durTxt(D.dur)}</b><small>Durasi</small></div></div>
      <div class="ptop"><b>Progres hari ini</b><span>${cnt(c)}/${D.items.length} · ${pct}%</span></div>
      <div class="bar"><i style="width:${pct}%"></i></div>
      <div class="ck-list">${D.items.map((it,i)=>{const x=EX[it.id];return `<button type="button" class="ck${c[i]?' on':''}" data-act="chk" data-i="${i}"><span class="bx">✓</span><span class="em">${exSVG(it.id,P.g,0)}</span><span class="tx"><b>${esc(x.n)}</b><small>${secTxt(it)}</small><em>${esc(x.how)}</em></span></button>`}).join('')}</div>
    </div>
    <div style="display:grid;gap:18px;align-content:start">
      <div class="card rise" style="--i:1"><div class="card-h"><div><div class="card-t">Penyesuaian untukmu</div><div class="card-s">Aturan yang diterapkan sistem pakar</div></div></div>
        <ul class="rlist">${P.rules.map(r=>`<li><strong>${esc(r[0])}</strong>${esc(r[1])}</li>`).join('')}</ul></div>
      <div class="card rise" style="--i:2"><div class="card-h"><div><div class="card-t">Catatan keselamatan</div></div></div>
        <ul class="rlist"><li class="w"><strong>Pemanasan & pendinginan</strong>Sudah termasuk di awal dan akhir setiap sesi. Jangan dilewati.</li>
        <li class="w"><strong>Minum air</strong>Minum 200–250 ml sebelum dan sesudah olahraga.</li>
        <li class="w"><strong>Berhenti jika</strong>Nyeri dada, pusing berat, sesak, atau nyeri tajam di sendi.</li>
        <li class="w"><strong>Kondisi khusus</strong>Jika hamil, menyusui, atau punya penyakit tertentu, konsultasikan dulu dengan dokter.</li></ul>
        <button class="btn btn-o" style="margin-top:14px;width:100%" data-act="reset">Mulai ulang program dari Minggu 1</button></div>
    </div>
  </div>`;
  const ws=$('#wks');
  if(ws){
    if(keep!=null)ws.scrollLeft=keep;
    else{const cur=ws.querySelector('.cur');if(cur)ws.scrollLeft=Math.max(0,cur.offsetLeft-ws.clientWidth/2+30)}
  }
}

/* ===== EVENT ===== */
$('#app').addEventListener('click',e=>{
  const b=e.target.closest('[data-act]');if(!b)return;
  const a=b.dataset.act;
  if(a==='tab'){sel=+b.dataset.d;render()}
  else if(a==='today'){vw=P.week;sel=P.today;render();window.scrollTo({top:$('#dtabs').offsetTop-90,behavior:'smooth'})}
  else if(a==='week'){vw=+b.dataset.w;sel=vw===P.week?P.today:1;render()}
  else if(a==='chk'){
    const D=weekPlan(P,vw).days[sel-1],c=getChecks(vw,sel,D.items),i=+b.dataset.i;
    c[i]=!c[i];saveO();render();
    if(c.every(Boolean))toast(`Minggu ${vw} · Day ${sel} selesai! Kerja bagus 🎉`);
  }
  else if(a==='start')openPlayer(+b.dataset.d,+b.dataset.w);
  else if(a==='log')logWeight($('#jw').value);
  else if(a==='reset'){if(confirm('Mulai ulang program dari Minggu 1? Centang yang sudah ada akan dihapus.')){loadS();O={start:isoDate(new Date()),done:{},fz:{},w0:+S.bb||0};saveO();sel=null;vw=null;render();toast('Program dimulai dari Minggu 1')}}
});
$('#app').addEventListener('keydown',e=>{if(e.key==='Enter'&&e.target.id==='jw'){e.preventDefault();logWeight(e.target.value)}});

/* ===== PEMUTAR LATIHAN ===== */
let FRAF=null;
function figStop(){if(FRAF){cancelAnimationFrame(FRAF);FRAF=null}}
/* hanya isi SVG yang bergerak (2 pose bolak-balik); layar dan teks tetap diam */
function figRun(id,g){
  figStop();
  const svg=$('#plFig');if(!svg)return;
  const sp=XD[id].sp||1.6;let ph=0,last=performance.now(),lt=-1;
  const loop=now=>{
    if(!PL||!document.body.contains(svg)){FRAF=null;return}
    const dt=Math.min(100,now-last);last=now;
    if(!PL.paused&&PL.ph!=='done')ph+=dt/(sp*1000);
    const t=Math.round((1-Math.cos(ph*2*Math.PI))/2*200)/200;
    if(t!==lt){svg.innerHTML=exInner(id,g,t);lt=t}
    FRAF=requestAnimationFrame(loop);
  };
  svg.innerHTML=exInner(id,g,0);
  FRAF=requestAnimationFrame(loop);
}
function openPlayer(d,w){
  const D=weekPlan(P,w).days[d-1];
  PL={d,week:w,items:D.items,steps:D.steps,k:-1,ph:'ready',t:10,total:10,paused:false,fail:{},done:0,timer:null};
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
  clearInterval(PL.timer);PL.ph='done';figStop();
  const c=getChecks(PL.week,PL.d,PL.items);
  drawPl();
  if(c.every(Boolean))toast(`Minggu ${PL.week} · Day ${PL.d} selesai! 🎉`);
}
function plPause(){if(!PL)return;PL.paused=!PL.paused;const b=$('#plP');if(b)b.textContent=PL.paused?'LANJUT':'JEDA'}
function plSkip(){if(PL&&PL.ph!=='done')stepDone(false)}
function plClose(){
  if(!PL)return;
  if(PL.ph!=='done'&&!confirm('Keluar dari latihan? Gerakan yang sudah selesai tetap tercentang.'))return;
  clearInterval(PL.timer);figStop();PL=null;
  $('#player').classList.remove('show');document.body.style.overflow='';render();
}
function updRing(){
  const r=$('#plR'),n=$('#plN');if(!r||!n)return;
  n.textContent=mmss(PL.t);r.style.strokeDashoffset=276.46*(1-PL.t/PL.total);
}
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
   <div class="pbody"><div class="pvis"><svg id="plFig" viewBox="${exVB(it.id)}" preserveAspectRatio="xMidYMax meet" role="img" aria-label="Peragaan ${esc(x.n)}"></svg></div>
   <div><div class="pst">${label}</div><div class="pnm">${esc(x.n)}</div><div class="psub">${sub||'&nbsp;'}</div><div class="phow">${esc(x.how)}</div>
   <div class="pring ${isRest?'rest':''}"><svg viewBox="0 0 100 100"><circle class="pb" cx="50" cy="50" r="44"/><circle class="pv" id="plR" cx="50" cy="50" r="44"/></svg><b id="plN">${mmss(PL.t)}</b></div>
   <div class="pbtn"><button class="a" id="plP" onclick="plPause()">${PL.paused?'LANJUT':'JEDA'}</button><button class="b" onclick="plSkip()">LEWATI</button></div></div></div>`;
  updRing();
  figRun(it.id,P.g);
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