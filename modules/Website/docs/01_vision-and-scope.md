# 01 — Website Vision & Scope
> **Project:** Dorak (دورك) Public Website
> **One Line:** The premium, bilingual digital storefront for the Dorak SaaS, designed to convert salon owners and attract clients across both the Men's Grooming and Women's Beauty universes without discrimination.

## 1. The Problem
Most salon and barber software websites suffer from three flaws:
- **Visual Clutter:** They look like outdated templates, failing to convey the premium nature of the platform.
- **Gender Bias:** They heavily lean into stereotypical colors (e.g., aggressive dark blues for men, overwhelming pinks for women), alienating half the audience or making the platform feel niche.
- **Static Content:** They are hardcoded, making it impossible for the marketing team to update features, pricing, or testimonials without developer intervention.

## 2. The Vision
A **neutral, premium, and modern** public website that serves as the front door to the Dorak ecosystem.
- **Shared Elements:** The core UI (navigation, footer, floor-plan preview, layout structure) remains identical for all users.
- **Dynamic Universes:** Based on the user's selected "Universe" (Men's, Women's, or Neutral), the site dynamically adjusts **accent colors and specific content sections** (like hero imagery and service highlights) without altering the core layout.
- **Backend-Driven:** All marketing content (features, pricing, testimonials) is pulled dynamically from the Laravel backend.

## 3. Scope — What is in, next, later
### ✅ MUST HAVE (MVP)
- **Landing Page:** Hero section, Dual-Universe showcase, Interactive Floor-Plan preview, How it Works, Testimonials, CTA.
- **Features Page:** Deep dive into Branch-First, Standalone Barbers, and Backend-Driven UI.
- **Pricing Page:** Clear visual representation of Freemium vs. Premium gates.
- **Universe Toggle:** A UI element that switches the site's accent theme and content sections.
- **Bilingual Support:** Full Arabic and English, with RTL/LTR handling.

### 🔭 LATER
- A dedicated blog for SEO and industry insights.
- A complex, multi-tenant CMS for individual branches to customize their own public micro-sites (this is handled in the Flutter apps for MVP).