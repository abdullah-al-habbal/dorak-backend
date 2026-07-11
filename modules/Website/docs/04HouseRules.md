# 04 — Website House Rules (The UI/UX Laws)
> The unshakeable laws of how the Dorak Website operates.

## A. Design & Theming (The No-Discrimination Law)
- **W1.** If a user lands on the site, they must see a **neutral, premium base theme**.
- **W2.** If a user selects a "Universe" (Men's/Women's), then the site may change **accent colors and imagery**, but the **core layout, navigation, and components must remain identical**.
- **W3.** The site must never use stereotypical or alienating color blocking (e.g., the entire site turning pink for women or dark blue for men).

## B. Bilingual & Dynamic Content
- **W4.** If the site loads, it must detect the browser language or default to Arabic.
- **W5.** If a translation is missing from the backend, it must fall back to the other language — **never show an empty label**.
- **W6.** All marketing content (features, pricing, testimonials) must be **dynamic from the backend**, not hardcoded in the frontend files.

## C. Backend-Driven UI Preview
- **W7.** The "Interactive Floor Plan" shown on the website must be a **scaled-down, read-only version** of the exact same JSON payload the Flutter app receives. It must prove the "Backend-Driven UI" concept visually.

## D. Performance & SEO
- **W8.** If the site loads, the critical above-the-fold content must be rendered server-side for SEO bots.
- **W9.** If a user is on a slow 3G network, the interactive floor plan demo must show a skeleton loader, never break the layout.