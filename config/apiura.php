<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Require Authentication
    |--------------------------------------------------------------------------
    |
    | When enabled, all APIura routes will require authentication
    | via Laravel's auth middleware. Recommended for shared staging environments.
    |
    */
    'require_auth' => env('APIURA_REQUIRE_AUTH', false),

    /*
    |--------------------------------------------------------------------------
    | User Model
    |--------------------------------------------------------------------------
    |
    | The fully qualified class name of your User model. Used for the
    | user relationship on saved requests and comments.
    |
    */
    'user_model' => env('APIURA_USER_MODEL', 'App\\Models\\User'),

    /*
    |--------------------------------------------------------------------------
    | OpenAPI Spec Path
    |--------------------------------------------------------------------------
    |
    | Path to the OpenAPI JSON spec file used by the APIura.
    |
    */
    'spec_path' => env('APIURA_SPEC_PATH', base_path('api.json')),

    /*
    |--------------------------------------------------------------------------
    | Allowed Environments
    |--------------------------------------------------------------------------
    |
    | The application environments where APIura is accessible.
    | Production is always blocked regardless of this setting.
    |
    */
    'allowed_environments' => explode(',', env('APIURA_ENVIRONMENTS', 'local,staging,testing')),

    /*
    |--------------------------------------------------------------------------
    | Rate Limit
    |--------------------------------------------------------------------------
    |
    | Maximum number of requests per minute to APIura endpoints.
    |
    */
    'rate_limit' => (int) env('APIURA_RATE_LIMIT', 60),

    /*
    |--------------------------------------------------------------------------
    | Pagination Per Page
    |--------------------------------------------------------------------------
    |
    | Default number of items per page for list endpoints.
    |
    */
    'per_page' => (int) env('APIURA_PER_PAGE', 50),

    /*
    |--------------------------------------------------------------------------
    | Schema Exclude Tables
    |--------------------------------------------------------------------------
    |
    | Tables to exclude from the Database Schema viewer and markdown export.
    | Supports exact names and wildcard prefixes (e.g. 'telescope_*').
    | Set APIURA_SCHEMA_EXCLUDE to a comma-separated list to override.
    |
    */
    'schema_exclude_tables' => array_filter(explode(',', env(
        'APIURA_SCHEMA_EXCLUDE',
        'migrations,password_reset_tokens,password_resets,failed_jobs,job_batches,jobs,cache,cache_locks,sessions,personal_access_tokens,telescope_*,saved_api_requests,saved_api_request_comments,saved_api_flows,apiura_modules'
    ))),

];
