<?php
// GEND-OFF — Deteksi Obesitas & Target Kalori (halaman terpisah dari dashboard)
// Letakkan di: htdocs/gend-off/deteksi_obesitas.php | aset di: assets/
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
<title>Deteksi Obesitas — GEND-OFF</title>
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
.cta-links{display:none;grid-template-columns:repeat(2,1fr);gap:10px;margin-top:14px}
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
        <div><div class="tb-title" id="tbT">Deteksi Obesitas</div><div class="tb-sub" id="tbS"></div></div>
      </div>
      <div class="tb-r">
        <div class="clk" id="clock"><span class="clk-ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 3"/></svg></span><div><b id="clkT">--:--:--</b><small id="clkD">-- --- ----</small></div></div>
        <div class="chip-user"><div class="av" id="uAv"><?= $inisial ?></div><div><b id="uName"><?= $username ?></b><small>Pengguna Aktif</small></div></div>
      </div>
    </header>

    <main class="content">
      <!-- 2. DETEKSI -->
      <section class="page active" id="page-deteksi">
        <div class="sec-h"><h1>Deteksi Obesitas &amp; Target Kalori</h1><p>Sistem pakar menganalisis datamu dengan forward chaining untuk menentukan diagnosis dan rekomendasi intervensi.</p></div>
        <div class="card">
          <form class="form-grid" onsubmit="return false">
            <div class="field"><label>Jenis Kelamin</label><select id="fG"><option value="L">Laki-laki</option><option value="P">Perempuan</option></select></div>
            <div class="field"><label>Usia (tahun)</label><input type="number" id="fU" placeholder="Mis. 22" min="15" max="80"></div>
            <div class="field"><label>Tinggi Badan (cm)</label><input type="number" id="fTB" placeholder="Mis. 165" min="100" max="250"></div>
            <div class="field"><label>Berat Badan (kg)</label><input type="number" step="0.1" id="fBB" placeholder="Mis. 75" min="20" max="300"></div>
            <div class="field"><label>Lingkar Perut (cm)</label>
              <div class="wmode" id="wMode"><button type="button" class="on" data-m="manual">Saya tahu (isi manual)</button><button type="button" data-m="auto">Tidak tahu (perkiraan)</button></div>
              <input type="number" step="0.1" id="fW" placeholder="Mis. 90" min="40" max="200">
              <div class="fhint" id="wHint">Ukur dengan meteran di sebatas pusar, setelah menghembuskan napas.</div>
            </div>
            <div class="field"><label>Target Berat Badan (kg)</label><input type="number" step="0.1" id="fT" placeholder="Mis. 60" min="30" max="200"></div>
            <div class="field"><label>Tingkat Aktivitas Fisik</label><select id="fA"><option value="1.2">Sedentari (jarang olahraga)</option><option value="1.375" selected>Ringan (1–3 hari/minggu)</option><option value="1.55">Sedang (3–5 hari/minggu)</option><option value="1.725">Berat (6–7 hari/minggu)</option></select></div>
            <div class="field"><label>Riwayat Penyakit (opsional)</label><input type="text" id="fP" placeholder="Mis. asam lambung, hipertensi"></div>
            <div class="field full"><button class="btn btn-p btn-w" id="btA">Analisis Sekarang →</button></div>
          </form>
        </div>
        <div class="card res" id="res"></div>
        <div class="card" id="bmiRef" style="margin-top:18px"></div>
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
 dumb:sv('<path d="M6.5 6.5v11M17.5 6.5v11M3 9v6M21 9v6M6.5 12h11"/>'),
 user:sv('<circle cx="12" cy="8" r="4"/><path d="M4 21c0-4.4 3.6-8 8-8s8 3.6 8 8"/>'),
 fire:sv('<path d="M12 22c4 0 7-3 7-7 0-3-2-5-3-7-1 2-2 3-3 3 0-4-2-7-4-9 0 4-4 6-4 12 0 4 3 8 7 8z"/>'),
 scale:sv('<rect x="3" y="4" width="18" height="16" rx="3"/><path d="M8 9a4 4 0 018 0"/><path d="M12 9l2-2"/>'),
 drop:sv('<path d="M12 2.5S5 10 5 15a7 7 0 0014 0c0-5-7-12.5-7-12.5z"/>'),
 target:sv('<circle cx="12" cy="12" r="9"/><circle cx="12" cy="12" r="5"/><circle cx="12" cy="12" r="1"/>'),
 bulb:sv('<path d="M9 18h6M10 22h4"/><path d="M12 2a7 7 0 00-4 12.7c.6.5 1 1.3 1 2.3h6c0-1 .4-1.8 1-2.3A7 7 0 0012 2z"/>'),
 dumbbell:sv('<path d="M6.5 6.5v11M17.5 6.5v11M3.5 9v6M20.5 9v6M6.5 12h11"/>'),
 close:sv('<path d="M18 6L6 18M6 6l12 12"/>')
};
/* navigasi: Deteksi = halaman ini, lainnya kembali ke dashboard.php */
const NAV=[
 {g:'Utama',items:[['dashboard','Dashboard','grid','dashboard.php']]},
 {g:'Pemeriksaan & Menu',items:[['deteksi','Deteksi Obesitas','pulse','deteksi_obesitas.php'],['gizi','Gizi Planner','salad','gizi_planner.php'],['olahraga','Olahraga','dumb','olahraga_planner.php']]},
 {g:'Konsultasi & Belajar',items:[['chatbot','Konsultasi AI','chat','dashboard.php#chatbot'],['edukasi','Edukasi Pengguna','book','dashboard.php#edukasi']]},
 {g:'Laporan & Akun',items:[['ekspor','Ekspor Laporan','file','dashboard.php#ekspor'],['profil','Kelola Profil','user','dashboard.php#profil']]}
];
$('#nav').innerHTML=NAV.map(n=>`<div class="nav-label">${n.g}</div>`+n.items.map(i=>`<a class="nav-item${i[0]==='deteksi'?' active':''}" href="${i[3]}"><span class="ic">${ICON[i[2]]}</span>${i[1]}</a>`).join('')).join('');
$$('#nav > *').forEach((e,i)=>e.style.setProperty('--i',i));
$$('[data-i]').forEach(e=>e.innerHTML=ICON[e.dataset.i]);

