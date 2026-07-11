# 07 — Website User Flows

## 1. Owner Discovery & Signup Flow
1. Visitor lands on the Home page.
2. Reads the value proposition (neutral premium design).
3. Clicks "Pricing" in the navbar.
4. Views the Freemium Gate comparison.
5. Clicks "Start for Free".
6. Redirected to the Filament `AdminPanel` or `BranchPanel` signup.

## 2. Client Universe Selection Flow
1. Visitor lands on the Home page (Default: Neutral or detected by browser).
2. Clicks the "Universe" toggle in the navbar.
3. Selects "Women's Beauty".
4. Site updates accent colors and hero imagery via lightweight JS (no full page reload).
5. Visitor continues browsing with tailored content.
6. Clicks "Download App" and is routed to the correct app store.

## 3. SEO Bot Crawling Flow
1. Search engine bot requests the `/features` URL.
2. Server renders the critical HTML (H1, meta tags, main text) using the default language (Arabic).
3. Bot indexes the structured content.
4. Interactive elements (like the floor plan demo) are ignored by the bot but remain functional for human users.