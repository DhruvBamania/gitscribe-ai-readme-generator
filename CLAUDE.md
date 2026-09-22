# Claude Instructions for GitScribe

## Context
GitScribe is a Laravel 12 application. Its main feature is authenticating users via GitHub (Socialite), fetching their repositories, generating a README using Google Gemini, and committing/PRing that README back to their GitHub repo.

## AI Preferences & Code Style
- **PHP:** Use PHP 8.2+ features (readonly properties, typed properties, match expressions).
- **Laravel:** Prefer modern Laravel 11/12 syntax.
- **Frontend:** Use Blade components where possible. Styling is handled with Bootstrap 5 via Vite. Avoid writing plain CSS unless absolutely necessary.
- **Error Handling:** When working with GitHub API or Gemini API (`app/Services/`), wrap calls in try/catch blocks and use Laravel's `Log::error()` to record failures.

## Important Directories
- `app/Http/Controllers`: Contains `DashboardController.php` and `PullRequestController.php` (Git operations).
- `app/Services/GeminiService.php`: Core logic for interacting with Gemini API.
- `resources/views/`: Blade templates using Bootstrap 5.

## Testing
- Tests are located in `tests/Feature` and `tests/Unit`. If you write a new feature, especially an API integration, write a basic test or mock for it.
