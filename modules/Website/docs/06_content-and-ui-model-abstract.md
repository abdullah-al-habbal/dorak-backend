# 06 — Content & UI Model (Abstract)
> **No database schema.** Entities are described as plain-English objects. This defines what the backend manages and serves to the website.

## 1. The Content Entities
**Marketing Page**
- identifier
- slug (e.g., 'home', 'features', 'pricing')
- title (translatable)
- meta description (translatable)
- list of **Sections** (ordered)

**Section**
- identifier
- type (e.g., 'hero', 'feature_list', 'testimonials', 'floor_plan_demo')
- content payload (translatable JSON: text, images, links)
- universe visibility (all, men_only, women_only)

**Testimonial**
- identifier
- author name
- author role (Owner, Client, Barber)
- quote (translatable)
- rating (1-5)

## 2. The UI State
**Website Session State**
- selected universe (neutral, men, women)
- selected language (ar, en)

## 3. Abstract Relationship View
```mermaid
erDiagram
    MARKETING_PAGE ||--|{ SECTION : "contains (ordered)"
    SECTION }o--o| UNIVERSE : "visible to"
    TESTIMONIAL }o--|| SECTION : "displayed in"