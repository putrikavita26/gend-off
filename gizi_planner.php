<?php
// GEND-OFF — Gizi Planner (menu & gizi harian sesuai target kalori)
// Letakkan di: htdocs/gend-off/gizi_planner.php | aset di: assets/
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
<title>Gizi Planner — GEND-OFF</title>
<link rel="icon" href="assets/logo.png">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=IBM+Plex+Mono:wght@500;600&family=Bebas+Neue&display=swap" rel="stylesheet">
<style>
:root{
  --dark:#0b3b36;--dark2:#12524a;--olive:#7a9a2e;--lime:#a4c23d;
  --tint:#f1f6e4;--tint2:#e6efd0;
  --bg:#f6f8f4;--surface:#fff;--border:#e6ebe0;--border-soft:#eef2e9;
  --text:#14302c;--muted:#5d716d;--faint:#93a29e;
  --red:#d8452e;--red-bg:#fceae6;--amber:#8a6300;--amber-bg:#fff4d6;--green:#0f7a4f;--green-bg:#e5f6ec;--blue:#2e86ab;--blue-bg:#e6f2f8;
  --radius:16px;--shadow:0 1px 2px rgba(11,59,54,.05),0 1px 1px rgba(11,59,54,.03);--shadow-md:0 10px 30px -10px rgba(11,59,54,.18);
  --sw:248px;--mono:'IBM Plex Mono',monospace;--ease:cubic-bezier(.2,.7,.2,1)
}
*{box-sizing:border-box;margin:0;padding:0;-webkit-tap-highlight-color:transparent}
body{font-family:'Plus Jakarta Sans',sans-serif;color:var(--text);background:var(--bg);-webkit-font-smoothing:antialiased;line-height:1.55}
a{color:inherit;text-decoration:none}
button{font-family:inherit;cursor:pointer;border:0;background:none;color:inherit}
input,select,textarea{font:inherit;color:inherit}
::-webkit-scrollbar{width:9px;height:9px}::-webkit-scrollbar-thumb{background:#cfd9c4;border-radius:8px}
:focus-visible{outline:2px solid var(--lime);outline-offset:2px}
h1,h2,h3,h4{font-weight:800;color:var(--dark);line-height:1.2}

/* ===== LAYOUT ===== */
.app{display:flex;min-height:100vh}
.sidebar{width:var(--sw);flex-shrink:0;background:#fff;border-right:1px solid var(--border);position:sticky;top:0;height:100vh;display:flex;flex-direction:column;z-index:80;transition:transform .3s var(--ease)}
.brand{display:flex;align-items:center;gap:12px;padding:20px 18px 18px;border-bottom:1px solid var(--border-soft)}
.brand img{width:44px;height:44px;object-fit:contain;flex-shrink:0}
.brand b{display:block;font-size:1.2rem;font-weight:800;letter-spacing:.3px;color:var(--dark);line-height:1}
.brand b span{color:var(--olive)}
.brand small{display:block;font-size:.62rem;font-weight:600;letter-spacing:1.4px;text-transform:uppercase;color:var(--faint);margin-top:5px}
.nav{flex:1;overflow-y:auto;padding:10px 12px 14px}
.nav-label{font-size:.66rem;text-transform:uppercase;letter-spacing:.9px;color:var(--faint);font-weight:800;padding:16px 10px 6px}
.nav-item{position:relative;display:flex;align-items:center;gap:11px;width:100%;padding:10px 11px;margin:2px 0;border-radius:10px;font-size:.88rem;font-weight:600;color:var(--muted);text-align:left;transition:background .15s,color .15s}
.nav-item:hover{background:var(--bg);color:var(--dark)}
.nav-item.active{background:var(--tint);color:var(--dark);font-weight:700}
.nav-item.active::before{content:"";position:absolute;left:-12px;top:8px;bottom:8px;width:3px;border-radius:0 4px 4px 0;background:linear-gradient(180deg,var(--lime),var(--olive))}
.nav-item .ic{width:18px;height:18px;flex-shrink:0;display:flex}
.nav-item .ic svg{width:100%;height:100%}
.nav-item.active .ic{color:var(--olive)}
.side-foot{padding:12px 14px 16px;border-top:1px solid var(--border-soft)}
.logout{display:flex;align-items:center;gap:10px;padding:10px 12px;border-radius:10px;font-size:.86rem;font-weight:700;color:var(--muted);border:1px solid var(--border);transition:.15s}
.logout:hover{color:var(--red);border-color:#f3c3ba;background:var(--red-bg)}
.logout svg{width:16px;height:16px}
.side-ver{font-family:var(--mono);font-size:.64rem;color:var(--faint);margin:10px 4px 0;line-height:1.6}
.scrim{position:fixed;inset:0;background:rgba(11,59,54,.35);z-index:70;opacity:0;pointer-events:none;transition:.25s}

.main{flex:1;min-width:0;display:flex;flex-direction:column}
.topbar{position:sticky;top:0;z-index:50;display:flex;align-items:center;justify-content:space-between;gap:16px;padding:14px 30px;background:rgba(255,255,255,.92);backdrop-filter:blur(10px);border-bottom:1px solid var(--border)}
.tb-l{display:flex;align-items:center;gap:12px}
.burger{display:none;width:40px;height:40px;border-radius:10px;border:1px solid var(--border);align-items:center;justify-content:center}
.burger svg{width:18px;height:18px}
.tb-title{font-size:1.15rem;font-weight:800;color:var(--dark)}
.tb-sub{font-size:.78rem;color:var(--muted)}
.tb-r{display:flex;align-items:center;gap:12px}
.date{font-family:var(--mono);font-size:.76rem;color:var(--muted);padding:7px 12px;border:1px solid var(--border);border-radius:10px;background:var(--bg)}
.chip-user{display:flex;align-items:center;gap:10px;padding:5px 14px 5px 5px;border:1px solid var(--border);border-radius:99px;background:#fff}
.av{width:32px;height:32px;border-radius:50%;display:grid;place-items:center;font-weight:800;font-size:.85rem;color:#fff;background:linear-gradient(155deg,var(--dark2),var(--dark))}
.chip-user b{display:block;font-size:.82rem;color:var(--dark);line-height:1.2}
.chip-user small{font-size:.7rem;color:var(--faint)}
.content{padding:26px 30px 60px;max-width:1360px;width:100%;margin:0 auto}
.page{display:none}.page.active{display:block;animation:fade .3s var(--ease)}
@keyframes fade{from{opacity:0;transform:translateY(6px)}}
.sec-h{margin-bottom:20px}
.sec-h h1{font-size:1.5rem;margin-bottom:4px}
.sec-h p{font-size:.88rem;color:var(--muted);max-width:640px}

/* ===== COMPONENTS ===== */
.card{background:var(--surface);border:1px solid var(--border);border-radius:var(--radius);box-shadow:var(--shadow);padding:22px;transition:box-shadow .2s,border-color .2s}
.card.hov:hover{box-shadow:var(--shadow-md);border-color:#d6e1c6}
.card-h{display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;margin-bottom:16px}
.card-t{font-size:.98rem;font-weight:800;color:var(--dark)}
.card-s{font-size:.76rem;color:var(--faint);font-weight:500;margin-top:2px}
.g4{display:grid;grid-template-columns:repeat(4,1fr);gap:18px}
.g2{display:grid;grid-template-columns:1.5fr 1fr;gap:18px}
.g2e{display:grid;grid-template-columns:1fr 1fr;gap:18px}
.mb{margin-bottom:18px}
.tag{display:inline-block;font-size:.72rem;font-weight:700;padding:4px 11px;border-radius:99px;background:var(--tint);color:var(--dark)}

.stat{display:flex;flex-direction:column;gap:2px}
.stat-top{display:flex;align-items:center;justify-content:space-between;margin-bottom:10px}
.stat-l{font-size:.8rem;font-weight:700;color:var(--muted)}
.stat-ic{width:36px;height:36px;border-radius:10px;display:grid;place-items:center}
.stat-ic svg{width:18px;height:18px}
.ic-g{background:var(--tint);color:var(--olive)}.ic-b{background:var(--blue-bg);color:var(--blue)}.ic-a{background:var(--amber-bg);color:var(--amber)}.ic-r{background:var(--red-bg);color:var(--red)}
.stat-v{font-family:var(--mono);font-size:1.9rem;font-weight:600;color:var(--dark);line-height:1.1}
.stat-v small{font-family:'Plus Jakarta Sans';font-size:.8rem;color:var(--faint);font-weight:600}
.stat-n{font-size:.76rem;color:var(--muted);margin-top:8px}
.bar{height:7px;border-radius:99px;background:var(--border-soft);overflow:hidden;margin-top:12px}
.bar i{display:block;height:100%;width:0;border-radius:99px;background:linear-gradient(90deg,var(--olive),var(--lime));transition:width 1s var(--ease)}
.bar.blue i{background:linear-gradient(90deg,#5fa8ce,#2e86ab)}
.badge{display:inline-block;font-size:.72rem;font-weight:800;padding:4px 12px;border-radius:99px}

.btn{display:inline-flex;align-items:center;justify-content:center;gap:8px;padding:10px 18px;border-radius:10px;font-size:.86rem;font-weight:700;border:1px solid transparent;transition:.15s;white-space:nowrap}
.btn svg{width:15px;height:15px}
.btn-p{background:var(--dark);color:#fff;box-shadow:0 4px 12px rgba(11,59,54,.2)}.btn-p:hover{background:var(--dark2);transform:translateY(-1px)}
.btn-o{background:#fff;border-color:var(--border);color:var(--text)}.btn-o:hover{border-color:var(--olive);color:var(--dark)}
.btn-w{width:100%;padding:13px}
.btn:active{transform:scale(.98)}

.form-grid{display:grid;grid-template-columns:1fr 1fr;gap:16px 18px}
.field{display:flex;flex-direction:column;gap:6px}.field.full{grid-column:1/-1}
.field label{font-size:.8rem;font-weight:700;color:var(--dark)}
.field input,.field select{border:1.5px solid var(--border);border-radius:10px;padding:11px 13px;font-size:.88rem;background:#fff;outline:none;transition:border-color .15s,box-shadow .15s}
.field input:hover,.field select:hover{border-color:#c9d6b8}
.field input:focus,.field select:focus{border-color:var(--olive);box-shadow:0 0 0 3px rgba(122,154,46,.16)}
.field select{appearance:none;background-image:url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="none" stroke="%235d716d" stroke-width="2"><path d="M5.5 7.5l4.5 4.5 4.5-4.5"/></svg>');background-repeat:no-repeat;background-position:right 12px center;background-size:14px;padding-right:34px}

/* chart */
#chart{position:relative}
#chart svg{width:100%;height:auto;display:block;overflow:visible}
.cl{fill:none;stroke:var(--olive);stroke-width:3;stroke-linecap:round;stroke-linejoin:round;stroke-dasharray:1;stroke-dashoffset:1;animation:draw 1.4s var(--ease) .1s forwards}
@keyframes draw{to{stroke-dashoffset:0}}
.ca{opacity:0;animation:fi 1s ease .6s forwards}@keyframes fi{to{opacity:1}}
.cd{fill:#fff;stroke:var(--olive);stroke-width:2.5;cursor:pointer;transition:r .2s;opacity:0;animation:fi .4s ease forwards}
.cd:hover{r:8}
.gridl{stroke:var(--border);stroke-dasharray:3 4}
.axl{font-size:11px;fill:var(--faint);font-weight:600}
#tip{position:absolute;pointer-events:none;padding:6px 11px;border-radius:8px;background:var(--dark);color:#fff;font-size:.74rem;font-weight:700;white-space:nowrap;opacity:0;transform:translate(-50%,-130%);transition:opacity .15s}
.addw{display:flex;gap:8px}
.addw input{width:112px;padding:8px 12px;border:1.5px solid var(--border);border-radius:10px;font-size:.84rem;outline:none}
.addw input:focus{border-color:var(--olive)}

/* insight + checklist */
.insight{display:flex;gap:12px;padding:14px;border-radius:12px;background:var(--tint);border:1px solid var(--tint2)}
.insight .ii{width:34px;height:34px;border-radius:10px;background:#fff;color:var(--olive);display:grid;place-items:center;flex-shrink:0}
.insight .ii svg{width:17px;height:17px}
.insight b{display:block;color:var(--dark);font-size:.88rem;margin-bottom:2px}
.insight p{font-size:.82rem;color:var(--muted);line-height:1.55}
.ck-top{display:flex;justify-content:space-between;align-items:center;margin:16px 0 8px}
.ck-top b{font-size:.9rem;color:var(--dark)}
.ck-list{display:flex;flex-direction:column;gap:7px;margin-top:10px}
.ck{display:flex;align-items:center;gap:11px;width:100%;padding:11px 12px;border-radius:10px;border:1px solid var(--border-soft);background:#fff;font-size:.85rem;font-weight:600;text-align:left;transition:.15s}
.ck:hover{background:var(--bg)}
.ck .bx{width:20px;height:20px;border-radius:6px;border:2px solid #c3d1ac;display:grid;place-items:center;flex-shrink:0;color:transparent;font-size:.7rem;font-weight:800;transition:.2s}
.ck.on .bx{background:var(--olive);border-color:var(--olive);color:#fff}
.ck.on span:last-child{color:var(--faint);text-decoration:line-through}

/* water */
.glasses{display:grid;grid-template-columns:repeat(8,1fr);gap:7px;margin-top:6px}
.gl{position:relative;height:42px;border-radius:5px 5px 11px 11px;border:1.5px solid #bcd9ec;background:#f4f9fd;overflow:hidden;transition:.2s}
.gl::after{content:"";position:absolute;left:0;right:0;bottom:0;height:0;background:linear-gradient(180deg,#8fd0f5,#4aa8e6);transition:height .5s var(--ease)}
.gl.on::after{height:84%}.gl:hover{transform:translateY(-3px)}

/* ring */
.ring-w{display:flex;align-items:center;gap:22px}
.ring{position:relative;width:128px;height:128px;flex-shrink:0}
.ring svg{width:100%;height:100%;transform:rotate(-90deg)}
.ring .bgc{fill:none;stroke:var(--border-soft);stroke-width:10}
.ring .val{fill:none;stroke:var(--olive);stroke-width:10;stroke-linecap:round;stroke-dasharray:282.74;stroke-dashoffset:282.74;transition:stroke-dashoffset 1.3s var(--ease)}
.ring .txt{position:absolute;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center}
.ring .txt b{font-family:var(--mono);font-size:1.5rem;font-weight:600;color:var(--dark);line-height:1}
.ring .txt small{font-size:.62rem;color:var(--faint);font-weight:700;text-transform:uppercase;letter-spacing:.6px;margin-top:3px}
.macros{flex:1;display:grid;gap:12px}
.mac label{display:flex;justify-content:space-between;font-size:.76rem;font-weight:700;color:var(--muted);margin-bottom:5px}
.mac .tk{height:7px;border-radius:99px;background:var(--border-soft);overflow:hidden}
.mac .tk i{display:block;height:100%;border-radius:99px;transform-origin:left;animation:grow 1s var(--ease) backwards}
@keyframes grow{from{transform:scaleX(0)}}

/* hasil deteksi */
.res{margin-top:18px;display:none}.res.show{display:block;animation:fade .4s var(--ease)}
.res-h{display:flex;justify-content:space-between;align-items:center;gap:16px;flex-wrap:wrap;padding-bottom:18px;margin-bottom:18px;border-bottom:1px solid var(--border-soft)}
.res-h .big{font-family:var(--mono);font-size:3.2rem;font-weight:600;color:var(--dark);line-height:1}
.chips4{display:grid;grid-template-columns:repeat(4,1fr);gap:12px;margin-bottom:18px}
.chip4{padding:14px;border-radius:12px;background:var(--bg);border:1px solid var(--border-soft);text-align:center}
.chip4 b{display:block;font-family:var(--mono);font-size:1.4rem;font-weight:600;color:var(--dark)}
.chip4 span{font-size:.68rem;font-weight:700;color:var(--faint);text-transform:uppercase;letter-spacing:.6px}
.fc{display:grid;grid-template-columns:1fr 1fr;gap:18px}
.chain{padding:18px;border-radius:14px;background:var(--bg);border:1px solid var(--border-soft)}
.nd{display:flex;gap:12px;position:relative;padding-bottom:12px;opacity:.2;transform:translateX(12px);transition:.5s var(--ease)}
.nd.on{opacity:1;transform:none}
.nd::before{content:"";position:absolute;left:14px;top:30px;bottom:-2px;width:2px;background:var(--tint2)}
.nd:last-child::before{display:none}
.nd .dot{width:30px;height:30px;border-radius:50%;flex-shrink:0;display:grid;place-items:center;font-size:.72rem;font-weight:800;background:#fff;border:2px solid var(--tint2);color:var(--olive);transition:.4s}
.nd.on .dot{background:var(--olive);border-color:var(--olive);color:#fff}
.nd .bx{font-size:.82rem;color:var(--text)}
.nd small{display:block;font-size:.62rem;font-weight:800;letter-spacing:1px;color:var(--olive);text-transform:uppercase}
.nd.fin .bx{padding:8px 12px;border-radius:10px;background:var(--tint);font-weight:700;color:var(--dark);margin-top:-3px}
.rlist{list-style:none;display:grid;gap:9px;align-content:start}
.rlist li{padding:12px 14px;border-radius:12px;background:#fff;border:1px solid var(--border-soft);border-left:3px solid var(--lime);font-size:.83rem;color:var(--muted)}
.rlist li strong{display:block;color:var(--dark);font-size:.86rem;margin-bottom:1px}

/* meal */
.tabs{display:flex;gap:8px;flex-wrap:wrap;margin-bottom:18px}
.tab{padding:9px 16px;border-radius:99px;border:1.5px solid var(--border);background:#fff;font-size:.84rem;font-weight:700;color:var(--muted);transition:.15s}
.tab:hover{border-color:var(--olive);color:var(--dark)}
.tab.on{background:var(--dark);border-color:var(--dark);color:#fff}
.tab small{opacity:.7;margin-left:4px;font-weight:600}
.mh{display:flex;align-items:center;gap:14px;margin-bottom:16px}
.mh .em{width:54px;height:54px;border-radius:14px;display:grid;place-items:center;font-size:1.7rem;background:var(--tint)}
.mh h3{font-size:1.2rem}.mh small{color:var(--faint);font-size:.76rem;font-weight:600}
.mh .kal{margin-left:auto;font-family:var(--mono);font-weight:600;font-size:1.1rem;color:var(--dark);padding:6px 14px;border-radius:99px;background:var(--tint)}
.mitem{padding:11px 14px;border-radius:12px;border:1px solid var(--border-soft);margin-bottom:8px}
.mitem .lr{display:flex;justify-content:space-between;gap:10px;font-size:.86rem;font-weight:600;color:var(--dark)}
.mitem .lr span{font-family:var(--mono);font-size:.78rem;color:var(--olive);white-space:nowrap}
.mitem .lb{height:5px;border-radius:99px;background:var(--border-soft);margin-top:8px;overflow:hidden}
.mitem .lb i{display:block;height:100%;border-radius:99px;background:linear-gradient(90deg,var(--olive),var(--lime));transform-origin:left;animation:grow .8s var(--ease) backwards}
.eat{margin-top:6px;width:100%;padding:12px;border-radius:10px;font-weight:700;font-size:.86rem;border:1.5px dashed #bccfa0;background:#fff;color:var(--dark);transition:.2s}
.eat:hover{background:var(--tint)}
.eat.on{background:var(--olive);border:1.5px solid var(--olive);color:#fff}
.tipb{margin-top:14px;padding:12px 14px;border-radius:10px;background:var(--tint);font-size:.82rem;color:var(--dark)}
.sumrow{margin-bottom:13px}
.sumrow label{display:flex;justify-content:space-between;font-size:.8rem;font-weight:700;color:var(--dark);margin-bottom:5px}
.sumrow label span:last-child{color:var(--faint);font-family:var(--mono);font-size:.74rem}
.sumrow .tk{height:7px;border-radius:99px;background:var(--border-soft);overflow:hidden}
.sumrow .tk i{display:block;height:100%;border-radius:99px;width:0;transition:width .9s var(--ease)}
.tot{display:flex;align-items:baseline;gap:8px;margin-bottom:16px}
.tot b{font-family:var(--mono);font-size:2rem;font-weight:600;color:var(--dark)}
.tot span{font-size:.8rem;color:var(--faint)}

/* chat */
.chat{display:flex;flex-direction:column;height:600px;padding:0;overflow:hidden}
.chat-h{display:flex;align-items:center;gap:12px;padding:16px 22px;border-bottom:1px solid var(--border-soft)}
.bot{position:relative;width:42px;height:42px;border-radius:50%;display:grid;place-items:center;font-size:1.3rem;background:var(--tint)}
.bot::after{content:"";position:absolute;right:0;bottom:0;width:11px;height:11px;border-radius:50%;background:#3fe08a;border:2px solid #fff}
.chat-h b{display:block;font-size:.9rem;color:var(--dark)}.chat-h small{font-size:.74rem;color:var(--faint)}
.chat-box{flex:1;padding:20px 22px;overflow-y:auto;display:flex;flex-direction:column;gap:12px;background:var(--bg)}
.msg{max-width:78%;padding:12px 16px;font-size:.88rem;line-height:1.55;animation:pop .35s var(--ease) both}
@keyframes pop{from{opacity:0;transform:translateY(8px) scale(.96)}}
.msg.b{align-self:flex-start;background:#fff;border:1px solid var(--border);border-radius:16px 16px 16px 4px}
.msg.u{align-self:flex-end;background:var(--dark);color:#fff;border-radius:16px 16px 4px 16px}
.dots{display:inline-flex;gap:4px;padding:3px 0}.dots i{width:6px;height:6px;border-radius:50%;background:var(--olive);animation:bn 1s infinite}
.dots i:nth-child(2){animation-delay:.15s}.dots i:nth-child(3){animation-delay:.3s}
@keyframes bn{40%{transform:translateY(-5px)}}
.qc{display:flex;gap:8px;flex-wrap:wrap;padding:12px 22px 0;background:#fff;border-top:1px solid var(--border-soft)}
.qc button{padding:7px 13px;border-radius:99px;font-size:.76rem;font-weight:700;border:1px solid var(--border);color:var(--dark);background:#fff;transition:.15s}
.qc button:hover{background:var(--tint);border-color:var(--lime)}
.chat-in{display:flex;gap:10px;padding:12px 18px 16px;background:#fff}
.chat-in input{flex:1;padding:12px 18px;border-radius:99px;border:1.5px solid var(--border);outline:none;font-size:.88rem}
.chat-in input:focus{border-color:var(--olive);box-shadow:0 0 0 3px rgba(122,154,46,.14)}
.chat-in button{padding:0 22px;border-radius:99px;background:var(--dark);color:#fff;font-weight:700;font-size:.86rem;transition:.15s}
.chat-in button:hover{background:var(--dark2)}

/* edukasi */
.ed{display:grid;grid-template-columns:repeat(3,1fr);gap:18px}
.art{cursor:pointer;padding:0;overflow:hidden;display:flex;flex-direction:column}
.art .th{height:110px;display:grid;place-items:center;font-size:2.8rem;background:var(--c)}
.art .th span{transition:transform .4s var(--ease)}.art:hover .th span{transform:scale(1.18) rotate(-6deg)}
.art .bd{padding:18px 20px 20px;display:flex;flex-direction:column;flex:1}
.art h3{font-size:1rem;margin-bottom:6px}.art p{font-size:.82rem;color:var(--muted);flex:1}
.art .rd{margin-top:12px;font-size:.8rem;font-weight:800;color:var(--olive)}
#modal{position:fixed;inset:0;z-index:200;display:none;place-items:center;padding:20px;background:rgba(11,59,54,.45);backdrop-filter:blur(3px)}
#modal.show{display:grid}
.mbox{position:relative;background:#fff;border-radius:18px;max-width:540px;width:100%;padding:30px;box-shadow:0 24px 70px rgba(11,59,54,.3);animation:pop .3s var(--ease)}
.mbox h2{font-size:1.3rem;margin-bottom:12px;padding-right:30px}.mbox p{font-size:.9rem;color:var(--muted);margin-bottom:10px}
.x{position:absolute;right:14px;top:14px;width:32px;height:32px;border-radius:8px;background:var(--bg);display:grid;place-items:center;color:var(--muted)}
.x:hover{background:var(--tint2)}.x svg{width:15px;height:15px}
.narrow{max-width:520px}

.toasts{position:fixed;right:22px;bottom:22px;z-index:300;display:grid;gap:10px}
.toast{display:flex;align-items:center;gap:10px;min-width:250px;padding:12px 18px;border-radius:12px;font-size:.84rem;font-weight:600;color:#fff;background:var(--dark);box-shadow:0 14px 34px rgba(11,59,54,.3);animation:tin .3s var(--ease)}
.toast.out{opacity:0;transform:translateX(30px);transition:.4s}
@keyframes tin{from{opacity:0;transform:translateX(30px)}}

/* =====================================================
   GEND-OFF · Dashboard polish layer
   (sidebar hijau tua, hero, stat card, animasi)
   ===================================================== */
:root{--dark3:#06221f}
body{
  background:
    radial-gradient(900px 520px at 100% -6%,rgba(164,194,61,.17),transparent 60%),
    radial-gradient(760px 480px at -8% 16%,rgba(18,82,74,.10),transparent 55%),
    var(--bg);
  background-attachment:fixed;
}
.page.active{animation:none}
@keyframes rise{from{opacity:0;transform:translateY(16px)}}
.rise{animation:rise .65s var(--ease) backwards;animation-delay:calc(var(--i,0)*70ms)}

/* ---------- Sidebar hijau tua ---------- */
.sidebar{
  background:
    linear-gradient(200deg,rgba(164,194,61,.18),transparent 38%),
    linear-gradient(165deg,var(--dark3) 0%,var(--dark) 52%,var(--dark2) 100%);
  border-right:0;color:#fff;overflow:hidden;
  box-shadow:12px 0 40px -20px rgba(6,34,31,.55);
}
.sidebar::after{
  content:"";position:absolute;left:-60px;bottom:-80px;width:240px;height:240px;border-radius:50%;pointer-events:none;
  background:radial-gradient(circle,rgba(164,194,61,.32),transparent 68%);animation:breathe 6s ease-in-out infinite;
}
@keyframes breathe{50%{transform:scale(1.18);opacity:.7}}
.sidebar>*{position:relative;z-index:1}
.brand{border-bottom:1px solid rgba(255,255,255,.1)}
.brand-mark{width:50px;height:50px;padding:4px;border-radius:14px;flex-shrink:0;display:grid;place-items:center;background:rgba(255,255,255,.96);box-shadow:0 8px 18px rgba(0,0,0,.25);transition:transform .35s var(--ease)}
.brand:hover .brand-mark{transform:rotate(-6deg) scale(1.06)}
.brand-mark img{width:100%;height:100%}
.brand b{color:#fff}
.brand b span{color:var(--lime)}
.brand small{color:rgba(255,255,255,.6)}
.nav::-webkit-scrollbar-thumb{background:rgba(255,255,255,.18)}
.nav>*{animation:slideIn .55s var(--ease) backwards;animation-delay:calc(var(--i,0)*45ms + .1s)}
@keyframes slideIn{from{opacity:0;transform:translateX(-16px)}}
.nav-label{color:rgba(255,255,255,.4)}
.nav-item{color:rgba(255,255,255,.78)}
.nav-item:hover{background:rgba(255,255,255,.09);color:#fff;transform:translateX(3px)}
.nav-item.active{
  background:linear-gradient(90deg,rgba(164,194,61,.26),rgba(164,194,61,.07));
  color:#fff;box-shadow:inset 0 0 0 1px rgba(164,194,61,.24);
}
.nav-item.active .ic{color:var(--lime)}
.side-foot{border-top:1px solid rgba(255,255,255,.1)}
.logout{color:rgba(255,255,255,.85);border-color:rgba(255,255,255,.18);background:rgba(255,255,255,.06)}
.logout:hover{color:#ffd9d6;border-color:rgba(229,72,77,.5);background:rgba(229,72,77,.18)}
.side-ver{color:rgba(255,255,255,.4)}

/* ---------- Topbar ---------- */
.topbar{background:rgba(255,255,255,.82);backdrop-filter:blur(14px) saturate(160%);-webkit-backdrop-filter:blur(14px) saturate(160%)}
.chip-user{background:#fff;box-shadow:var(--shadow)}
.chip-user .av{position:relative}
.chip-user .av::after{content:"";position:absolute;right:-1px;bottom:-1px;width:10px;height:10px;border-radius:50%;background:#3fe08a;border:2px solid #fff}
.live{display:inline-block;width:7px;height:7px;border-radius:50%;background:#3fe08a;margin-right:6px;vertical-align:1px;animation:livePulse 1.8s infinite}
@keyframes livePulse{0%{box-shadow:0 0 0 0 rgba(63,224,138,.6)}100%{box-shadow:0 0 0 8px transparent}}

/* ---------- Judul halaman ---------- */
.sec-h h1{font-size:1.6rem;letter-spacing:-.3px}
.sec-h p{margin-top:8px}

/* ---------- Hero sapaan ---------- */
.hero-card{
  position:relative;overflow:hidden;display:flex;align-items:center;justify-content:space-between;gap:28px;flex-wrap:wrap;
  padding:30px 36px;margin-bottom:20px;border-radius:24px;color:#fff;
  background:
    radial-gradient(480px 240px at 100% 0%,rgba(164,194,61,.16),transparent 70%),
    linear-gradient(135deg,var(--dark2) 0%,var(--dark) 60%,var(--dark3) 100%);
  border:1px solid rgba(255,255,255,.08);box-shadow:0 26px 50px -26px rgba(6,34,31,.65);
}
.hero-txt{position:relative;z-index:2;max-width:560px}
.hero-card h1{font-family:inherit;font-weight:800;font-size:clamp(1.6rem,2.8vw,2.2rem);letter-spacing:-.4px;line-height:1.2;color:#fff;margin-bottom:8px}
.hero-card p{color:rgba(255,255,255,.8);font-size:.92rem;max-width:470px;margin-bottom:20px}
.hero-vid{position:absolute;top:0;right:0;width:54%;height:100%;object-fit:cover;object-position:center 8%;z-index:0;pointer-events:none;
  -webkit-mask-image:linear-gradient(90deg,transparent 0%,rgba(0,0,0,.55) 30%,#000 62%);mask-image:linear-gradient(90deg,transparent 0%,rgba(0,0,0,.55) 30%,#000 62%)}
.hero-card::after{content:"";position:absolute;inset:0;z-index:1;pointer-events:none;background:linear-gradient(90deg,transparent 40%,rgba(11,59,54,.42) 100%)}
.hero-txt{z-index:2}
.hmeta{display:flex;gap:8px;flex-wrap:wrap;margin-bottom:16px}
.hm{display:inline-flex;align-items:center;gap:5px;padding:5px 12px;border-radius:99px;background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.16);font-size:.76rem;font-weight:600;color:rgba(255,255,255,.85)}
.hm b{font-family:var(--mono);font-weight:600;color:var(--lime)}
.hero-btns{display:flex;gap:10px;flex-wrap:wrap}
.btn-l{background:linear-gradient(135deg,var(--olive),var(--lime));color:var(--dark);box-shadow:0 10px 22px rgba(0,0,0,.25)}
.btn-l:hover{transform:translateY(-2px);box-shadow:0 14px 28px rgba(0,0,0,.3)}
.btn-g{background:rgba(255,255,255,.1);border-color:rgba(255,255,255,.3);color:#fff}
.btn-g:hover{background:rgba(255,255,255,.2)}


/* ---------- Jam real time ---------- */
.clk{display:flex;align-items:center;gap:9px;padding:5px 13px 5px 6px;border-radius:11px;color:#fff;background:linear-gradient(150deg,var(--dark2),var(--dark3));border:1px solid rgba(255,255,255,.08);box-shadow:0 10px 20px -12px rgba(6,34,31,.75)}
.clk-ic{width:26px;height:26px;border-radius:8px;display:grid;place-items:center;flex-shrink:0;background:rgba(255,255,255,.12);color:var(--lime)}
.clk-ic svg{width:14px;height:14px}
.clk b{display:block;font-family:var(--mono);font-size:.8rem;font-weight:600;letter-spacing:.4px;line-height:1.2;color:#fff}
.clk small{display:block;font-size:.62rem;font-weight:600;color:rgba(255,255,255,.7);line-height:1.25}

/* ---------- Toggle grafik, hasil, progres, intervensi ---------- */
.seg{display:inline-flex;padding:3px;border-radius:99px;background:var(--bg);border:1px solid var(--border);margin-bottom:14px}
.seg button{padding:6px 15px;border-radius:99px;font-size:.78rem;font-weight:700;color:var(--muted);transition:.25s}
.seg button.on{background:linear-gradient(135deg,var(--dark),var(--dark2));color:#fff}
.empty p{font-size:.86rem;color:var(--muted);margin-bottom:14px;max-width:420px}
.lr-big{display:flex;align-items:center;gap:14px;margin-bottom:16px;flex-wrap:wrap}
.lr-big .big{font-family:var(--mono);font-size:2.6rem;font-weight:600;color:var(--dark);line-height:1}
.kv{display:grid;gap:9px;margin-bottom:16px}
.kv div{display:flex;justify-content:space-between;gap:10px;padding:10px 13px;border-radius:10px;background:var(--bg);border:1px solid var(--border-soft);font-size:.84rem}
.kv span{color:var(--muted);font-weight:600}.kv b{color:var(--dark);text-align:right}
.dot{display:inline-block;width:8px;height:8px;border-radius:50%;margin-right:6px}
.pg-top{display:flex;align-items:baseline;gap:10px;margin-bottom:4px}
.pg-top b{font-family:var(--mono);font-size:2.5rem;font-weight:600;color:var(--dark);line-height:1}
.pg-top span{font-size:.82rem;color:var(--muted);font-weight:600}
.ptrack{position:relative;height:10px;border-radius:99px;background:var(--border-soft);margin:26px 0 9px}
.ptrack i{display:block;height:100%;width:0;border-radius:99px;background:linear-gradient(90deg,var(--olive),var(--lime));transition:width 1.1s var(--ease)}
.ptrack .knob{position:absolute;top:50%;left:0;width:18px;height:18px;border-radius:50%;background:#fff;border:3px solid var(--olive);transform:translate(-50%,-50%);box-shadow:0 2px 6px rgba(11,59,54,.25);transition:left 1.1s var(--ease)}
.plbl{display:flex;justify-content:space-between;font-size:.74rem;font-weight:700;color:var(--faint);font-family:var(--mono);margin-bottom:16px}
.iv-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:14px}
.iv{padding:18px;border-radius:14px;border:1px solid var(--border-soft);background:var(--bg);transition:transform .25s var(--ease),background .25s,box-shadow .25s}
.iv:hover{background:#fff;box-shadow:var(--shadow-md);transform:translateY(-3px)}
.ivi{width:36px;height:36px;border-radius:10px;display:grid;place-items:center;background:var(--tint);color:var(--olive);margin-bottom:12px}
.ivi svg{width:18px;height:18px}
.iv h4{font-size:.92rem;margin-bottom:5px}
.iv p{font-size:.82rem;color:var(--muted);line-height:1.55}
.iv-st{display:flex;gap:8px;flex-wrap:wrap;margin-top:14px}

/* ---------- Panel detail kartu ---------- */
.detail{padding:22px 24px}
.sd-head{display:flex;align-items:center;justify-content:space-between;gap:14px;flex-wrap:wrap;margin-bottom:16px}
.sd-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:12px}
.sd-grid.anim>*{animation:rise .45s var(--ease) backwards}
.sd-grid.anim>*:nth-child(2){animation-delay:.05s}.sd-grid.anim>*:nth-child(3){animation-delay:.1s}.sd-grid.anim>*:nth-child(4){animation-delay:.15s}
.sd-it{padding:14px 16px;border-radius:12px;background:var(--bg);border:1px solid var(--border-soft)}
.sd-it span{display:block;font-size:.74rem;font-weight:700;color:var(--faint)}
.sd-it b{display:block;font-family:var(--mono);font-size:1.12rem;font-weight:600;color:var(--dark);margin-top:3px}

/* ---------- Mode lingkar perut, tabel BMI, CTA defisit ---------- */
.wmode{display:flex;gap:4px;padding:3px;border-radius:99px;background:var(--bg);border:1px solid var(--border);width:max-content;max-width:100%;flex-wrap:wrap}
.wmode button{padding:6px 14px;border-radius:99px;font-size:.76rem;font-weight:700;color:var(--muted);transition:.25s}
.wmode button.on{background:linear-gradient(135deg,var(--dark),var(--dark2));color:#fff}
.field input.err{border-color:var(--red);box-shadow:0 0 0 3px rgba(216,69,46,.12)}
.field input[readonly]{background:var(--tint);color:var(--dark);font-weight:600;cursor:not-allowed}
.fhint{font-size:.76rem;color:var(--faint);line-height:1.5}
.fhint.err{color:var(--red);font-weight:600}
.fhint.ok{color:var(--green);font-weight:600}
.note-ok{display:flex;gap:12px;padding:16px 18px;border-radius:14px;background:var(--green-bg);border:1px solid #bfe6cf;margin-bottom:18px}
.note-ok.warn{background:var(--amber-bg);border-color:#f0dc9a}
.note-ok b{display:block;color:var(--dark);font-size:.92rem;margin-bottom:2px}
.note-ok p{font-size:.84rem;color:var(--muted)}
.cta{padding:18px 20px;border-radius:14px;background:linear-gradient(135deg,var(--tint),#fff);border:1px solid var(--tint2);margin-bottom:18px}
.cta-h{display:flex;align-items:center;justify-content:space-between;gap:14px;flex-wrap:wrap}
.cta-h b{display:block;color:var(--dark);font-size:.95rem}.cta-h span{font-size:.8rem;color:var(--muted)}
.cta-links{display:none;grid-template-columns:repeat(3,1fr);gap:10px;margin-top:14px}
.cta-links.show{display:grid;animation:fade .35s var(--ease)}
.cta-links a{justify-content:center}
.cta-links a.btn{background:linear-gradient(135deg,var(--dark),var(--dark2));color:#fff;border:0;box-shadow:0 8px 16px -8px rgba(11,59,54,.5);transition:transform .25s var(--ease),box-shadow .25s,filter .25s}
.cta-links a.btn:hover{transform:translateY(-2px);filter:brightness(1.12);box-shadow:0 12px 20px -8px rgba(11,59,54,.6)}
.bmitbl{width:100%;border-collapse:collapse;font-size:.84rem}
.bmitbl th{text-align:left;padding:12px 14px;background:linear-gradient(135deg,var(--dark),var(--dark2));color:#fff;font-size:.74rem;text-transform:uppercase;letter-spacing:.6px}
.bmitbl th:first-child{border-radius:10px 0 0 10px}.bmitbl th:last-child{border-radius:0 10px 10px 0}
.bmitbl td{padding:11px 12px;border-bottom:1px solid var(--border-soft);color:var(--muted)}
.bmitbl td:first-child{color:var(--dark);font-weight:700}
.bmitbl tr.me td{background:var(--tint);color:var(--dark);font-weight:700}
.bmitbl tr.me td:last-child::after{content:" ← kamu";color:var(--olive)}
.tblwrap{overflow-x:auto}
@media(max-width:900px){.cta-links{grid-template-columns:1fr}}

/* ---------- Tombol ---------- */
.btn-p{position:relative;overflow:hidden;background:linear-gradient(135deg,var(--dark),var(--dark2))}
.btn-p::after{content:"";position:absolute;top:0;left:-80%;width:50%;height:100%;background:linear-gradient(100deg,transparent,rgba(255,255,255,.3),transparent);transform:skewX(-20deg);transition:left .6s}
.btn-p:hover::after{left:130%}

/* ---------- Kartu ---------- */
.card{background:linear-gradient(165deg,#fff 0%,#fbfdf8 100%)}
.card.hov:hover{transform:translateY(-4px);box-shadow:0 24px 44px -20px rgba(11,59,54,.3);border-color:#cfdcbb}
.stat{position:relative;overflow:hidden;background:radial-gradient(170px 120px at 100% 0%,var(--tint),transparent 70%),#fff}
.stat-ic{transition:transform .4s var(--ease)}
.card:hover .stat-ic{transform:rotate(-8deg) scale(1.1)}
.stat{cursor:pointer;user-select:none;transition:transform .25s var(--ease),box-shadow .3s,border-color .3s}
.stat::before{content:"";position:absolute;inset:0;border-radius:inherit;pointer-events:none;opacity:0;transition:opacity .45s var(--ease);
  background:radial-gradient(190px 130px at 100% 0%,rgba(164,194,61,.34),transparent 65%),linear-gradient(150deg,var(--dark2),var(--dark3))}
.stat>*{position:relative;z-index:1}
.stat.sel::before{opacity:1}
.stat.sel{border-color:rgba(255,255,255,.08);box-shadow:0 20px 38px -20px rgba(6,34,31,.75)}
.stat:not(.sel):hover{border-color:#cfdcbb}
.stat:focus-visible{outline:2px solid var(--lime);outline-offset:3px}
.stat .stat-l,.stat .stat-v,.stat .stat-n,.stat .stat-v small,.stat .stat-ic{transition:color .35s,background .35s}
.stat.sel .stat-l{color:rgba(255,255,255,.76)}
.stat.sel .stat-ic{background:rgba(255,255,255,.14);color:var(--lime)}
.stat.sel .stat-v{color:#fff}
.stat.sel .stat-v small{color:rgba(255,255,255,.6)}
.stat.sel .stat-n{color:rgba(255,255,255,.72)}
.stat.sel .stat-n b{color:#fff}
.stat.sel .bar{background:rgba(255,255,255,.15)}
.bar i{position:relative;overflow:hidden}
.bar i::after{content:"";position:absolute;inset:0;background:linear-gradient(100deg,transparent,rgba(255,255,255,.55),transparent);transform:translateX(-100%);animation:shine 3.6s ease-in-out 1.4s infinite}
@keyframes shine{50%,100%{transform:translateX(100%)}}
.ring .val{stroke:url(#rg)}
.gl.on{box-shadow:0 6px 14px -6px rgba(74,168,230,.6)}

/* ---------- Tab, chat, toast ---------- */
.tab.on{background:linear-gradient(135deg,var(--dark),var(--dark2));box-shadow:0 6px 14px -6px rgba(11,59,54,.5)}
.chat-h{background:linear-gradient(135deg,var(--tint),#fff)}
.msg.u{background:linear-gradient(135deg,var(--dark),var(--dark2))}
.chat-in button{background:linear-gradient(135deg,var(--dark),var(--dark2))}
.toast{border-left:4px solid var(--lime)}
.insight{background:linear-gradient(135deg,var(--tint),#fff)}
.mbox{border-top:4px solid var(--lime)}

@media(max-width:1180px){.g4{grid-template-columns:1fr 1fr}.g2{grid-template-columns:1fr}.ed{grid-template-columns:1fr 1fr}}
@media(max-width:900px){
  .sidebar{position:fixed;left:0;top:0;transform:translateX(-100%)}.sidebar.open{transform:none;box-shadow:0 0 40px rgba(11,59,54,.2)}
  .scrim.show{opacity:1;pointer-events:auto}
  .burger{display:flex}.date,.chip-user div{display:none}.chip-user{padding:5px}
  .topbar{padding:12px 16px}.content{padding:18px 16px 50px}
  .form-grid,.g2e,.fc{grid-template-columns:1fr}.chips4{grid-template-columns:1fr 1fr}.ed{grid-template-columns:1fr}
  .ring-w{flex-direction:column;align-items:flex-start}
}
@media(max-width:560px){.g4{grid-template-columns:1fr}.msg{max-width:92%}}
@media(max-width:900px){.hero-vid{width:100%;opacity:.28}.iv-grid{grid-template-columns:1fr}.clk small,.clk-ic{display:none}.clk{padding:8px 12px}.sd-grid{grid-template-columns:1fr 1fr}.hero-card{padding:24px 20px}.hero-side{min-width:0;width:100%}.sec-h h1{font-size:1.4rem}}
@media(prefers-reduced-motion:reduce){*{animation:none!important;transition:none!important}.cl{stroke-dashoffset:0!important}.ca,.cd{opacity:1!important}}
/* ---------- Gizi Planner ---------- */
.gz-top{background:linear-gradient(135deg,var(--dark),var(--dark2));color:#fff;border:0;margin-bottom:18px;padding:24px 26px;position:relative;overflow:hidden}
.gz-top::after{content:"";position:absolute;right:-60px;top:-70px;width:240px;height:240px;border-radius:50%;background:radial-gradient(circle,rgba(164,194,61,.35),transparent 70%);pointer-events:none}
.gz-h{display:flex;justify-content:space-between;align-items:flex-end;gap:16px;flex-wrap:wrap;position:relative;z-index:1}
.gz-k{font-size:.72rem;font-weight:800;letter-spacing:1px;text-transform:uppercase;color:rgba(255,255,255,.7)}
.gz-big{display:flex;align-items:baseline;gap:8px}.gz-big b{font-family:var(--mono);font-size:2.6rem;font-weight:600;line-height:1.1;color:#fff}.gz-big small{color:rgba(255,255,255,.7);font-size:.9rem}
.gz-tag{max-width:380px;text-align:right}.gz-tag p{font-size:.82rem;color:rgba(255,255,255,.8);margin-top:8px}.gz-tag a{color:var(--lime);font-weight:700;text-decoration:underline}
.gz-badge{display:inline-block;padding:5px 14px;border-radius:99px;font-size:.8rem;font-weight:800;background:rgba(164,194,61,.22);color:var(--lime);border:1px solid rgba(164,194,61,.4)}
.gz-badge.warn{background:rgba(245,184,46,.18);color:#f5c84e;border-color:rgba(245,184,46,.4)}
.gz-m{display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-top:18px;position:relative;z-index:1}
.gz-m>div{padding:13px 16px;border-radius:12px;background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.12)}
.gz-m b{display:block;font-family:var(--mono);font-size:1.2rem;font-weight:600;color:#fff}
.gz-m span{font-size:.7rem;font-weight:700;letter-spacing:.6px;text-transform:uppercase;color:rgba(255,255,255,.65)}
.vsw{margin-bottom:10px}
.mname{font-size:.84rem;color:var(--muted);font-weight:600;margin-bottom:12px}
.mq{font-size:.74rem;color:var(--faint);margin-top:3px;font-weight:600}
.mm{display:grid;grid-template-columns:repeat(3,1fr);gap:8px;margin:12px 0}
.mm>div{padding:10px;border-radius:10px;background:var(--bg);border:1px solid var(--border-soft);text-align:center}
.mm b{display:block;font-family:var(--mono);font-size:1rem;color:var(--dark)}
.mm span{font-size:.66rem;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:var(--faint)}
.sbh{font-size:.72rem;font-weight:800;letter-spacing:.9px;text-transform:uppercase;color:var(--faint);margin:20px 0 12px}
.nrow{margin-bottom:12px}
.nrow label{display:flex;justify-content:space-between;font-size:.8rem;font-weight:700;color:var(--dark);margin-bottom:5px}
.nrow label span:last-child{font-family:var(--mono);font-size:.74rem;color:var(--faint)}
.nrow .tk{height:7px;border-radius:99px;background:var(--border-soft);overflow:hidden}
.nrow .tk i{display:block;height:100%;width:0;border-radius:99px;transition:width .9s var(--ease)}
.tab.done small::after{content:" ✓"}
@media(max-width:900px){.gz-tag{text-align:left}.gz-m{grid-template-columns:1fr}}
/* ---------- Gizi Planner: 4 card ---------- */
.g3{display:grid;grid-template-columns:repeat(3,1fr);gap:18px;align-items:stretch}.g3>.card{display:flex;flex-direction:column;min-width:0}
.gh{display:flex;gap:10px;align-items:center;margin-bottom:14px}
.gh i{font-style:normal;width:28px;height:28px;border-radius:50%;display:grid;place-items:center;background:linear-gradient(135deg,var(--dark),var(--dark2));color:#fff;font-weight:800;font-size:.82rem;flex-shrink:0}
.gh b{font-size:1.02rem;color:var(--dark)}.gh small{display:block;font-size:.74rem;color:var(--faint);font-weight:600}
.nut,.fd{display:flex;gap:12px;align-items:center;width:100%;text-align:left;padding:12px 14px;border-radius:12px;border:1.5px solid var(--border-soft);background:#fff;margin-bottom:8px;transition:.2s}
.nut:hover,.fd:hover{border-color:var(--olive);transform:translateX(2px)}
.nut.on,.fd.on{border-color:var(--dark);background:var(--tint);box-shadow:0 8px 18px -12px rgba(11,59,54,.5)}
.ne{font-size:1.5rem}.nb{flex:1;min-width:0}.nb b{display:block;color:var(--dark);font-size:.9rem}.nb small{display:block;font-size:.74rem;color:var(--muted)}
.nb em{display:block;font-style:normal;font-size:.72rem;color:var(--faint);margin-top:3px;font-weight:600}
.nv{text-align:right;white-space:nowrap}.nv b{display:block;font-family:var(--mono);font-size:1.15rem;color:var(--dark)}.nv small{font-size:.7rem;color:var(--faint);font-weight:600}
.lim{display:flex;flex-wrap:wrap;gap:8px;margin-top:12px}
.lim span{padding:6px 12px;border-radius:99px;background:var(--bg);border:1px solid var(--border);font-size:.74rem;font-weight:700;color:var(--dark)}
.adj{margin-top:14px;padding:12px 14px;border-radius:12px;background:var(--tint);font-size:.78rem;color:var(--dark)}.adj li{margin:4px 0 4px 16px}
.jt{padding:10px 12px;border-radius:10px;background:var(--bg);font-size:.76rem;color:var(--muted);margin-bottom:12px}
.mth{padding:13px 14px;border-radius:12px;border:1px solid var(--border-soft);border-left:4px solid var(--green);margin-bottom:9px;background:#fff}
.mth.batas{border-left-color:#f5b82e}.mth.no{border-left-color:var(--red)}
.mth .t{display:flex;justify-content:space-between;gap:8px;align-items:center}.mth .t b{color:var(--dark);font-size:.9rem}
.stt{font-size:.68rem;font-weight:800;padding:3px 10px;border-radius:99px;background:var(--green-bg);color:var(--green);white-space:nowrap}
.batas .stt{background:var(--amber-bg);color:var(--amber)}.no .stt{background:var(--red-bg);color:var(--red)}
.mth p{font-size:.78rem;color:var(--muted);margin-top:4px}
.mth .nm{font-family:var(--mono);font-size:.76rem;color:var(--olive);margin-top:6px;font-weight:600}
.monw{display:grid;grid-template-columns:repeat(5,1fr);gap:14px;margin-bottom:22px}
.mg{display:grid;grid-template-columns:repeat(2,1fr);gap:20px}
.mgh{display:flex;justify-content:space-between;align-items:center;margin-bottom:8px}.mgh b{color:var(--dark);font-size:.96rem}.mgh small{font-family:var(--mono);font-size:.74rem;color:var(--faint)}
.ck{display:flex;gap:10px;align-items:flex-start;padding:9px 11px;border-radius:10px;cursor:pointer;border:1px solid var(--border-soft);margin-bottom:6px;background:#fff;transition:.2s}
.ck input{accent-color:var(--dark);width:18px;height:18px;margin-top:2px;flex-shrink:0}
.ck.on{background:var(--tint);border-color:#cfdcbb}.ck.on b{text-decoration:line-through;opacity:.65}
.ck b{display:block;color:var(--dark);font-size:.84rem}.ck small{display:block;color:var(--faint);font-size:.72rem;font-weight:600}
.ck span{margin-left:auto;font-family:var(--mono);font-size:.74rem;color:var(--olive);white-space:nowrap}
@media(max-width:1180px){.g3{grid-template-columns:1fr}.monw{grid-template-columns:1fr 1fr}}
@media(max-width:900px){.mg{grid-template-columns:1fr}}
.lw{position:relative;flex:1;min-height:340px}
.ls{position:absolute;inset:0;overflow-y:auto;padding-right:4px}
.srch{position:relative;margin-bottom:10px}
.srch svg{position:absolute;left:12px;top:50%;transform:translateY(-50%);width:16px;height:16px;color:var(--faint);pointer-events:none}
.srch input{width:100%;padding:10px 12px 10px 36px;border:1.5px solid var(--border);border-radius:10px;background:#fff;font-size:.84rem;transition:border-color .2s,box-shadow .2s}
.srch input:focus{outline:none;border-color:var(--olive);box-shadow:0 0 0 3px rgba(164,194,61,.2)}
.ls .sbh:first-child{margin-top:4px}
@media(max-width:1180px){.lw{position:static;min-height:0}.ls{position:static}}
</style>
</head>
<body>
<div class="scrim" id="scrim"></div>
<div class="app">

  <!-- ===== SIDEBAR ===== -->
  <aside class="sidebar" id="sidebar">
    <a href="index.php" class="brand"><span class="brand-mark"><img src="assets/logo.png" alt="Logo"></span><span><b>GEND<span>-OFF</span></b><small>Gizi-On, Gendut-Off</small></span></a>
    <nav class="nav" id="nav"></nav>
    <div class="side-foot">
      <a href="login.php" class="logout" onclick="return confirm('Yakin ingin keluar?')"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><path d="M16 17l5-5-5-5"/><path d="M21 12H9"/></svg> Keluar dari akun</a>
      <div class="side-ver">GEND-OFF v2.0<br>© <?= $tahun ?> Sistem Pakar Gizi</div>
    </div>
  </aside>

  <!-- ===== MAIN ===== -->
  <div class="main">
    <header class="topbar">
      <div class="tb-l">
        <button class="burger" id="burger" aria-label="Menu"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M4 7h16M4 12h16M4 17h16"/></svg></button>
        <div><div class="tb-title" id="tbT">Gizi Planner</div><div class="tb-sub" id="tbS"></div></div>
      </div>
      <div class="tb-r">
        <div class="clk" id="clock"><span class="clk-ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 3"/></svg></span><div><b id="clkT">--:--:--</b><small id="clkD">-- --- ----</small></div></div>
        <div class="chip-user"><div class="av" id="uAv"><?= $inisial ?></div><div><b id="uName"><?= $username ?></b><small>Pengguna Aktif</small></div></div>
      </div>
    </header>

    <main class="content">
      <!-- GIZI PLANNER -->
      <section class="page active" id="page-gizi">
        <div class="sec-h"><h1>Gizi Planner</h1><p>Kebutuhan gizi harianmu dihitung dari hasil Deteksi Obesitas: gizi apa yang dibutuhkan, makanan sumbernya, cara mengolahnya, lalu menu harian dengan ceklist.</p></div>
        <div class="card gz-top" id="gzTop"></div>
        <div class="g3">
          <div class="card" id="c1"></div>
          <div class="card" id="c2"></div>
          <div class="card" id="c3"></div>
        </div>
        <div class="gh" style="margin:26px 0 14px"><i>4</i><div><b>Menu harian &amp; ceklist makan</b><small>Pilih waktu makan, centang yang sudah dimakan, gizinya langsung terhitung</small></div></div>
        <div class="tabs" id="mTabs"></div>
        <div class="g2e">
          <div class="card" id="mCard"></div>
          <div class="card" id="mSum"></div>
        </div>
      </section>
    </main>
  </div>
</div>

<div class="toasts" id="toasts"></div>

<script>
const $=s=>document.querySelector(s),$$=s=>[...document.querySelectorAll(s)];
const fmt=n=>Math.round(n).toLocaleString('id-ID');
const dec=(n,d=1)=>n.toFixed(d).replace('.',',');
const esc=s=>String(s).replace(/[&<>"]/g,c=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;'}[c]));
const sv=p=>`<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">${p}</svg>`;
const ICON={
 grid:sv('<rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/>'),
 pulse:sv('<path d="M3 12h4l3-8 4 16 3-8h4"/>'),
 salad:sv('<path d="M4 11h16a8 8 0 01-16 0z"/><path d="M8 11c0-3 2-5 4-5s4 2 4 5"/>'),
 chat:sv('<path d="M21 12a8 8 0 01-11.6 7.1L3 21l1.9-5.6A8 8 0 1121 12z"/>'),
 book:sv('<path d="M4 4h6a3 3 0 013 3v13a2 2 0 00-2-2H4z"/><path d="M20 4h-6a3 3 0 00-3 3v13a2 2 0 012-2h7z"/>'),
 file:sv('<path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6M9 13h6M9 17h6"/>'),
 user:sv('<circle cx="12" cy="8" r="4"/><path d="M4 21c0-4.4 3.6-8 8-8s8 3.6 8 8"/>'),
 fire:sv('<path d="M12 22c4 0 7-3 7-7 0-3-2-5-3-7-1 2-2 3-3 3 0-4-2-7-4-9 0 4-4 6-4 12 0 4 3 8 7 8z"/>'),
 scale:sv('<rect x="3" y="4" width="18" height="16" rx="3"/><path d="M8 9a4 4 0 018 0"/><path d="M12 9l2-2"/>'),
 drop:sv('<path d="M12 2.5S5 10 5 15a7 7 0 0014 0c0-5-7-12.5-7-12.5z"/>'),
 target:sv('<circle cx="12" cy="12" r="9"/><circle cx="12" cy="12" r="5"/><circle cx="12" cy="12" r="1"/>'),
 bulb:sv('<path d="M9 18h6M10 22h4"/><path d="M12 2a7 7 0 00-4 12.7c.6.5 1 1.3 1 2.3h6c0-1 .4-1.8 1-2.3A7 7 0 0012 2z"/>'),
 close:sv('<path d="M18 6L6 18M6 6l12 12"/>')
};

/* navigasi: Gizi Planner = halaman ini, lainnya ke halaman masing-masing */
const NAV=[
 {g:'Utama',items:[['dashboard','Dashboard','grid','dashboard.php']]},
 {g:'Pemeriksaan & Menu',items:[['deteksi','Deteksi Obesitas','pulse','deteksi_obesitas.php'],['gizi','Gizi Planner','salad','gizi_planner.php']]},
 {g:'Konsultasi & Belajar',items:[['chatbot','Konsultasi AI','chat','dashboard.php#chatbot'],['edukasi','Edukasi Pengguna','book','dashboard.php#edukasi']]},
 {g:'Laporan & Akun',items:[['ekspor','Ekspor Laporan','file','dashboard.php#ekspor'],['profil','Kelola Profil','user','dashboard.php#profil']]}
];
$('#nav').innerHTML=NAV.map(n=>`<div class="nav-label">${n.g}</div>`+n.items.map(i=>`<a class="nav-item${i[0]==='gizi'?' active':''}" href="${i[3]}"><span class="ic">${ICON[i[2]]}</span>${i[1]}</a>`).join('')).join('');
$$('#nav > *').forEach((e,i)=>e.style.setProperty('--i',i));
$$('[data-i]').forEach(e=>e.innerHTML=ICON[e.dataset.i]);

/* ===== STATE (kunci sama dengan dashboard agar data tersinkron) ===== */
const KEY='gendoff_dash_v3';let S;
function load(){
  let raw=null;try{raw=JSON.parse(localStorage.getItem(KEY)||'null')}catch(e){}
  const first=!raw;
  S=Object.assign({target:1600,tb:170,bb:70.8,goal:65,water:first?4:0,eaten:first?{0:true}:{},checks:first?[true,true,false,false]:[false,false,false,false],day:'',weights:null,waists:null,last:null},raw||{});
  const today=new Date().toDateString();
  if(!first&&S.day!==today){S.water=0;S.eaten={};S.checks=[false,false,false,false];S.chk={};S.mk=[]}
  S.day=today;
  if(!S.chk||typeof S.chk!=='object')S.chk={};if(!Array.isArray(S.mk))S.mk=[];
  if(!S.weights){const v=[72.5,72.2,71.8,71.9,71.5,71.0,70.8];S.weights=v.map((w,i)=>{const d=new Date();d.setDate(d.getDate()-(6-i));return{d:d.toDateString(),w}})}
  if(!S.waists){const v=[90,89.6,89.2,88.8,88.4,88,87.6];S.waists=v.map((w,i)=>{const d=new Date();d.setDate(d.getDate()-(6-i));return{d:d.toDateString(),w}})}
  save();
}
function save(){try{localStorage.setItem(KEY,JSON.stringify(S))}catch(e){}}
function toast(msg){const t=document.createElement('div');t.className='toast';t.textContent=msg;$('#toasts').appendChild(t);setTimeout(()=>t.classList.add('out'),2600);setTimeout(()=>t.remove(),3100)}
function countTo(el,to,d=0,ms=800){const from=parseFloat(el.dataset.v||0);el.dataset.v=to;const t0=performance.now();
  const f=t=>{const k=Math.min(1,(t-t0)/ms),e=1-Math.pow(1-k,3),v=from+(to-from)*e;el.textContent=d?dec(v,d):fmt(v);if(k<1)requestAnimationFrame(f)};requestAnimationFrame(f)}
function stagger(t){
  $$(`#page-${t} .sec-h, #page-${t} .tabs, #page-${t} .card`).forEach((e,i)=>{
    e.classList.remove('rise');e.style.setProperty('--i',i);void e.offsetWidth;e.classList.add('rise');
  });
}
const side=$('#sidebar'),scrim=$('#scrim');
function closeSide(){side.classList.remove('open');scrim.classList.remove('show')}
$('#burger').onclick=()=>{side.classList.add('open');scrim.classList.add('show')};scrim.onclick=closeSide;

/* ===== DATA MAKANAN =====
   FOOD: [nama, emoji, label porsi, gram, kkal, protein, karbo, lemak, serat, metode[]]
   metode: [nama, bahan tambahan, +kkal, +lemak g, status ok|batas|no, catatan] */
const FOOD={
 tl:['Telur ayam','🥚','1 butir',55,78,6,1,5,0,[['Rebus','Tanpa tambahan',0,0,'ok','Paling hemat kalori, rebus 9–10 menit'],['Dadar teflon','+1 sdt minyak, bawang, daun bawang',45,5,'batas','Pakai teflon, hindari santan atau keju'],['Goreng/ceplok','+2 sdm minyak',160,18,'no','Menyerap banyak minyak, kalori hampir tiga kali lipat']]],
 ay:['Dada ayam tanpa kulit','🍗','1 potong',100,165,31,0,4,0,[['Panggang/kukus','Bumbu rempah + perasan jeruk',10,0,'ok','Buang kulit agar lemak jenuh turun'],['Tumis sayur','+1 sdt minyak, bawang, brokoli',50,5,'batas','Tambah sayur supaya kenyang tanpa banyak kalori'],['Goreng tepung','+tepung, 2 sdm minyak',190,15,'no','Tepung dan minyak menambah ±190 kkal']]],
 ik:['Ikan nila','🐟','1 potong',100,128,26,0,3,0,[['Kukus/pepes','Daun pisang, kunyit, tomat',20,1,'ok','Tanpa minyak, aroma kuat tanpa kalori tambahan'],['Panggang','+1 sdt kecap, jeruk nipis',15,0,'ok','Olesi tipis agar tidak gosong'],['Goreng','+2 sdm minyak',170,18,'no','Minyak menempel di kulit, kalori naik tajam']]],
 tp:['Tempe','🫘','2 potong',50,100,10,4,5,1,[['Kukus/panggang','Tanpa tambahan',0,0,'ok','Protein nabati, rendah lemak jenuh'],['Tumis','+1 sdt minyak, cabai, bawang',45,5,'batas','Sedikit minyak, kurangi kecap manis'],['Goreng (mendoan)','+tepung, 2 sdm minyak',150,16,'no','Berminyak, cukup sesekali']]],
 nm:['Nasi merah','🍚','1 centong',100,110,3,23,1,2,[['Tanak biasa','Air saja',0,0,'ok','Takar 100 g matang per centong'],['Nasi goreng','+1 sdm minyak, kecap, telur',110,8,'batas','Pakai 1 sdt minyak dan banyak sayur'],['Nasi uduk','+santan',90,8,'no','Santan tinggi lemak jenuh']]],
 ub:['Ubi jalar','🍠','1 buah sedang',100,86,2,20,0,3,[['Rebus/kukus','Tanpa tambahan',0,0,'ok','Indeks glikemik lebih rendah daripada digoreng'],['Panggang','Tanpa minyak',5,0,'ok','Manis alami, tak perlu gula'],['Goreng','+2 sdm minyak',150,15,'no','Kalori hampir dua kali lipat']]],
 ot:['Oatmeal','🥣','3 sdm kering',40,152,5,27,3,4,[['Seduh air panas','+kayu manis, potongan buah',20,0,'ok','Tambah buah, bukan gula'],['Pakai susu rendah lemak','+150 ml susu',55,1,'batas','Hitung kalori susunya'],['Granola + madu','+2 sdm madu, granola',140,4,'no','Gula tambahan cepat menambah kalori']]],
 av:['Alpukat','🥑','½ buah',100,160,2,9,15,7,[['Dimakan langsung','Perasan jeruk nipis',0,0,'ok','Lemak tak jenuh, ½ buah sudah cukup'],['Smoothie tanpa gula','+susu skim, es',45,0,'batas','Jangan tambah susu kental manis'],['Jus + susu kental manis','+2 sdm SKM, gula',140,2,'no','Gula tambahan menggandakan kalori']]],
 kt:['Kacang tanah','🥜','1 genggam',30,95,4,4,7,2,[['Rebus','Garam sedikit',0,0,'ok','Satu genggam saja, mudah kebablasan'],['Sangrai kering','Tanpa minyak',10,0,'batas','Takar 30 g, jangan langsung dari toples'],['Goreng/kacang bawang','+minyak, tepung',90,8,'no','Berminyak dan asin']]],
 al:['Almond','🌰','1 genggam kecil',20,116,4,4,10,3,[['Mentah/sangrai','Tanpa garam',0,0,'ok','20 g sekitar 15 butir'],['Campur yoghurt','+100 g yoghurt plain',63,2,'batas','Pilih yoghurt tanpa gula'],['Almond cokelat','+gula, cokelat',90,5,'no','Gula tambahan']]],
 by:['Bayam','🥬','1 mangkuk',100,23,3,4,0,2,[['Rebus/blanch','Tanpa minyak',0,0,'ok','Rebus 2 menit agar vitamin tidak hilang'],['Tumis bawang putih','+1 sdt minyak',45,5,'batas','Sedikit minyak membantu serap vitamin A dan K'],['Lodeh santan','+100 ml santan',130,13,'no','Santan tinggi lemak jenuh']]],
 br:['Brokoli','🥦','1 mangkuk',100,35,2,7,0,3,[['Kukus','Tanpa tambahan',0,0,'ok','Kukus 4–5 menit tetap renyah'],['Tumis','+1 sdt minyak, bawang putih',45,5,'batas','Pakai teflon agar minyak sedikit'],['Gratin keju','+keju, krim',150,12,'no','Keju dan krim menambah lemak jenuh']]],
 pp:['Pepaya','🍈','1 potong',150,65,1,16,0,3,[['Segar dipotong','Tanpa tambahan',0,0,'ok','Serat dan enzim baik untuk pencernaan'],['Jus tanpa gula','+air, es',5,0,'batas','Serat berkurang bila disaring'],['Es campur sirup','+sirup, susu kental manis',120,2,'no','Gula tambahan tinggi']]]
};
const SRC={p:['tl','ay','ik','tp'],c:['nm','ub','ot'],l:['av','kt','al'],fi:['by','br','pp']};
const VK={p:5,c:6,l:7,fi:8};
/* makanan lain untuk fitur cari (porsi dan gizi perkiraan); indeks 9 = kelompok pengolahan */
const EXTRA={
 sp:['Daging sapi tanpa lemak','🥩','1 potong',100,180,27,0,8,0,'g'],ud:['Udang','🦐','1 porsi',100,99,24,0,.3,0,'g'],cu:['Cumi-cumi','🦑','1 porsi',100,92,16,3,1,0,'g'],
 tn:['Tuna kaleng (dalam air)','🐟','1 porsi',100,116,26,0,1,0,'g'],sl:['Salmon','🍣','1 potong',100,206,22,0,12,0,'g'],th:['Tahu','⬜','2 potong',100,76,8,2,5,0,'g'],
 yg:['Yoghurt plain','🥛','1 cup',100,63,5,7,2,0,'d'],sk:['Susu skim','🥛','1 gelas',250,90,8,12,0,0,'d'],cc:['Keso cottage','🧀','1 porsi',100,98,11,3,4,0,'d'],
 ks:['Kacang merah rebus','🫘','1 porsi',100,127,9,23,.5,6,'g'],kh:['Kacang hijau rebus','🫘','1 porsi',100,105,7,19,.4,8,'g'],ed:['Edamame rebus','🫛','1 porsi',80,95,9,7,4,4,'g'],
 kn:['Kentang rebus','🥔','1 buah',100,87,2,20,0,2,'g'],sg:['Singkong rebus','🥔','1 porsi',100,160,1,38,0,2,'g'],jg:['Jagung rebus','🌽','1 tongkol',100,96,3,21,1.5,2,'g'],
 tr:['Talas rebus','🥔','1 porsi',100,112,.5,26,0,4,'g'],np:['Nasi putih','🍚','1 centong',100,130,3,28,0,0,'g'],ps:['Pisang','🍌','1 buah',100,90,1,23,0,2,'f'],lb:['Labu kuning','🎃','1 porsi',100,26,1,7,0,.5,'v'],
 ch:['Biji chia','🌱','1 sdm',15,73,2.5,6,4.6,5,'n'],kw:['Kenari','🌰','1 genggam kecil',20,131,3,3,13,1,'n'],sb:['Biji bunga matahari','🌻','1 genggam kecil',20,117,4,4,10,2,'n'],
 sk2:['Selai kacang tanpa gula','🥜','1 sdm',16,94,4,3,8,1,'n'],mz:['Minyak zaitun','🫒','1 sdt',5,44,0,0,5,0,'o'],
 ap:['Apel','🍎','1 buah',150,78,.4,21,.3,3.6,'f'],jr:['Jeruk','🍊','1 buah',130,61,1,15,0,3,'f'],pr:['Pir','🍐','1 buah',150,86,.5,23,0,5,'f'],jb:['Jambu biji','🍈','1 buah',100,68,3,14,1,5,'f'],
 wt:['Wortel rebus','🥕','1 mangkuk',100,35,1,8,0,3,'v'],kk:['Kangkung','🥬','1 mangkuk',100,20,2,3,0,2,'v'],bc:['Buncis rebus','🫛','1 mangkuk',100,35,2,8,0,3,'v'],
 kf:['Kembang kol','🥦','1 mangkuk',100,23,2,4,.5,2,'v'],sw:['Sawi hijau','🥬','1 mangkuk',100,15,2,2,0,2,'v']
};
const METH={
 g:[['Rebus/kukus','Tanpa minyak',0,0,'ok','Cara paling hemat kalori'],['Tumis/panggang','+1 sdt minyak',45,5,'batas','Pakai sedikit minyak dan tambah sayur'],['Goreng','+2 sdm minyak',160,18,'no','Menyerap banyak minyak, kalori naik tajam']],
 v:[['Rebus/kukus','Tanpa minyak',0,0,'ok','Rebus singkat agar vitamin tidak hilang'],['Tumis','+1 sdt minyak',45,5,'batas','Pakai teflon agar minyak sedikit'],['Lodeh santan','+100 ml santan',130,13,'no','Santan tinggi lemak jenuh']],
 f:[['Segar dipotong','Tanpa tambahan',0,0,'ok','Serat utuh, paling mengenyangkan'],['Jus tanpa gula','+air, es',5,0,'batas','Serat berkurang bila disaring'],['Jus/es dengan gula','+gula, susu kental manis',120,2,'no','Gula tambahan tinggi']],
 n:[['Mentah/sangrai kering','Tanpa garam',0,0,'ok','Takar satu genggam kecil'],['Campur yoghurt/oatmeal','+yoghurt plain',63,2,'batas','Hitung kalori campurannya'],['Berlapis gula/cokelat','+gula, cokelat',90,5,'no','Gula tambahan dan lemak jenuh']],
 d:[['Langsung dimakan/diminum','Tanpa gula',0,0,'ok','Pilih plain atau rendah lemak'],['Campur buah','+buah potong',60,0,'batas','Hitung kalori buahnya'],['Rasa manis/kental manis','+gula, SKM',120,2,'no','Gula tambahan tinggi']],
 o:[['Dressing salad','Perasan jeruk, cuka',0,0,'ok','Takar dengan sendok teh'],['Tumis ringan','Pengganti minyak lain',0,0,'batas','Jangan ditambah minyak lain'],['Deep frying','Minimal 2 sdm',200,22,'no','Sangat padat kalori']]
};
const ALL=Object.assign({},FOOD,EXTRA);
const meths=F=>Array.isArray(F[9])?F[9]:METH[F[9]];
const TH={p:5,c:15,l:3,fi:2.5}; /* ambang kandungan per 100 g */
const TIP=['Kaya serat dan protein untuk menahan lapar sampai makan siang. Aman untuk penderita asam lambung.','Isi setengah piringmu dengan sayur supaya kenyang lebih lama.','Pilih camilan utuh dan hindari minuman manis.','Beri jeda 2–3 jam sebelum tidur agar pencernaan nyaman.'];
/* menu harian: [makanan, indeks metode, porsi dasar]; indeks MEALS sama dengan dashboard */
const MEALS=[
 {k:'Sarapan',e:'🍳',share:.22,d:[['ot',0,1],['tl',0,1],['pp',0,1]]},
 {k:'Makan Siang',e:'🍛',share:.34,d:[['nm',0,1.5],['ay',0,1],['by',1,1],['tp',0,1]]},
 {k:'Snack',e:'🍎',share:.10,d:[['al',0,1],['pp',0,1]]},
 {k:'Makan Malam',e:'🐟',share:.34,d:[['ik',0,1],['nm',0,1.5],['br',1,1],['av',0,.5]]}
];
const ORDER=[0,1,3,2];

/* ===== HITUNG ===== */
const mealKcal=i=>Math.round(S.target*MEALS[i].share);
const qs=q=>{const w=Math.floor(q),h=q-w>=.5;return h?(w?w+'½':'½'):String(w)};
function needs(){
  const T=S.target,L=S.last||{},p=Math.round(1.2*Math.min(S.bb,S.goal)),l=Math.round(T*.25/9),c=Math.max(0,Math.round((T-p*4-l*9)/4)),fi=L.g==='L'?30:25;
  return{p:{n:'Protein',e:'💪',g:p,kc:p*4,w:'1,2 g × berat target; menjaga otot saat defisit'},
   c:{n:'Karbohidrat',e:'🌾',g:c,kc:c*4,w:'Sisa energi; pilih karbohidrat kompleks'},
   l:{n:'Lemak',e:'🥑',g:l,kc:l*9,w:'±25% energi; utamakan lemak tak jenuh'},
   fi:{n:'Serat',e:'🥬',g:fi,kc:0,w:'Bikin kenyang lebih lama, pencernaan lancar'}};
}
const itemOf=(id,mj,q)=>{const F=FOOD[id],M=F[9][mj];return{F,M,q,g:Math.round(F[3]*q),kcal:Math.round((F[4]+M[2])*q),p:F[5]*q,c:F[6]*q,l:(F[7]+M[3])*q,fi:F[8]*q}};
function mealItems(i){
  const m=MEALS[i],bud=mealKcal(i),base=m.d.reduce((s,x)=>s+(FOOD[x[0]][4]+FOOD[x[0]][9][x[1]][2])*x[2],0),f=Math.min(3,Math.max(.5,bud/base));
  return m.d.map(x=>itemOf(x[0],x[1],Math.max(.5,Math.round(x[2]*f*2)/2)));
}
function eatenTot(){const t={kcal:0,p:0,c:0,l:0,fi:0};
  MEALS.forEach((_,i)=>{const ck=S.chk[i]||[];mealItems(i).forEach((x,j)=>{if(ck[j])for(const k in t)t[k]+=x[k]})});return t}
function sync(i){const ck=S.chk[i]||[];let k=0,n=0;
  mealItems(i).forEach((x,j)=>{if(ck[j]){k+=x.kcal;n++}});
  S.mk[i]=k;S.eaten[i]=n>0;if(n)S.checks[1]=true}

/* ===== TAMPILAN ===== */
let selN='p',selId='tl',qry='',mi=0;
const gh=(n,t,s)=>`<div class="gh"><i>${n}</i><div><b>${t}</b><small>${s}</small></div></div>`;
function drawTop(){
  const L=S.last;
  $('#gzTop').innerHTML=`<div class="gz-h"><div><span class="gz-k">Target energi harian</span><div class="gz-big"><b id="gzT" data-v="0">0</b><small>kkal</small></div></div>
    <div class="gz-tag">${L?`<span class="gz-badge">Dari hasil deteksimu</span><p>Semua angka di bawah dihitung dari analisis terakhir. <a href="deteksi_obesitas.php">Deteksi ulang</a></p>`:`<span class="gz-badge warn">Belum ada analisis</span><p>Angka masih bawaan. <a href="deteksi_obesitas.php">Lakukan deteksi obesitas</a> agar gizi sesuai kondisimu.</p>`}</div></div>
    <div class="gz-m"><div><b>${L?dec(L.bmi,1):'—'}</b><span>BMI</span></div><div><b>${L?L.cat:'—'}</b><span>Kategori</span></div><div><b>${L?L.risk:'—'}</b><span>Risiko kesehatan</span></div></div>`;
  countTo($('#gzT'),S.target);
}
function draw1(){
  const N=needs(),T=S.target,L=S.last,pen=(L&&L.pen)||'',adj=[];
  const air=Math.min(3500,Math.max(2000,Math.round(S.bb*30/100)*100));
  if(L){adj.push(`Kategori ${L.cat}: ${L.cat==='Kurus'?'tambah asupan bergizi':L.cat==='Normal'?'jaga keseimbangan gizi':'defisit kalori, target '+fmt(T)+' kkal'}`);
    if(L.risk!=='Rendah')adj.push(`Risiko ${L.risk}: batasi lemak jenuh dan gorengan`)}
  if(/lambung|maag|gerd/.test(pen))adj.push('Asam lambung: porsi kecil tapi sering, hindari pedas, asam, kopi, gorengan');
  if(/hipertensi|darah tinggi/.test(pen))adj.push('Hipertensi: garam di bawah 5 g/hari');
  if(/diabetes|gula/.test(pen))adj.push('Gula darah: pilih karbohidrat kompleks, tanpa gula tambahan');
  if(/kolesterol/.test(pen))adj.push('Kolesterol: kurangi santan, jeroan, gorengan');
  $('#c1').innerHTML=gh(1,'Gizi yang dibutuhkan','Pilih gizi untuk melihat sumber makanannya')+
   Object.keys(N).map(k=>`<button class="nut ${k===selN?'on':''}" data-n="${k}"><span class="ne">${N[k].e}</span><span class="nb"><b>${N[k].n}</b><small>${N[k].w}</small><em>${ORDER.map(i=>MEALS[i].k.replace('Makan ','')+' '+Math.round(N[k].g*MEALS[i].share)).join(' · ')} g</em></span><span class="nv"><b>${N[k].g} g</b><small>${k==='fi'?'per hari':Math.round(N[k].kc/T*100)+'% energi'}</small></span></button>`).join('')+
   `<div class="lim"><span>💧 Air ±${dec(air/1000,1)} L (${Math.round(air/250)} gelas)</span><span>🍬 Gula tambahan ≤ 50 g</span><span>🧂 Garam ≤ 5 g (1 sdt)</span></div>`+
   (adj.length?`<div class="adj"><b>Penyesuaian dari analisismu</b><ul>${adj.map(a=>`<li>${a}</li>`).join('')}</ul></div>`:'');
}
function rowF(id,N){
  const F=ALL[id],v=F[VK[selN]],jt=Math.max(.5,Math.round(N.g*.25/v*2)/2),nl=N.n.toLowerCase();
  return `<button class="fd ${id===selId?'on':''}" data-f="${id}"><span class="ne">${F[1]}</span><span class="nb"><b>${F[0]}</b><small>${F[2]} (${F[3]} g): ${dec(v,1)} g ${nl} · ${F[4]} kkal</small><em>Jatah harian: ${qs(jt)} porsi ≈ ${Math.round(jt*F[3])} g</em></span><span class="nv"><b>${Math.round(v/N.g*100)}%</b><small>kebutuhan/porsi</small></span></button>`;
}
function list2(){
  const N=needs()[selN],nl=N.n.toLowerCase(),q=qry.trim().toLowerCase(),k=VK[selN],main=SRC[selN],hit=id=>!q||ALL[id][0].toLowerCase().includes(q);
  const m=main.filter(hit),o=Object.keys(ALL).filter(id=>!main.includes(id)&&ALL[id][k]/ALL[id][3]*100>=TH[selN]&&hit(id)).sort((a,b)=>ALL[b][k]/ALL[b][3]-ALL[a][k]/ALL[a][3]);
  return m.map(id=>rowF(id,N)).join('')+(o.length?(q?'':`<div class="sbh">Makanan lain yang mengandung ${nl}</div>`)+o.map(id=>rowF(id,N)).join(''):'')+
   (!m.length&&!o.length?`<div class="jt">Tidak ada makanan "${esc(qry)}" dengan kandungan ${nl} yang cukup.</div>`:'');
}
function draw2(){
  const N=needs()[selN],nl=N.n.toLowerCase();
  $('#c2').innerHTML=gh(2,'Sumber makanan '+nl,'Cari dan pilih makanan yang mengandung '+nl)+
   `<div class="srch"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="M20 20l-3.5-3.5"/></svg><input id="q2" type="search" placeholder="Cari makanan lain yang mengandung ${nl}…" value="${esc(qry)}" autocomplete="off"></div>
   <div class="jt">Kebutuhanmu <b>${N.g} g</b> per hari. Variasikan 3–4 sumber; tiap jatah menutup ±25% kebutuhan.</div>
   <div class="lw"><div class="ls" id="l2">${list2()}</div></div>`;
}
function draw3(){
  const F=ALL[selId],T=S.target,lam=/lambung|maag|gerd/.test((S.last&&S.last.pen)||'');
  const st={ok:'Aman untuk defisit',batas:'Batasi',no:'Hindari saat defisit'};
  $('#c3').innerHTML=gh(3,'Pengolahan '+F[0].toLowerCase(),'Kalori per '+F[2]+' ('+F[3]+' g) setelah dimasak')+
   `<div class="lw"><div class="ls">${meths(F).map(M=>{const k=F[4]+M[2],l=F[7]+M[3];
    return `<div class="mth ${M[4]}"><div class="t"><b>${F[1]} ${M[0]}</b><span class="stt">${st[M[4]]}</span></div><p>${M[1]}. ${M[5]}.${lam&&M[4]!=='ok'?' Riwayat asam lambung: sebaiknya dihindari.':''}</p><div class="nm">${Math.round(k)} kkal · lemak ${dec(l,1)} g · ${dec(k/T*100,1)}% target harian</div></div>`}).join('')}
   <div class="jt" style="margin:12px 0 0">Anggaran per makan: ${ORDER.map(i=>MEALS[i].k+' '+mealKcal(i)).join(' · ')} kkal. Pilih pengolahan yang masih muat, dan hitung bahan tambahannya.</div></div></div>`;
}
const mealDone=i=>{const ck=S.chk[i]||[];return mealItems(i).every((_,j)=>ck[j])};
function draw4(){
  const N=needs(),t=eatenTot(),T=S.target,m=MEALS[mi],its=mealItems(mi),ck=S.chk[mi]||[],all=mealDone(mi);
  const pl={kcal:0,p:0,c:0,l:0,fi:0},gt={kcal:0,p:0,c:0,l:0,fi:0};
  its.forEach((x,j)=>{for(const k in pl){pl[k]+=x[k];if(ck[j])gt[k]+=x[k]}});
  $('#mTabs').innerHTML=ORDER.map(i=>`<button class="tab ${i===mi?'on':''} ${S.eaten[i]?'done':''}" data-i="${i}">${MEALS[i].e} ${MEALS[i].k}<small>${mealKcal(i)}</small></button>`).join('');
  $('#mCard').innerHTML=`<div class="mh"><div class="em">${m.e}</div><div><h3>${m.k}</h3><small>Anggaran ±${mealKcal(mi)} kkal · ${Math.round(m.share*100)}% dari targetmu</small></div><div class="kal">${S.mk[mi]||0} / ${mealKcal(mi)} kkal</div></div>
   ${its.map((x,j)=>`<label class="ck ${ck[j]?'on':''}"><input type="checkbox" data-m="${mi}" data-j="${j}" ${ck[j]?'checked':''}><div><b>${x.F[0]} · ${x.M[0]}</b><small>${qs(x.q)} × ${x.F[2]} ≈ ${x.g} g · P ${Math.round(x.p)} · K ${Math.round(x.c)} · L ${Math.round(x.l)} · S ${Math.round(x.fi)} g</small></div><span>${x.kcal} kkal</span></label>`).join('')}
   <div class="sbh" style="margin:14px 0 8px">Gizi menu ini (dimakan / rencana)</div>
   <div class="mm" style="grid-template-columns:repeat(4,1fr)">${[['Protein','p'],['Karbo','c'],['Lemak','l'],['Serat','fi']].map(([n,k])=>`<div><b>${Math.round(gt[k])}/${Math.round(pl[k])} g</b><span>${n}</span></div>`).join('')}</div>
   <button class="eat ${all?'on':''}" id="btAll">${all?'✓ Semua sudah dimakan':'Tandai semua sudah dimakan'}</button>
   <div class="tipb"><b>Catatan gizi:</b> ${TIP[mi]}</div>`;
  const bar=(l,v,g,bg)=>`<div class="nrow"><label><span>${l}</span><span>${Math.round(v)} / ${g} g</span></label><div class="tk"><i data-w="${Math.min(100,g?v/g*100:0)}" style="background:${bg}"></i></div></div>`;
  $('#mSum').innerHTML=`<div class="card-t" style="margin-bottom:12px">Ringkasan hari ini</div><div class="tot"><b>${fmt(t.kcal)}</b><span>dari ${fmt(T)} kkal</span></div>
   ${ORDER.map(i=>`<div class="sumrow"><label><span>${MEALS[i].e} ${MEALS[i].k} ${mealDone(i)?'✓':''}</span><span>${S.mk[i]||0} / ${mealKcal(i)} kkal</span></label><div class="tk"><i data-w="${Math.min(100,(S.mk[i]||0)/mealKcal(i)*100)}" style="background:${S.eaten[i]?'linear-gradient(90deg,#7a9a2e,#a4c23d)':'#d6dfc8'}"></i></div></div>`).join('')}
   <p class="card-s" style="margin-top:6px">${T-t.kcal>=0?`Sisa ${fmt(T-t.kcal)} kkal masih aman hari ini.`:'Sudah melewati target hari ini.'}</p>
   <div class="sbh">Gizi yang sudah masuk</div>
   ${bar('Protein',t.p,N.p.g,'linear-gradient(90deg,#12524a,#0f7a4f)')}${bar('Karbohidrat',t.c,N.c.g,'linear-gradient(90deg,#7a9a2e,#a4c23d)')}${bar('Lemak',t.l,N.l.g,'linear-gradient(90deg,#e0a526,#f5b82e)')}${bar('Serat',t.fi,N.fi.g,'linear-gradient(90deg,#2e86ab,#6bb6d6)')}`;
  requestAnimationFrame(()=>$$('#mSum .tk i').forEach(i=>i.style.width=i.dataset.w+'%'));
}
$('#c1').addEventListener('click',e=>{const b=e.target.closest('.nut');if(!b)return;selN=b.dataset.n;selId=SRC[selN][0];qry='';draw1();draw2();draw3()});
$('#c2').addEventListener('input',e=>{if(e.target.id!=='q2')return;qry=e.target.value;$('#l2').innerHTML=list2()});
$('#c2').addEventListener('click',e=>{const b=e.target.closest('.fd');if(!b)return;selId=b.dataset.f;$('#l2').innerHTML=list2();draw3()});
$('#mTabs').addEventListener('click',e=>{const b=e.target.closest('.tab');if(!b)return;mi=+b.dataset.i;draw4()});
$('#mCard').addEventListener('change',e=>{const c=e.target.closest('input[data-m]');if(!c)return;
  const i=+c.dataset.m,j=+c.dataset.j;S.chk[i]=S.chk[i]||[];S.chk[i][j]=c.checked;sync(i);save();draw4();
  if(c.checked)toast('Tercatat: '+mealItems(i)[j].F[0]+' ('+mealItems(i)[j].kcal+' kkal)')});
$('#mCard').addEventListener('click',e=>{if(!e.target.closest('#btAll'))return;
  const its=mealItems(mi),all=mealDone(mi);S.chk[mi]=its.map(()=>!all);sync(mi);save();draw4();
  if(!all)toast(MEALS[mi].k+' dicatat ('+S.mk[mi]+' kkal)')});

/* ===== INIT ===== */
load();
function tick(){const n=new Date();
  $('#clkT').textContent=n.toLocaleTimeString('id-ID',{hour:'2-digit',minute:'2-digit',second:'2-digit',hour12:false}).replace(/\./g,':');
  $('#clkD').textContent=n.toLocaleDateString('id-ID',{weekday:'long',day:'2-digit',month:'long',year:'numeric'})}
tick();setInterval(tick,1000);
$('#tbT').textContent='Gizi Planner';$('#tbS').textContent='Gizi, sumber makanan, pengolahan, dan ceklist harian';
MEALS.forEach((_,i)=>sync(i));save();
drawTop();draw1();draw2();draw3();draw4();stagger('gizi');
</script>
</body>
</html>