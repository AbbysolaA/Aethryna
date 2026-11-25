# Colour Palette Bank

Reusable palettes for future Aethryna projects. Each one is built on the **60/30/10 rule**: 60% dominant, 30% secondary, 10% accent.

- `palettes.json`: source of truth (names, hex codes, roles, notes)
- `palettes.css`: CSS custom properties, plus `.palette-*` classes that expose `--c-60`, `--c-30` and `--c-10`

| Palette | 60 (dominant) | 30 (secondary) | 10 (accent) | Ratio seen in the reference |
|---|---|---|---|---|
| **Late Sets**, jazz / riso / nightlife | Cream Paper `#F8DBC1` | Riso Teal `#1D646C` | Fluorescent Pink `#EA4F88` | 60-40 · 40-40-20 |
| **Fired Twice**, ceramics / earthy craft | Raw Clay `#D0BCAC` | Iron Brown `#461C0C` | Turquoise `#269991` | 80-20 · 60-30-10 |
| **Ribbon Finals**, sport / premium events | Petrol Blue `#002D37` | Ivory `#FEF0DD` | Saffron Gold `#F7A724` | 70-30 · 60-30-10 ✅ cleanest |
| **Drift** (Lumen Aquarium), aquarium / night glow | Deep Sea Blue `#012438` | Foam White `#DBF4FC` | Sunset Orange `#F16F1B` | 60-30-10 (orange glow ~20) |
| **Zesty** (Citra), drinks / playful pop | Lemon Yellow `#FCDA25` | Electric Lavender `#954BF6` | Charcoal `#2A272C` | 60-30-10 |
| **Hanare**, ryokan / Japanese minimal | Stone Grey `#B8BECB` | Deep Indigo `#142950` | Vermilion `#E93D0D` | ~60-10-30 |
| **Glacier** (Kalda), adventure travel / cinematic | Black Basalt `#0B1013` | Bone `#E8F3F6` | Glacier Blue `#1BA6CD` | 70-30 · 60-30-10 |
| **Deep Seats** (Velluto), furniture / tactile luxury | Lilac `#C9BBD8` | Deep Plum `#3F0820` | Velvet Green `#52AE4C` | ~50-20-30 |

## Notes
- **Late Sets**: the reference overspends teal and pink (big teal headline and info block, plus a large pink hero image). For a true 60/30/10, make the hero image duotone teal and keep pink for CTAs and small highlights.
- **Fired Twice**: brown works mainly as type and linework, so the page reads as 80-20 overall. Keep turquoise rare, like the glaze.
- **Ribbon Finals**: the best example of the rule. Petrol background, ivory type and nav, gold reserved for the hero motion and the primary CTA.


## Recommended: meal planning and wellbeing apps → **Jollof Kitchen**
Built for Supperfold-style apps (formerly Project Phoenix) (checklists, meal planner, streaks, rewards). It avoids the sage/cream/gold palette that keeps showing up by default.

| Role | Colour | Use |
|---|---|---|
| 60 Background | Rice Paper `#FAF6F1` (cards `#FFFFFF`) | Calm, open base with lots of whitespace |
| 25 Ink / brand | Aubergine Ink `#2B1E33`, Aubergine `#4A2F5C` | Text, headings, nav pill |
| 10 Action | Jollof `#E0522B` (buttons and links in Jollof Deep `#C2401C`) | Progress ring, active tab, CTAs |
| 5 Success | Cucumber `#23766B` | Ticks, "Ready now", done states |
| Reward only | Plantain `#F2B33A` | Stars, badges, level-ups |

Every text pairing passes WCAG AA (ink on paper 14.6:1, white on Jollof Deep 5.2:1, ink on Plantain 8.3:1). There's a dark mode too; see `palettes.json`.

## Four-colour palettes by industry
These are from the "which industry are you designing for?" set. Each has a **support** colour between the secondary and the accent, so the split is roughly 60 / 25 / 10 / 5. The hex codes were sampled from the video and are approximate (the room's warm light may tint them), so for print use the Pantone codes.

| Palette | Industry | Dominant | Secondary | Support | Accent |
|---|---|---|---|---|---|
| **Veyra** | Tech / fintech / apps | Midnight Navy `#1F1D46` · 2768 CP | Electric Blue `#1E4994` · P 104-8 C | Ice Blue `#DDE9EB` · P 115-9 C | Chartreuse `#E2D800` · P 166-8 C |
| **Little Crumb** | Bakery / café / food | Oat `#F7EFCF` · P 1-3 C | Toffee Brown `#3D1D20` · 4975 C | Olive `#A18D5D` · P 9-13 U | Strawberry Pink `#E9A5A0` · P 62-3 C |
| **Wax Seal** | Law / finance / luxury stationery | Deep Teal `#042C35` · 5463 CP | Stone `#E1DFD9` · P 169-1 C | Slate Blue `#6781A7` · P 111-12 C | Burnt Orange `#A35A29` · P 28-16 C |
| **Oriel** | Skincare / beauty / wellness | Nude `#EFC896` · P 20-2 C | Cream `#F3EDDC` · P 7-1 C | Terracotta `#B26542` · P 35-14 C | Gold `#D7AA3E` · P 10-15 C |

- **Veyra**: chartreuse on navy is extremely loud, so keep it to the logo mark and one CTA.
- **Wax Seal**: the burnt orange appears only as the wax seal, which is the best example in the bank of a 5% accent doing a lot of work.

## Rule of thumb: the accent is already in the product
In the last five references, the accent colour comes from the **hero subject** (the jellyfish, the can, the parasol, the ice, the sofa). Pick the accent from your product photo first. Then choose a calm dominant colour and a high-contrast type colour around it. Because the photo already carries most of the accent, keep UI accents (CTAs, one highlighted word) small.

- **Zesty**: lavender acts as the 30 and charcoal as the 10, which is the reverse of the swatch order.
- **Hanare** and **Deep Seats**: the accent photo takes about 30%, more than the usual 10. It still works because the type colour stays quiet and the accent complements the background.

## Usage
```html
<link rel="stylesheet" href="/design/palettes/palettes.css">
<body class="palette-ribbon-finals">
  <!-- background: var(--c-60); text: var(--c-30); buttons: var(--c-10) -->
</body>
```
