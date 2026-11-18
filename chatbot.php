<?php
// GEND-OFF — Konsultasi AI (halaman terpisah dari dashboard)
// Letakkan di: htdocs/gend-off/chatbot.php | aset di: assets/
session_start();
require_once __DIR__ . '/gend_core.php';

// Halaman ini hanya untuk pengguna yang sudah login
if (!isset($_SESSION['user_id'])) { header('Location: login.php'); exit; }

const CHATBOT_FEATURE_ENABLED = false;
if (!CHATBOT_FEATURE_ENABLED) {
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Konsultasi AI Segera Hadir — GEND-OFF</title>
<link rel="icon" href="assets/logo.png">
<style>
*{box-sizing:border-box}
body{min-height:100vh;margin:0;padding:24px;display:grid;place-items:center;background:#f6f8f4;color:#14302c;font-family:Arial,sans-serif}
main{width:min(100%,560px);padding:40px;border:1px solid #e6ebe0;border-radius:20px;background:#fff;box-shadow:0 12px 40px rgba(11,59,54,.08);text-align:center}
.brand{display:block;margin:0 auto 28px;width:62px;height:62px}
.icon{width:64px;height:64px;margin:0 auto 18px;display:grid;place-items:center;border-radius:20px;background:#f1f6e4;font-size:30px}
.badge{display:inline-block;padding:6px 12px;border-radius:99px;background:#f1f6e4;color:#41600e;font-size:12px;font-weight:700}
h1{margin:16px 0 10px;color:#0b3b36;font-size:28px}
p{margin:0;color:#5d716d;line-height:1.7}
.actions{display:flex;justify-content:center;gap:10px;flex-wrap:wrap;margin-top:26px}
a{padding:11px 16px;border:1px solid #e6ebe0;border-radius:10px;color:#14302c;font-size:14px;font-weight:700;text-decoration:none}
a.primary{border-color:#0b3b36;background:#0b3b36;color:#fff}
a:hover{filter:brightness(.96)}
@media(max-width:480px){main{padding:30px 22px}h1{font-size:24px}}
</style>
</head>
<body>
<main>
  <img class="brand" src="assets/logo.png" alt="Logo GEND-OFF">
  <div class="icon" aria-hidden="true">🤖</div>
  <span class="badge">Segera Hadir</span>
  <h1>Konsultasi AI sedang disiapkan</h1>
  <p>Fitur ini sedang kami kembangkan dan belum tersedia. Kami akan mengumumkannya saat siap digunakan.</p>
  <div class="actions">
    <a class="primary" href="dashboard.php">Kembali ke Dashboard</a>
    <a href="edukasi_pengguna.php">Lihat Edukasi Gizi</a>
  </div>
</main>
</body>
</html>
<?php
exit;
}

$csrf = gend_csrf();
$username = htmlspecialchars($_SESSION['username'] ?? 'Faaiz');
$inisial  = htmlspecialchars(mb_strtoupper(mb_substr($_SESSION['username'] ?? 'F', 0, 1)));
$tahun    = date('Y');
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Konsultasi AI — GEND-OFF</title>
<link rel="icon" href="assets/logo.png">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=IBM+Plex+Mono:wght@500;600&display=swap" rel="stylesheet">
<style>
:root{
  --dark:#0b3b36;--dark2:#12524a;--dark3:#06221f;--olive:#7a9a2e;--lime:#a4c23d;
  --tint:#f1f6e4;--tint2:#e6efd0;--bg:#f6f8f4;--border:#e6ebe0;--border-soft:#eef2e9;
  --text:#14302c;--muted:#5d716d;--faint:#93a29e;--red:#d8452e;--red-bg:#fceae6;
  --radius:16px;--shadow:0 1px 2px rgba(11,59,54,.05),0 1px 1px rgba(11,59,54,.03);
  --sw:248px;--mono:'IBM Plex Mono',monospace;--ease:cubic-bezier(.2,.7,.2,1)
}
*{box-sizing:border-box;margin:0;padding:0;-webkit-tap-highlight-color:transparent}
html,body{height:100%}
body{font-family:'Plus Jakarta Sans',sans-serif;color:var(--text);-webkit-font-smoothing:antialiased;line-height:1.55;overflow:hidden;
  background:radial-gradient(900px 520px at 100% -6%,rgba(164,194,61,.17),transparent 60%),radial-gradient(760px 480px at -8% 16%,rgba(18,82,74,.10),transparent 55%),var(--bg)}
a{color:inherit;text-decoration:none}
button,input{font-family:inherit;color:inherit}
button{cursor:pointer;border:0;background:none}
::-webkit-scrollbar{width:9px;height:9px}::-webkit-scrollbar-thumb{background:#cfd9c4;border-radius:8px}
:focus-visible{outline:2px solid var(--lime);outline-offset:2px}
h1{font-weight:800;color:var(--dark);line-height:1.2}

/* ===== LAYOUT: satu layar penuh ===== */
.app{display:flex;height:100vh;height:100dvh}
.sidebar{width:var(--sw);flex-shrink:0;height:100%;display:flex;flex-direction:column;z-index:80;color:#fff;overflow:hidden;position:relative;
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

.main{flex:1;min-width:0;height:100%;display:flex;flex-direction:column;overflow:hidden}
.topbar{flex-shrink:0;display:flex;align-items:center;justify-content:space-between;gap:16px;padding:14px 30px;background:rgba(255,255,255,.82);backdrop-filter:blur(14px) saturate(160%);-webkit-backdrop-filter:blur(14px) saturate(160%);border-bottom:1px solid var(--border)}
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

/* konten mengisi sisa tinggi layar; hanya .chat-box yang scroll */
.content{flex:1;min-height:0;display:flex;flex-direction:column;padding:18px 30px 22px;max-width:1100px;width:100%;margin:0 auto}
.sec-h{flex-shrink:0;margin-bottom:14px}
.sec-h h1{font-size:1.4rem;letter-spacing:-.3px}
.sec-h p{font-size:.84rem;color:var(--muted);margin-top:4px}

/* ===== CHAT ===== */
.chat{flex:1;min-height:0;display:flex;flex-direction:column;overflow:hidden;background:#fff;border:1px solid var(--border);border-radius:var(--radius);box-shadow:var(--shadow)}
.chat-h{flex-shrink:0;display:flex;align-items:center;gap:12px;padding:12px 20px;border-bottom:1px solid var(--border-soft);background:linear-gradient(135deg,var(--tint),#fff)}
.bot{position:relative;width:40px;height:40px;border-radius:50%;display:grid;place-items:center;font-size:1.25rem;background:var(--tint)}
.bot::after{content:"";position:absolute;right:0;bottom:0;width:11px;height:11px;border-radius:50%;background:#3fe08a;border:2px solid #fff}
.chat-h b{display:block;font-size:.9rem;color:var(--dark)}.chat-h small{font-size:.74rem;color:var(--faint)}
.chat-box{flex:1;min-height:0;overflow-y:auto;padding:18px 22px;display:flex;flex-direction:column;gap:12px;background:var(--bg);scroll-behavior:smooth}
.msg{max-width:78%;padding:11px 16px;font-size:.88rem;line-height:1.55;animation:pop .35s var(--ease) both}
@keyframes pop{from{opacity:0;transform:translateY(8px) scale(.96)}}
.msg.b{align-self:flex-start;background:#fff;border:1px solid var(--border);border-radius:16px 16px 16px 4px}
.msg.u{align-self:flex-end;background:linear-gradient(135deg,var(--dark),var(--dark2));color:#fff;border-radius:16px 16px 4px 16px}
.dots{display:inline-flex;gap:4px;padding:3px 0}.dots i{width:6px;height:6px;border-radius:50%;background:var(--olive);animation:bn 1s infinite}
.dots i:nth-child(2){animation-delay:.15s}.dots i:nth-child(3){animation-delay:.3s}
@keyframes bn{40%{transform:translateY(-5px)}}
.qc{flex-shrink:0;display:flex;gap:8px;flex-wrap:wrap;padding:10px 20px 0;background:#fff;border-top:1px solid var(--border-soft)}
.qc button{padding:6px 13px;border-radius:99px;font-size:.76rem;font-weight:700;border:1px solid var(--border);color:var(--dark);background:#fff;transition:.15s}
.qc button:hover{background:var(--tint);border-color:var(--lime)}
.chat-in{flex-shrink:0;display:flex;gap:10px;padding:10px 18px 14px;background:#fff}
.chat-in input{flex:1;min-width:0;padding:12px 18px;border-radius:99px;border:1.5px solid var(--border);outline:none;font-size:.88rem}
.chat-in input:focus{border-color:var(--olive);box-shadow:0 0 0 3px rgba(122,154,46,.14)}
.chat-in button{padding:0 22px;border-radius:99px;background:linear-gradient(135deg,var(--dark),var(--dark2));color:#fff;font-weight:700;font-size:.86rem;transition:.15s}
.chat-in button:hover{filter:brightness(1.15)}

@media(max-width:900px){
  .sidebar{position:fixed;left:0;top:0;transform:translateX(-100%)}.sidebar.open{transform:none;box-shadow:0 0 40px rgba(11,59,54,.2)}
  .scrim.show{opacity:1;pointer-events:auto}
  .burger{display:flex}.chip-user div,.clk small,.clk-ic{display:none}.chip-user{padding:5px}.clk{padding:8px 12px}
  .topbar{padding:10px 14px}.content{padding:12px 14px 14px}.sec-h p{display:none}.msg{max-width:90%}
}
@media(max-height:640px){.sec-h{display:none}.content{padding-top:12px}}
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
      <a href="logout.php" class="logout" onclick="return confirm('Yakin ingin keluar?')"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><path d="M16 17l5-5-5-5"/><path d="M21 12H9"/></svg> Keluar dari akun</a>
      <div class="side-ver">GEND-OFF v2.0<br>© <?= $tahun ?> Sistem Pakar Gizi</div>
    </div>
  </aside>

  <div class="main">
    <header class="topbar">
      <div class="tb-l">
        <button class="burger" id="burger" aria-label="Menu"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M4 7h16M4 12h16M4 17h16"/></svg></button>
        <div><div class="tb-title">Konsultasi AI</div><div class="tb-sub">Tanya jawab seputar diet dan gizi</div></div>
      </div>
      <div class="tb-r">
        <div class="clk"><span class="clk-ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 3"/></svg></span><div><b id="clkT">--:--:--</b><small id="clkD">-- --- ----</small></div></div>
        <div class="chip-user"><div class="av"><?= $inisial ?></div><div><b id="uName"><?= $username ?></b><small>Pengguna Aktif</small></div></div>
      </div>
    </header>

    <main class="content">
      <div class="sec-h"><h1>Konsultasi AI</h1><p>Tanyakan apa saja seputar diet, kalori, olahraga, dan gaya hidup sehat.</p></div>
      <div class="chat">
        <div class="chat-h"><div class="bot">🤖</div><div><b>Asisten Gizi GEND-OFF</b><small>● Groq AI · siap membantu</small></div></div>
        <div class="chat-box" id="chatBox"></div>
        <div class="qc" id="qc"></div>
        <form class="chat-in" id="chatF"><input type="text" id="chatI" placeholder="Ketik pertanyaanmu di sini..." autocomplete="off" maxlength="2000"><button type="submit">Kirim</button></form>
      </div>
    </main>
  </div>
</div>

<script>
const CHAT_CSRF=<?= json_encode($csrf, JSON_HEX_TAG|JSON_HEX_AMP|JSON_HEX_APOS|JSON_HEX_QUOT) ?>;
const $=s=>document.querySelector(s),$$=s=>[...document.querySelectorAll(s)];
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
 {g:'Pemeriksaan & Menu',items:[['deteksi','Deteksi Obesitas','pulse','deteksi_obesitas.php'],['gizi','Gizi Planner','salad','gizi_planner.php'],['olahraga','Olahraga','dumb','olahraga.php']]},
 {g:'Belajar',items:[['edukasi','Edukasi Pengguna','book','edukasi_pengguna.php']]},
 {g:'Laporan & Akun',items:[['ekspor','Ekspor Laporan','file','dashboard.php#ekspor'],['profil','Kelola Profil','user','profil.php']]}
];
$('#nav').innerHTML=NAV.map(n=>`<div class="nav-label">${n.g}</div>`+n.items.map(i=>`<a class="nav-item${i[0]==='chatbot'?' active':''}" href="${i[3]}"><span class="ic">${ICON[i[2]]}</span>${i[1]}</a>`).join('')).join('');
const side=$('#sidebar'),scrim=$('#scrim');
function closeSide(){side.classList.remove('open');scrim.classList.remove('show')}
$('#burger').onclick=()=>{side.classList.add('open');scrim.classList.add('show')};scrim.onclick=closeSide;

const QS=['Boleh makan gorengan?','Berapa target kalorinya?','Tips biar nggak lapar','Berapa air yang harus diminum?','Olahraga apa yang cocok?'];
const box=$('#chatBox');
const chatHistory=[];
let sending=false;
function addMsg(t,w,html){const d=document.createElement('div');d.className='msg '+w;if(html)d.innerHTML=t;else d.textContent=t;box.appendChild(d);box.scrollTop=box.scrollHeight;return d}
async function ask(q){
  q=q.trim();
  if(!q||sending)return;
  sending=true;
  const input=$('#chatI'),send=$('#chatF button');
  addMsg(q,'u');input.value='';input.disabled=true;send.disabled=true;
  if(chatHistory.length>=12)chatHistory.splice(0,2);
  chatHistory.push({role:'user',content:q});
  const typing=addMsg('<span class="dots"><i></i><i></i><i></i></span>','b',true);
  try{
    const response=await fetch('api_chatbot.php',{
      method:'POST',
      headers:{'Content-Type':'application/json','X-CSRF-Token':CHAT_CSRF},
      body:JSON.stringify({messages:chatHistory})
    });
    const data=await response.json();
    if(!response.ok||!data.ok)throw new Error(data.error||'Jawaban AI tidak dapat dimuat. Coba lagi.');
    typing.textContent=data.reply;
    chatHistory.push({role:'assistant',content:data.reply});
  }catch(error){
    typing.textContent=error.message||'Tidak dapat menghubungi AI. Coba lagi.';
    chatHistory.pop();
  }finally{
    sending=false;input.disabled=false;send.disabled=false;input.focus();box.scrollTop=box.scrollHeight;
  }
}
$('#chatF').onsubmit=e=>{e.preventDefault();ask($('#chatI').value)};
$('#qc').innerHTML=QS.map(q=>`<button type="button">${q}</button>`).join('');
$('#qc').onclick=e=>{const b=e.target.closest('button');if(b)ask(b.textContent)};

/* ===== INIT ===== */
function tick(){const n=new Date();
  $('#clkT').textContent=n.toLocaleTimeString('id-ID',{hour:'2-digit',minute:'2-digit',second:'2-digit',hour12:false}).replace(/\./g,':');
  $('#clkD').textContent=n.toLocaleDateString('id-ID',{weekday:'long',day:'2-digit',month:'long',year:'numeric'})}
tick();setInterval(tick,1000);
addMsg(`Halo ${$('#uName').textContent}! 👋 Saya asisten pakar gizimu. Ada yang ingin ditanyakan soal diet defisit kalori hari ini?`,'b');
$('#chatI').focus();
</script>
</body>
</html>