# Sprint & Task Board

Use this file to track current development progress. AI Agents: update this file when you complete a task.

## 🏃 Current Sprint

### 📝 To Do
- [ ] Implement robust error handling for GitHub API rate limits.
- [ ] Add support for customizing the README template via the UI.
- [ ] Write Feature tests for the complete PR workflow using mocks.
- [ ] Add caching for the user's repository list to improve dashboard load times.

### 🏗 In Progress
- [ ] None

### ✅ Done
- [x] **Sprint: Autonomous Full-Project Wiki Builder**
  - [x] **UI:** Add "Generate Wiki" button to the Dashboard repository list.
  - [x] **GitHub API:** Implement `commitMultipleFiles` via `createWikiPullRequest` in `GitHubService` to support pushing entire `docs/` folders.
  - [x] **AI Logic:** Add `planWikiArchitecture`, `pickFilesForWikiPage`, and `writeWikiPage` methods to `GeminiService`.
  - [x] **Background Processing:** Create `GenerateWikiJob` to handle the heavy AI looping asynchronously so the UI doesn't freeze.
  - [x] **Controller:** Add `WikiController@generate` to dispatch the job and notify the user.
- [x] **Sprint: Detailed Audit Report**
  - [x] Analyze security and dependencies (authentication, API keys, etc.).
  - [x] Review code quality, architecture, and Laravel best practices.
  - [x] Evaluate performance, database queries, and API rate handling.
  - [x] Compile findings into a comprehensive `docs/audit_report_2026-09-23.md` file.
- [x] Initial Laravel 12 setup.
- [x] GitHub OAuth integration (Socialite).
- [x] Gemini AI integration for README generation.
- [x] Add AI-Assisted development configuration files (AGENTS.md, etc.).

---

## 🗄 Backlog
- Support GitLab and Bitbucket integrations.
- Introduce a dark mode toggle for the UI.
- Add user-selectable language support for the generated documentation.