/* ===== STATE (kunci sama dengan dashboard agar data tersinkron) ===== */
const KEY='gendoff_dash_v3';let S;
function load(){
  let raw=null;try{raw=JSON.parse(localStorage.getItem(KEY)||'null')}catch(e){}
  const first=!raw;
  S=Object.assign({target:1600,tb:170,bb:70.8,goal:65,water:first?4:0,eaten:first?{0:true}:{},checks:first?[true,true,false,false]:[false,false,false,false],day:'',weights:null,waists:null,last:null},raw||{});
  const today=new Date().toDateString();
  if(!first&&S.day!==today){S.water=0;S.eaten={};S.checks=[false,false,false,false]}
  S.day=today;
  if(!S.weights){const v=[72.5,72.2,71.8,71.9,71.5,71.0,70.8];S.weights=v.map((w,i)=>{const d=new Date();d.setDate(d.getDate()-(6-i));return{d:d.toDateString(),w}})}
  if(!S.waists){const v=[90,89.6,89.2,88.8,88.4,88,87.6];S.waists=v.map((w,i)=>{const d=new Date();d.setDate(d.getDate()-(6-i));return{d:d.toDateString(),w}})}
  save();
}
function save(){try{localStorage.setItem(KEY,JSON.stringify(S))}catch(e){}}
const cat=b=>b<18.5?['Kurus','#fff4d6','#8a6300']:b<23?['Normal','#e5f6ec','#0f7a4f']:b<25?['Overweight','#fff4d6','#8a6300']:b<30?['Obesitas I','#fde8e6','#c2362b']:['Obesitas II','#fbd9d6','#a11f17'];
function toast(msg){const t=document.createElement('div');t.className='toast';t.textContent=msg;$('#toasts').appendChild(t);setTimeout(()=>t.classList.add('out'),2600);setTimeout(()=>t.remove(),3100)}
function countTo(el,to,d=0,ms=800){const from=parseFloat(el.dataset.v||0);el.dataset.v=to;const t0=performance.now();
  const f=t=>{const k=Math.min(1,(t-t0)/ms),e=1-Math.pow(1-k,3),v=from+(to-from)*e;el.textContent=d?dec(v,d):fmt(v);if(k<1)requestAnimationFrame(f)};requestAnimationFrame(f)}
