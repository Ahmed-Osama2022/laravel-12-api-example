# About Page API

A small, headless Laravel 12 API that serves the content of a single **About** page
to the Vue 3 frontend running from teh repo [Guthub Repo link](https://github.com/Ahmed-Osama2022/vue-example-app)`.

The frontend owns presentation; this project owns the content, the schema and the
seeded data. One public, versioned endpoint returns the whole page as a single JSON
document shaped exactly like `api.json`.

- **Framework:** Laravel 12 (PHP >= 8.2)
- **Database:** SQLite (default) — any Laravel-supported driver works
- **Auth:** Laravel Sanctum installed; the About endpoint is public
- **API version:** `/api/v1`

---

## Requirements

| Tool     | Version | Notes                                                         |
| -------- | ------- | ------------------------------------------------------------- |
| PHP      | 8.2+    | with `pdo_sqlite` / `sqlite3` when using the default database |
| Composer | 2.x     |                                                               |
| SQLite   | 3.x     | the file lives at `database/database.sqlite`                  |

---

## Quick start

```bash
cd laravel-12-api-example

# 1. Install PHP dependencies
composer install

# 2. Create the environment file and application key
cp .env.example .env
php artisan key:generate

# 3. Create the SQLite database file
touch database/database.sqlite

# 4. Create the tables and seed the About page content
php artisan migrate --seed

# 5. Serve the API
php artisan serve
```

The API is now available at **`http://localhost:8000/api/v1/about`**.

Verify it:

```bash
curl -s http://localhost:8000/api/v1/about | head -c 300
```

Expected: HTTP `200` with a JSON body starting with `{"data":{"meta":...`.

> `composer run setup` performs steps 1–4, plus `npm install` and `npm run build`.
> Use it only if you also want the Vite assets built for this app. For API-only work,
> the manual steps above are enough.

Prefer a single terminal during development? After `npm install`, `composer run dev`
starts the API server, queue listener, log tail and Vite dev server together.

### Using a different database

Set the connection in `.env` and re-run the migrations:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=about_api
DB_USERNAME=root
DB_PASSWORD=
```

```bash
php artisan migrate --seed
```

---

## API reference

### `GET /api/v1/about`

Returns the published About page document. No authentication required.

| Property          | Value                                                                    |
| ----------------- | ------------------------------------------------------------------------ |
| Response envelope | `{ "data": { ... } }`                                                    |
| Success           | `200 OK`, `Content-Type: application/json`                               |
| Not found         | `404 Not Found` — the page row is missing (usually an unseeded database) |
| CORS              | allowed by Laravel's default config for `api/*`                          |

Response shape:

| Key               | Type   | Description                                                                                     |
| ----------------- | ------ | ----------------------------------------------------------------------------------------------- |
| `meta`            | object | `title` — document title                                                                        |
| `hero`            | object | `badge`, `title`, `highlight`, `description`, `primaryCta{label,to}`, `secondaryCta{label,to?}` |
| `stats`           | array  | `{ value, label }` items, ordered                                                               |
| `mission`         | object | `eyebrow`, `title`, `paragraphs[]`, `quote{text,initials,author,role}`                          |
| `valuesSection`   | object | section heading `{ eyebrow, title }`                                                            |
| `values`          | array  | `{ icon, title, text }` items, ordered                                                          |
| `timelineSection` | object | section heading `{ eyebrow, title }`                                                            |
| `timeline`        | array  | `{ year, title, text }` items, ordered                                                          |
| `teamSection`     | object | section heading `{ eyebrow, title, description }`                                               |
| `team`            | array  | `{ name, role, initials, avatarColor }` items, ordered                                          |
| `cta`             | object | `{ title, description }`                                                                        |
| `contact`         | object | `{ email, footerNote }`                                                                         |

Trimmed example:

```json
{
  "data": {
    "meta": { "title": "About us — Example" },
    "hero": {
      "badge": "About us",
      "title": "We build tools that",
      "highlight": "get out of the way",
      "primaryCta": { "label": "Try the product", "to": "/" },
      "secondaryCta": { "label": "Say hello" }
    },
    "stats": [{ "value": "2021", "label": "Founded" }],
    "mission": { "eyebrow": "Our mission", "paragraphs": ["..."] },
    "valuesSection": { "eyebrow": "What we believe", "title": "Four rules we don't break" },
    "values": [{ "icon": "bolt", "title": "Move fast", "text": "..." }],
    "timelineSection": { "eyebrow": "Our story", "title": "From side project to daily habit" },
    "timeline": [{ "year": "2021", "title": "A weekend prototype", "text": "..." }],
    "teamSection": { "eyebrow": "The people", "title": "Small team, big care" },
    "team": [{ "name": "Amara Okafor", "role": "Co-founder & CEO", "initials": "AO", "avatarColor": "rose" }],
    "cta": { "title": "Let's make work feel lighter", "description": "..." },
    "contact": { "email": "hello@example.com", "footerNote": "© 2026 Example Inc." }
  }
}
```

The complete, canonical payload is in [`api.json`](api.json).

### Other routes

| Method | URI             | Middleware     | Description                                     |
| ------ | --------------- | -------------- | ----------------------------------------------- |
| GET    | `/api/v1/about` | public         | About page content                              |
| GET    | `/api/user`     | `auth:sanctum` | Skeleton route returning the authenticated user |
| GET    | `/up`           | public         | Framework health check                          |

### Versioning

`v1` is frozen: clients can rely on its shape. Any breaking change ships as
`/api/v2` behind a new `Route::prefix('v2')` group and a new controller namespace
(`App\Http\Controllers\Api\V2`).

---

## Data model

The About page is one record in `pages` (keyed by the unique slug `about`), with
four ordered child collections. Ordering is preserved by `sort_order`.

```
pages (slug: "about")
├── page_stats            value, label
├── page_values           icon, title, text
├── page_timeline_entries year, title, text
└── page_team_members     name, role, initials, avatar_color
```

| Table                   | Key columns                                                                                                     |
| ----------------------- | --------------------------------------------------------------------------------------------------------------- |
| `pages`                 | `slug` (unique), `meta_title`, `hero_*`, `mission_*`, section headings, `cta_*`, `contact_email`, `footer_note` |
| `page_stats`            | `page_id` (FK, cascade), `value`, `label`, `sort_order`                                                         |
| `page_values`           | `page_id` (FK, cascade), `icon`, `title`, `text`, `sort_order`                                                  |
| `page_timeline_entries` | `page_id` (FK, cascade), `year`, `title`, `text`, `sort_order`                                                  |
| `page_team_members`     | `page_id` (FK, cascade), `name`, `role`, `initials`, `avatar_color`, `sort_order`                               |

`mission_paragraphs` is stored as JSON and cast to an array on the `Page` model.

---

## Project structure

```
app/
├── Http/
│   ├── Controllers/Api/V1/AboutController.php   single-action controller
│   └── Resources/AboutResource.php              JSON shape for the frontend
├── Models/                                      Page + four child models
database/
├── migrations/                                  pages + page_* tables
└── seeders/
    ├── AboutPageSeeder.php                      all About content, re-runnable
    └── DatabaseSeeder.php                       calls AboutPageSeeder
routes/api.php                                   /api/v1/about
api.json                                         canonical payload reference
```

---

## Seeding content

All copy lives in `database/seeders/AboutPageSeeder.php`. To change the page content,
edit the seeder (or the database), then refresh:

```bash
# from scratch
php artisan migrate:fresh --seed

# only the About page (safe to run repeatedly)
php artisan db:seed --class=AboutPageSeeder
```

The seeder is idempotent: it matches the page by slug and replaces its child
collections inside a transaction, so repeated runs never duplicate rows.

---

## Testing

```bash
php artisan test
```

The suite currently contains the Laravel skeleton tests. There is no feature test
covering `/api/v1/about` yet; add one before making this endpoint part of a release
gate.

---

## Code style

Formatting follows `.editorconfig`: UTF-8, LF, final newline, **2-space indentation**.

```bash
./vendor/bin/pint --dirty
```

Pint is installed as a dev dependency; the `.editorconfig` values are the source of
truth for indentation.

---

## Frontend integration

The Vue app at `http://localhost:5173` can consume the endpoint directly:

```js
const { data } = await axios.get('http://localhost:8000/api/v1/about')

// `data.data` is the full page document
console.log(data.data.hero.title)
```

Or proxy `/api` through Vite so the browser stays on one origin — add to the Vue
app's `vite.config.js`:

```js
server: {
  proxy: {
    '/api': 'http://localhost:8000',
  },
}
```

Laravel's default CORS configuration already allows `api/*` requests. For production,
publish and restrict it:

```bash
php artisan config:publish cors
```

then set `allowed_origins` to your real frontend origin instead of `*`.

---

## Troubleshooting

| Symptom                              | Cause / fix                                                                           |
| ------------------------------------ | ------------------------------------------------------------------------------------- |
| `404` on `/api/v1/about`             | The page row is missing. Run `php artisan migrate --seed`.                            |
| `500` "Database file does not exist" | Create it: `touch database/database.sqlite`, then migrate.                            |
| Changes to `.env` ignored            | `php artisan config:clear`.                                                           |
| Routes 404 after deploy              | `php artisan route:clear` (or re-cache with `php artisan route:cache`).               |
| CORS error in the browser            | Check the request uses `/api/*`; publish `config/cors.php` and set `allowed_origins`. |
| Port 8000 in use                     | `php artisan serve --port=8001`.                                                      |
