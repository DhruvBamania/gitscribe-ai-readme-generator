# AI Agent Guidelines (GitScribe)

Welcome, AI Agent! You are working on **GitScribe**, a Laravel 12 application that automatically generates comprehensive `README.md` files for GitHub repositories using the GitHub REST API and Google's Gemini AI.

## Quick Links
- **Architecture & Deep Dive:** [`AI_DEVELOPMENT_GUIDE.md`](./AI_DEVELOPMENT_GUIDE.md)
- **Claude Specific Instructions:** [`CLAUDE.md`](./CLAUDE.md)
- **Coding Standards:** [`.agents/rules/coding_standards.md`](./.agents/rules/coding_standards.md)
- **Current Sprint/Tasks:** [`sprint.md`](./sprint.md)

## Core Technologies
- **Backend:** Laravel 12 (PHP 8.2+)
- **Frontend:** Laravel Blade, Bootstrap 5, Vite
- **Integrations:** Laravel Socialite (GitHub OAuth), GitHub REST API v3, Google Gemini API

## Key Agent Rules
1. **Always read `.agents/rules/coding_standards.md`** before writing new PHP, Blade, or JavaScript code.
2. **Do not use raw SQL queries.** Always use Eloquent ORM or Query Builder.
3. **Do not remove or alter existing Tailwind/Bootstrap classes** without checking `resources/views/` for how layouts are structured.
4. **Before modifying AI prompt generation**, review `.agents/skills/gitscribe-api/SKILL.md` to understand how `GeminiService.php` is structured.
5. **Always update `sprint.md`** when completing a task or starting a new one if requested by the user.

## Agent Toolkit
- If you are an agent supporting `.agents` workspaces (like Antigravity), make sure to index and use the local workspace skills located in `.agents/skills/`.
