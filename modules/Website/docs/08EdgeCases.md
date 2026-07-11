# 08 — Website Edge Cases

## EC-W1: Missing Translation for Marketing Content ✅
**Scenario:** Admin adds a new feature in Arabic but forgets English.
**Behavior:** The site shows the Arabic text for English users. A label is **never empty**.

## EC-W2: Slow Network for Floor Plan Demo ✅
**Scenario:** The JSON payload for the demo floor plan is slow to load on 3G.
**Behavior:** Show a sleek skeleton loader matching the floor plan's dimensions. Do not break the layout or shift content.

## EC-W3: SEO Bots vs. Dynamic Content ✅
**Scenario:** Google bot crawls the site, but the marketing content is loaded via JS.
**Behavior:** Ensure critical SEO content (H1, meta descriptions, main text) is rendered **server-side** in the initial HTML response. Only the interactive enhancements (like the floor plan) rely on client-side rendering.

## EC-W4: Universe Toggle on Slow Connection 🟡
**Scenario:** User clicks the Universe toggle, but the new accent images are slow to load.
**Behavior:** The accent colors should swap instantly via CSS variables. The images should fade in smoothly once loaded, without flashing or layout shifts.