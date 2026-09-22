# AI Development Guide: GitScribe

This guide provides deep technical context to help AI assistants understand the architecture and flow of GitScribe.

## 1. System Architecture Overview

GitScribe operates on a simple but effective workflow:
1. **OAuth Authentication:** Users authenticate via GitHub using `laravel/socialite`.
2. **Dashboard:** The user sees a list of their GitHub repositories. This requires fetching from GitHub's REST API using the user's stored access token.
3. **Generation Trigger:** The user selects a repository. GitScribe fetches repository metadata (language, tree, description).
4. **AI Processing:** `app/Services/GeminiService.php` constructs a specialized prompt containing the metadata and sends it to the Gemini API.
5. **Git Operations:** `app/Http/Controllers/PullRequestController.php` (or similar Git service) creates a new branch on the target repository, commits the generated markdown, and opens a Pull Request.

## 2. Core Components

### `GeminiService.php`
- Location: `app/Services/GeminiService.php`
- Purpose: Communicates with Google's Gemini API.
- Design: It builds a highly structured prompt to enforce markdown formatting, badges, and the required watermark footer.

### `PullRequestController.php`
- Location: `app/Http/Controllers/PullRequestController.php`
- Purpose: Handles the complex GitHub API flow for opening a PR.
- Flow: 
  1. Get main branch SHA.
  2. Create a new ref (branch).
  3. Create a blob (the README content).
  4. Create a tree (incorporating the blob).
  5. Create a commit.
  6. Update branch ref.
  7. Open Pull Request.

## 3. Database Schema Concepts

The database is primarily used for session management, user tracking, and caching OAuth tokens.
- **Users Table:** Stores GitHub ID, Name, Email, Avatar, and `github_token` (and `github_refresh_token`).
- Tokens must be stored securely and refreshed if expired.

## 4. Frontend Conventions

- GitScribe uses **Bootstrap 5** loaded via CDN or compiled via Vite.
- Blade views (`resources/views/`) are structured into layouts (e.g., `layouts/app.blade.php`).
- Ensure all forms use `@csrf` tokens.
- For interactive elements, Vanilla JS or Alpine.js (if added) is preferred over heavy frameworks like React/Vue, keeping the stack lightweight.

## 5. Security & Best Practices
- **API Keys:** Never hardcode keys. Always use `config('services.gemini.key')` or `env()`.
- **Token Scope:** The GitHub OAuth scope must request `repo` access to allow creating PRs.
- **Validation:** Always validate incoming requests using Laravel Form Requests.
