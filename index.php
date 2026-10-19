<?php
// GEND-OFF — Landing page (pengunjung belum login)
// Letakkan di: htdocs/gend-off/index.php | assets di: htdocs/gend-off/assets/
session_start();
if (isset($_SESSION['user_id'])) { header('Location: dashboard.php'); exit; }
$tahun = date('Y');
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>GEND-OFF — Gizi-On, Gendut-Off | Sistem Pakar Diet Defisit Kalori</title>
<meta name="description" content="Sistem pakar perancangan menu diet defisit kalori untuk penanganan obesitas pada dewasa muda dengan forward chaining.">
<link rel="icon" href="assets/logo.png">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Bebas+Neue&family=Instrument+Serif:ital@0;1&display=swap" rel="stylesheet">
<style>
:root{
  --dark:#0b3b36; --dark2:#12524a; --olive:#7a9a2e; --lime:#a4c23d; --soft:#f3f7ec; --line:#e2ead3;
  --text:#14302c; --muted:#5d716d; --red:#e5484d; --yellow:#f5b82e; --green:#3fa66a;
  --r:24px; --shadow:0 18px 50px rgba(11,59,54,.12);
}
*{box-sizing:border-box;margin:0;padding:0;-webkit-tap-highlight-color:transparent}
html{scroll-behavior:smooth}
body{font-family:'Plus Jakarta Sans',sans-serif;color:var(--text);background:#fff;overflow-x:hidden;line-height:1.6}
img,video{max-width:100%;display:block}
a{text-decoration:none;color:inherit}
a:focus,button:focus,summary:focus{outline:none}
a:focus-visible,button:focus-visible,summary:focus-visible,input:focus-visible{outline:2px solid var(--lime);outline-offset:3px}
.wrap{width:min(1180px,92%);margin:auto}
h1,h2,h3{font-family:'Bebas Neue',sans-serif;letter-spacing:.5px;line-height:1.02;font-weight:400}
.italic{font-style:italic;color:var(--olive)}

/* header */
header{position:fixed;top:12px;left:0;right:0;z-index:100}
.nav{display:flex;align-items:center;justify-content:space-between;gap:16px;padding:8px 12px 8px 12px;border-radius:999px;
  background:rgba(255,255,255,.94);backdrop-filter:blur(18px) saturate(150%);-webkit-backdrop-filter:blur(18px) saturate(150%);
  border:1px solid rgba(255,255,255,.9);box-shadow:0 12px 36px rgba(0,0,0,.2)}
.brand{display:flex;align-items:center;gap:12px;color:#fff}
.brand img{width:60px;height:60px;object-fit:contain;transition:.3s}
.brand:hover img{transform:scale(1.08) rotate(-4deg)}
header .brand{color:var(--dark)}
header .brand b span{color:var(--olive)}
header .brand small{color:var(--muted)}
footer .brand img{background:#fff;border-radius:16px;padding:3px;width:58px;height:58px}
.brand .bt{display:flex;flex-direction:column;line-height:1}
.brand b{font-size:1.6rem;font-weight:800;letter-spacing:.5px}
.brand b span{color:var(--lime)}
.brand small{font-size:.66rem;font-weight:600;letter-spacing:1.6px;text-transform:uppercase;color:rgba(255,255,255,.75);margin-top:5px}
.menu{display:flex;gap:4px;list-style:none}
.menu a{display:block;padding:10px 16px;border-radius:999px;font-weight:600;font-size:.92rem;color:var(--dark);transition:.25s}
.menu a:hover{background:rgba(122,154,46,.18);color:var(--olive)}
.menu a:active{background:var(--dark);color:#fff}
.nav-cta{display:flex;gap:8px;align-items:center}
.btn{display:inline-flex;align-items:center;justify-content:center;gap:8px;padding:13px 26px;border-radius:999px;font-weight:700;font-size:.95rem;border:0;cursor:pointer;font-family:inherit;transition:.3s;position:relative;overflow:hidden}
.btn-p{background:linear-gradient(135deg,var(--dark),var(--dark2));color:#fff;box-shadow:0 10px 24px rgba(11,59,54,.3)}
.btn-p:hover{transform:translateY(-3px);box-shadow:0 16px 30px rgba(11,59,54,.38)}
.btn-p::after{content:"";position:absolute;top:0;left:-80%;width:50%;height:100%;background:linear-gradient(100deg,transparent,rgba(255,255,255,.35),transparent);transform:skewX(-20deg);transition:.6s}
.btn-p:hover::after{left:130%}
.btn-l{background:linear-gradient(135deg,var(--olive),var(--lime));color:var(--dark);box-shadow:0 10px 24px rgba(0,0,0,.25)}
.btn-l:hover{transform:translateY(-3px)}
.btn-o{background:transparent;border:1.5px solid rgba(255,255,255,.55);color:#fff}
.btn-o:hover{background:rgba(255,255,255,.16);transform:translateY(-3px)}
header .btn-o{border-color:var(--dark);color:var(--dark)}
header .btn-o:hover{background:var(--dark);color:#fff}
header .btn-l{background:linear-gradient(135deg,var(--dark),var(--dark2));color:#fff;box-shadow:0 8px 20px rgba(11,59,54,.3)}
.btn-sm{padding:10px 20px;font-size:.88rem}
.burger{display:none;width:44px;height:44px;border-radius:50%;border:0;background:var(--dark);color:#fff;font-size:1.2rem;font-weight:700;cursor:pointer}

/* hero: satu layar penuh */
.hero{position:relative;height:100vh;height:100svh;min-height:600px;display:flex;flex-direction:column;color:#fff;overflow:hidden;border-radius:0 0 44px 44px;background:var(--dark)}
.hero video,.hero .poster{position:absolute;inset:0;width:100%;height:100%;object-fit:cover;z-index:0}
.hero::before{content:"";position:absolute;inset:0;z-index:1;background:linear-gradient(105deg,rgba(6,34,31,.92) 10%,rgba(11,59,54,.68) 55%,rgba(11,59,54,.35))}
.hero>.wrap{position:relative;z-index:2}
.hero-in{flex:1;display:flex;align-items:center;padding:110px 0 50px}
.hero-txt{max-width:700px}
.tag{display:inline-flex;gap:8px;align-items:center;padding:8px 16px;border-radius:999px;background:rgba(255,255,255,.14);border:1px solid rgba(255,255,255,.3);backdrop-filter:blur(10px);font-size:.82rem;font-weight:600}
.tag i{width:8px;height:8px;border-radius:50%;background:var(--lime);animation:pulse 1.6s infinite}
@keyframes pulse{0%{box-shadow:0 0 0 0 rgba(164,194,61,.7)}100%{box-shadow:0 0 0 12px transparent}}
.hero h1{font-size:clamp(3.2rem,min(9vw,14vh),6.4rem);margin:16px 0}
.hero h1 em{color:var(--lime);font-style:normal;display:block}
.tl{display:block;min-height:1.02em}
.tl.typing::after{content:"";display:inline-block;width:.06em;height:.82em;margin-left:.06em;background:var(--lime);vertical-align:-.04em;animation:blink .8s steps(1) infinite}
@keyframes blink{50%{opacity:0}}
.hero p{max-width:540px;color:rgba(255,255,255,.9);font-size:1.05rem;margin-bottom:26px}
.hero-btns{display:flex;gap:12px;flex-wrap:wrap}
.hero-stats{padding-bottom:26px}
.stat-g{display:grid;grid-template-columns:repeat(4,1fr);gap:10px;padding:18px 10px;border-radius:var(--r);background:rgba(255,255,255,.13);backdrop-filter:blur(20px);-webkit-backdrop-filter:blur(20px);border:1px solid rgba(255,255,255,.28)}
.stat{text-align:center;padding:0 8px;border-right:1px solid rgba(255,255,255,.2)}
.stat:last-child{border:0}
.stat b{font-family:'Bebas Neue';font-size:2.5rem;font-weight:400;color:var(--lime);line-height:1}
.stat span{display:block;color:rgba(255,255,255,.88);font-size:.82rem;line-height:1.3;margin-top:4px}

/* sections */
section{padding:100px 0;position:relative}
.sec-h{text-align:center;max-width:680px;margin:0 auto 50px}
.sec-h small{display:inline-block;padding:6px 16px;border-radius:999px;background:rgba(122,154,46,.16);color:var(--olive);font-weight:700;font-size:.8rem;margin-bottom:14px}
.sec-h h2{font-size:clamp(2.4rem,5vw,3.8rem);color:var(--dark)}
.sec-h p{color:var(--muted);margin-top:12px}

/* about */
.about{display:grid;grid-template-columns:1fr 1.05fr;gap:64px;align-items:center}
.ab-img{position:relative;overflow:hidden;border-radius:40px;box-shadow:0 30px 60px rgba(11,59,54,.18);cursor:pointer}
.ab-img .main{display:block;width:100%;aspect-ratio:1/1;object-fit:cover}
.ab-img::after{content:"";position:absolute;top:0;left:-70%;width:45%;height:100%;pointer-events:none;
  background:linear-gradient(100deg,transparent,rgba(255,255,255,.75),transparent);transform:skewX(-20deg);opacity:0}
.ab-img:hover::after{opacity:1;animation:flash 1.1s ease-in-out infinite}
@keyframes flash{from{left:-70%}to{left:130%}}
@keyframes bob{50%{transform:translateY(-12px)}}
.about h2{font-size:clamp(2.4rem,5vw,3.6rem);color:var(--dark);margin-bottom:14px}
.about p{color:var(--muted);margin-bottom:12px}
.mini3{display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin:22px 0 28px}
.mini3 div{padding:16px 14px;border-radius:18px;background:var(--soft);border:1px solid var(--line);transition:.3s}
.mini3 div:hover{transform:translateY(-6px);background:#fff;box-shadow:var(--shadow)}
.mini3 i{font-style:normal;font-size:1.5rem}
.mini3 b{display:block;color:var(--dark);font-size:.9rem;margin:4px 0 2px}
.mini3 span{font-size:.8rem;color:var(--muted);line-height:1.4;display:block}

/* cards */
.grid3{display:grid;grid-template-columns:repeat(3,1fr);gap:22px}
.card{padding:30px 26px;border-radius:var(--r);background:var(--soft);border:1px solid var(--line);transition:.35s;transform-style:preserve-3d}
.card:hover{transform:translateY(-10px);background:#fff;box-shadow:var(--shadow);border-color:transparent}
.ico{width:58px;height:58px;border-radius:18px;display:grid;place-items:center;font-size:1.7rem;background:linear-gradient(135deg,var(--dark),var(--dark2));margin-bottom:18px;transition:.35s}
.card:hover .ico{transform:rotate(-8deg) scale(1.1);background:linear-gradient(135deg,var(--olive),var(--lime))}
.card h3{font-size:1.7rem;color:var(--dark);margin-bottom:8px}
.card p{color:var(--muted);font-size:.93rem}

/* cara kerja */
.dark{background:linear-gradient(160deg,var(--dark),#0a2f2b);color:#fff;border-radius:44px;margin:0 2%}
.dark .sec-h h2{color:#fff}.dark .sec-h p{color:rgba(255,255,255,.75)}
.dark .sec-h small{background:rgba(255,255,255,.12);color:var(--lime)}
.steps{display:grid;grid-template-columns:repeat(4,1fr);gap:18px;counter-reset:s}
.step{padding:28px 22px;border-radius:var(--r);background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.18);transition:.3s}
.step:hover{background:rgba(255,255,255,.16);transform:translateY(-8px)}
.step::before{counter-increment:s;content:"0" counter(s);font-family:'Bebas Neue';font-size:3rem;color:var(--lime);display:block;line-height:1}
.step h3{font-size:1.5rem;margin:8px 0 6px}
.step p{font-size:.88rem;color:rgba(255,255,255,.78)}

/* BMI */
.bmi{display:grid;grid-template-columns:1fr 1.1fr;border-radius:40px;overflow:hidden;box-shadow:var(--shadow);border:1px solid var(--line);min-height:500px}
.bmi-l{position:relative;color:#fff;padding:44px;display:flex;flex-direction:column;justify-content:flex-end;background:var(--dark) url('assets/gambarkiri.png') center/cover}
.bmi-l::before{content:"";position:absolute;inset:0;background:linear-gradient(0deg,rgba(6,34,31,.95) 20%,rgba(11,59,54,.45))}
.bmi-l>*{position:relative}
.bmi-l h2{font-size:clamp(2.4rem,4.5vw,3.6rem);margin-bottom:10px}
.bmi-l h2 span{color:var(--lime)}
.bmi-l p{color:rgba(255,255,255,.85)}
.bmi-r{padding:44px;background:#fff;display:flex;flex-direction:column;justify-content:center;gap:16px}
.bmi-r h3{font-size:2rem;color:var(--dark)}
.form{display:grid;grid-template-columns:1fr 1fr;gap:14px}
.form label{font-size:.82rem;font-weight:700;color:var(--dark);display:block;margin-bottom:6px}
.form input{width:100%;padding:16px 18px;border-radius:16px;border:1.5px solid var(--line);background:var(--soft);font:inherit;font-size:1rem;outline:0;transition:.25s}
.form input:focus{border-color:var(--olive);box-shadow:0 0 0 4px rgba(122,154,46,.2);background:#fff}
.form .btn{grid-column:1/-1;padding:16px}
.res{padding:20px;border-radius:20px;background:var(--soft);border:1px dashed var(--olive);text-align:center;color:var(--muted);font-size:.92rem}
.res.on{background:#fff;border:2px solid var(--line);animation:pop .4s}
@keyframes pop{from{transform:scale(.94);opacity:0}}
.res b{font-family:'Bebas Neue';font-size:3.2rem;font-weight:400;color:var(--dark);line-height:1}
.pill{display:inline-block;padding:5px 16px;border-radius:999px;color:#fff;font-weight:700;font-size:.85rem;margin-left:8px;vertical-align:middle}
.scale{display:grid;grid-template-columns:repeat(5,1fr);gap:4px;font-size:.66rem;text-align:center;color:var(--muted);font-weight:600}
.scale i{display:block;height:8px;border-radius:99px;margin-bottom:5px}

/* faq */
.faq{max-width:800px;margin:auto;display:grid;gap:12px}
details{padding:20px 24px;border-radius:20px;background:var(--soft);border:1px solid var(--line);transition:.3s}
details:hover,details[open]{background:#fff;box-shadow:0 10px 26px rgba(11,59,54,.09)}
summary{cursor:pointer;font-weight:700;list-style:none;display:flex;justify-content:space-between;color:var(--dark)}
summary::-webkit-details-marker{display:none}
summary::after{content:"+";font-size:1.4rem;line-height:1;color:var(--olive);transition:.3s}
details[open] summary::after{transform:rotate(45deg)}
details p{margin-top:10px;color:var(--muted);font-size:.93rem}

/* CTA video */
.cta{position:relative;text-align:center;padding:90px 30px;border-radius:40px;color:#fff;overflow:hidden;background:var(--dark)}
.cta video{position:absolute;inset:0;width:100%;height:100%;object-fit:cover}
.cta::before{content:"";position:absolute;inset:0;z-index:1;background:linear-gradient(135deg,rgba(6,34,31,.88),rgba(11,59,54,.7))}
.cta>*:not(video){position:relative;z-index:2}
.cta h2{font-size:clamp(2.6rem,6vw,4.6rem)}
.cta p{opacity:.9;margin:10px auto 26px;max-width:520px}

/* footer */
footer{background:var(--dark);color:#fff;border-radius:44px 44px 0 0;padding:64px 0 24px;margin-top:40px}
.f-g{display:grid;grid-template-columns:1.6fr 1fr 1fr 1fr;gap:34px;padding-bottom:36px;border-bottom:1px solid rgba(255,255,255,.18)}
.f-g p{color:rgba(255,255,255,.75);font-size:.9rem;margin-top:14px;max-width:320px}
.f-g h4{color:var(--lime);margin-bottom:14px;font-size:1rem}
.f-g li{list-style:none;margin-bottom:9px;font-size:.92rem;color:rgba(255,255,255,.82)}
.f-g li a{transition:.2s;display:inline-block}
.f-g li a:hover{color:var(--lime);transform:translateX(5px)}
.copy{text-align:center;padding-top:22px;font-size:.84rem;color:rgba(255,255,255,.7)}

/* reveal */
.rv{opacity:0;transform:translateY(40px);transition:opacity .8s,transform .8s cubic-bezier(.2,.7,.2,1)}
.rv.in{opacity:1;transform:none}
.d1{transition-delay:.12s}.d2{transition-delay:.24s}.d3{transition-delay:.36s}

@media(max-height:760px) and (min-width:981px){
  .hero-in{padding-top:96px}.hero p{margin-bottom:18px;font-size:.98rem}.hero h1{margin:10px 0}
  .stat b{font-size:2rem}.stat-g{padding:12px 8px}.hero-stats{padding-bottom:16px}
}
@media(max-width:980px){
  .about,.bmi{grid-template-columns:1fr}
  .grid3,.steps{grid-template-columns:1fr 1fr}
  .f-g{grid-template-columns:1fr 1fr}
  .bmi-l{min-height:300px}
  .stat-g{grid-template-columns:1fr 1fr;gap:14px 0}
  .stat:nth-child(2){border:0}
  .hero{height:auto;min-height:100svh}
  .hero-in{padding:120px 0 30px}
}
@media(max-width:760px){
  .burger{display:block}
  .menu{position:absolute;top:84px;left:0;right:0;flex-direction:column;padding:12px;border-radius:24px;background:rgba(255,255,255,.98);backdrop-filter:blur(20px);box-shadow:var(--shadow);display:none}
  .menu.open{display:flex;animation:pop .3s}
  .nav-cta .btn-o{display:none}
  .brand img{width:48px;height:48px}.brand b{font-size:1.3rem}.brand small{display:none}
  .grid3,.steps,.mini3,.form{grid-template-columns:1fr}
  .bmi-l,.bmi-r{padding:28px}
  section{padding:70px 0}
  .dark,footer{border-radius:30px}
}
@media(prefers-reduced-motion:reduce){*{animation:none!important;transition:none!important}.rv{opacity:1;transform:none}}

/* =====================================================
   UPGRADE — gaya tambahan (bagian hero, BMI, CTA, footer tetap)
   ===================================================== */
:root{--cream:#fbfcf6;--ink:#0a2f2b;--serif:'Instrument Serif',Georgia,serif}
::selection{background:var(--lime);color:var(--dark)}
.italic{font-family:var(--serif);font-style:italic;font-weight:400;color:var(--olive);letter-spacing:0}
.sec-h h2 .italic,.about h2 .italic{font-size:1.08em}
body::-webkit-scrollbar{width:10px}body::-webkit-scrollbar-thumb{background:var(--olive);border-radius:9px}
#prog{position:fixed;top:0;left:0;height:4px;width:0;z-index:300;background:linear-gradient(90deg,var(--olive),var(--lime),#e6f08a);box-shadow:0 0 12px var(--lime)}
#glow{position:fixed;left:0;top:0;width:520px;height:520px;margin:-260px 0 0 -260px;border-radius:50%;pointer-events:none;z-index:0;
  background:radial-gradient(circle,rgba(164,194,61,.16),transparent 65%);transition:opacity .4s;opacity:0}
.menu a.act{background:rgba(122,154,46,.18);color:var(--olive)}
#top{position:fixed;right:22px;bottom:22px;z-index:150;width:52px;height:52px;border-radius:50%;border:0;cursor:pointer;
  background:linear-gradient(135deg,var(--dark),var(--dark2));color:var(--lime);font-size:1.3rem;font-weight:800;
  box-shadow:0 12px 28px rgba(11,59,54,.35);transform:translateY(90px);opacity:0;transition:.4s}
#top.show{transform:none;opacity:1}#top:hover{transform:translateY(-5px) scale(1.06)}
section,footer,.hero{position:relative;z-index:1}

/* stats */
.stats{padding:90px 0 40px}
.st-g{display:grid;grid-template-columns:repeat(4,1fr);gap:18px}
.st{position:relative;padding:30px 24px;border-radius:30px;background:#fff;border:1px solid var(--line);overflow:hidden;transition:.4s}
.st::before{content:"";position:absolute;right:-40px;top:-40px;width:130px;height:130px;border-radius:50%;background:radial-gradient(circle,rgba(164,194,61,.35),transparent 70%);transition:.5s}
.st:hover{transform:translateY(-8px);box-shadow:var(--shadow)}.st:hover::before{transform:scale(2.2)}
.st b{font-family:'Bebas Neue';font-weight:400;font-size:4.2rem;line-height:1;color:var(--dark);display:block}
.st b sup{font-size:1.8rem;color:var(--olive);vertical-align:top;margin-left:2px}
.st span{display:block;font-weight:700;color:var(--dark);margin-top:4px}
.st small{display:block;color:var(--muted);font-size:.83rem;margin-top:4px;line-height:1.45}

/* about (teks) */
.about-l{display:grid;gap:10px;margin:18px 0 26px}
.about-l li{list-style:none;display:flex;gap:12px;align-items:flex-start;color:var(--text);font-size:.95rem;font-weight:500}
.about-l li::before{content:"✓";flex:none;width:26px;height:26px;border-radius:50%;display:grid;place-items:center;background:linear-gradient(135deg,var(--olive),var(--lime));color:#fff;font-weight:800;font-size:.8rem;margin-top:1px}
.about-btns{display:flex;gap:12px;flex-wrap:wrap;align-items:center}
.lnk{font-weight:700;color:var(--dark);border-bottom:2px solid var(--lime);padding-bottom:2px;transition:.25s}
.lnk:hover{color:var(--olive);letter-spacing:.3px}

/* bento fitur */
.bento{display:grid;grid-template-columns:repeat(6,1fr);grid-auto-rows:minmax(210px,auto);gap:20px}
.b{position:relative;padding:30px;border-radius:32px;background:var(--cream);border:1px solid var(--line);overflow:hidden;transition:opacity .8s,transform .5s cubic-bezier(.2,.7,.2,1),box-shadow .4s,border-color .4s;isolation:isolate}
.b::before{content:"";position:absolute;inset:0;z-index:-1;opacity:0;transition:.4s;background:radial-gradient(420px circle at var(--mx,50%) var(--my,50%),rgba(164,194,61,.28),transparent 60%)}
.b:hover{transform:translateY(-8px);box-shadow:var(--shadow);border-color:var(--lime)}.b:hover::before{opacity:1}
.b .ico{margin-bottom:16px}.b:hover .ico{transform:rotate(-8deg) scale(1.1);background:linear-gradient(135deg,var(--olive),var(--lime))}
.b h3{font-size:1.9rem;color:var(--dark);margin-bottom:8px}.b p{color:var(--muted);font-size:.93rem;max-width:44ch}
.b1{grid-column:span 3;grid-row:span 2;background:linear-gradient(160deg,var(--dark),#0a2f2b);color:#fff;border-color:transparent}
.b1 h3{color:#fff}.b1 p{color:rgba(255,255,255,.78)}
.b2{grid-column:span 3}.b3{grid-column:span 3}.b4{grid-column:span 2}.b5{grid-column:span 2}.b6{grid-column:span 2}
.ring{display:flex;gap:22px;align-items:center;margin-top:22px;padding:20px;border-radius:24px;background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.16)}
.ring svg{width:130px;height:130px;flex:none;transform:rotate(-90deg)}
.ring .tr{fill:none;stroke:rgba(255,255,255,.14);stroke-width:11}
.ring .pg{fill:none;stroke:var(--lime);stroke-width:11;stroke-linecap:round;stroke-dasharray:276.5;stroke-dashoffset:276.5;transition:stroke-dashoffset 1.8s cubic-bezier(.2,.7,.2,1) .3s}
.b1.in .pg{stroke-dashoffset:83}
.ring-t b{font-family:'Bebas Neue';font-weight:400;font-size:2.6rem;color:var(--lime);line-height:1;display:block}
.ring-t span{font-size:.82rem;color:rgba(255,255,255,.75);display:block}
.mini-bars{margin-top:14px;display:grid;gap:8px}
.mini-bars div{display:flex;align-items:center;gap:10px;font-size:.76rem;color:rgba(255,255,255,.8)}
.mini-bars i{display:block;height:7px;border-radius:9px;background:linear-gradient(90deg,var(--olive),var(--lime));width:0;transition:width 1.4s cubic-bezier(.2,.7,.2,1) .6s}
.b1.in .mini-bars i{width:var(--w)}
.chips{display:flex;gap:6px;flex-wrap:wrap;margin-top:16px}
.chip{padding:6px 12px;border-radius:99px;font-size:.74rem;font-weight:700;color:#fff}
.chat{margin-top:16px;display:grid;gap:8px;font-size:.8rem}
.chat div{padding:9px 14px;border-radius:16px 16px 16px 4px;background:#fff;border:1px solid var(--line);color:var(--text);max-width:88%}
.chat div:nth-child(2){justify-self:end;border-radius:16px 16px 4px 16px;background:var(--dark);color:#fff;border:0}
.typingdots{display:inline-flex;gap:3px}.typingdots i{width:5px;height:5px;border-radius:50%;background:var(--olive);animation:bounce 1s infinite}
.typingdots i:nth-child(2){animation-delay:.15s}.typingdots i:nth-child(3){animation-delay:.3s}
@keyframes bounce{40%{transform:translateY(-5px)}}

/* forward chaining demo */
.fcsec{padding:90px 0}
.fc{display:grid;grid-template-columns:.85fr 1.15fr;gap:26px;align-items:stretch}
.fc-in{padding:32px;border-radius:34px;background:var(--soft);border:1px solid var(--line)}
.fc-in h3{font-size:2rem;color:var(--dark);margin-bottom:4px}
.fc-in>p{color:var(--muted);font-size:.9rem;margin-bottom:16px}
.pfs{display:grid;gap:10px}
.pf{display:flex;align-items:center;gap:14px;text-align:left;padding:14px 16px;border-radius:20px;border:1.5px solid var(--line);background:#fff;cursor:pointer;font:inherit;transition:.3s}
.pf:hover{transform:translateX(6px);border-color:var(--olive)}
.pf.on{background:var(--dark);color:#fff;border-color:var(--dark);box-shadow:0 14px 28px rgba(11,59,54,.25)}
.pf .av{width:46px;height:46px;border-radius:50%;display:grid;place-items:center;font-size:1.4rem;background:var(--soft);flex:none}
.pf.on .av{background:rgba(255,255,255,.15)}
.pf b{display:block;font-size:.95rem}.pf small{opacity:.7;font-size:.78rem}
.fc-run{margin-top:16px;width:100%}
.fc-out{padding:32px;border-radius:34px;background:linear-gradient(160deg,var(--dark),#0a2f2b);color:#fff;position:relative;overflow:hidden}
.fc-out::after{content:"";position:absolute;right:-90px;bottom:-90px;width:260px;height:260px;border-radius:50%;background:radial-gradient(circle,rgba(164,194,61,.3),transparent 70%);animation:bob 6s ease-in-out infinite}
.chain{display:grid;gap:0;position:relative;z-index:1}
.nd{display:flex;gap:16px;position:relative;padding-bottom:18px;opacity:.15;transform:translateX(24px);transition:.6s cubic-bezier(.2,.7,.2,1)}
.nd.on{opacity:1;transform:none}
.nd::before{content:"";position:absolute;left:21px;top:44px;bottom:-2px;width:2px;background:linear-gradient(var(--lime),rgba(164,194,61,.15))}
.nd:last-child::before{display:none}
.nd .dot{flex:none;width:44px;height:44px;border-radius:50%;display:grid;place-items:center;font-family:'Bebas Neue';font-size:1.2rem;background:rgba(255,255,255,.1);border:1.5px solid rgba(255,255,255,.3);color:var(--lime);transition:.5s}
.nd.on .dot{background:var(--lime);color:var(--dark);border-color:var(--lime);box-shadow:0 0 0 6px rgba(164,194,61,.2)}
.nd .bx{flex:1;padding:12px 16px;border-radius:18px;background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.14)}
.nd small{font-size:.68rem;font-weight:800;letter-spacing:1.4px;color:var(--lime);text-transform:uppercase}
.nd p{font-size:.9rem;color:rgba(255,255,255,.92);margin-top:2px}
.nd p b{color:var(--lime)}
.nd.fin .bx{background:linear-gradient(135deg,var(--olive),var(--lime));border:0}
.nd.fin small,.nd.fin p,.nd.fin p b{color:var(--dark)}

/* contoh menu */
.mnsec{padding:40px 0 90px}
.mn{display:grid;grid-template-columns:.9fr 1.1fr;gap:26px;align-items:stretch}
.mn-tabs{display:flex;gap:8px;flex-wrap:wrap;margin-bottom:18px}
.tab{padding:10px 20px;border-radius:99px;border:1.5px solid var(--line);background:#fff;font:inherit;font-weight:700;font-size:.88rem;cursor:pointer;color:var(--dark);transition:.3s}
.tab:hover{border-color:var(--olive);transform:translateY(-2px)}
.tab.on{background:var(--dark);color:#fff;border-color:var(--dark)}
.mcard{position:relative;border-radius:34px;padding:34px;background:var(--soft);border:1px solid var(--line);min-height:100%;overflow:hidden}
.mcard .em{position:absolute;right:20px;top:6px;font-size:7.5rem;opacity:.16;line-height:1;transition:.5s}
.mcard h3{font-size:2.3rem;color:var(--dark)}
.mcard .kal{display:inline-flex;align-items:baseline;gap:6px;margin:6px 0 14px;padding:6px 16px;border-radius:99px;background:var(--dark);color:var(--lime);font-family:'Bebas Neue';font-size:1.7rem}
.mcard .kal small{font-family:'Plus Jakarta Sans';font-size:.75rem;color:#fff}
.mcard ul{list-style:none;display:grid;gap:8px;margin-bottom:18px}
.mcard li{display:flex;justify-content:space-between;gap:10px;padding:11px 14px;border-radius:14px;background:#fff;border:1px solid var(--line);font-size:.9rem;font-weight:600;color:var(--dark)}
.mcard li span{color:var(--muted);font-weight:500;white-space:nowrap}
.mcard .tip{font-size:.85rem;color:var(--muted);padding-left:14px;border-left:3px solid var(--lime)}
.mcard.sw{animation:pop .45s}
.mac{display:grid;gap:14px}
.mrow label{display:flex;justify-content:space-between;font-size:.82rem;font-weight:700;color:var(--dark);margin-bottom:5px}
.mrow .tk{height:10px;border-radius:99px;background:var(--line);overflow:hidden}
.mrow .tk i{display:block;height:100%;border-radius:99px;width:0;transition:width 1s cubic-bezier(.2,.7,.2,1)}
.mn-r{display:flex;flex-direction:column;gap:20px}
.tot{padding:28px;border-radius:34px;background:linear-gradient(160deg,var(--dark),#0a2f2b);color:#fff;flex:1}
.tot h4{font-family:'Bebas Neue';font-weight:400;font-size:1.7rem;letter-spacing:.5px;margin-bottom:4px}
.tot>p{font-size:.84rem;color:rgba(255,255,255,.72);margin-bottom:18px}
.tot .mrow label{color:#fff}.tot .mrow .tk{background:rgba(255,255,255,.15)}
.tot-n{display:flex;align-items:baseline;gap:8px;margin-bottom:16px}
.tot-n b{font-family:'Bebas Neue';font-weight:400;font-size:3.4rem;color:var(--lime);line-height:1}
.note{font-size:.76rem;color:var(--muted);text-align:center}

/* cara kerja (upgrade) */
.dark{overflow:hidden}
.dark::before{content:"";position:absolute;left:-120px;top:-120px;width:380px;height:380px;border-radius:50%;background:radial-gradient(circle,rgba(164,194,61,.22),transparent 70%);animation:bob 7s ease-in-out infinite}
.dark .wrap{position:relative}
.steps{position:relative}
.steps::before{content:"";position:absolute;left:6%;right:6%;top:62px;height:2px;background:rgba(255,255,255,.16)}
.steps::after{content:"";position:absolute;left:6%;top:62px;height:2px;width:0;background:linear-gradient(90deg,var(--olive),var(--lime));transition:width 2.2s cubic-bezier(.2,.7,.2,1) .4s;box-shadow:0 0 10px var(--lime)}
.steps.in::after{width:88%}
.step{position:relative;z-index:1;background:rgba(255,255,255,.07);backdrop-filter:blur(6px)}
.step .si{width:46px;height:46px;border-radius:50%;display:grid;place-items:center;background:var(--lime);font-size:1.3rem;margin:-6px 0 12px;box-shadow:0 0 0 8px rgba(164,194,61,.18)}
.step:hover .si{transform:rotate(-12deg) scale(1.12)}
.step .si{transition:.3s}
.step::before{position:absolute;right:18px;top:12px;font-size:3.4rem;opacity:.22}
.dark .cta-row{display:flex;justify-content:center;margin-top:40px}

/* mitos vs fakta */
.mf{padding:90px 0 60px}
.mf-g{display:grid;grid-template-columns:repeat(3,1fr);gap:22px}
.flip{perspective:1100px;height:290px;cursor:pointer}
.fl-in{position:relative;width:100%;height:100%;transition:transform .8s cubic-bezier(.2,.7,.2,1);transform-style:preserve-3d}
.flip:hover .fl-in,.flip.fp .fl-in{transform:rotateY(180deg)}
.fl-f,.fl-b{position:absolute;inset:0;border-radius:32px;padding:30px;backface-visibility:hidden;-webkit-backface-visibility:hidden;display:flex;flex-direction:column;justify-content:space-between}
.fl-f{background:#fff;border:1.5px solid var(--line);box-shadow:var(--shadow)}
.fl-b{background:linear-gradient(145deg,var(--olive),var(--lime));color:var(--dark);transform:rotateY(180deg)}
.fl-f .lb,.fl-b .lb{display:inline-block;align-self:flex-start;padding:5px 14px;border-radius:99px;font-size:.72rem;font-weight:800;letter-spacing:1.4px}
.fl-f .lb{background:rgba(229,72,77,.12);color:var(--red)}
.fl-b .lb{background:rgba(255,255,255,.55);color:var(--dark)}
.fl-f h3{font-size:2.1rem;color:var(--dark)}
.fl-f .hint{font-size:.78rem;color:var(--muted);font-weight:600}
.fl-b p{font-size:.95rem;font-weight:600;line-height:1.55}

/* faq (upgrade) */
.faq details{counter-increment:q;position:relative;padding-left:70px}
.faq{counter-reset:q}
.faq details::before{content:"0" counter(q);position:absolute;left:22px;top:18px;font-family:'Bebas Neue';font-size:1.6rem;color:var(--lime);line-height:1.2}
.faq details[open]{border-color:var(--lime)}
.faq details p{animation:fade .4s}
@keyframes fade{from{opacity:0;transform:translateY(-6px)}}
.disc{max-width:800px;margin:24px auto 0;padding:16px 22px;border-radius:20px;background:rgba(245,184,46,.13);border:1px solid rgba(245,184,46,.45);font-size:.85rem;color:#6b5210;display:flex;gap:12px;align-items:flex-start}

@media(max-width:980px){
  .st-g{grid-template-columns:1fr 1fr}
  .bento{grid-template-columns:1fr 1fr}.b1{grid-column:span 2;grid-row:auto}.b2,.b3{grid-column:span 2}.b4,.b5,.b6{grid-column:span 1}
  .fc,.mn{grid-template-columns:1fr}
  .mf-g{grid-template-columns:1fr}
  .steps::before,.steps::after{display:none}
}
@media(max-width:760px){
  .st-g,.bento{grid-template-columns:1fr}.b1,.b2,.b3,.b4,.b5,.b6{grid-column:auto}
  .ring{flex-direction:column;text-align:center}
  .faq details{padding-left:56px}.faq details::before{left:16px}
  .mq span{font-size:1.3rem}
  #top{right:14px;bottom:14px}
}
@media(hover:none){#glow{display:none}}

/* =====================================================
   REVISI 16 OKT — marquee, tentang, bento+robot, cara kerja, piring kalori
   ===================================================== */

/* 6. label berjalan: 1 baris, pil selang-seling hijau tua / hijau muda */
.mqx{position:relative;margin:30px 0 0;padding:22px 0;overflow:hidden;z-index:2}
.mqx::before,.mqx::after{content:"";position:absolute;top:0;bottom:0;width:120px;z-index:3;pointer-events:none}
.mqx::before{left:0;background:linear-gradient(90deg,#fff,transparent)}
.mqx::after{right:0;background:linear-gradient(270deg,#fff,transparent)}
.mqtrack{display:flex;width:max-content;animation:mqL 60s linear infinite}
.mqx:hover .mqtrack{animation-play-state:paused}
@keyframes mqL{to{transform:translateX(-50%)}}
.mqtrack span{display:inline-flex;align-items:center;gap:12px;margin-right:16px;padding:10px 28px 10px 10px;border-radius:99px;
  font-family:'Plus Jakarta Sans',sans-serif;font-weight:800;font-size:.92rem;letter-spacing:.1em;text-transform:uppercase;white-space:nowrap;
  box-shadow:0 12px 26px rgba(11,59,54,.18);transition:transform .3s,box-shadow .3s}
.mqtrack span i{font-style:normal;width:40px;height:40px;border-radius:50%;display:grid;place-items:center;font-size:1.1rem}
.mqtrack span:nth-child(odd){background:linear-gradient(135deg,var(--dark),var(--dark2));color:#fff}
.mqtrack span:nth-child(odd) i{background:var(--lime)}
.mqtrack span:nth-child(even){background:linear-gradient(135deg,#a4c23d,#cfe58a);color:var(--dark)}
.mqtrack span:nth-child(even) i{background:var(--dark)}
.mqtrack span:hover{transform:translateY(-4px) scale(1.03);box-shadow:0 18px 32px rgba(11,59,54,.26)}

/* statistik: angka tidak turun baris */
.st b{display:flex;align-items:flex-start;gap:4px;white-space:nowrap}
.st b sup{margin-left:0;padding-top:.45rem;line-height:1}

/* 1. tentang: tinggi gambar mengikuti tinggi teks */
.about{align-items:stretch;gap:56px}
.about-txt{display:flex;flex-direction:column;justify-content:center}
.ab-img{position:relative;height:100%;min-height:440px}
.ab-img .main{position:absolute;inset:0;width:100%;height:100%;aspect-ratio:auto;object-fit:cover}
.ab-badge{position:absolute;left:18px;right:18px;bottom:18px;z-index:2;display:flex;align-items:center;gap:12px;padding:12px 16px;border-radius:20px;color:#fff;
  background:rgba(11,59,54,.55);backdrop-filter:blur(14px);-webkit-backdrop-filter:blur(14px);border:1px solid rgba(255,255,255,.28)}
.ab-badge>span{width:42px;height:42px;flex:none;border-radius:50%;display:grid;place-items:center;font-size:1.3rem;background:var(--lime)}
.ab-badge b{display:block;font-size:.95rem;line-height:1.2}
.ab-badge small{display:block;font-size:.76rem;opacity:.85;line-height:1.35}
.about p{margin-bottom:6px}
.about-l{margin:16px 0 18px}
.mini3{margin:16px 0 22px}
.mini3 div{padding:14px 12px}

/* 2 & 3. bento rapi + chatbot panjang dengan robot */
.bento{grid-template-columns:repeat(6,1fr);grid-auto-rows:auto;gap:22px;align-items:stretch}
.b{display:flex;flex-direction:column}
.b1{grid-column:span 3;grid-row:span 2}
.b2,.b3{grid-column:span 3}
.b4{grid-column:span 6}
.b5,.b6{grid-column:span 3}
.b2 .chips,.b3 .chips{margin-top:auto;padding-top:18px}
.wk{margin-top:16px;flex:1;display:flex;flex-direction:column;padding:16px 20px;border-radius:24px;background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.16);min-height:150px}
.wk-h{display:flex;justify-content:space-between;gap:10px;font-size:.78rem;color:rgba(255,255,255,.75);margin-bottom:10px}
.wk-h b{color:var(--lime);white-space:nowrap}
.wk-b{flex:1;min-height:90px;display:grid;grid-template-columns:repeat(7,1fr);gap:10px}
.wk-b div{display:flex;flex-direction:column;justify-content:flex-end;align-items:center;gap:6px;height:100%}
.wk-b i{display:block;width:100%;max-width:28px;height:0;border-radius:9px 9px 4px 4px;background:linear-gradient(180deg,var(--lime),var(--olive));transition:height 1.2s cubic-bezier(.2,.7,.2,1) .5s}
.b1.in .wk-b i{height:var(--h)}
.wk-b small{font-size:.66rem;color:rgba(255,255,255,.6)}

.b4{display:grid;grid-template-columns:1.05fr .95fr;gap:34px;align-items:center;padding:34px;background:linear-gradient(135deg,#fbfcf6,#f1f6e3)}
.cb-l{display:flex;flex-direction:column}
.cl{display:grid;gap:10px;margin-top:18px}
.cl .u,.cl .a{padding:11px 16px;max-width:88%;font-size:.88rem;font-weight:500;line-height:1.45}
.cl .u{justify-self:end;background:var(--dark);color:#fff;border-radius:18px 18px 4px 18px}
.cl .a{justify-self:start;background:#fff;border:1px solid var(--line);color:var(--text);border-radius:18px 18px 18px 4px}
.cin{display:flex;align-items:center;gap:10px;margin-top:16px;padding:7px 7px 7px 20px;border-radius:99px;background:#fff;border:1.5px solid var(--line);color:var(--muted);font-size:.85rem}
.cin span{flex:1}
.cin b{width:38px;height:38px;border-radius:50%;display:grid;place-items:center;background:linear-gradient(135deg,var(--olive),var(--lime));color:var(--dark);font-size:.95rem}
.rb{position:relative;min-height:340px;border-radius:30px;overflow:hidden;display:grid;place-items:center;
  background:radial-gradient(circle at 50% 42%,rgba(164,194,61,.38),transparent 62%),linear-gradient(160deg,var(--dark),#0a2f2b)}
.rb::before{content:"";position:absolute;left:50%;top:50%;width:300px;height:300px;margin:-150px 0 0 -150px;border-radius:50%;border:1.5px dashed rgba(164,194,61,.4);animation:rot 36s linear infinite}
@keyframes rot{to{transform:rotate(360deg)}}
/* ROBOT — natural: napas, kepala miring, mata ikut kursor, kedip, lambaian siku */
.rbt{position:relative;z-index:1;width:min(250px,70%);height:auto;overflow:visible;cursor:pointer;-webkit-tap-highlight-color:transparent}
.rbt .rsh{transform-box:fill-box;transform-origin:center;animation:rshd 4.6s ease-in-out infinite}
@keyframes rshd{50%{transform:scale(.84);opacity:.65}}
.rbt .rfloat{animation:flt 4.6s ease-in-out infinite}
@keyframes flt{50%{transform:translateY(-9px)}}
.rbt .torso{transform-box:fill-box;transform-origin:50% 100%;animation:breathe 4.6s ease-in-out infinite}
@keyframes breathe{50%{transform:scale(1.025,1.015)}}
.rbt .headlook{transform-origin:130px 152px;transform:translate(calc(var(--hx,0)*1px),calc(var(--hy,0)*1px)) rotate(calc(var(--hr,0)*1deg));transition:transform .45s cubic-bezier(.2,.8,.3,1)}
.rbt .head{transform-origin:130px 152px;animation:headidle 5.5s ease-in-out infinite}
@keyframes headidle{0%,100%{transform:rotate(1deg) translateY(0)}35%{transform:rotate(4deg) translateY(1px)}70%{transform:rotate(1.5deg) translateY(-1px)}}
.rbt .eyes{transform:translate(calc(var(--ex,0)*1px),calc(var(--ey,0)*1px));transition:transform .18s ease-out}
.rbt .eye{transform-box:fill-box;transform-origin:center;animation:eyeblink 5s ease-in-out infinite}
@keyframes eyeblink{0%,95%,100%{transform:scaleY(1)}97.5%{transform:scaleY(.06)}}
.rbt .ant-g{transform-origin:130px 56px;animation:antsway 3.4s ease-in-out infinite}
@keyframes antsway{0%,100%{transform:rotate(-7deg)}50%{transform:rotate(7deg)}}
.rbt .ant-b{animation:antp 1.8s ease-in-out infinite}
@keyframes antp{50%{fill:#eef9a8}}
.rbt .ant-glow{transform-box:fill-box;transform-origin:center;animation:antg 1.8s ease-in-out infinite}
@keyframes antg{0%,100%{opacity:.25;transform:scale(.8)}50%{opacity:.65;transform:scale(1.25)}}
.rbt .logo-c{transform-box:fill-box;transform-origin:center;animation:logop 2.8s ease-in-out infinite}
@keyframes logop{50%{transform:scale(1.06)}}
.rbt .mouth{transform-box:fill-box;transform-origin:50% 0;animation:talk 2.2s ease-in-out infinite}
@keyframes talk{0%,100%{transform:scaleY(1)}20%{transform:scaleY(.7)}40%{transform:scaleY(1.05)}60%{transform:scaleY(.8)}}
.rb .bubL{animation:bubin .7s cubic-bezier(.3,1.6,.5,1) both,bob 5s ease-in-out .7s infinite}
@keyframes bubin{from{transform:scale(.3);opacity:0}}
.rbt .cheek{animation:chk 5s ease-in-out infinite}
@keyframes chk{50%{opacity:.6}}
.rbt .arm-l{transform-origin:88px 174px;animation:swl 5.2s ease-in-out infinite}
@keyframes swl{0%,100%{transform:rotate(2deg)}50%{transform:rotate(-4deg)}}
.rbt .arm-r{transform-origin:172px 174px;animation:swr 2.6s ease-in-out infinite}
@keyframes swr{0%,100%{transform:rotate(-6deg)}50%{transform:rotate(5deg)}}
.rbt .fore-r{transform-origin:192px 198px;animation:wave 1.1s ease-in-out infinite}
@keyframes wave{0%,100%{transform:rotate(-22deg)}50%{transform:rotate(26deg)}}
.rbt .hand-r{transform-box:fill-box;transform-origin:center;animation:wave 1.1s ease-in-out infinite .1s}
.rbt.hop .rfloat{animation:hop .6s cubic-bezier(.3,1.6,.5,1)}
@keyframes hop{0%{transform:translateY(0)}35%{transform:translateY(-22px) scale(1.03)}100%{transform:translateY(0)}}
.rbt.hop .eye{animation:happy .6s ease-out}
@keyframes happy{40%{transform:scaleY(.45)}}
@media (prefers-reduced-motion:reduce){.rbt *,.rbt{animation:none!important;transition:none!important}}
.rb .bub{position:absolute;z-index:2;padding:8px 14px;border-radius:16px;font-size:.76rem;font-weight:700;color:var(--dark);background:rgba(255,255,255,.96);box-shadow:0 10px 24px rgba(0,0,0,.25);animation:bob 5s ease-in-out infinite}
.rb .bubL{left:16px;top:22px;border-bottom-left-radius:4px}
.rb .bubR{right:16px;bottom:26px;background:var(--lime);border-bottom-right-radius:4px;animation-delay:1.2s}

.b5,.b6{flex-direction:row;align-items:center;gap:20px;padding:28px 30px}
.b5 .ico,.b6 .ico{margin:0;flex:none;width:68px;height:68px;font-size:2rem}
.b5 h3,.b6 h3{margin-bottom:6px}

/* 4. cara kerja: garis lurus diganti border menyala per kotak */
@property --ang{syntax:'<angle>';inherits:false;initial-value:0deg}
.steps{gap:22px}
.steps::before,.steps::after{display:none!important}
.step{border:1.5px solid rgba(255,255,255,.14);isolation:isolate;transition:transform .5s,background .5s,box-shadow .5s,border-color .5s}
.step::after{content:"";position:absolute;inset:-2px;border-radius:calc(var(--r) + 2px);padding:3px;pointer-events:none;opacity:0;transition:opacity .6s;
  background:conic-gradient(from var(--ang),transparent 0deg,transparent 150deg,var(--olive) 250deg,var(--lime) 330deg,#eef7a6 360deg);
  -webkit-mask:linear-gradient(#000 0 0) content-box,linear-gradient(#000 0 0);-webkit-mask-composite:xor;
  mask:linear-gradient(#000 0 0) content-box exclude,linear-gradient(#000 0 0);mask-composite:exclude;
  animation:angspin 3.2s linear infinite}
@keyframes angspin{to{--ang:360deg}}
.step.lit,.step:hover{background:rgba(164,194,61,.13);border-color:rgba(164,194,61,.55);box-shadow:0 0 34px rgba(164,194,61,.26);transform:translateY(-8px)}
.step.lit::after,.step:hover::after{opacity:1}
.step.lit::before{opacity:.6;color:var(--lime)}
.step.lit .si{transform:scale(1.12) rotate(-8deg)}

/* 5. piring defisit kalori */
.mnx{display:grid;gap:24px}
.seg{display:grid;grid-template-columns:repeat(4,1fr);gap:6px;width:100%;max-width:680px;margin:0 auto;padding:6px;border-radius:99px;background:var(--soft);border:1px solid var(--line)}
.seg .tab{display:flex;align-items:center;justify-content:center;gap:8px;padding:13px 10px;border:0;background:transparent;border-radius:99px;white-space:nowrap}
.seg .tab:hover{background:rgba(122,154,46,.15);transform:none}
.seg .tab.on{background:linear-gradient(135deg,var(--dark),var(--dark2));color:#fff;box-shadow:0 10px 22px rgba(11,59,54,.28)}
.mnx-g{display:grid;grid-template-columns:1fr 1fr;gap:24px;align-items:stretch}
.mcard{display:flex;flex-direction:column;min-height:0;padding:30px;background:#fff;box-shadow:var(--shadow)}
.mcard .mh{display:flex;align-items:center;gap:16px;margin-bottom:20px}
.mcard .mh .em{position:static;right:auto;top:auto;flex:none;width:64px;height:64px;border-radius:20px;display:grid;place-items:center;font-size:2rem;opacity:1;
  background:linear-gradient(135deg,rgba(122,154,46,.2),rgba(164,194,61,.38))}
.mcard .mh h3{font-size:2.1rem;line-height:1}
.mcard .mh small{display:block;color:var(--muted);font-size:.8rem;font-weight:600;margin-top:4px}
.mcard .mh .kal{margin:0 0 0 auto}
.mcard ul{gap:10px;margin-bottom:18px}
.mcard li{display:block;padding:12px 16px;background:var(--cream)}
.lr{display:flex;justify-content:space-between;gap:10px}
.lb{height:6px;margin-top:8px;border-radius:99px;background:var(--line);overflow:hidden}
.lb i{display:block;height:100%;border-radius:99px;background:linear-gradient(90deg,var(--olive),var(--lime));animation:grow .9s cubic-bezier(.2,.7,.2,1)}
@keyframes grow{from{width:0}}
.mcard .tip{margin-top:auto;padding:12px 16px;border-radius:16px;border-left:4px solid var(--lime);background:rgba(164,194,61,.14);color:var(--dark);font-weight:500}
.tot{display:flex;flex-direction:column;gap:20px;padding:30px;flex:none}
.tot-h{display:flex;justify-content:space-between;align-items:center;gap:10px}
.tot-h h4{margin:0}
.badge{padding:6px 14px;border-radius:99px;background:rgba(164,194,61,.2);color:var(--lime);font-size:.7rem;font-weight:800;letter-spacing:1px;white-space:nowrap}
.tot-s{font-size:.82rem;color:rgba(255,255,255,.7);margin:-12px 0 0}
.tot-main{display:grid;grid-template-columns:auto 1fr;gap:28px;align-items:center}
.plate{position:relative;width:190px;height:190px}
.plate svg{width:100%;height:100%;transform:rotate(-90deg)}
.plate .rim{fill:rgba(255,255,255,.05);stroke:rgba(255,255,255,.18);stroke-width:1.5}
.plate .seg-c{fill:none;stroke-width:15;transition:stroke-dasharray 1.4s cubic-bezier(.2,.7,.2,1)}
.plate-c{position:absolute;inset:0;display:grid;place-content:center;text-align:center}
.plate-c b{font-family:'Bebas Neue';font-weight:400;font-size:2.9rem;line-height:1;color:var(--lime)}
.plate-c span{font-size:.74rem;color:rgba(255,255,255,.75)}
.lg{display:grid;gap:14px}
.lg div{display:flex;align-items:center;gap:10px;font-size:.92rem;color:rgba(255,255,255,.92)}
.lg i{width:12px;height:12px;border-radius:4px;flex:none}
.lg b{margin-left:auto;font-family:'Bebas Neue';font-weight:400;font-size:1.6rem;letter-spacing:.5px;color:#fff}
.dist{margin-top:auto}
.dist h5{font-size:.78rem;font-weight:700;letter-spacing:.3px;color:rgba(255,255,255,.75);margin-bottom:10px}
.dbar{display:flex;gap:4px;height:12px}
.dbar i{display:block;border-radius:99px;transition:opacity .3s}
.dbar i.dim{opacity:.28}
.dlab{display:grid;grid-template-columns:repeat(4,1fr);gap:6px;margin-top:10px}
.dlab div{padding:8px 4px;border-radius:14px;border:1px solid transparent;text-align:center;font-size:.72rem;color:rgba(255,255,255,.7);transition:.3s}
.dlab div b{display:block;font-size:1rem;color:#fff}
.dlab div.on{background:rgba(255,255,255,.1);border-color:rgba(255,255,255,.22)}

/* responsif revisi */
@media(max-width:980px){
  .ab-img{height:auto;min-height:340px}
  .bento{grid-template-columns:1fr 1fr}
  .b1,.b2,.b3,.b4{grid-column:span 2}.b1{grid-row:auto}
  .b5,.b6{grid-column:span 2}
  .b4{grid-template-columns:1fr}
  .mnx-g{grid-template-columns:1fr}
}
@media(max-width:760px){
  .bento{grid-template-columns:1fr}
  .b1,.b2,.b3,.b4,.b5,.b6{grid-column:auto}
  .b4{padding:24px}
  .seg{grid-template-columns:1fr 1fr;border-radius:28px}
  .tot-main{grid-template-columns:1fr;justify-items:center}
  .lg{width:100%}
  .mqtrack span{font-size:.78rem;padding:8px 20px 8px 8px}.mqtrack span i{width:34px;height:34px}
  .mqx::before,.mqx::after{width:40px}
  .mcard .mh h3{font-size:1.8rem}
}

/* 2. navigasi: putih bersih, sinar hijau lembut hanya di luar tepinya */
.nav{position:relative;background:#fff;-webkit-backdrop-filter:none;backdrop-filter:none;border:1px solid #fff;box-shadow:0 8px 24px rgba(11,59,54,.10)}
.nav::before{content:"";position:absolute;inset:-6px -16px;z-index:-1;border-radius:999px;pointer-events:none;
  background:linear-gradient(100deg,rgba(18,82,74,.55),rgba(164,194,61,.85) 50%,rgba(18,82,74,.55));
  filter:blur(20px);opacity:.55;animation:navglow 5s ease-in-out infinite alternate}
@keyframes navglow{from{opacity:.4}to{opacity:.65}}
</style>
</head>
<body>
<div id="prog"></div><div id="glow"></div><button id="top" aria-label="Kembali ke atas">↑</button>

<header>
  <div class="wrap">
    <nav class="nav">
      <a href="#home" class="brand"><img src="assets/logo.png" alt="Logo GEND-OFF"><span class="bt"><b>GEND<span>-OFF</span></b><small>Gizi-On, Gendut-Off</small></span></a>
      <ul class="menu" id="menu">
        <li><a href="#home">Beranda</a></li>
        <li><a href="#tentang">Tentang</a></li>
        <li><a href="#fitur">Fitur</a></li>
        <li><a href="#cara">Cara Kerja</a></li>
        <li><a href="#cek-bmi">Cek BMI</a></li>
        <li><a href="#faq">FAQ</a></li>
      </ul>
      <div class="nav-cta">
        <a href="login.php" class="btn btn-o btn-sm">Masuk</a>
        <a href="register.php" class="btn btn-l btn-sm">Daftar</a>
        <button class="burger" id="burger" aria-label="Menu">☰</button>
      </div>
    </nav>
  </div>
</header>

<!-- HERO -->
<section class="hero" id="home" style="padding:0">
  <img class="poster" src="assets/buahsayur.png" alt="">
  <video autoplay muted loop playsinline poster="assets/buahsayur.png"><source src="assets/video_index.mp4" type="video/mp4"></video>
  <div class="wrap hero-in">
    <div class="hero-txt">
      <span class="tag rv"><i></i> Sistem Pakar · Forward Chaining</span>
      <h1 class="rv d1" aria-label="GIZI-ON, GENDUT-OFF"><span class="tl" id="t1" data-text="GIZI-ON,"></span><em class="tl" id="t2" data-text="GENDUT-OFF"></em></h1>
      <p class="rv d2">Rancang menu diet defisit kalori yang aman dan personal untuk dewasa muda. Isi data tubuhmu, sistem pakar menghitung BMI, risiko, target kalori, hingga menu harianmu.</p>
      <div class="hero-btns rv d3">
        <a href="register.php" class="btn btn-l">Mulai Sekarang →</a>
        <a href="#cara" class="btn btn-o">Lihat Cara Kerja</a>
      </div>
    </div>
  </div>
</section>

<!-- MARQUEE -->
<div class="mqx" aria-hidden="true">
  <div class="mqtrack">
    <span><i>🧮</i>Forward Chaining</span><span><i>🔥</i>Defisit Kalori</span><span><i>🍽️</i>Menu Personal</span><span><i>⚖️</i>Cek BMI Gratis</span><span><i>🤖</i>Chatbot AI</span><span><i>🧑‍🎓</i>Dewasa Muda</span><span><i>🥗</i>Gizi Seimbang</span><span><i>📈</i>Pantau Progres</span>
  </div>
</div>

<!-- STATISTIK -->
<section class="stats">
  <div class="wrap st-g">
    <div class="st rv"><b><span data-count="5">0</span></b><span>Kategori status BMI</span><small>Kurus sampai Obesitas II, standar Asia-Pasifik.</small></div>
    <div class="st rv d1"><b><span data-count="6">0</span></b><span>Fitur dalam satu dashboard</span><small>Dari deteksi sampai ekspor laporan.</small></div>
    <div class="st rv d2"><b><span data-count="4">0</span></b><span>Langkah menuju target</span><small>Daftar, isi data, inferensi, ikuti rencana.</small></div>
    <div class="st rv d3"><b><span data-count="24">0</span><sup>/7</sup></b><span>Chatbot AI siaga</span><small>Tanya soal gizi kapan saja kamu mau.</small></div>
  </div>
</section>

<!-- TENTANG -->
<section id="tentang" style="padding-top:50px">
  <div class="wrap about">
    <div class="ab-img rv">
      <img class="main" src="assets/buahsayur.png" alt="Buah dan sayur sehat berbentuk hati">
      <div class="ab-badge"><span>🌿</span><div><b>Forward Chaining</b><small>Aturan pakar berbasis standar gizi Indonesia</small></div></div>
    </div>
    <div class="rv d1 about-txt">
      <div class="sec-h" style="text-align:left;margin:0 0 10px"><small>Tentang GEND-OFF</small></div>
      <h2>Sistem pakar untuk <span class="italic">hidup lebih sehat</span></h2>
      <p><b>GEND-OFF</b> adalah sistem pakar perancang menu diet defisit kalori untuk penanganan obesitas pada dewasa muda. Data tubuh dan gaya hidupmu dinalar dengan <b>forward chaining</b>, layaknya konsultasi dengan ahli gizi.</p>
      <ul class="about-l">
        <li>Tanpa diet ekstrem, defisit dihitung aman</li>
        <li>Kesimpulan bisa ditelusuri dari aturan pakar</li>
        <li>Menu sesuai kalori, aktivitas, dan riwayat penyakit</li>
      </ul>
      <div class="mini3">
        <div><i>📏</i><b>Berbasis aturan pakar</b><span>Standar gizi Indonesia</span></div>
        <div><i>🍽️</i><b>Menu personal</b><span>Sesuai kalori &amp; aktivitas</span></div>
        <div><i>📈</i><b>Progres terpantau</b><span>Visual setiap hari</span></div>
      </div>
      <div class="about-btns"><a href="register.php" class="btn btn-p">Coba Gratis →</a><a href="#inferensi" class="lnk">Lihat simulasi pakar ↓</a></div>
    </div>
  </div>
</section>

<!-- FITUR (BENTO) -->
<section id="fitur" style="padding-top:40px">
  <div class="wrap">
    <div class="sec-h rv"><small>Fitur Unggulan</small><h2>Semua yang kamu butuhkan <span class="italic">dalam satu tempat</span></h2><p>Dari deteksi obesitas sampai meal planner, lengkap di satu dashboard.</p></div>
    <div class="bento">
      <div class="b b1 rv">
        <div class="ico">📊</div><h3>Dashboard Pengguna</h3>
        <p>Skor BMI, cincin kalori, sisa kalori, grafik berat &amp; lingkar perut, target air putih, dan checklist harian.</p>
        <div class="ring">
          <svg viewBox="0 0 100 100"><circle class="tr" cx="50" cy="50" r="44"/><circle class="pg" cx="50" cy="50" r="44"/></svg>
          <div class="ring-t"><b>1.050</b><span>kkal tersisa hari ini</span>
            <div class="mini-bars">
              <div>Protein<i style="--w:70px"></i></div><div>Karbo<i style="--w:96px"></i></div><div>Lemak<i style="--w:54px"></i></div>
            </div></div>
        </div>
        <div class="wk">
          <div class="wk-h"><span>Contoh grafik berat badan 7 hari</span><b>↓ turun bertahap</b></div>
          <div class="wk-b">
            <div><i style="--h:92%"></i><small>Sen</small></div><div><i style="--h:86%"></i><small>Sel</small></div><div><i style="--h:80%"></i><small>Rab</small></div><div><i style="--h:75%"></i><small>Kam</small></div><div><i style="--h:70%"></i><small>Jum</small></div><div><i style="--h:64%"></i><small>Sab</small></div><div><i style="--h:58%"></i><small>Min</small></div>
          </div>
        </div>
      </div>
      <div class="b b2 rv d1">
        <div class="ico">🩺</div><h3>Deteksi Obesitas</h3>
        <p>Input data tubuh, dapatkan BMI, label diagnosis berwarna, indikator risiko, estimasi waktu, dan rekomendasi pakar.</p>
        <div class="chips"><span class="chip" style="background:#f5b82e;color:#0b3b36">Kurus</span><span class="chip" style="background:#3fa66a">Normal</span><span class="chip" style="background:#f5b82e;color:#0b3b36">Overweight</span><span class="chip" style="background:#e5484d">Obesitas I</span><span class="chip" style="background:#c0282d">Obesitas II</span></div>
      </div>
      <div class="b b3 rv d2">
        <div class="ico">🥗</div><h3>Meal &amp; Gizi Planner</h3>
        <p>Rencana menu harian dengan kalori, makronutrien, dan anjuran pengolahan makanan yang tepat.</p>
        <div class="chips"><span class="chip" style="background:var(--dark)">🍳 Sarapan</span><span class="chip" style="background:var(--dark)">🍛 Siang</span><span class="chip" style="background:var(--dark)">🐟 Malam</span><span class="chip" style="background:var(--olive)">🍎 Snack</span></div>
      </div>

      <div class="b b4 rv">
        <div class="cb-l">
          <div class="ico">🤖</div><h3>Chatbot AI</h3>
          <p>Tanya seputar diet, gizi, dan olahraga kapan saja. Asisten siap menjawab 24/7.</p>
          <div class="cl">
            <div class="u">Boleh makan malam pakai nasi?</div>
            <div class="a">Boleh! Atur porsinya sekitar 80–100 g dan perbanyak sayur ya 🥦</div>
            <div class="a"><span class="typingdots"><i></i><i></i><i></i></span></div>
          </div>
          <div class="cin"><span>Tanya seputar diet &amp; gizi…</span><b>➤</b></div>
        </div>
        <div class="rb">
          <span class="bub bubL">Halo! Mau tanya apa? 👋</span>
          <svg class="rbt" id="rbt" viewBox="0 0 260 300" role="img" aria-label="Maskot robot chatbot GEND-OFF">
            <defs>
              <linearGradient id="rbW" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#ffffff"/><stop offset=".65" stop-color="#f1f6e4"/><stop offset="1" stop-color="#d9e4bd"/></linearGradient>
              <linearGradient id="rbV" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#14554d"/><stop offset="1" stop-color="#072a26"/></linearGradient>
              <linearGradient id="rbG" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#c3de5a"/><stop offset="1" stop-color="#7a9a2e"/></linearGradient>
              <radialGradient id="rbB" cx=".35" cy=".3" r=".8"><stop offset="0" stop-color="#f6ffc0"/><stop offset=".5" stop-color="#b6d94a"/><stop offset="1" stop-color="#7a9a2e"/></radialGradient>
              <radialGradient id="rbE" cx=".4" cy=".35" r=".75"><stop offset="0" stop-color="#e2f58a"/><stop offset="1" stop-color="#9fc232"/></radialGradient>
              <filter id="rbBl" x="-50%" y="-50%" width="200%" height="200%"><feGaussianBlur stdDeviation="3.5"/></filter>
              <filter id="rbBs" x="-50%" y="-50%" width="200%" height="200%"><feGaussianBlur stdDeviation="2"/></filter>
            </defs>
            <ellipse class="rsh" cx="130" cy="287" rx="62" ry="8" fill="rgba(0,0,0,.34)"/>
            <g class="rfloat">
              <!-- kaki -->
              <rect x="101" y="238" width="14" height="14" rx="6" fill="#b9c7a0"/>
              <rect x="145" y="238" width="14" height="14" rx="6" fill="#b9c7a0"/>
              <path d="M90 258c0-9 8-14 18-14s18 5 18 14c0 6-4 9-9 9H99c-5 0-9-3-9-9z" fill="url(#rbG)"/>
              <path d="M134 258c0-9 8-14 18-14s18 5 18 14c0 6-4 9-9 9h-18c-5 0-9-3-9-9z" fill="url(#rbG)"/>
              <ellipse cx="104" cy="250" rx="7" ry="3" fill="#fff" opacity=".35"/>
              <ellipse cx="148" cy="250" rx="7" ry="3" fill="#fff" opacity=".35"/>

              <!-- lengan kiri (ayun pelan) -->
              <g class="arm-l">
                <path d="M88 174 L70 204 L66 230" fill="none" stroke="#c7d4ad" stroke-width="22" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M88 174 L70 204 L66 230" fill="none" stroke="#f3f7e6" stroke-width="18" stroke-linecap="round" stroke-linejoin="round"/>
                <circle cx="70" cy="204" r="6" fill="#b9c7a0" opacity=".7"/>
                <circle cx="66" cy="233" r="12" fill="url(#rbG)"/>
                <ellipse cx="62" cy="229" rx="4" ry="3" fill="#fff" opacity=".4"/>
              </g>

              <!-- badan -->
              <g class="torso">
                <path d="M84 176c0-14 10-24 24-24h44c14 0 24 10 24 24v40c0 22-14 36-34 36h-24c-20 0-34-14-34-36z" fill="url(#rbW)" stroke="#cbd7b0" stroke-width="1.5"/>
                <ellipse cx="108" cy="170" rx="14" ry="6" fill="#fff" opacity=".7"/>
                <rect x="100" y="173" width="60" height="52" rx="18" fill="#fff" stroke="#cbd7b0" stroke-width="1.5"/>
                <image class="logo-c" href="assets/logo.png" x="104" y="176" width="52" height="46" preserveAspectRatio="xMidYMid meet"/>
                <rect x="104" y="232" width="52" height="7" rx="3.5" fill="url(#rbG)"/>
                <circle cx="116" cy="164" r="2.6" fill="#f5b82e"/><circle cx="130" cy="164" r="2.6" fill="#a4c23d"/><circle cx="144" cy="164" r="2.6" fill="#e5484d" opacity=".85"/>
              </g>

              <!-- lengan kanan (melambai dari siku) -->
              <g class="arm-r">
                <path d="M172 174 L192 198" fill="none" stroke="#c7d4ad" stroke-width="22" stroke-linecap="round"/>
                <path d="M172 174 L192 198" fill="none" stroke="#f3f7e6" stroke-width="18" stroke-linecap="round"/>
                <g class="fore-r">
                  <path d="M192 198 L202 154" fill="none" stroke="#c7d4ad" stroke-width="22" stroke-linecap="round"/>
                  <path d="M192 198 L202 154" fill="none" stroke="#f3f7e6" stroke-width="18" stroke-linecap="round"/>
                  <circle cx="203" cy="145" r="12.5" fill="url(#rbG)"/>
                  <ellipse cx="199" cy="141" rx="4" ry="3" fill="#fff" opacity=".4"/>
                </g>
                <circle cx="192" cy="198" r="5" fill="#b9c7a0" opacity=".7"/>
              </g>

              <!-- leher -->
              <rect x="116" y="146" width="28" height="14" rx="6" fill="#aebb95"/>

              <!-- kepala (ikut kursor + gerak idle) -->
              <g class="headlook" id="rbLook"><g class="head">
                <g class="ant-g">
                  <line x1="130" y1="58" x2="130" y2="30" stroke="#b9d062" stroke-width="5" stroke-linecap="round"/>
                  <circle class="ant-glow" cx="130" cy="21" r="14" fill="#c8e45a" filter="url(#rbBl)"/>
                  <circle class="ant-b" cx="130" cy="21" r="9" fill="url(#rbB)"/>
                </g>
                <rect x="45" y="86" width="22" height="42" rx="11" fill="url(#rbG)"/>
                <rect x="193" y="86" width="22" height="42" rx="11" fill="url(#rbG)"/>
                <rect x="50" y="95" width="9" height="24" rx="4.5" fill="#0b3b36" opacity=".25"/>
                <rect x="201" y="95" width="9" height="24" rx="4.5" fill="#0b3b36" opacity=".25"/>
                <rect x="62" y="50" width="136" height="104" rx="46" fill="url(#rbW)" stroke="#cbd7b0" stroke-width="1.5"/>
                <path d="M84 62c14-8 36-10 56-8" stroke="#fff" stroke-width="5" stroke-linecap="round" fill="none" opacity=".85"/>
                <rect x="76" y="64" width="108" height="76" rx="34" fill="url(#rbV)"/>
                <path d="M92 74c12-6 30-8 46-6-18 2-34 8-46 20z" fill="#fff" opacity=".14"/>
                <g class="eyes">
                  <g class="eye"><ellipse cx="106" cy="97" rx="10" ry="13" fill="url(#rbE)"/><circle cx="109.5" cy="92" r="3.6" fill="#fff" opacity=".95"/></g>
                  <g class="eye"><ellipse cx="154" cy="97" rx="10" ry="13" fill="url(#rbE)"/><circle cx="157.5" cy="92" r="3.6" fill="#fff" opacity=".95"/></g>
                  <g class="mouth"><path d="M113 114 Q130 138 147 114 Q130 119 113 114z" fill="#a4c23d" stroke="#a4c23d" stroke-width="3" stroke-linejoin="round"/><path d="M122 124 Q130 130 138 124 Q130 135 122 124z" fill="#f5b82e" opacity=".9"/></g>
                </g>
                <circle class="cheek" cx="90" cy="118" r="7" fill="#f5b82e" opacity=".4" filter="url(#rbBs)"/>
                <circle class="cheek" cx="170" cy="118" r="7" fill="#f5b82e" opacity=".4" filter="url(#rbBs)"/>
              </g></g>
            </g>
          </svg>
          <span class="bub bubR">Kalori hari ini 1.050 kkal</span>
        </div>
      </div>

      <div class="b b5 rv d1"><div class="ico">📚</div><div><h3>Edukasi Gizi</h3><p>Artikel dan tips tentang obesitas, defisit kalori, dan pola makan seimbang.</p></div></div>
      <div class="b b6 rv d2"><div class="ico">📄</div><div><h3>Ekspor Laporan</h3><p>Unduh laporan kesehatan dan riwayat progres untuk dibagikan atau diarsipkan.</p></div></div>
    </div>
  </div>
</section>

<!-- SIMULASI FORWARD CHAINING -->
<section class="fcsec" id="inferensi">
  <div class="wrap">
    <div class="sec-h rv"><small>Simulasi Sistem Pakar</small><h2>Lihat <span class="italic">forward chaining</span> bekerja</h2><p>Pilih satu profil contoh, lalu saksikan fakta berubah menjadi kesimpulan langkah demi langkah.</p></div>
    <div class="fc">
      <div class="fc-in rv">
        <h3>Pilih profil contoh</h3><p>Data ini hanya ilustrasi untuk demo.</p>
        <div class="pfs" id="pfs"></div>
        <button class="btn btn-p fc-run" id="fcrun">▶ Jalankan inferensi</button>
      </div>
      <div class="fc-out rv d1"><div class="chain" id="chain"></div></div>
    </div>
  </div>
</section>

<!-- CONTOH MENU -->
<section class="mnsec" id="menu-contoh">
  <div class="wrap">
    <div class="sec-h rv"><small>Contoh Menu Harian</small><h2>Seperti inilah <span class="italic">piring defisit kalori</span></h2><p>Klik tiap waktu makan untuk melihat contoh menu, kalori, dan tips pengolahannya.</p></div>
    <div class="mnx">
      <div class="mn-tabs seg rv" id="tabs"></div>
      <div class="mnx-g">
        <div class="mcard rv" id="mcard"></div>
        <div class="tot rv d1">
          <div class="tot-h"><h4>Total contoh sehari</h4><span class="badge">DEFISIT KALORI</span></div>
          <p class="tot-s">Perbandingan makronutrien (estimasi ilustrasi)</p>
          <div class="tot-main"><div class="plate" id="plate"></div><div class="lg" id="lg"></div></div>
          <div class="dist"><h5>Pembagian kalori per waktu makan</h5><div class="dbar" id="dbar"></div><div class="dlab" id="dlab"></div></div>
        </div>
      </div>
      <p class="note">*Contoh ilustrasi. Menu asli di GEND-OFF disesuaikan dengan hasil diagnosismu.</p>
    </div>
  </div>
</section>

<!-- CARA KERJA -->
<section class="dark" id="cara">
  <div class="wrap">
    <div class="sec-h rv"><small>Cara Kerja</small><h2>Empat langkah menuju <span class="italic" style="color:var(--lime)">tubuh ideal</span></h2><p>Forward chaining memproses fakta dari datamu menjadi kesimpulan dan rekomendasi.</p></div>
    <div class="steps rv" id="stepsw">
      <div class="step"><div class="si">📝</div><h3>Daftar &amp; Masuk</h3><p>Buat akun gratis dan lengkapi profilmu.</p></div>
      <div class="step"><div class="si">⚖️</div><h3>Isi Data Tubuh</h3><p>Jenis kelamin, usia, TB, BB, lingkar perut, target BB, aktivitas, dan riwayat penyakit.</p></div>
      <div class="step"><div class="si">🧠</div><h3>Inferensi Pakar</h3><p>Fakta dicocokkan dengan aturan untuk menentukan diagnosis dan target kalori.</p></div>
      <div class="step"><div class="si">🎯</div><h3>Ikuti Rencana</h3><p>Dapatkan menu, olahraga, dan pantau progres di dashboard.</p></div>
    </div>
    <div class="cta-row rv"><a href="register.php" class="btn btn-l">Mulai Langkah Pertama →</a></div>
  </div>
</section>

<!-- MITOS VS FAKTA -->
<section class="mf">
  <div class="wrap">
    <div class="sec-h rv"><small>Mitos vs Fakta</small><h2>Jangan percaya <span class="italic">semua kata orang</span></h2><p>Arahkan kursor (atau ketuk di HP) untuk membalik kartu.</p></div>
    <div class="mf-g">
      <div class="flip rv"><div class="fl-in"><div class="fl-f"><span class="lb">MITOS</span><h3>Skip makan biar cepat kurus</h3><span class="hint">Balik kartu ↻</span></div><div class="fl-b"><span class="lb">FAKTA</span><p>Melewatkan makan sering memicu lapar berlebih dan makan balas dendam. Defisit moderat yang terukur lebih aman dan lebih bisa dipertahankan.</p><span class="hint">GEND-OFF menghitungnya untukmu</span></div></div></div>
      <div class="flip rv d1"><div class="fl-in"><div class="fl-f"><span class="lb">MITOS</span><h3>Karbohidrat itu musuh diet</h3><span class="hint">Balik kartu ↻</span></div><div class="fl-b"><span class="lb">FAKTA</span><p>Tubuh tetap butuh karbohidrat sebagai energi. Yang perlu diatur adalah jenis dan porsinya, misalnya pilih karbohidrat kompleks seperti nasi merah atau ubi.</p><span class="hint">Porsi lebih penting daripada larangan</span></div></div></div>
      <div class="flip rv d2"><div class="fl-in"><div class="fl-f"><span class="lb">MITOS</span><h3>Keringat banyak = lemak terbakar</h3><span class="hint">Balik kartu ↻</span></div><div class="fl-b"><span class="lb">FAKTA</span><p>Keringat adalah cara tubuh mengatur suhu, bukan ukuran lemak yang terbakar. Pantau progres lewat berat, lingkar perut, dan konsistensi.</p><span class="hint">Lihat grafik di dashboard</span></div></div></div>
    </div>
  </div>
</section>

<!-- CEK BMI -->
<section id="cek-bmi">
  <div class="wrap">
    <div class="bmi rv">
      <div class="bmi-l">
        <h2>Cek BMI <span>cepat</span></h2>
        <p>Hitung BMI-mu dalam hitungan detik. Untuk diagnosis lengkap, rekomendasi menu, dan estimasi waktu mencapai target, daftar dulu ya.</p>
      </div>
      <div class="bmi-r">
        <h3>Masukkan datamu</h3>
        <form class="form" onsubmit="return false">
          <div><label for="tb">Tinggi badan (cm)</label><input type="number" id="tb" placeholder="mis. 165" min="100" max="250"></div>
          <div><label for="bb">Berat badan (kg)</label><input type="number" id="bb" placeholder="mis. 70" min="20" max="300"></div>
          <button class="btn btn-p" id="hit">Hitung BMI</button>
        </form>
        <div class="res" id="res">Hasil BMI-mu akan tampil di sini.</div>
        <div class="scale">
          <div><i style="background:#f5b82e"></i>Kurus<br>&lt;18,5</div>
          <div><i style="background:#3fa66a"></i>Normal<br>18,5–22,9</div>
          <div><i style="background:#f5b82e"></i>Overweight<br>23–24,9</div>
          <div><i style="background:#e5484d"></i>Obesitas I<br>25–29,9</div>
          <div><i style="background:#c0282d"></i>Obesitas II<br>≥30</div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- FAQ -->
<section id="faq" style="padding-top:20px">
  <div class="wrap">
    <div class="sec-h rv"><small>FAQ</small><h2>Pertanyaan <span class="italic">umum</span></h2></div>
    <div class="faq">
      <details class="rv"><summary>Apa itu forward chaining?</summary><p>Metode penalaran yang berangkat dari fakta (data tubuh dan gaya hidupmu), lalu mencocokkannya dengan aturan pakar hingga menghasilkan kesimpulan, misalnya kategori obesitas dan rekomendasi menu.</p></details>
      <details class="rv"><summary>Apakah GEND-OFF menggantikan dokter atau ahli gizi?</summary><p>Tidak. GEND-OFF adalah alat bantu informasi. Untuk kondisi medis tertentu, tetap konsultasikan dengan tenaga kesehatan.</p></details>
      <details class="rv"><summary>Siapa yang cocok memakai website ini?</summary><p>Dewasa muda yang ingin mengelola berat badan secara aman lewat pola makan defisit kalori.</p></details>
      <details class="rv"><summary>Apakah gratis?</summary><p>Ya, kamu bisa mendaftar dan mencoba fitur-fiturnya.</p></details>
      <details class="rv"><summary>Data apa saja yang perlu aku isi?</summary><p>Jenis kelamin, usia, tinggi dan berat badan, lingkar perut, target berat badan, tingkat aktivitas, dan riwayat penyakit. Semakin lengkap, semakin akurat rekomendasinya.</p></details>
      <details class="rv"><summary>Kenapa kategori BMI memakai standar Asia-Pasifik?</summary><p>Orang Asia cenderung punya risiko metabolik pada BMI yang lebih rendah, sehingga batas overweight mulai dari 23 dan obesitas dari 25.</p></details>
    </div>
    <div class="disc rv"><span>⚠️</span><span>Hasil perhitungan bersifat edukatif. Jika kamu hamil, menyusui, atau punya penyakit tertentu, konsultasikan dulu dengan dokter atau ahli gizi sebelum memulai diet.</span></div>
  </div>
</section>

<!-- CTA -->
<section style="padding:20px 0 40px">
  <div class="wrap">
    <div class="cta rv">
      <video autoplay muted loop playsinline><source src="assets/video_index.mp4" type="video/mp4"></video>
      <h2>Siap mulai gizi-on?</h2>
      <p>Bergabung sekarang dan susun menu diet defisit kalori pertamamu hari ini.</p>
      <a href="register.php" class="btn btn-l">Daftar Sekarang →</a>
    </div>
  </div>
</section>

<footer>
  <div class="wrap">
    <div class="f-g">
      <div><a href="#home" class="brand"><img src="assets/logo.png" alt=""><span class="bt"><b>GEND<span>-OFF</span></b><small>Gizi-On, Gendut-Off</small></span></a><p>Sistem pakar perancangan menu diet defisit kalori untuk penanganan obesitas pada dewasa muda.</p></div>
      <div><h4>Navigasi</h4><ul><li><a href="#tentang">Tentang</a></li><li><a href="#fitur">Fitur</a></li><li><a href="#cara">Cara Kerja</a></li><li><a href="#faq">FAQ</a></li></ul></div>
      <div><h4>Akun</h4><ul><li><a href="login.php">Masuk</a></li><li><a href="register.php">Daftar</a></li></ul></div>
      <div><h4>Fitur</h4><ul><li>Deteksi Obesitas</li><li>Meal Planner</li><li>Chatbot AI</li><li>Ekspor Laporan</li></ul></div>
    </div>
    <p class="copy">© <?= $tahun ?> GEND-OFF. Informasi bersifat edukatif, bukan pengganti saran medis.</p>
  </div>
</footer>

<script>
const menu=document.getElementById('menu');
document.getElementById('burger').onclick=()=>menu.classList.toggle('open');
menu.querySelectorAll('a').forEach(a=>a.onclick=()=>menu.classList.remove('open'));

(function(){
  const a=document.getElementById('t1'),b=document.getElementById('t2');
  const T1=a.dataset.text,T2=b.dataset.text;
  if(matchMedia('(prefers-reduced-motion:reduce)').matches){a.textContent=T1;b.textContent=T2;return}
  const wait=ms=>new Promise(r=>setTimeout(r,ms));
  const type=async(el,t)=>{el.classList.add('typing');for(let i=1;i<=t.length;i++){el.textContent=t.slice(0,i);await wait(85)}};
  const erase=async(el)=>{el.classList.add('typing');let t=el.textContent;while(t.length){t=t.slice(0,-1);el.textContent=t;await wait(70)}};
  (async()=>{
    await wait(500);
    while(true){
      await type(a,T1); a.classList.remove('typing');
      await type(b,T2);
      await wait(2200);
      await erase(b); b.classList.remove('typing');
      await erase(a); a.classList.remove('typing');
      await wait(500);
    }
  })();
})();
const count=el=>{const t=+el.dataset.count,s=el.dataset.suf||'';let n=0;const st=Math.max(.2,t/40);
  const i=setInterval(()=>{n+=st;if(n>=t){n=t;clearInterval(i)}el.textContent=Math.floor(n)+s},30)};
const io=new IntersectionObserver(es=>es.forEach(e=>{if(e.isIntersecting){e.target.classList.add('in');
  e.target.querySelectorAll('[data-count]').forEach(count);io.unobserve(e.target)}}),{threshold:.12});
document.querySelectorAll('.rv').forEach(el=>io.observe(el));

document.querySelectorAll('[data-tilt]').forEach(c=>{
  c.addEventListener('mousemove',e=>{const r=c.getBoundingClientRect(),x=(e.clientX-r.left)/r.width-.5,y=(e.clientY-r.top)/r.height-.5;
    c.style.transform=`perspective(700px) rotateY(${x*8}deg) rotateX(${-y*8}deg) translateY(-6px)`});
  c.addEventListener('mouseleave',()=>c.style.transform='')});

document.getElementById('hit').onclick=()=>{
  const tb=+document.getElementById('tb').value/100,bb=+document.getElementById('bb').value,r=document.getElementById('res');
  if(!tb||!bb){r.className='res';r.textContent='Isi tinggi dan berat badan terlebih dahulu.';return}
  const b=bb/(tb*tb);let l,c;
  if(b<18.5){l='Kurus';c='#f5b82e'}else if(b<23){l='Normal';c='#3fa66a'}else if(b<25){l='Overweight';c='#f5b82e'}
  else if(b<30){l='Obesitas I';c='#e5484d'}else{l='Obesitas II';c='#c0282d'}
  r.className='res on';
  r.innerHTML=`<b>${b.toFixed(1)}</b><span class="pill" style="background:${c}">${l}</span><p style="font-size:.85rem;margin-top:6px">Klasifikasi Asia-Pasifik. <a href="register.php" style="color:#7a9a2e;font-weight:700">Daftar</a> untuk analisis lengkap.</p>`;
};

/* ===== UPGRADE JS ===== */
(function(){
  // progress bar, back-to-top, scrollspy
  const prog=document.getElementById('prog'),top=document.getElementById('top');
  const links=[...document.querySelectorAll('.menu a')];
  const secs=links.map(a=>document.querySelector(a.getAttribute('href'))).filter(Boolean);
  function onScroll(){
    const h=document.documentElement,max=h.scrollHeight-h.clientHeight;
    prog.style.width=(max>0?h.scrollTop/max*100:0)+'%';
    top.classList.toggle('show',h.scrollTop>700);
    let cur=null;secs.forEach(s=>{if(s.getBoundingClientRect().top<innerHeight*.4)cur=s.id});
    links.forEach(a=>a.classList.toggle('act',cur&&a.getAttribute('href')==='#'+cur&&cur!=='home'));
  }
  addEventListener('scroll',onScroll,{passive:true});onScroll();
  top.onclick=()=>scrollTo({top:0,behavior:'smooth'});

  // cursor glow
  const g=document.getElementById('glow');
  if(matchMedia('(hover:hover)').matches){
    addEventListener('mousemove',e=>{g.style.opacity=1;g.style.transform=`translate(${e.clientX}px,${e.clientY}px)`},{passive:true});
    document.addEventListener('mouseleave',()=>g.style.opacity=0);
  }

  // marquee duplikat
  document.querySelectorAll('.mqtrack').forEach(t=>{t.innerHTML=t.innerHTML.repeat(6)});

  // spotlight bento
  document.querySelectorAll('.b').forEach(b=>b.addEventListener('mousemove',e=>{
    const r=b.getBoundingClientRect();b.style.setProperty('--mx',(e.clientX-r.left)+'px');b.style.setProperty('--my',(e.clientY-r.top)+'px')}));

  // highlight border tiap langkah (menyala berurutan)
  const sw=document.getElementById('stepsw'),stp=[...sw.querySelectorAll('.step')];
  let sk=0,sOn=false;
  const paint=()=>stp.forEach((s,j)=>s.classList.toggle('lit',j<sk));
  new IntersectionObserver((es,o)=>es.forEach(e=>{if(e.isIntersecting&&!sOn){sOn=true;sk=1;paint();
    setInterval(()=>{sk=(sk+1)%(stp.length+2);paint()},1400);o.disconnect()}}),{threshold:.3}).observe(sw);

  // flip tap (mobile)
  document.querySelectorAll('.flip').forEach(f=>f.addEventListener('click',()=>f.classList.toggle('fp')));

  /* ---- simulasi forward chaining ---- */
  const P=[
    {n:'Tata',e:'👩',u:21,g:'P',tb:160,bb:72,a:1.375,al:'aktivitas ringan'},
    {n:'Jeno',e:'👨',u:22,g:'L',tb:172,bb:92,a:1.55,al:'aktivitas sedang'},
    {n:'Karina',e:'🧑',u:20,g:'P',tb:162,bb:54,a:1.375,al:'aktivitas ringan'}
  ];
  let sel=0,timers=[];
  const pfs=document.getElementById('pfs'),chain=document.getElementById('chain');
  function drawPf(){pfs.innerHTML=P.map((p,i)=>`<button class="pf ${i==sel?'on':''}" data-i="${i}"><span class="av">${p.e}</span><span><b>${p.n}, ${p.u} th</b><small>${p.tb} cm · ${p.bb} kg · ${p.al}</small></span></button>`).join('');
    pfs.querySelectorAll('.pf').forEach(b=>b.onclick=()=>{sel=+b.dataset.i;drawPf();run()})}
  function run(){
    timers.forEach(clearTimeout);timers=[];
    const p=P[sel],m=p.tb/100,bmi=p.bb/(m*m);
    let cat,defisit=true;
    if(bmi<18.5){cat='Kurus';defisit=false}else if(bmi<23){cat='Normal';defisit=false}else if(bmi<25)cat='Overweight';else if(bmi<30)cat='Obesitas I';else cat='Obesitas II';
    const bmr=10*p.bb+6.25*p.tb-5*p.u+(p.g=='L'?5:-161),tdee=bmr*p.a;
    const floor=p.g=='L'?1500:1200;
    const tgt=defisit?Math.max(floor,tdee-500):tdee;
    const f=n=>Math.round(n).toLocaleString('id-ID');
    const rows=[
      ['F','FAKTA',`${p.n}, ${p.u} tahun, ${p.g=='L'?'laki-laki':'perempuan'}, TB <b>${p.tb} cm</b>, BB <b>${p.bb} kg</b>, ${p.al}.`],
      ['R1','ATURAN 1 · Hitung BMI',`BMI = BB ÷ TB² = <b>${bmi.toFixed(1)}</b>`],
      ['R2','ATURAN 2 · Klasifikasi',`BMI ${bmi.toFixed(1)} masuk kategori <b>${cat}</b> (Asia-Pasifik).`],
      ['R3','ATURAN 3 · Kebutuhan energi',`BMR <b>${f(bmr)}</b> kkal × faktor aktivitas ${p.a} = TDEE <b>${f(tdee)}</b> kkal.`],
      ['R4','ATURAN 4 · Target kalori',defisit?`Kategori ${cat} → defisit 500 kkal. Target <b>${f(tgt)} kkal/hari</b>${tgt==floor?' (dibatasi batas aman minimum)':''}.`:`Kategori ${cat} → tidak perlu defisit. Jaga kalori di sekitar <b>${f(tgt)} kkal/hari</b>.`],
      ['✓','KESIMPULAN',defisit?`Rekomendasi: menu defisit kalori ${f(tgt)} kkal, estimasi turun ±0,5 kg per minggu.`:`Rekomendasi: pertahankan pola makan seimbang${cat=='Kurus'?' dan konsultasikan penambahan berat badan sehat':''}.`]
    ];
    chain.innerHTML=rows.map((r,i)=>`<div class="nd ${i==rows.length-1?'fin':''}"><div class="dot">${r[0]}</div><div class="bx"><small>${r[1]}</small><p>${r[2]}</p></div></div>`).join('');
    const nds=chain.querySelectorAll('.nd');
    nds.forEach((n,i)=>timers.push(setTimeout(()=>n.classList.add('on'),250+i*650)));
  }
  drawPf();run();
  document.getElementById('fcrun').onclick=run;

  /* ---- contoh menu ---- */
  const M=[
    {k:'Sarapan',e:'🍳',kal:350,items:[['Oatmeal + pisang','200 kkal'],['2 telur rebus','140 kkal'],['Teh tawar','10 kkal']],tip:'Rebus atau kukus lebih baik daripada digoreng.',p:30,c:48,l:22},
    {k:'Makan Siang',e:'🍛',kal:520,items:[['Nasi merah 100 g','130 kkal'],['Dada ayam panggang','165 kkal'],['Tumis kangkung (1 sdt minyak)','90 kkal'],['Tempe bacem 2 potong','135 kkal']],tip:'Isi setengah piring dengan sayur supaya kenyang lebih lama.',p:32,c:45,l:23},
    {k:'Makan Malam',e:'🐟',kal:430,items:[['Pepes ikan kembung','180 kkal'],['Sayur bening bayam','50 kkal'],['Nasi 80 g','100 kkal'],['Pepaya 1 potong','100 kkal']],tip:'Beri jeda 2–3 jam sebelum tidur.',p:34,c:42,l:24},
    {k:'Snack',e:'🍎',kal:200,items:[['Yoghurt plain 100 g','70 kkal'],['Apel sedang','80 kkal'],['Almond 8 butir','50 kkal']],tip:'Pilih snack utuh, hindari minuman manis.',p:20,c:58,l:22}
  ];
  const COL=['#a4c23d','#7fd1a0','#f5b82e','#ff9f7a'];
  const tabs=document.getElementById('tabs'),mc=document.getElementById('mcard'),plate=document.getElementById('plate');
  let ti=0;
  const tot=M.reduce((s,m)=>s+m.kal,0);
  const pr=Math.round(M.reduce((s,m)=>s+m.p*m.kal,0)/tot),ca=Math.round(M.reduce((s,m)=>s+m.c*m.kal,0)/tot),li=100-pr-ca;
  const segs=[['Karbohidrat',ca,COL[0]],['Protein',pr,COL[1]],['Lemak',li,COL[2]]];
  const CI=2*Math.PI*44;

  function drawTabs(){tabs.innerHTML=M.map((m,i)=>`<button class="tab ${i==ti?'on':''}" data-i="${i}"><span>${m.e}</span>${m.k}</button>`).join('');
    tabs.querySelectorAll('.tab').forEach(b=>b.onclick=()=>{ti=+b.dataset.i;drawTabs();drawCard();drawDist()})}
  function drawCard(){
    const m=M[ti],mx=Math.max(...m.items.map(i=>parseInt(i[1])));
    mc.className='mcard';void mc.offsetWidth;mc.className='mcard sw';
    mc.innerHTML=`<div class="mh"><span class="em">${m.e}</span><div><h3>${m.k}</h3><small>${m.items.length} menu</small></div><div class="kal">${m.kal}<small>kkal</small></div></div>`+
      `<ul>${m.items.map(i=>`<li><div class="lr"><b>${i[0]}</b><span>${i[1]}</span></div><div class="lb"><i style="width:${Math.round(parseInt(i[1])/mx*100)}%"></i></div></li>`).join('')}</ul>`+
      `<p class="tip">💡 ${m.tip}</p>`}
  function drawDist(){
    document.getElementById('dbar').innerHTML=M.map((m,i)=>`<i style="flex:${m.kal};background:${COL[i]}" class="${i==ti?'':'dim'}"></i>`).join('');
    document.getElementById('dlab').innerHTML=M.map((m,i)=>`<div class="${i==ti?'on':''}">${m.e}<b>${m.kal}</b>kkal</div>`).join('')}

  plate.innerHTML=`<svg viewBox="0 0 140 140"><circle class="rim" cx="70" cy="70" r="66"/>${segs.map(s=>`<circle class="seg-c" data-p="${s[1]}" cx="70" cy="70" r="44" stroke="${s[2]}" stroke-dasharray="0 ${CI}"/>`).join('')}</svg><div class="plate-c"><b id="totk">0</b><span>kkal / hari</span></div>`;
  document.getElementById('lg').innerHTML=segs.map(s=>`<div><i style="background:${s[2]}"></i><span>${s[0]}</span><b>${s[1]}%</b></div>`).join('');
  drawTabs();drawCard();drawDist();

  new IntersectionObserver((es,o)=>es.forEach(e=>{if(e.isIntersecting){
    let acc=0;plate.querySelectorAll('.seg-c').forEach(c=>{const len=CI*(+c.dataset.p/100);c.style.strokeDashoffset=-acc;c.style.strokeDasharray=`${len-4} ${CI}`;acc+=len});
    const totk=document.getElementById('totk');let n=0;const st=tot/50;
    const iv=setInterval(()=>{n+=st;if(n>=tot){n=tot;clearInterval(iv)}totk.textContent=Math.round(n).toLocaleString('id-ID')},26);
    o.disconnect()}}),{threshold:.3}).observe(plate);
})();

/* robot: mata & kepala mengikuti kursor, lirik acak saat diam, klik = loncat */
(function(){
  const svg=document.getElementById('rbt'); if(!svg) return;
  const reduce=window.matchMedia('(prefers-reduced-motion: reduce)').matches; if(reduce) return;
  const look=document.getElementById('rbLook');
  const clamp=(v,a,b)=>Math.max(a,Math.min(b,v));
  let last=0;
  function aim(nx,ny){
    svg.style.setProperty('--ex',(nx*6).toFixed(2)); svg.style.setProperty('--ey',(ny*4.5).toFixed(2));
    look.style.setProperty('--hx',(nx*4).toFixed(2)); look.style.setProperty('--hy',(ny*2.5).toFixed(2));
    look.style.setProperty('--hr',(nx*4).toFixed(2));
    svg.style.setProperty('--hx',0);
  }
  document.addEventListener('mousemove',e=>{
    const r=svg.getBoundingClientRect(); if(r.bottom<0||r.top>innerHeight) return;
    const cx=r.left+r.width/2, cy=r.top+r.height*.36;
    aim(clamp((e.clientX-cx)/(innerWidth*.35),-1,1), clamp((e.clientY-cy)/(innerHeight*.35),-1,1));
    last=Date.now();
  },{passive:true});
  document.addEventListener('mouseleave',()=>aim(0,0));
  setInterval(()=>{ if(Date.now()-last>3500){ aim((Math.random()*2-1)*.9,(Math.random()*2-1)*.5); } },2600);
  svg.addEventListener('click',()=>{svg.classList.remove('hop');void svg.getBoundingClientRect();svg.classList.add('hop');setTimeout(()=>svg.classList.remove('hop'),650)});
})();
</script>
</body>
</html>