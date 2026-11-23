# Colour Palette Bank

Reusable palettes for future Aethryna projects. Each one is built on the **60/30/10 rule**: 60% dominant, 30% secondary, 10% accent.

- `palettes.json`: source of truth (names, hex codes, roles, notes)
- `palettes.css`: CSS custom properties, plus `.palette-*` classes that expose `--c-60`, `--c-30` and `--c-10`

| Palette | 60 (dominant) | 30 (secondary) | 10 (accent) | Ratio seen in the reference |
|---|---|---|---|---|
| **Late Sets**, jazz / riso / nightlife | Cream Paper `#F8DBC1` | Riso Teal `#1D646C` | Fluorescent Pink `#EA4F88` | 60-40 · 40-40-20 |
| **Fired Twice**, ceramics / earthy craft | Raw Clay `#D0BCAC` | Iron Brown `#461C0C` | Turquoise `#269991` | 80-20 · 60-30-10 |
| **Ribbon Finals**, sport / premium events | Petrol Blue `#002D37` | Ivory `#FEF0DD` | Saffron Gold `#F7A724` | 70-30 · 60-30-10 ✅ cleanest |

## Notes
- **Late Sets**: the reference overspends teal and pink (big teal headline and info block, plus a large pink hero image). For a true 60/30/10, make the hero image duotone teal and keep pink for CTAs and small highlights.
- **Fired Twice**: brown works mainly as type and linework, so the page reads as 80-20 overall. Keep turquoise rare, like the glaze.
- **Ribbon Finals**: the best example of the rule. Petrol background, ivory type and nav, gold reserved for the hero motion and the primary CTA.

## Usage
```html
<link rel="stylesheet" href="/design/palettes/palettes.css">
<body class="palette-ribbon-finals">
  <!-- background: var(--c-60); text: var(--c-30); buttons: var(--c-10) -->
</body>
```
