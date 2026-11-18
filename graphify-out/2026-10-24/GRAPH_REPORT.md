# Graph Report - gend-off  (2026-10-23)

## Corpus Check
- cluster-only mode — file stats not available

## Summary
- 307 nodes · 507 edges · 18 communities (12 shown, 6 thin omitted)
- Extraction: 97% EXTRACTED · 3% INFERRED · 0% AMBIGUOUS · INFERRED: 14 edges (avg confidence: 0.84)
- Token cost: 1,268 input · 94 output

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

## God Nodes (most connected - your core abstractions)
1. `render()` - 21 edges
2. `renderAll()` - 16 edges
3. `drawPl()` - 13 edges
4. `statInfo()` - 11 edges
5. `draw4()` - 9 edges
6. `figSVG()` - 9 edges
7. `weekPlan()` - 8 edges
8. `mealItems()` - 8 edges
9. `dec()` - 8 edges
10. `getChecks()` - 7 edges

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

## Communities (18 total, 6 thin omitted)

### Community 0 - "olahraga_planner.php"
Cohesion: 0.05
Nodes (71): AF, ang(), BB, buildDay(), buildPlan(), buildSteps(), cnt(), COL (+63 more)

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
Cohesion: 0.11
Nodes (10): addMsg(), ask(), box, ICON, NAV, QS, RULES, S (+2 more)

### Community 6 - "profil.php"
Cohesion: 0.11
Nodes (9): ACT, ICON, NAV, P, rows, rowsEl, S, scrim (+1 more)

### Community 7 - "figSVG"
Cohesion: 0.20
Nodes (16): allPts(), circ_(), dr(), exBox(), exInner(), fig(), figRun(), figSVG() (+8 more)

### Community 8 - "login.php"
Cohesion: 0.19
Nodes (11): aim(), cap, capTxt, eyes, left, look(), pw, setSad() (+3 more)

### Community 9 - "index.php"
Cohesion: 0.25
Nodes (8): count(), drawCard(), drawDist(), drawPf(), drawTabs(), io, menu, run()

### Community 10 - "edukasi_pengguna.php"
Cohesion: 0.20
Nodes (5): ART, ICON, NAV, scrim, side

### Community 11 - "Gend-off Logo"
Cohesion: 0.40
Nodes (5): Person measuring waist with tape, Gend-off Logo, Avocado Mascot, Beetroot Mascot, Carrot Mascot

## Knowledge Gaps
- **10 isolated node(s):** `Person measuring waist with tape`, `Avocado Mascot`, `Beetroot Mascot`, `Carrot Mascot`, `Graphify Tool` (+5 more)
  These have ≤1 connection - possible missing edges or undocumented components. (Counts symbols only; 146 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **6 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `drawPl()` connect `olahraga_planner.php` to `figSVG`?**
  _High betweenness centrality (0.001) - this node is a cross-community bridge._
- **What connects `Person measuring waist with tape`, `Avocado Mascot`, `Beetroot Mascot` to the rest of the system?**
  _10 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `olahraga_planner.php` be split into smaller, more focused modules?**
  _Cohesion score 0.05368421052631579 - nodes in this community are weakly interconnected._
- **Should `gizi_planner.php` be split into smaller, more focused modules?**
  _Cohesion score 0.08562367864693446 - nodes in this community are weakly interconnected._
- **Should `dashboard.php` be split into smaller, more focused modules?**
  _Cohesion score 0.12162162162162163 - nodes in this community are weakly interconnected._
- **Should `register.php` be split into smaller, more focused modules?**
  _Cohesion score 0.09971509971509972 - nodes in this community are weakly interconnected._
- **Should `deteksi_obesitas.php` be split into smaller, more focused modules?**
  _Cohesion score 0.11594202898550725 - nodes in this community are weakly interconnected._