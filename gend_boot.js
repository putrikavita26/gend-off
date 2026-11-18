/* GEND-OFF - fungsi bantu di browser. Dicetak otomatis oleh gend_boot_script() setelah window.GEND (data dari database) terisi. */
(function(){
var G=window.GEND,KEY='gendoff_dash_v3',OWN='gendoff_owner',OKEY='gendoff_olahraga_v1';
var WARNA=[['#fde8e6','#c2362b'],['#fff4d6','#8a6300'],['#e5f6ec','#0f7a4f'],['#fff4d6','#8a6300'],['#fbd9d6','#a11f17']];
/* kategori IMT dari basis pengetahuan (database). Hasil: {nama,bg,fg,adj,over,kurus,idx,s} */
G.kat=function(bmi){
  bmi=Math.round(+bmi*10)/10;var s=G.ref.status,r=null,i;
  for(i=0;i<s.length;i++){if(bmi>=s[i].min&&bmi<s[i].max){r=s[i];break}}
  if(!r)r=bmi<s[0].min?s[0]:s[s.length-1];
  var idx=s.indexOf(r),c=s.length===5?WARNA[idx]:(r.adj>0?WARNA[1]:r.adj===0?WARNA[2]:WARNA[4]);
  return{nama:r.nama,bg:c[0],fg:c[1],adj:r.adj,over:r.adj<0,kurus:r.adj>0,idx:idx,s:r};
};
G.cat=function(b){var k=G.kat(b);return[k.nama,k.bg,k.fg]};
G.normal=function(){var s=G.ref.status,n=s.filter(function(x){return x.adj===0})[0]||s[0];return[n.min,Math.round((n.max-.1)*10)/10]};
G.laju=function(adj){return adj<0?Math.max(.2,-adj*7/7700):.5};            /* kg per minggu dari defisit kalori (7700 kkal per kg) */
G.risk=function(g,bmi,wa){var k=G.kat(bmi),lim=g==='L'?90:80;
  return wa>=lim?(k.over?['Tinggi','#d8452e']:['Sedang','#c97a10']):(k.over?['Sedang','#c97a10']:['Rendah','#0f7a4f'])};
G.waktu=function(over,bb,goal,adj){var d=bb-goal;if(!(over&&d>0))return'Tidak perlu defisit';
  var wk=d/G.laju(adj);return Math.max(1,Math.ceil(wk*.9))+' – '+Math.ceil(wk*1.1)+' minggu'};
G.usia=function(t){var d=new Date(t+'T00:00:00'),n=new Date(),a=n.getFullYear()-d.getFullYear(),m=n.getMonth()-d.getMonth();
  if(m<0||(m===0&&n.getDate()<d.getDate()))a--;return a};
G.akt=function(id){return G.ref.aktivitas.filter(function(a){return a.id===id})[0]};
G.buatLast=function(p,d){var k=G.kat(p.bmi),a=G.akt(p.id_aktivitas),r=G.risk(p.jk,p.bmi,p.lp);
  return{bmi:p.bmi,cat:k.nama,cbg:k.bg,cfg:k.fg,risk:r[0],rcol:r[1],wk:G.waktu(k.over,p.bb,p.goal,k.adj),wa:p.lp,we:!!p.we,
    tgt:Math.round(p.target),d:d,g:p.jk,age:G.usia(p.tgl_lahir),af:a?a.faktor:1.2,pen:p.pen||''}};
var tg=function(s){return new Date(s+'T00:00:00').toDateString()};
/* Menyalin data dari database ke localStorage yang dipakai halaman. Data harian (air, ceklist) dipertahankan. */
G.terapkan=function(st){
  var S={};try{S=JSON.parse(localStorage.getItem(KEY)||'{}')||{}}catch(e){S={}}
  if(localStorage.getItem(OWN)!==G.uid){S={};try{localStorage.removeItem(OKEY)}catch(e){}}   /* data lokal milik akun lain / belum bertanda: dibersihkan */
  G.profil=st.profil;G.progres=st.progres;
  if(st.profil){var p=st.profil,n=st.progres.length,lastD=n?st.progres[n-1].d:null;
    S.tb=p.tb;S.bb=p.bb;S.goal=p.goal;S.target=Math.round(p.target);
    S.weights=st.progres.filter(function(r){return r.bb!=null}).map(function(r){return{d:tg(r.d),w:r.bb}});
    S.waists=st.progres.filter(function(r){return r.lp!=null}).map(function(r){return{d:tg(r.d),w:r.lp}});
    S.last=G.buatLast(p,lastD?tg(lastD):new Date().toDateString());
  }else{delete S.last;S.weights=[];S.waists=[]}
  try{localStorage.setItem(KEY,JSON.stringify(S));localStorage.setItem(OWN,G.uid)}catch(e){}
  return S;
};
/* kirim ke server (JSON + token CSRF). Hasil: Promise berisi objek respons */
G.kirim=function(url,data){
  return fetch(url,{method:'POST',credentials:'same-origin',headers:{'Content-Type':'application/json','X-CSRF-Token':G.csrf},body:JSON.stringify(data)})
    .then(function(r){return r.json().catch(function(){return{ok:false,error:'Respons server tidak valid.'}})})
    .catch(function(){return{ok:false,error:'Tidak dapat terhubung ke server.'}})
    .then(function(r){if(r&&r.login){location.href='login.php'}return r});
};
G.deteksi=function(inp){return G.kirim('api_deteksi.php',inp).then(function(r){if(r.ok&&r.state)G.terapkan(r.state);return r})};
G.catat=function(inp){return G.kirim('api_progres.php',inp).then(function(r){if(r.ok&&r.state)G.terapkan(r.state);return r})};
G.terapkan({profil:G.profil,progres:G.progres});
})();
