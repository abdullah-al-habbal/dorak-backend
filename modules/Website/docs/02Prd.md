# 02 — Website Product Requirements Document (PRD)
> **This is the master document for the public website.** 

## 1. Product Summary
The Dorak Website is the primary conversion engine for the SaaS. It must convince **Brand Owners** to sign up for the management engine, and convince **Clients** to download the Flutter app. It achieves this through a premium, gender-neutral design that dynamically adapts its accents and content based on the user's chosen "Universe".

## 2. Goals & Non-Goals
### Goals
1. **Convert Owners:** Clearly explain the operational benefits (Branch-First, multi-branch) to drive B2B signups.
2. **Delight Clients:** Show the interactive floor plan and dual-universe experience to drive app downloads.
3. **Zero Discrimination:** The base design must be gender-neutral. The "Universe" selection only changes content and accent colors, never the core layout.
4. **Dynamic Marketing:** Allow the platform admin to update pricing, features, and testimonials from the Filament Admin panel without touching code.

### Non-Goals
- Building the actual application UI (that is the Flutter app's job).
- Creating a complex, WYSIWYG page builder for the marketing team (MVP uses structured content entities).

## 3. User Types (Website Visitors)
| Persona | Who they are | Primary Need on Website |
|---|---|---|
| **Brand Owner** | Salon/Barbershop owner | Understand if this software solves my operational chaos. See pricing. Sign up. |
| **Client** | Man or woman seeking grooming | See if this app has shops near me. Understand how the floor plan works. Download the app. |
| **Freelance Barber** | Independent stylist | Understand how I can keep my independence and get discovered. |

## 4. Resolved Decisions
1. **Neutral Base Theme:** The website will use a premium, neutral base (e.g., sleek slate/charcoal or clean white) with gold/teal accents. 
2. **Universe Adaptation:** Selecting "Men's" or "Women's" will shift the accent colors (e.g., warm copper vs. elegant rose) and swap hero imagery, but the navigation, footer, and structural components remain 100% identical.
3. **Backend-Driven Content:** No marketing text is hardcoded in the frontend. All text is fetched from the Laravel API.

## 5. Open Decisions 🟡
1. **Exact Color Palette:** The exact hex codes for the "Men's" and "Women's" accent colors need final design approval.
2. **Universe Persistence:** Should the website remember the user's Universe selection via a cookie, or reset to "Neutral" on every visit?