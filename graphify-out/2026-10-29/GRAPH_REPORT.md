# Graph Report - gend-off  (2026-10-29)

## Corpus Check
- cluster-only mode — file stats not available

## Summary
- 328 nodes · 573 edges · 19 communities (13 shown, 6 thin omitted)
- Extraction: 98% EXTRACTED · 2% INFERRED · 0% AMBIGUOUS · INFERRED: 14 edges (avg confidence: 0.84)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `ac9b5cdf`
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

## God Nodes (most connected - your core abstractions)
1. `render()` - 21 edges
2. `renderAll()` - 16 edges
3. `drawPl()` - 13 edges
4. `daftar_user()` - 12 edges
5. `statInfo()` - 11 edges
6. `cek_login()` - 10 edges
7. `sb_request()` - 10 edges
8. `draw4()` - 9 edges
9. `figSVG()` - 9 edges
10. `weekPlan()` - 8 edges

## Surprising Connections (you probably didn't know these)
- `Gend-off Logo` --conceptually_related_to--> `Person measuring waist with tape`  [INFERRED]
  assets/logo.png → assets/gambargendutdanmeteran.jpeg
- `Gend-off Logo` --conceptually_related_to--> `Avocado Mascot`  [INFERRED]
  assets/logo.png → assets/maskot_alpukat.webp
- `Gend-off Logo` --conceptually_related_to--> `Beetroot Mascot`  [INFERRED]
  assets/logo.png → assets/maskot_bit.webp
- `Gend-off Logo` --conceptually_related_to--> `Carrot Mascot`  [INFERRED]
  assets/logo.png → assets/maskot_wortel.webp

## Import Cycles
- None detected.

## Hyperedges (group relationships)
- **Fitness Mascots** — assets_maskot_alpukat, assets_maskot_bit, assets_maskot_wortel [EXTRACTED 1.00]

## Communities (19 total, 6 thin omitted)

### Community 0 - "olahraga_planner.php"
Cohesion: 0.05
Nodes (72): AF, ang(), BB, buildDay(), buildPlan(), buildSteps(), cnt(), COL (+64 more)

### Community 1 - "gizi_planner.php"
Cohesion: 0.09
Nodes (38): ALL, countTo(), dec(), draw1(), draw2(), draw3(), draw4(), drawTop() (+30 more)

### Community 2 - "dashboard.php"
Cohesion: 0.12
Nodes (33): addWaist(), bmiNow(), cat(), closeSide(), countTo(), dec(), dlabel(), drawChart() (+25 more)

### Community 3 - "register.php"
Cohesion: 0.10
Nodes (23): aim(), cap, capTxt, cf, cfHint, checkCf(), checkPw(), eyes (+15 more)

### Community 4 - "deteksi_obesitas.php"
Cohesion: 0.12
Nodes (16): BMI_RULES, bmiRuleIdx(), countTo(), dec(), estWaist(), fmt(), ICON, load() (+8 more)

### Community 5 - "chatbot.php"
Cohesion: 0.27
Nodes (22): cek_login(), daftar_user(), sb_auth_hapus_user(), sb_auth_login(), sb_auth_logout(), sb_auth_refresh(), sb_auth_signup(), sb_config() (+14 more)

### Community 6 - "profil.php"
Cohesion: 0.11
Nodes (10): addMsg(), ask(), box, ICON, NAV, QS, RULES, S (+2 more)

### Community 7 - "figSVG"
Cohesion: 0.11
Nodes (9): ACT, ICON, NAV, P, rows, rowsEl, S, scrim (+1 more)

### Community 8 - "login.php"
Cohesion: 0.22
Nodes (15): allPts(), circ_(), dr(), exBox(), exInner(), fig(), figSVG(), lerp() (+7 more)

### Community 9 - "index.php"
Cohesion: 0.21
Nodes (11): aim(), cap, capTxt, eyes, left, look(), pw, setSad() (+3 more)

### Community 10 - "edukasi_pengguna.php"
Cohesion: 0.25
Nodes (8): count(), drawCard(), drawDist(), drawPf(), drawTabs(), io, menu, run()

### Community 11 - "Gend-off Logo"
Cohesion: 0.20
Nodes (5): ART, ICON, NAV, scrim, side

### Community 12 - "Copilot Instructions"
Cohesion: 0.40
Nodes (5): Person measuring waist with tape, Gend-off Logo, Avocado Mascot, Beetroot Mascot, Carrot Mascot

## Knowledge Gaps
- **10 isolated node(s):** `Person measuring waist with tape`, `Avocado Mascot`, `Beetroot Mascot`, `Carrot Mascot`, `Graphify Tool` (+5 more)
  These have ≤1 connection - possible missing edges or undocumented components. (Counts symbols only; 144 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **6 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **What connects `Person measuring waist with tape`, `Avocado Mascot`, `Beetroot Mascot` to the rest of the system?**
  _10 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `olahraga_planner.php` be split into smaller, more focused modules?**
  _Cohesion score 0.05331510594668489 - nodes in this community are weakly interconnected._
- **Should `gizi_planner.php` be split into smaller, more focused modules?**
  _Cohesion score 0.08562367864693446 - nodes in this community are weakly interconnected._
- **Should `dashboard.php` be split into smaller, more focused modules?**
  _Cohesion score 0.12162162162162163 - nodes in this community are weakly interconnected._
- **Should `register.php` be split into smaller, more focused modules?**
  _Cohesion score 0.10461538461538461 - nodes in this community are weakly interconnected._
- **Should `deteksi_obesitas.php` be split into smaller, more focused modules?**
  _Cohesion score 0.11594202898550725 - nodes in this community are weakly interconnected._
- **Should `profil.php` be split into smaller, more focused modules?**
  _Cohesion score 0.1111111111111111 - nodes in this community are weakly interconnected._