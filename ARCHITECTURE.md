# FoodFusion System Design

## Summary

FoodFusion is a content + social platform for recipes, video shows, chefs, and community.

---

## 1. High-level architecture / components

- Frontend
  - Public website (responsive).
  - Client-side routing, server-side rendering(Laravel - PHP).
- Backend
  - RESTful API, user auth, content service, media service (uncertain), search service.
- Database(s)
  - Relational DB (MySql) for structured data;
<!--NoSQL (Mongo/Elastic) for flexible content/comments; Redis for caching and rate-limiting.
-->
- Media & Video
  - Limited media format for scalability (e.g., restrict upload size/duration, optimize formats, lazy-load images/videos).
  - Cloud storage (S3), CDN (CloudFront), video transcoding (AWS Elemental / Mux).
- Search & Recommendations
  - ElasticSearch/OpenSearch for search and faceted filtering.
  - Recommendation engine (collaborative + content-based) — Python microservice.
- Analytics & Personalization
  - Event pipeline (Segment / Snowplow) → data warehouse (BigQuery).
- Third-party
  - Social login (Google, Facebook, Apple), email (SendGrid), image optimization (Imgix).
- Admin & Moderation
  - Admin panel for content moderation, analytics, user management.

---

## 2. Main user roles

- Guest / Anonymous visitor
- Registered user / Home cook
- Verified Creator / Chef / Brand — a Registered user recognized with a badge (e.g., blue check): can access creator tools
- Admin / Platform manager

---

## 3. Sitemap (top-level) — estimated pages ≈ 30 (configurable)

1. Home (landing)
2. Explore / Browse (discover)
3. Search results (recipes, videos, chefs, articles)
4. Recipe page (detailed)
5. Video page (show/episode)
6. Chef profile / Creator page
7. Collection / Channel page (e.g., “Vegan”, “Holiday”)
8. Category pages (e.g., “Desserts”)
9. Community / Forums / Q&A
10. Saved / Favorites / Collections (user)
11. My Recipes (user-submitted drafts)
12. Create / Submit recipe (editor)
13. Recipe editor (advanced)
14. Notifications / Inbox [uncertain]
15. Settings (profile, preferences, dietary)
16. Contributor dashboard / Analytics
17. Admin dashboard (content moderation, site config)
18. Privacy / Terms / About / Contact
19. Help / FAQ / Tutorials
20. Error / 404 / Maintenance pages
21. API docs (optional)
22. Accessibility options page
23. Multi-lingual/localization switcher pages
24. Developer/Partners (integrations)

---

## 4. Page-by-page: what each page contains (concise spec)

### 1 — Home (landing)

- Hero: trending recipe/show, CTA (subscribe, explore).
- Personalized rows: “Recommended for you”, “Because you liked X”.
- Trending categories, seasonal collections.
- Featured video episode.
- Top chefs/creators carousel.
- Footer with quick links.

### 2 — Explore / Browse

- Category filters (cuisine, meal, diet, time, difficulty).
- Sort (popular, new, rating, prep time).
- Grid list with cards (image, title, time, rating, badges).
- Toggle to view Videos / Recipes.

### 3 — Search results

- Unified results: tabs for Recipes, Videos, Chefs, Articles, Products.
- Faceted filters and autocomplete suggestions.
- Highlighted query match and synonyms.

### 4 — Recipe page (core)

- Header: title, cover image/video, chef name + follow, rating, cook time, servings, dietary badges.
- Quick actions: Save, Share, Print, Add to Planner, Scale servings.
- Ingredients list (grouped).
- Step-by-step instructions (expandable steps, timers, estimated time per step).
- Nutrition panel (calories, macros) with per-serving / per-recipe toggle.
- Cooking video (embedded) + time stamps jump-to-step.
- Tools & pantry substitutes.
- Comments / Q&A section (with accepted answer).
- Related recipes and variants (e.g., vegetarian, gluten-free).
- Schema.org metadata for SEO (Recipe schema).
- Revision history (for creator/author).

### 5 — Video page (show/episode)

- Video player with chapters and thumbnails.
- Episode details, transcript, recipe link(s).
- Related episodes and playlists.
- Creator info and social links.
- Timestamps linking to steps in recipe.

### 6 — Chef / Creator profile

- Bio, social links, follower count.
- Featured recipes and videos.
- Badges/verification.
- Creator’s collections and schedule.
- “Hire/Book” or contact CTA (optional).

### 7 — Collection / Channel page

- Banner + description.
- Grid of content (recipes, videos).
- Subscribe to collection.

### 8 — Category page

- Featured recipes and popular creators in that category.
- Educational content or guides (e.g., "Baking Basics").

### 9 — Community / Forums / Q&A

- Threads by topic (techniques, substitutions).
- Upvotes, accepted answers, moderation.
- Local meetups/events.

