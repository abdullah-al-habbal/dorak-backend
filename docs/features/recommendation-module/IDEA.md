# Idea

**Slug:** recommendation-module
**Type:** feature
**Created:** 2026-07-19

## In the user's words

Build Phase 5 — Recommendation Module. Personalise explore results for clients using explicit signals (favorites, history, face-shape compatibility) and vector embeddings (pgvector + OpenAI).

## What this changes for users

Explore results (branches, barbers) sorted by relevance, not just geographic proximity. New filters let clients narrow results by service type, price, rating, availability, and face-shape compatibility.

## Research skipped: no externals

All dependencies known: laravel/ai v0.9.1 installed, OpenAI embeddings configured, pgvector needs `CREATE EXTENSION vector;`. No external API research needed.

## Open questions

- Hosting PostgreSQL version? Need ≥14 for pgvector.
- OpenAI embedding API key configured? text-embedding-3-small is default.
- Should weights be configurable per-tenant or global?
- Client preferences UI — part of this phase or separate?
