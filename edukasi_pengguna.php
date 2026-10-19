<?php
// GEND-OFF — Edukasi Pengguna (halaman terpisah dari dashboard)
// Letakkan di: htdocs/gend-off/edukasi_pengguna.php | aset di: assets/
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
<title>Edukasi Pengguna — GEND-OFF</title>
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
button{font-family:inherit;cursor:pointer;border:0;background:none;color:inherit}
::-webkit-scrollbar{width:9px;height:9px}::-webkit-scrollbar-thumb{background:#cfd9c4;border-radius:8px}
:focus-visible{outline:2px solid var(--lime);outline-offset:2px}
h1,h3{font-weight:800;color:var(--dark);line-height:1.25}

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
.sec-h h1{font-size:1.6rem;letter-spacing:-.3px}
.sec-h p{font-size:.88rem;color:var(--muted);max-width:680px;margin-top:8px}
@keyframes rise{from{opacity:0;transform:translateY(14px)}}

/* ===== KARTU ARTIKEL ===== */
.ed{display:grid;grid-template-columns:repeat(3,1fr);gap:18px}
.art{display:flex;flex-direction:column;overflow:hidden;background:linear-gradient(165deg,#fff 0%,#fbfdf8 100%);border:1px solid var(--border);border-radius:var(--radius);box-shadow:var(--shadow);
  transition:transform .25s var(--ease),box-shadow .25s,border-color .25s;animation:rise .55s var(--ease) backwards;animation-delay:calc(var(--i,0)*70ms)}
.art:hover{transform:translateY(-4px);box-shadow:0 24px 44px -20px rgba(11,59,54,.3);border-color:#cfdcbb}
.art .th{height:96px;display:grid;place-items:center;font-size:2.6rem;background:var(--c)}
.art .th span{transition:transform .4s var(--ease)}.art:hover .th span{transform:scale(1.18) rotate(-6deg)}
.art .bd{padding:18px 20px 20px;display:flex;flex-direction:column;flex:1}
.src{display:flex;align-items:center;gap:8px;flex-wrap:wrap;margin-bottom:8px}
.badge{font-size:.68rem;font-weight:800;padding:3px 10px;border-radius:99px;background:var(--tint);color:var(--dark);letter-spacing:.2px}
.badge.cnn{background:#fde8e6;color:#a11f17}
.yr{font-family:var(--mono);font-size:.7rem;color:var(--faint)}
.art h3{font-size:1rem;margin-bottom:6px}
.art p{font-size:.84rem;color:var(--muted);flex:1}
.art .rd{margin-top:14px;font-size:.8rem;font-weight:800;color:var(--olive)}
.art:hover .rd{color:var(--dark)}
.note{margin-top:22px;padding:14px 18px;border-radius:12px;background:var(--tint);border:1px solid var(--tint2);font-size:.82rem;color:var(--muted)}
.note b{color:var(--dark)}

@media(max-width:1180px){.ed{grid-template-columns:1fr 1fr}}
@media(max-width:900px){
  .sidebar{position:fixed;left:0;top:0;transform:translateX(-100%)}.sidebar.open{transform:none;box-shadow:0 0 40px rgba(11,59,54,.2)}
  .scrim.show{opacity:1;pointer-events:auto}
  .burger{display:flex}.chip-user div,.clk small,.clk-ic{display:none}.chip-user{padding:5px}.clk{padding:8px 12px}
  .topbar{padding:12px 16px}.content{padding:18px 16px 50px}.ed{grid-template-columns:1fr}.sec-h h1{font-size:1.4rem}
}
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
        <div><div class="tb-title">Edukasi Pengguna</div><div class="tb-sub">Artikel gizi dan gaya hidup sehat</div></div>
      </div>
      <div class="tb-r">
        <div class="clk"><span class="clk-ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 3"/></svg></span><div><b id="clkT">--:--:--</b><small id="clkD">-- --- ----</small></div></div>
        <div class="chip-user"><div class="av"><?= $inisial ?></div><div><b><?= $username ?></b><small>Pengguna Aktif</small></div></div>
      </div>
    </header>

    <main class="content">
      <div class="sec-h"><h1>Edukasi Pengguna</h1><p>Ringkasan singkat dari artikel media dan sumber kesehatan terpercaya untuk menunjang program dietmu. Klik kartu untuk membaca artikel lengkap di sumbernya.</p></div>
      <div class="ed" id="edG"></div>
      <div class="note"><b>Catatan:</b> Ringkasan ditulis ulang secara singkat dari artikel aslinya. Informasi bersifat edukatif dan bukan pengganti saran dokter atau ahli gizi.</div>
    </main>
  </div>
</div>

<script>
const $=s=>document.querySelector(s);
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
 {g:'Pemeriksaan & Menu',items:[['deteksi','Deteksi Obesitas','pulse','deteksi_obesitas.php'],['gizi','Gizi Planner','salad','gizi_planner.php'],['olahraga','Olahraga','dumb','olahraga.php']]},
 {g:'Konsultasi & Belajar',items:[['chatbot','Konsultasi AI','chat','chatbot.php'],['edukasi','Edukasi Pengguna','book','edukasi_pengguna.php']]},
 {g:'Laporan & Akun',items:[['ekspor','Ekspor Laporan','file','dashboard.php#ekspor'],['profil','Kelola Profil','user','dashboard.php#profil']]}
];
$('#nav').innerHTML=NAV.map(n=>`<div class="nav-label">${n.g}</div>`+n.items.map(i=>`<a class="nav-item${i[0]==='edukasi'?' active':''}" href="${i[3]}"><span class="ic">${ICON[i[2]]}</span>${i[1]}</a>`).join('')).join('');
const side=$('#sidebar'),scrim=$('#scrim');
function closeSide(){side.classList.remove('open');scrim.classList.remove('show')}
$('#burger').onclick=()=>{side.classList.add('open');scrim.classList.add('show')};scrim.onclick=closeSide;

/* ===== ARTIKEL (ringkasan ditulis ulang; link ke sumber asli) =====
   [judul, emoji, warna, ringkasan, sumber, tahun, url] */
const ART=[
 ['12 Makanan Karbohidrat Kompleks untuk Diet','🍚','#eef5d8',
  'Karbohidrat kompleks dicerna lebih lambat dan kaya serat, sehingga energi lebih stabil dan tidak memicu lonjakan gula darah. Nasi merah punya kalori serupa nasi putih, tetapi seratnya jauh lebih banyak.',
  'CNN Indonesia','2020','https://www.cnnindonesia.com/gaya-hidup/20200604150830-255-509898/12-makanan-mengandung-karbohidrat-kompleks-cocok-untuk-diet/'],
 ['Pentingnya Minum Air Setiap Hari','💧','#e3f1fa',
  'Air putih tidak mengandung kalori maupun gula, sehingga lebih aman untuk berat badan dibanding minuman manis. Bawa botol minum dan makan buah berair untuk membantu memenuhi kebutuhan cairan.',
  'CNN Indonesia','2016','https://www.cnnindonesia.com/keluarga/20160907122355-436-156667/pentingnya-minum-air-setiap-hari/'],
 ['5 Cara Diet Defisit Kalori yang Benar','🔥','#fff1d3',
  'Defisit kalori berarti makan lebih sedikit dari kebutuhan harian, umumnya sekitar 500–750 kkal per hari. Hasilnya lebih optimal bila dipadukan dengan olahraga rutin dan sebaiknya dikonsultasikan ke dokter.',
  'Alodokter','','https://www.alodokter.com/5-cara-melakukan-diet-defisit-kalori-untuk-turunkan-berat-badan'],
 ['Protein Penting, Bukan Hanya untuk Atlet','🥚','#fbe6da',
  'Protein menjadi bahan perbaikan otot setelah aktivitas dan membantu menjaga massa otot tanpa lemak. Membagi protein di setiap waktu makan, termasuk sarapan, membantu energi stabil dan nafsu makan terkontrol.',
  'ANTARA','','https://www.antaranews.com/berita/5552655/bukan-hanya-atlet-protein-penting-untuk-energi-harian'],
 ['Kurang Tidur 90 Menit Bisa Bikin BB Naik','😴','#e8e4f8',
  'Sebuah studi menemukan berat badan peserta naik sekitar 0,5 kg setelah tidurnya dikurangi 90 menit per malam. Peneliti mengingatkan ada keterbatasan studi, jadi tetap jaga tidur cukup sebagai bagian dari program diet.',
  'detikHealth','','https://health.detik.com/berita-detikhealth/d-8599210/studi-ungkap-kurang-tidur-90-menit-per-malam-bisa-bikin-bb-naik'],
 ['3 Cara Mudah Membaca Informasi Gizi','🏷️','#dff3e6',
  'Angka pada tabel gizi berlaku per takaran saji, bukan per kemasan, jadi cek berapa sajian dalam satu kemasan. Perhatikan gula, garam, dan lemak, serta persentase AKG yang dihitung dari kebutuhan 2.150 kkal.',
  'CNN Indonesia','2022','https://www.cnnindonesia.com/gaya-hidup/20220309102957-262-768670/3-cara-mudah-membaca-informasi-gizi-pada-produk-kemasan']
];
$('#edG').innerHTML=ART.map((a,i)=>`<a class="art" style="--i:${i}" href="${a[6]}" target="_blank" rel="noopener noreferrer">
  <div class="th" style="--c:${a[2]}"><span>${a[1]}</span></div>
  <div class="bd"><div class="src"><span class="badge${a[4]==='CNN Indonesia'?' cnn':''}">${esc(a[4])}</span>${a[5]?`<span class="yr">${a[5]}</span>`:''}</div>
  <h3>${esc(a[0])}</h3><p>${esc(a[3])}</p><span class="rd">Baca di ${esc(a[4])} ↗</span></div></a>`).join('');

function tick(){const n=new Date();
  $('#clkT').textContent=n.toLocaleTimeString('id-ID',{hour:'2-digit',minute:'2-digit',second:'2-digit',hour12:false}).replace(/\./g,':');
  $('#clkD').textContent=n.toLocaleDateString('id-ID',{weekday:'long',day:'2-digit',month:'long',year:'numeric'})}
tick();setInterval(tick,1000);
</script>
</body>
</html>