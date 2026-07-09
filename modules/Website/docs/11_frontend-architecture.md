# 11 — Frontend Architecture (Level 3)
> How the website is structured internally. No code, just structural conventions.

## 1. Component-Based Blade
- All UI elements are strictly isolated as Blade components (e.g., Navbar, Footer, HeroSection, FloorPlanDemo).
- No inline HTML in page views. Pages simply compose components.
- Layouts extend a master `app` layout that handles the `<html>` tag, `<head>` (SEO meta), and global scripts.

## 2. Tailwind Configuration Strategy
- The Tailwind config defines the **neutral base colors** (slate, gray, white) as the foundation.
- **CSS Variables** (`--accent-primary`, `--accent-secondary`, `--hero-image`) are defined in the root.
- The "Universe" selection is handled by toggling a `data-theme` attribute on the `<html>` tag. Tailwind utility classes reference the CSS variables, allowing instant theme switching without generating duplicate CSS for every universe.

## 3. Alpine.js State Management
- Alpine.js is used exclusively for lightweight, localized interactivity.
- **Global State:** An Alpine component at the `<body>` level manages the `currentUniverse` and `currentLanguage`.
- **Universe Toggle:** When clicked, it updates the `data-theme` attribute on the `<html>` tag and triggers a smooth transition for images.
- **Floor Plan Demo:** An Alpine component fetches the JSON payload from the API and renders the abstract shapes and chairs using inline SVG or absolute positioning.

## 4. Bilingual Routing
- URLs are structured with a language prefix (e.g., `/ar/features`, `/en/features`).
- A middleware intercepts the request, sets the Laravel `App::setLocale()`, and passes the correct language to the Blade views.
- If a user visits the root `/`, they are redirected to their browser's preferred language, or default to `/ar`.

## 5. SEO & Performance Invariants
- **Critical CSS:** The initial HTML payload includes all CSS required for above-the-fold content.
- **Server-Side Rendering:** All marketing text, H1 tags, and meta descriptions are rendered by PHP/Blade before sending the response.
- **Lazy Loading:** The interactive floor-plan demo and below-the-fold images are lazy-loaded via Alpine.js only when they enter the viewport.