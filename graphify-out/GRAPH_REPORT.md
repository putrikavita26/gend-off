# Graph Report - gend-off  (2026-11-19)

## Corpus Check
- 21 files · ~1,992,190 words
- Verdict: corpus is large enough that graph structure adds value.
- Unclassified: 2 file(s) not represented in the graph (top: (none) 2)

## Summary
- 362 nodes · 642 edges · 29 communities (17 shown, 12 thin omitted)
- Extraction: 95% EXTRACTED · 5% INFERRED · 0% AMBIGUOUS · INFERRED: 33 edges (avg confidence: 0.85)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `ff1cd697`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- olahraga_planner.php
- gizi_planner.php
- dashboard.php
- register.php
- deteksi_obesitas.php
- chatbot.php
- profil.php
- figSVG
- login.php
- index.php
- edukasi_pengguna.php
- Gend-off Logo
- Copilot Instructions
- Fruit and Vegetable Image
- Fruit and Vegetable Love Image
- Right Side Image
- Left Side Image
- logout.php
- Fruit and Vegetable Image
- Fruit and Vegetable Love Image
- Right Side Image
- Left Side Image
- public.catatan_makan

## God Nodes (most connected - your core abstractions)
1. `render()` - 21 edges
2. `renderAll()` - 16 edges
3. `draw4()` - 13 edges
4. `drawPl()` - 13 edges
5. `sb_log()` - 13 edges
6. `daftar_user()` - 12 edges
7. `sb_ok()` - 12 edges
8. `statInfo()` - 11 edges
9. `cek_login()` - 10 edges
10. `sb_request()` - 10 edges

## Surprising Connections (you probably didn't know these)
- `gizi_food_row()` --calls--> `sb_ok()`  [INFERRED]
  api_gizi_makan.php → supabase.php
- `gizi_api_error()` --calls--> `gend_json()`  [INFERRED]
  api_gizi_makan.php → gend_core.php
- `gizi_api_error()` --calls--> `sb_log()`  [INFERRED]
  api_gizi_makan.php → supabase.php
- `gizi_num()` --calls--> `gend_json()`  [INFERRED]
  api_gizi_makan.php → gend_core.php
- `gend_api_awal()` --calls--> `sb_token()`  [INFERRED]
  gend_core.php → supabase.php

## Import Cycles
- None detected.

## Hyperedges (group relationships)
- **Fitness Mascots** — assets_maskot_alpukat, assets_maskot_bit, assets_maskot_wortel [EXTRACTED 1.00]

## Communities (29 total, 12 thin omitted)

### Community 0 - "olahraga_planner.php"
Cohesion: 0.07
Nodes (44): CATALOG_VERSION, countTo(), customFoods, dec(), draw1(), draw2(), draw3(), draw4() (+36 more)

### Community 1 - "gizi_planner.php"
Cohesion: 0.12
Nodes (34): addWaist(), bmiNow(), cat(), catatProgres(), closeSide(), countTo(), dec(), dlabel() (+26 more)

### Community 2 - "dashboard.php"
Cohesion: 0.14
Nodes (35): gizi_api_error(), gizi_food_row(), gizi_num(), gend_api_awal(), gend_boot(), gend_cari_status(), gend_catat_progres(), gend_csrf() (+27 more)

### Community 3 - "register.php"
Cohesion: 0.07
Nodes (30): AF, BB, buildDay(), buildPlan(), buildSteps(), COL, dayIndex(), DAYS (+22 more)

### Community 4 - "deteksi_obesitas.php"
Cohesion: 0.10
Nodes (23): aim(), cap, capTxt, cf, cfHint, checkCf(), checkPw(), eyes (+15 more)

### Community 5 - "chatbot.php"
Cohesion: 0.12
Nodes (16): BMI_RULES, bmiRuleIdx(), countTo(), dec(), estWaist(), fmt(), ICON, load() (+8 more)

### Community 6 - "profil.php"
Cohesion: 0.15
Nodes (10): addMsg(), ask(), box, CHAT_CSRF, chatHistory, ICON, NAV, QS (+2 more)

### Community 7 - "figSVG"
Cohesion: 0.18
Nodes (18): allPts(), circ_(), dr(), exBox(), exInner(), exSVG(), exVB(), fig() (+10 more)

### Community 8 - "login.php"
Cohesion: 0.18
Nodes (18): cnt(), dkey(), durTxt(), f1(), getChecks(), kRange(), logWeight(), markDone() (+10 more)

### Community 9 - "index.php"
Cohesion: 0.11
Nodes (9): ACT, ICON, NAV, P, rows, rowsEl, S, scrim (+1 more)

### Community 10 - "edukasi_pengguna.php"
Cohesion: 0.21
Nodes (13): drawPl(), esc(), finishPl(), fmt(), go(), kDone(), mmss(), openPlayer() (+5 more)

### Community 11 - "Gend-off Logo"
Cohesion: 0.21
Nodes (11): aim(), cap, capTxt, eyes, left, look(), pw, setSad() (+3 more)

### Community 12 - "Copilot Instructions"
Cohesion: 0.25
Nodes (8): count(), drawCard(), drawDist(), drawPf(), drawTabs(), io, menu, run()

### Community 13 - "Fruit and Vegetable Image"
Cohesion: 0.20
Nodes (5): ART, ICON, NAV, scrim, side

### Community 14 - "Fruit and Vegetable Love Image"
Cohesion: 0.53
Nodes (6): ang(), elbPose(), ikA(), kneePose(), pikePose(), pushPose()

### Community 15 - "Right Side Image"
Cohesion: 0.40
Nodes (5): Person measuring waist with tape, Gend-off Logo, Avocado Mascot, Beetroot Mascot, Carrot Mascot

### Community 26 - "public.catatan_makan"
Cohesion: 0.43
Nodes (4): catatan_makan_owner_date_idx, makanan_custom_user_owner_name_idx, public.catatan_makan, public.makanan_custom_user

## Knowledge Gaps
- **10 isolated node(s):** `Person measuring waist with tape`, `Avocado Mascot`, `Beetroot Mascot`, `Carrot Mascot`, `Graphify Tool` (+5 more)
  These have ≤1 connection - possible missing edges or undocumented components. (Counts symbols only; 149 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **12 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `render()` connect `login.php` to `logout.php`, `edukasi_pengguna.php`, `register.php`, `figSVG`?**
  _High betweenness centrality (0.001) - this node is a cross-community bridge._
- **Are the 5 inferred relationships involving `sb_log()` (e.g. with `gizi_api_error()` and `gend_catat_progres()`) actually correct?**
  _`sb_log()` has 5 INFERRED edges - model-reasoned connections that need verification._
- **What connects `Person measuring waist with tape`, `Avocado Mascot`, `Beetroot Mascot` to the rest of the system?**
  _10 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `olahraga_planner.php` be split into smaller, more focused modules?**
  _Cohesion score 0.07329462989840348 - nodes in this community are weakly interconnected._
- **Should `gizi_planner.php` be split into smaller, more focused modules?**
  _Cohesion score 0.11948790896159317 - nodes in this community are weakly interconnected._
- **Should `dashboard.php` be split into smaller, more focused modules?**
  _Cohesion score 0.14439946018893388 - nodes in this community are weakly interconnected._
- **Should `register.php` be split into smaller, more focused modules?**
  _Cohesion score 0.06890756302521009 - nodes in this community are weakly interconnected._