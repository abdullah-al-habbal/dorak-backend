# 09 — C4 Context (Level 1)
> **The website as one box**, and everyone/everything that touches it.

## The One-Box View
The Dorak Website is a public-facing container that consumes the Dorak API for dynamic marketing content and the floor-plan demo payload.

```mermaid
flowchart TB
    subgraph PEOPLE[People who visit the Website]
        OW["Brand Owner<br/>(Looking for software)"]
        CL["Client<br/>(Looking to download app)"]
        BB["Barber<br/>(Looking for independence)"]
        BOT["SEO Bots<br/>(Google, Bing)"]
    end
    WEB["DORAK WEBSITE<br/>Public Marketing & Conversion Site<br/>(Bilingual, Dual-Universe)"]
    subgraph EXT[Supporting external services]
        API["Dorak Laravel API<br/>(Serves marketing content & floor plan JSON)"]
        ANALYTICS["Analytics<br/>(GA4 / Plausible)"]
    end
    OW -->|"views features, pricing"| WEB
    CL -->|"selects universe, downloads app"| WEB
    BB -->|"reads about standalone barber"| WEB
    BOT -->|"crawls server-rendered HTML"| WEB
    WEB -->|"fetches dynamic content"| API
    WEB -->|"sends page events"| ANALYTICS