### 10 — Saved / Collections

- User-created collections (e.g., “Weeknight meals”).
- Shared public collections.

### 11 — My Recipes

- Drafts and published recipes, analytics (views, saves).
- Edit and version control.

### 12 — Create / Submit recipe

- Guided form: title → time → tags → ingredients → steps → media → nutrition → publish controls.
- Autosave, preview, collaboration invite.
- Moderation queue for new creators.

### 13 — Recipe editor (advanced)

- WYSIWYG for steps + rich media insertion.
- Structured ingredient inputs (quantity, unit, descriptor, optional).
- Import from URL, paste, or CSV.
- Schema validation (for completeness and SEO).

### 14 — Notifications / Inbox

- Comments, replies, mentions, planner reminders.

### 15 — Settings / Preferences

- Profile info, dietary preferences (vegan, allergies), measurement units, notification prefs.
- Privacy and connected accounts.

### 16 — Contributor dashboard

- Content performance (views, saves), payout, editorial notes, scheduling.

### 17 — Admin dashboard

- Content moderation, user management, flagged posts, analytics, A/B testing controls.

### 18–24 — Legal, help, misc

- Standard pages: terms, privacy, API docs, multi-lingual, error pages.

---

## 5. Recipe data model / schema (authoritative)

A recipe should be structured (for UI, scaling, and search).

Example (conceptual):

    {
      "id": "UUID",
      "title": "string",
      "subtitle": "string (optional)",
      "slug": "string",
      "description": "string (short)",
      "chefId": "UUID",
      "contributors": ["userId"],
      "images": [{ "url": "string", "alt": "string", "caption": "string" }],
      "heroVideo": { "url": "string", "duration": "seconds", "thumbnails": [] },
      "prepTime": "minutes",
      "cookTime": "minutes",
      "totalTime": "minutes (computed)",
      "servings": "number",
      "nutrition": {
        "calories": 0, "fat": 0, "carbs": 0, "protein": 0, "fiber": 0, "sugar": 0, "sodium": 0
      },
      "ingredients": [
        {
          "groupId": "optional",
          "text": "2 cups flour, sifted",
          "quantity": 2,
          "unit": "cup",
          "ingredientRef": "ingredientId",
          "optional": false,
          "notes": "sifted"
        }
      ],
      "steps": [
        {
          "order": 1,
          "description": "string",
          "media": [],
          "duration": "minutes",
          "timer": { "start": 0, "end": 0 },
          "temperature": "180°C"
        }
      ],
      "tags": ["italian", "dessert", "gluten-free"],
      "cuisine": "string",
      "mealType": ["breakfast", "dinner"],
      "difficulty": "easy|medium|hard",
      "ratings": { "avg": 0.0, "count": 0 },
      "reviews": [],
      "commentsEnabled": true,
      "publishedAt": "datetime",
      "visibility": "public|unlisted|private",
      "language": "string",
      "relatedRecipes": [],
      "variants": [{ "id": "UUID", "modifications": "string" }],
      "schemaOrg": "JSON-LD",
      "metadata": { "seoTitle": "string", "seoDescription": "string" },
      "moderationStatus": "approved|pending|rejected",
      "analytics": { "views": 0, "saves": 0, "conversions": 0 }
    }

- Ingredient canonicalization
  - Maintain a canonical Ingredient DB (name variants, synonyms, nutritional data).
  - Use for search, substitution suggestions, grocery shopping, nutrition calc.
- Steps & timings (optional)
  - Allow timestamps per step to sync with video chapters.
  - Allow each step to include micro-actions (start timer, set temp).
- Scaling the recipe (optional)
  - Scaling algorithm: scale quantities proportionally, normalize units, respect min/max granularity (e.g., “1 egg” rounds to integer or suggests ratios).

---

## 6. How recipes are constructed (author & UX flow)

1. Create metadata: title, description, tags, cuisine, servings.
2. Add ingredients: structured entry with quantity, unit, linked canonical ingredient, optional flag, group label (e.g., Dough, Filling).
3. Add steps: numbered, with rich text, images, and optional timers/temperature.
4. Add media: hero image, step images, short video clips (mobile-first capture recommended).
5. Nutrition: auto-calc from ingredients (editable by author).
6. SEO & Schema: automatic JSON-LD generation; author can customize.
7. Preview: desktop / mobile / printable view (print-friendly CSS).
8. Publish options: publish now, schedule, or submit for editorial review.
9. Post-publish: platform generates related content, pushes to subscribers, indexes in search.

---

## 7. How things link (navigation & data flows)

- Global navigation → Home / Search / Explore / Profile.
- From a recipe, links to:
  - Chef profile (author)
  - Related recipes & variants
  - Video (if exists)
  - Products (ingredients/tools)
  - Comments and Q&A
  - Add to planner / shopping list
