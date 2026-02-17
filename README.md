# APIura

A drop-in interactive API documentation and testing tool for any Laravel application. Browse, test, save, and annotate your API endpoints — all from a single page. No npm, no build step.

![Laravel 10+](https://img.shields.io/badge/Laravel-10%2B-red) ![PHP 8.2+](https://img.shields.io/badge/PHP-8.2%2B-blue) ![License MIT](https://img.shields.io/badge/License-MIT-green)

---

## What You Get

- **IDE-Style Interface** — VS Code-inspired layout with activity bar, sidebar, tabbed editor, and status bar
- **Interactive API Browser** — All your API endpoints organized by tags, with search and filtering
- **Request Builder** — Build and fire requests with path params, query params, headers, and JSON body
- **Response Preview** — Syntax-highlighted JSON responses with status codes and timing
- **Tabbed Editing** — Open multiple endpoints in tabs with independent state per tab
- **Request History** — Local history of all requests sent, persisted in localStorage
- **Save Requests** — Persist requests with their responses for team reference
- **Comments** — Annotate saved requests by role (backend, frontend, QA)
- **API Flows** — Create multi-step request sequences with variable extraction between steps
- **Modules** — Organize saved requests and flows into hierarchical folders (up to 3 levels deep)
- **Database Schema Viewer** — Browse all your tables, columns, indexes, and foreign keys
- **Environment Profiles** — Save and switch between base URL / auth token configurations
- **Export to Markdown** — Download your API docs + DB schema as a ZIP
- **Export OpenAPI** — Export your spec with saved cases, examples, or clean
- **Telescope Integration** — View recent request history if Telescope is installed
- **Dark/Light Mode** — Toggle theme preference with full CSS variable theming
- **Keyboard Shortcuts** — Ctrl+Enter to send, Ctrl+S to save, Ctrl+L to clear
- **Import cURL** — Paste a cURL command to populate the request builder
- **Zero Dependencies** — Single Blade file using Alpine.js + Tailwind from CDN

---

## Requirements

- **Laravel 10+** (tested on 11 and 12)
- **PHP 8.2+**
- **[Scramble](https://github.com/dedoc/scramble)** (recommended) — for auto-generating the OpenAPI spec from your routes

---

## Installation

### Step 1: Install the package

```bash
composer require apiura/apiura --dev
```

The package uses Laravel's auto-discovery, so the service provider is registered automatically.

### Step 2: Publish the config (optional)

```bash
php artisan vendor:publish --tag=apiura-config
```

### Step 3: Run migrations

```bash
php artisan migrate
```

### Step 4: Generate your API spec

```bash
# Install Scramble if you haven't already
composer require dedoc/scramble

# Generate the OpenAPI spec file
php artisan scramble:export
```

This creates `api.json` in your project root. APIura reads this file to display your endpoints.

If Scramble is installed, APIura will generate the spec live — the static file is only used as a fallback.

### Step 5: Visit the explorer

```
http://your-app.test/apiura
```

Done.

---

## Configuration

All values can be set via `.env`. Publish the config file first with `php artisan vendor:publish --tag=apiura-config`.

### Environment Variables

```env
# ── APIura Configuration ──────────────────────────────────────

# Require login to access APIura (recommended for shared staging servers)
APIURA_REQUIRE_AUTH=false

# Path to the static OpenAPI JSON spec file (fallback when Scramble is not installed)
APIURA_SPEC_PATH=api.json

# Comma-separated list of APP_ENV values where APIura is accessible
# Production is ALWAYS blocked regardless of this setting
APIURA_ENVIRONMENTS=local,staging,testing

# Maximum requests per minute to APIura endpoints
APIURA_RATE_LIMIT=60

# Default items per page for saved requests and flows lists
APIURA_PER_PAGE=50

# Fully qualified class name of your User model
APIURA_USER_MODEL=App\Models\User

# Comma-separated table names to hide from the Database Schema viewer
APIURA_SCHEMA_EXCLUDE=migrations,password_reset_tokens,password_resets,failed_jobs,job_batches,jobs,cache,cache_locks,sessions,personal_access_tokens,telescope_*,saved_api_requests,saved_api_request_comments,saved_api_flows,apiura_modules
```

### Config Options

| Key | Env Variable | Default | Description |
|-----|-------------|---------|-------------|
| `require_auth` | `APIURA_REQUIRE_AUTH` | `false` | When `true`, adds Laravel's `auth` middleware. Users must be logged in. |
| `user_model` | `APIURA_USER_MODEL` | `App\Models\User` | Your User model class for saved request/comment ownership. |
| `spec_path` | `APIURA_SPEC_PATH` | `base_path('api.json')` | Path to the OpenAPI JSON spec file. |
| `allowed_environments` | `APIURA_ENVIRONMENTS` | `local,staging,testing` | Environments where the explorer is accessible. **Production is always blocked.** |
| `rate_limit` | `APIURA_RATE_LIMIT` | `60` | Max requests per minute to APIura endpoints. |
| `per_page` | `APIURA_PER_PAGE` | `50` | Items per page when listing saved requests and flows. |
| `schema_exclude_tables` | `APIURA_SCHEMA_EXCLUDE` | *(see above)* | Tables to hide from the DB Schema viewer. Supports wildcards. |

---

## Publishing Assets

```bash
# Publish config only
php artisan vendor:publish --tag=apiura-config

# Publish views (to customize the UI)
php artisan vendor:publish --tag=apiura-views

# Publish migrations (to modify them)
php artisan vendor:publish --tag=apiura-migrations
```

---

## Package Structure

```
apiura/
├── composer.json
├── LICENSE
├── README.md
├── config/
│   └── apiura.php
├── database/migrations/
│   └── 2024_01_01_000000_create_apiura_tables.php
├── resources/views/
│   └── apiura.blade.php                    # The entire SPA
├── routes/
│   └── apiura.php
└── src/
    ├── ApiuraServiceProvider.php
    ├── Console/Commands/
    │   └── GenerateApiDocs.php              # docs:generate artisan command
    ├── Http/
    │   ├── Controllers/
    │   │   ├── ApiuraController.php
    │   │   ├── ApiuraModuleController.php
    │   │   ├── SavedApiFlowController.php
    │   │   ├── SavedApiRequestCommentController.php
    │   │   └── SavedApiRequestController.php
    │   ├── Middleware/
    │   │   └── EnsureApiuraAccess.php
    │   ├── Requests/
    │   │   ├── StoreApiuraModuleRequest.php
    │   │   ├── StoreSavedApiFlowRequest.php
    │   │   ├── StoreSavedApiRequestCommentRequest.php
    │   │   ├── StoreSavedApiRequestRequest.php
    │   │   ├── UpdateApiuraModuleRequest.php
    │   │   ├── UpdateSavedApiFlowRequest.php
    │   │   └── UpdateSavedApiRequestRequest.php
    │   └── Resources/
    │       ├── ApiuraModuleResource.php
    │       ├── SavedApiFlowResource.php
    │       ├── SavedApiRequestCommentResource.php
    │       └── SavedApiRequestResource.php
    ├── Models/
    │   ├── ApiuraModule.php
    │   ├── SavedApiFlow.php
    │   ├── SavedApiRequest.php
    │   └── SavedApiRequestComment.php
    └── Services/
        ├── ModuleDuplicateDetectionService.php
        └── OpenApiExportService.php
```

---

## Database Tables

The migration creates 4 tables:

### `apiura_modules`

Hierarchical folders for organizing saved requests and flows.

| Column | Type | Nullable | Description |
|--------|------|----------|-------------|
| `id` | bigint | No | Primary key |
| `parent_id` | bigint | Yes | FK to self (cascade delete) |
| `name` | string | No | Module name |
| `description` | text | Yes | Description |
| `sort_order` | int | No | For drag-drop ordering |

### `saved_api_requests`

Saved API requests with their responses.

| Column | Type | Nullable | Description |
|--------|------|----------|-------------|
| `id` | bigint | No | Primary key |
| `user_id` | bigint | Yes | FK to users (optional) |
| `module_id` | bigint | Yes | FK to apiura_modules |
| `name` | string | Yes | Display name |
| `priority` | string | Yes | `low`, `medium`, `high`, `critical` |
| `team` | string | Yes | Team label |
| `method` | string | No | HTTP method |
| `path` | string | No | API path |
| `path_params` | json | Yes | Path parameters |
| `query_params` | json | Yes | Query parameters |
| `headers` | json | Yes | Request headers |
| `body` | json | Yes | Request body |
| `response_status` | int | Yes | HTTP status code |
| `response_headers` | json | Yes | Response headers |
| `response_body` | longtext | Yes | Full response |

### `saved_api_request_comments`

Comments attached to saved requests, tagged by team role.

| Column | Type | Nullable | Description |
|--------|------|----------|-------------|
| `id` | bigint | No | Primary key |
| `saved_api_request_id` | bigint | No | FK (cascade delete) |
| `user_id` | bigint | Yes | FK to users (optional) |
| `author_name` | string | Yes | Name if no user |
| `author_type` | enum | No | `backend`, `frontend`, `qa`, `other` |
| `content` | text | No | Comment text |
| `status` | string | No | `info`, `resolved`, `wontfix`, `warning`, `critical` |

### `saved_api_flows`

Multi-step API test sequences.

| Column | Type | Nullable | Description |
|--------|------|----------|-------------|
| `id` | bigint | No | Primary key |
| `module_id` | bigint | Yes | FK to apiura_modules |
| `name` | string | No | Flow name |
| `description` | text | Yes | Description |
| `steps` | json | No | Array of step configs |
| `default_headers` | json | Yes | Headers for all steps |
| `continue_on_error` | boolean | No | Continue after failure |

---

## Routes Reference

All routes are prefixed with `/apiura` and protected by the `EnsureApiuraAccess` middleware.

| Method | Path | Controller | Description |
|--------|------|------------|-------------|
| GET | `/apiura` | `ApiuraController@index` | Main explorer page |
| GET | `/apiura/db-schema` | `ApiuraController@dbSchema` | Database schema JSON |
| GET | `/apiura/export-md` | `ApiuraController@exportMarkdown` | Download docs as ZIP |
| GET | `/apiura/export-openapi/{mode}` | `ApiuraController@exportOpenApi` | Export OpenAPI JSON |
| GET | `/apiura/telescope` | `ApiuraController@telescopeEntries` | Request history |
| GET | `/apiura/telescope/{uuid}` | `ApiuraController@telescopeEntry` | Single request details |
| GET | `/apiura/saved-requests` | `SavedApiRequestController@index` | List saved requests |
| POST | `/apiura/saved-requests` | `SavedApiRequestController@store` | Save a request |
| GET | `/apiura/saved-requests/{id}` | `SavedApiRequestController@show` | Get saved request |
| PUT | `/apiura/saved-requests/{id}` | `SavedApiRequestController@update` | Update saved request |
| DELETE | `/apiura/saved-requests/{id}` | `SavedApiRequestController@destroy` | Delete saved request |
| GET | `/apiura/saved-requests/{id}/comments` | `...CommentController@index` | List comments |
| POST | `/apiura/saved-requests/{id}/comments` | `...CommentController@store` | Add comment |
| PUT | `/apiura/saved-requests/{id}/comments/{cid}` | `...CommentController@update` | Update comment |
| DELETE | `/apiura/saved-requests/{id}/comments/{cid}` | `...CommentController@destroy` | Delete comment |
| GET | `/apiura/modules` | `ApiuraModuleController@index` | List modules |
| POST | `/apiura/modules` | `ApiuraModuleController@store` | Create module |
| POST | `/apiura/modules/reorder` | `ApiuraModuleController@reorder` | Reorder modules |
| POST | `/apiura/modules/move-items` | `ApiuraModuleController@moveItems` | Move items |
| POST | `/apiura/modules/import-preview` | `ApiuraModuleController@importPreview` | Preview import |
| POST | `/apiura/modules/import-execute` | `ApiuraModuleController@importExecute` | Execute import |
| GET | `/apiura/modules/{id}` | `ApiuraModuleController@show` | Get module |
| PUT | `/apiura/modules/{id}` | `ApiuraModuleController@update` | Update module |
| DELETE | `/apiura/modules/{id}` | `ApiuraModuleController@destroy` | Delete module |
| GET | `/apiura/flows` | `SavedApiFlowController@index` | List flows |
| POST | `/apiura/flows` | `SavedApiFlowController@store` | Create flow |
| POST | `/apiura/flows/bulk` | `SavedApiFlowController@bulkStore` | Bulk create flows |
| POST | `/apiura/flows/bulk-delete` | `SavedApiFlowController@bulkDestroy` | Bulk delete flows |
| GET | `/apiura/flows/{id}` | `SavedApiFlowController@show` | Get flow |
| PUT | `/apiura/flows/{id}` | `SavedApiFlowController@update` | Update flow |
| DELETE | `/apiura/flows/{id}` | `SavedApiFlowController@destroy` | Delete flow |

---

## Security

### Production is Always Blocked

The `EnsureApiuraAccess` middleware **always returns 404 in production**, regardless of any config setting. There is no override flag.

### Environment Allow-list

After the production check, the middleware checks if `APP_ENV` is in the `allowed_environments` config (default: `local`, `staging`, `testing`).

### Optional Authentication

Set `APIURA_REQUIRE_AUTH=true` to require login via Laravel's `auth` middleware.

### Rate Limiting

All routes are throttled using the `apiura` rate limiter (default: 60 req/min). The package registers this rate limiter automatically.

### CSRF

Since these are web routes, Laravel's CSRF protection applies automatically. The JavaScript handles CSRF tokens by reading the `XSRF-TOKEN` cookie.

---

## Optional Integrations

### Telescope

If [Laravel Telescope](https://laravel.com/docs/telescope) is installed, the explorer automatically shows a "Telescope" option with recent API requests. No configuration needed.

### Scramble

[Scramble](https://github.com/dedoc/scramble) is the recommended tool for auto-generating the OpenAPI spec. If installed, APIura generates the spec live. Otherwise, set `APIURA_SPEC_PATH` to your static spec file.

---

## Customization

### Change the URL prefix

Publish the routes and modify the prefix:

```bash
php artisan vendor:publish --tag=apiura-views
```

Or override the routes in your own `routes/web.php`.

### Custom User model

If your User model is not `App\Models\User`, set it in your `.env`:

```env
APIURA_USER_MODEL=App\Models\Admin
```

### Publish views for UI customization

```bash
php artisan vendor:publish --tag=apiura-views
```

Views will be copied to `resources/views/vendor/apiura/`.

---

## Troubleshooting

### "404 Not Found" when visiting `/apiura`

1. Check your `APP_ENV` — must be `local`, `staging`, or `testing` (not `production`)
2. Run `php artisan route:list --name=apiura` to verify routes are registered
3. Make sure the package is installed: `composer show apiura/apiura`

### Empty endpoint list

1. Install Scramble: `composer require dedoc/scramble`
2. Generate the spec: `php artisan scramble:export`
3. Verify `api.json` exists in your project root

### CSRF token errors on POST/PUT/DELETE

The explorer expects Laravel's default `XSRF-TOKEN` cookie. Ensure the `ValidateCsrfToken` middleware is active (default in Laravel).

---

## Development (local path)

To develop the package alongside your Laravel app, use Composer's path repository:

```json
// In your Laravel app's composer.json:
"repositories": [
    {
        "type": "path",
        "url": "../apiura-kit"
    }
]
```

Then: `composer require apiura/apiura --dev`

This creates a symlink — changes in the package directory are instantly reflected.

---

## License

MIT — see [LICENSE](LICENSE) for details.