function stagger(t){
  $$(`#page-${t} .sec-h, #page-${t} .card:not(.res)`).forEach((e,i)=>{
    e.classList.remove('rise');e.style.setProperty('--i',i);void e.offsetWidth;e.classList.add('rise');
  });
}
const side=$('#sidebar'),scrim=$('#scrim');
function closeSide(){side.classList.remove('open');scrim.classList.remove('show')}
$('#burger').onclick=()=>{side.classList.add('open');scrim.classList.add('show')};scrim.onclick=closeSide;

/* ===== TABEL ATURAN BMI (Asia-Pasifik) ===== */
const BMI_RULES=[
 ['Kurus','< 18,5',false,'Tidak gemuk/obesitas. Tidak perlu defisit; fokus menambah berat badan sehat.'],
 ['Normal (ideal)','18,5 – 22,9',false,'Tidak gemuk/obesitas. Tidak perlu defisit; jaga pola makan seimbang.'],
 ['Overweight (gemuk)','23,0 – 24,9',true,'Perlu penanganan defisit kalori ringan (±500 kkal/hari).'],
 ['Obesitas I','25,0 – 29,9',true,'Perlu penanganan defisit kalori dan olahraga rutin.'],
 ['Obesitas II','≥ 30,0',true,'Perlu penanganan defisit kalori, olahraga bertahap, dan arahan tenaga kesehatan.']
];
const bmiRuleIdx=b=>b<18.5?0:b<23?1:b<25?2:b<30?3:4;
function renderBmiRef(bmi){
  const hi=bmi==null?-1:bmiRuleIdx(bmi);
  $('#bmiRef').innerHTML=`<div class="card-h"><div><div class="card-t">Tabel Aturan BMI</div><div class="card-s">Defisit kalori hanya untuk kategori gemuk dan obesitas</div></div></div>
   <div class="tblwrap"><table class="bmitbl"><tr><th>Kategori</th><th>BMI</th><th>Penanganan defisit</th><th>Aturan</th></tr>
   ${BMI_RULES.map((r,i)=>`<tr class="${i===hi?'me':''}"><td>${r[0]}</td><td>${r[1]}</td><td>${r[2]?'Diperlukan':'Tidak diperlukan'}</td><td>${r[3]}</td></tr>`).join('')}</table></div>`;
}
renderBmiRef(null);

