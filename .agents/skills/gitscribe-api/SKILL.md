---
name: gitscribe-api
description: Skill for understanding and modifying the GitScribe API integrations (GitHub and Gemini).
---

# GitScribe API Skill

When the user requests modifications to the README generation process, prompt structure, or GitHub Pull Request flow, refer to these instructions.

## 1. Modifying the Gemini Prompt

The core AI interaction happens in `app/Services/GeminiService.php`.

- **To adjust the tone or structure of the README:** Modify the `$prompt` variable inside the `generateReadme` method.
- **Adding new metadata:** If you need to add, say, the number of stars a repo has, you must first update the Controller to fetch this from GitHub, then pass it into the `GeminiService` constructor or method, and finally include it in the `$prompt` string.
- **API Formatting:** GitScribe currently requests standard Markdown. Do not instruct the AI to return JSON unless the parsing logic is completely rewritten.

## 2. Modifying the GitHub PR Flow

GitScribe uses a multi-step GitHub REST API process to commit files and open a Pull Request programmatically.

- **Files:** The logic usually resides in `PullRequestController.php` or a dedicated `GitHubService.php`.
- **Steps Required to open a PR:**
  1. Get the reference (SHA) of the default branch (e.g., `main`).
  2. Create a new branch reference (e.g., `refs/heads/gitscribe/update`).
  3. Create a blob for the new `README.md` content.
  4. Create a tree containing the new blob, based on the base tree.
  5. Create a commit using the new tree and the base commit SHA.
  6. Update the new branch reference to point to the new commit.
  7. Create a Pull Request from the new branch to the base branch.

*If an error occurs during PR creation, verify that the application holds the `repo` OAuth scope.*
