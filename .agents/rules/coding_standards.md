# Coding Standards: GitScribe

## 1. PHP & Laravel Conventions
- **PHP Version:** Strictly use PHP 8.2+ features. E.g., `readonly` classes, typed properties, match expressions.
- **PSR-12 Standard:** All PHP code must adhere to PSR-12 coding style.
- **Dependency Injection:** Always use constructor injection for services and dependencies in Controllers and Services.
- **Facades:** Use Laravel Facades sparingly. Prefer injecting contracts when building robust services.
- **Database Querying:** NEVER use raw SQL (e.g., `DB::statement()`). Always use the Eloquent ORM.
- **Validation:** Always use Form Requests (`php artisan make:request`) rather than validating directly in the controller method.

## 2. Frontend / Blade
- **Structure:** Use Laravel Blade Components (`x-`) for reusable UI elements.
- **Styling:** GitScribe relies on **Bootstrap 5**. Do not add utility classes from Tailwind or custom CSS unless the problem cannot be solved using standard Bootstrap classes.
- **Assets:** Use Vite to compile any SCSS or JS (`@vite(['resources/css/app.css', 'resources/js/app.js'])`).

## 3. GitHub API Integration
- Wrap GitHub API calls (using the `Http` facade or dedicated client) in `try...catch` blocks.
- Respect Rate Limits. If applicable, check headers for `X-RateLimit-Remaining` and log warnings.
- Securely retrieve tokens via the authenticated user's model (`$user->github_token`).

## 4. AI / Gemini Integration
- **Context is King:** Always pass as much repository context as possible (file trees, language usage, description) into the Gemini prompt.
- **Prompt Isolation:** The raw prompt text should remain well-structured within `GeminiService.php` or a dedicated prompt config file. Do not mix prompt strings heavily with business logic.
- **Timeouts:** API calls to Gemini can take a while. Ensure the HTTP request timeout is appropriately set (`Http::timeout(60)`).