/* ===== LINGKAR PERUT: manual / perkiraan otomatis + validasi ===== */
let wMode='manual';
/* perkiraan kasar dari BMI & jenis kelamin (bukan hasil ukur) */
const estWaist=(g,bmi)=>Math.round((2*bmi+(g==='L'?34:28))*2)/2;
function waistRange(g,bmi,tb){
  const e=estWaist(g,bmi);
  return {e,min:Math.max(40,Math.round(e*.78)),max:Math.min(200,Math.round(e*1.22,0)),tb};
}
/* kembalikan pesan error, atau '' jika sesuai dengan TB & BB */
function waistError(g,tb,bb,wa){
  if(!tb||!bb)return 'Isi tinggi dan berat badan dulu agar lingkar perut bisa diperiksa.';
  if(!wa)return 'Isi lingkar perut atau pilih perkiraan otomatis.';
  const bmi=bb/Math.pow(tb/100,2),r=waistRange(g,bmi,tb);
  if(wa<40||wa>200)return 'Lingkar perut harus antara 40–200 cm.';
  if(wa/tb>0.9)return `Lingkar perut tidak masuk akal untuk tinggi ${tb} cm (maksimal ±${Math.round(tb*.9)} cm).`;
  if(wa<r.min||wa>r.max)return `Tidak sesuai dengan TB ${tb} cm dan BB ${dec(bb,1)} kg (BMI ${dec(bmi,1)}). Rentang wajar sekitar ${r.min}–${r.max} cm. Ukur ulang atau pakai perkiraan.`;
  return '';
}
function syncWaist(){
  const g=$('#fG').value,tb=+$('#fTB').value,bb=+$('#fBB').value,inp=$('#fW'),h=$('#wHint');
  inp.classList.remove('err');h.className='fhint';
  if(wMode==='auto'){
    inp.readOnly=true;
    if(tb&&bb){const v=estWaist(g,bb/Math.pow(tb/100,2));inp.value=v;h.textContent='Perkiraan kasar dari BMI dan jenis kelamin. Hasil ukur dengan meteran lebih akurat.'}
    else{inp.value='';inp.placeholder='Isi TB & BB dulu';h.textContent='Perkiraan dihitung otomatis setelah tinggi dan berat badan diisi.'}
  }else{
    inp.readOnly=false;inp.placeholder='Mis. 90';
    const wa=+inp.value;
    if(!wa){h.textContent='Ukur dengan meteran di sebatas pusar, setelah menghembuskan napas.'}
    else{const er=waistError(g,tb,bb,wa);if(er){inp.classList.add('err');h.classList.add('err');h.textContent=er}else{h.classList.add('ok');h.textContent='Sesuai dengan tinggi dan berat badanmu.'}}
  }
}
$('#wMode').addEventListener('click',e=>{const b=e.target.closest('button');if(!b)return;wMode=b.dataset.m;
  $$('#wMode button').forEach(x=>x.classList.toggle('on',x===b));
  if(wMode==='manual')$('#fW').value='';syncWaist()});
['fG','fTB','fBB','fW'].forEach(id=>$('#'+id).addEventListener('input',syncWaist));
$('#fG').addEventListener('change',syncWaist);