- Search results link to unified content; facets navigate within current query (URL reflects filters).
- User actions feed into recommendation engine: saves, view time, likes, shares.
- Events (user interactions) stream to analytics for personalization models.
- Admin links: flagged content → moderation UI → user/recipe actions (ban, edit, remove).
- Commerce links: recipe ingredient → product page → checkout.

---

## 8. Key microfeatures & UX details

- Inline timers with push notifications on completion.
- Step-by-step mode: full-screen cooking mode with large fonts, voice control, hands-free gestures.
- Substitutions: clickable ingredient shows substitutes and why. (uncertain)
- Scaling toggle: change servings and auto-scale ingredients. (optional)
- Save to collection & shareable cookbooks.
- Print & save as PDF (print CSS).
- Accessibility: alt texts, high-contrast mode, screen-reader friendly step navigation. (optional)
- Localization: unit conversions, translated metadata.

---

## 9. API & integrations (not confirmed)

<!-- TODO: Review thie part to confirm -->
- API endpoints (not confirmed)
  - GET /api/recipes?query=&tags=&cuisine=&diet=
  - GET /api/recipes/:id
  - POST /api/recipes (auth/creator)
  - PUT /api/recipes/:id
  - POST /api/users/:id/save
  - GET /api/search/suggest?q=
  - POST /api/planner/week
- Integrations
  - Smart devices: oven or timer APIs (IoT integration). [uncertain]
  - Nutrition databases (USDA) for nutrition calculation. [uncertain]
  - Video platforms (Mux, Vimeo) for hosting & analytics. [uncertain]
  - Social sharing integration (Open Graph tags).

---

## 10. Moderation & trust & safety (optional)

- Auto-moderation: profanity filters, spam detectors.
- Manual moderation queue for new creators.
- Community moderation: upvote/downvote, report.
- Creator verification process for high-profile chefs.
- Copyright handling for images/videos with DMCA flow.

---

## 11. Personalization & recommendations

<!-- TODO: review later -->

- Signals: views, saves, time-on-recipe, clicks, completions.
- Models:
  - Content-based: match ingredients, cuisine, tags.
  - Collaborative filtering: users who liked X also liked Y.
  - Session-based suggestions: next step or side dish suggestions while cooking.
- A/B testing for homepage rows and email recommendations.

---

## 12. Non-functional requirements

- Scalability — stateless services, autoscaling, CDN.
- Performance — SSR and pre-render important pages, cache popular recipes.
- SEO — structured data (JSON-LD), server render, canonical URLs.
- Availability — SLA 99.9%+, multi-AZ deployments.
- Security — OAuth, rate-limits, input sanitization, content uploads scanned.
- Data privacy — GDPR, CCPA options, data export.

---

## 13. Metrics & analytics to track

- User metrics: DAU/MAU, retention, recipe completion rate.
- Content metrics: views, saves, avg rating, conversions (shop).
- Engagement: comments per recipe, time spent in step-by-step mode.
- Revenue: subscription ARPU, product sales.

---

## 14. MVP scope

1. Core: publish & view recipes (structured schema), search, chef profiles, responsive web.
2. Basic social: save, comments, follow.
3. Video support (upload & stream), simple recommendations (popularity).
4. Admin moderation UI.

MVP pages: ~10 (Home, Explore, Search, Recipe, Video, Chef, Create Recipe, Saved, Auth/Settings, Admin).

---

## 15. Page/component (UI building estimate)

- Unique components: ~60 (header, footer, recipe card, video player, ingredient item, step item, planner card, product card, modal types, forms)

---

## 16. User flows (short)

- Guest → Search “chicken curry” → filter “30m” → open recipe → step-by-step → add missing ingredient to shopping list → send to Instacart → checkout.
- Creator → Create recipe → upload video → submit for review → published → notify subscribers.

---

## 17. Security & privacy notes

- Store PII encrypted, minimal retention, opt-out for personalization.
- Rate-limit uploads, validate media types, virus-scan.
- CSRF/XSS protection, CSP headers for front-end.

---

## 18. Optional advanced features (future roadmap)

- Live cooking classes / WebRTC sessions.
- Multi-user collaborative meal planning.
- AI features: auto-generate recipe variations, convert recipes to shopping lists, autocomplete ingredients and quantities, image-to-recipe parsing.
- Voice assistant skill (Alexa/Google) for hands-free cooking.
- Nutrition goals & integration with fitness trackers.

---

## 19. Deliverables (pick one next)

- Detailed Figma sitemap + low-fidelity wireframes for core pages.
- Database schema (SQL) and sample queries for recipes, search indexes.
- REST API spec.
- Prioritized MVP backlog with user stories and acceptance criteria.
- Sample Recipe JSON-LD (schema.org) that maps to the model above.