/* ===== DETEKSI (forward chaining) ===== */
$('#btA').onclick=()=>{
  syncWaist();
  const g=$('#fG').value,age=+$('#fU').value,tb=+$('#fTB').value,bb=+$('#fBB').value,wa=+$('#fW').value,goal=+$('#fT').value,af=+$('#fA').value,pen=$('#fP').value.toLowerCase();
  if(!age||!tb||!bb||!wa||!goal){toast('Lengkapi semua data wajib dulu ya');return}
  if(wMode==='manual'){const er=waistError(g,tb,bb,wa);if(er){toast('Periksa lingkar perut: '+er);$('#fW').focus();return}}
  const m=tb/100,bmi=bb/(m*m),c=cat(bmi),over=bmi>=23,estW=wMode==='auto';
  const bmr=10*bb+6.25*tb-5*age+(g==='L'?5:-161),tdee=bmr*af,floor=g==='L'?1500:1200;
  const tgt=over?Math.max(floor,tdee-500):tdee,defisit=Math.round(tdee-tgt);
  const lim=g==='L'?90:80,risk=wa>=lim?(bmi>=25?['Tinggi','#d8452e']:['Sedang','#c97a10']):(bmi>=25?['Sedang','#c97a10']:['Rendah','#0f7a4f']);
  const diff=bb-goal,wk=over&&diff>0?diff/.5:0,wkTxt=wk?`${Math.max(1,Math.ceil(wk*.9))} – ${Math.ceil(wk*1.1)} minggu`:'Tidak perlu defisit';
  const prot=Math.round(1.2*Math.min(bb,goal));
  S.tb=tb;S.bb=bb;S.goal=goal;S.target=Math.round(tgt);
  const td=new Date().toDateString(),l=S.weights[S.weights.length-1];if(l.d===td)l.w=bb;else S.weights.push({d:td,w:bb});
  const lw=S.waists[S.waists.length-1];if(lw.d===td)lw.w=wa;else S.waists.push({d:td,w:wa});
  S.last={bmi,cat:c[0],cbg:c[1],cfg:c[2],risk:risk[0],rcol:risk[1],wk:wkTxt,wa,we:estW,tgt:Math.round(tgt),d:td,g,age,af,pen};save();
  const adv=[];
  if(/lambung|maag|gerd/.test(pen))adv.push(['Asam lambung','Makan porsi kecil tapi sering, hindari pedas, asam, kopi, dan gorengan.']);
  if(/hipertensi|darah tinggi/.test(pen))adv.push(['Hipertensi','Batasi garam di bawah 5 g/hari, kurangi makanan olahan dan asin.']);
  if(/diabetes|gula/.test(pen))adv.push(['Gula darah','Pilih karbohidrat kompleks, hindari gula tambahan, atur jeda makan.']);
  if(/kolesterol/.test(pen))adv.push(['Kolesterol','Kurangi santan, jeroan, dan gorengan; perbanyak serat (oat, sayur).']);
  const rows=[
   ['F','FAKTA',`${g==='L'?'Laki-laki':'Perempuan'}, ${age} th, TB <b>${tb} cm</b>, BB <b>${bb} kg</b>, perut <b>${wa} cm</b>${estW?' (perkiraan)':''}.`],
   ['1','ATURAN 1 · BMI',`BB ÷ TB² = <b>${dec(bmi,1)}</b>`],
   ['2','ATURAN 2 · Klasifikasi',`Kategori <b>${c[0]}</b> (Asia-Pasifik).`],
   ['3','ATURAN 3 · Energi',`BMR <b>${fmt(bmr)}</b> × ${af} = TDEE <b>${fmt(tdee)}</b> kkal.`],
   ['4','ATURAN 4 · Target',over?`${c[0]} → defisit ${fmt(defisit)} kkal. Target <b>${fmt(tgt)} kkal/hari</b>${tgt===floor?' (batas aman minimum)':''}.`:`${c[0]} → tanpa defisit. Jaga di <b>${fmt(tgt)} kkal/hari</b>.`],
   ['✓','KESIMPULAN',over?`Menu defisit ${fmt(tgt)} kkal, estimasi turun ±0,5 kg/minggu.`:`Pertahankan pola makan seimbang${bmi<18.5?' dan konsultasikan penambahan berat badan sehat':''}.`]];
  const R=$('#res');
  R.innerHTML=`<div class="res-h"><div><div class="card-s" style="margin-bottom:6px;text-transform:uppercase;letter-spacing:1px;font-weight:800">Hasil diagnosis sistem pakar</div><div style="display:flex;align-items:center;gap:14px"><span class="big" id="rB" data-v="0">0</span><span class="badge" style="background:${c[1]};color:${c[2]};font-size:.9rem;padding:6px 16px">${c[0]}</span></div></div>
    <div style="text-align:right"><div class="card-s">Perkiraan waktu capai target</div><b style="font-size:1.25rem;color:var(--dark)">${wkTxt}</b></div></div>
   <div class="chips4"><div class="chip4"><b>${fmt(bmr)}</b><span>BMR kkal</span></div><div class="chip4"><b>${fmt(tdee)}</b><span>TDEE kkal</span></div><div class="chip4"><b>${fmt(tgt)}</b><span>Target kkal/hari</span></div><div class="chip4"><b>${prot} g</b><span>Protein/hari</span></div></div>
   ${over?`<div class="cta"><div class="cta-h"><div><b>Kategori ${c[0]}: penanganan defisit disarankan</b><span>Mulai program defisit kalori lewat planner gizi, olahraga, dan menu.</span></div><button type="button" class="btn btn-p" id="btDef">Mulai penanganan defisit</button></div>
     <div class="cta-links" id="defLinks"><a class="btn btn-o" href="gizi_planner.php">Gizi Planner</a><a class="btn btn-o" href="olahraga_planner.php">Olahraga Planner</a></div></div>`
   :`<div class="note-ok ${bmi<18.5?'warn':''}"><div><b>${bmi<18.5?'Kamu tergolong kurus, bukan gemuk atau obesitas':'Berat badanmu ideal, tidak gemuk atau obesitas'}</b><p>BMI ${dec(bmi,1)} belum masuk kategori gemuk/obesitas pada tabel aturan BMI, jadi tidak memerlukan penanganan khusus defisit kalori.${bmi<18.5?' Fokus pada asupan bergizi untuk menaikkan berat badan secara sehat.':' Pertahankan pola makan seimbang dan aktivitas rutin.'}</p></div></div>`}
   <div class="fc"><div class="chain" id="chn">${rows.map((r,i)=>`<div class="nd ${i===rows.length-1?'fin':''}"><div class="dot">${r[0]}</div><div class="bx"><small>${r[1]}</small>${r[2]}</div></div>`).join('')}</div>
   <ul class="rlist"><li style="border-left-color:${risk[1]}"><strong>Risiko kesehatan: <span style="color:${risk[1]}">${risk[0]}</span></strong>${wa>=lim?`Lingkar perut melewati batas aman (${lim} cm).`:'Lingkar perut masih dalam batas aman.'}</li>
    ${over?`<li><strong>Olahraga</strong>Kardio (jalan cepat/sepeda) 30 menit/hari dan latihan beban ringan 2× seminggu.</li>
    <li><strong>Makanan &amp; gizi</strong>Perbanyak serat (bayam, brokoli), protein tanpa lemak (dada ayam, tahu, tempe).</li>
    <li><strong>Pengolahan makanan</strong>Kurangi deep frying; utamakan direbus, dikukus, dipanggang, atau ditumis sedikit minyak.</li>`
    :`<li><strong>Tanpa program defisit</strong>${bmi<18.5?'Naikkan asupan bergizi (kacang, telur, ikan, susu) dan latihan beban ringan.':'Cukup jaga gizi seimbang, olahraga ringan rutin, dan pantau berat badan berkala.'}</li>`}
    ${adv.map(a=>`<li style="border-left-color:#f5b82e"><strong>${a[0]}</strong>${a[1]}</li>`).join('')}
    <li style="border-left-color:#f5b82e"><strong>Catatan</strong>Jika hamil, menyusui, atau punya penyakit tertentu, konsultasikan dulu dengan dokter atau ahli gizi.</li></ul></div>`;
  R.classList.remove('show');void R.offsetWidth;R.classList.add('show');
  countTo($('#rB'),bmi,1,900);renderBmiRef(bmi);
  const bd=$('#btDef');if(bd)bd.onclick=()=>$('#defLinks').classList.toggle('show');
  $$('#chn .nd').forEach((n,i)=>setTimeout(()=>n.classList.add('on'),250+i*500));
  setTimeout(()=>R.scrollIntoView({behavior:'smooth',block:'start'}),120);
  toast(`Target ${fmt(tgt)} kkal diterapkan ke dashboard`);
};


/* ===== INIT ===== */
load();
function tick(){const n=new Date();
  $('#clkT').textContent=n.toLocaleTimeString('id-ID',{hour:'2-digit',minute:'2-digit',second:'2-digit',hour12:false}).replace(/\./g,':');
  $('#clkD').textContent=n.toLocaleDateString('id-ID',{weekday:'long',day:'2-digit',month:'long',year:'numeric'})}
tick();setInterval(tick,1000);
$('#tbT').textContent='Deteksi Obesitas';$('#tbS').textContent='Analisis BMI, risiko, dan target kalori';
syncWaist();stagger('deteksi');
</script>
</body>
</html>