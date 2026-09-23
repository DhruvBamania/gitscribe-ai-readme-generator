<div align="center">

# GitScribe: AI-Powered README Generation for Your GitHub Projects

*Transform your project documentation with intelligent, automated READMEs.*

</div>

[![PHP](https://img.shields.io/badge/PHP-8.2+-8892BF?style=for-the-badge&logo=php)](https://www.php.net/)
[![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel)](https://laravel.com/)
[![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)](LICENSE)
[![Build Status](https://img.shields.io/badge/Build-Passing-brightgreen?style=for-the-badge)](https://github.com/gitscribe-ai/gitscribe-ai-readme-generator/actions)

---

## Table of Contents

-   [About GitScribe](#about-gitscribe)
-   [Features](#features)
-   [Tech Stack](#tech-stack)
-   [Installation](#installation)
    -   [Prerequisites](#prerequisites)
    -   [Local Setup (Recommended)](#local-setup-recommended)
    -   [Docker Setup](#docker-setup)
-   [Configuration](#configuration)
-   [Usage](#usage)
-   [Folder Structure](#folder-structure)
-   [Contributing](#contributing)
-   [License](#license)

---

## About GitScribe

GitScribe is an innovative, AI-powered Laravel application engineered to automate the creation of professional and highly readable `README.md` files for your GitHub projects. By integrating directly into your development workflow via GitHub Pull Requests, GitScribe analyzes your project's context, code, and dependencies to generate comprehensive and accurate documentation. Say goodbye to manual README updates and ensure your projects are always presented with best-in-class documentation.

This project aims to streamline the documentation process, allowing developers to focus more on coding and less on descriptive overhead. Whether you're maintaining a small open-source utility or a complex SaaS product, GitScribe ensures your READMEs reflect the quality and professionalism of your work.

---

## Features

-   **AI-Powered Generation**: Leverages advanced AI models to understand project context and generate relevant, high-quality `README.md` content.
-   **GitHub PR Integration**: Seamlessly integrates with GitHub Pull Requests to automatically propose README updates or creations.
-   **Laravel Foundation**: Built on the robust and scalable Laravel 12 framework, ensuring reliability and maintainability.
-   **Dynamic Content**: Generates sections like installation guides, tech stacks, and usage instructions based on project analysis.
-   **Markdown Excellence**: Outputs beautifully formatted and structured Markdown, ready for immediate use.
-   **Extensible Agent System**: Designed with an adaptable agent architecture allowing for custom AI behaviors and specialized documentation tasks.

---

## Tech Stack

GitScribe is built on a modern and robust technology stack:

-   **Backend**:
    -   **PHP**: `^8.2`
    -   **Laravel Framework**: `^12.0`
    -   **Laravel Socialite**: `^5.27` (for OAuth integrations, likely GitHub)
    -   **Spatie Laravel Markdown**: `^2.8` (for enhanced Markdown rendering/processing)
-   **Frontend**:
    -   **Node.js**: (runtime environment for assets)
    -   **npm**: (package manager)
    -   **Vite**: `^7.0.7` (next-generation frontend tooling)
    -   **Tailwind CSS**: `^4.0.0` (utility-first CSS framework)
-   **Database**:
    -   Supports **MySQL** and **PostgreSQL** (via `pdo_mysql` and `pdo_pgsql` extensions).
-   **Development Tools**:
    -   **Composer**: (PHP dependency manager)
    -   **Laravel Sail**: `^1.41` (Docker development environment for Laravel)
    -   **Laravel Pint**: `^1.24` (PHP code style fixer)
    -   **PHPUnit**: `^11.5.50` (testing framework)
-   **Web Server**:
    -   **Apache**: (Configured via `Dockerfile`)

---

## Installation

To get GitScribe up and running, follow these steps.

### Prerequisites

Ensure you have the following installed on your development machine:

-   **PHP**: `8.2` or higher
-   **Composer**: Latest version
-   **Node.js**: `18.x` or higher
-   **npm**: Latest version
-   **Git**: Latest version
-   A database server (e.g., MySQL, PostgreSQL, SQLite)

### Local Setup (Recommended)

The easiest way to get started is by using the provided `setup` script, which handles Composer dependencies, `.env` file generation, and asset compilation.

1.  **Clone the Repository**:
    ```bash
    git clone https://github.com/gitscribe-ai/gitscribe-ai-readme-generator.git
    cd gitscribe-ai-readme-generator
    ```

2.  **Run the Setup Script**:
    This script will:
    *   Install PHP dependencies via Composer.
    *   Copy `.env.example` to `.env` if it doesn't exist.
    *   Generate a unique application key.
    *   Run database migrations.
    *   Install Node.js dependencies via npm.
    *   Build frontend assets via Vite.

    ```bash
    composer run setup
    ```
    > **Note**: If you encounter `npm command not found` or similar issues, ensure Node.js and npm are correctly installed and in your system's PATH.

3.  **Configure `.env`**:
    Open the newly created `.env` file and configure your database connection and any other necessary environment variables (e.g., GitHub API credentials for Socialite, AI service keys).

    ```dotenv
    # Example .env configuration
    APP_NAME="GitScribe AI"
    APP_ENV=local
    APP_KEY=base64:YOUR_GENERATED_APP_KEY
    APP_DEBUG=true
    APP_URL=http://localhost:8000

    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=gitscribe
    DB_USERNAME=root
    DB_PASSWORD=

    # AI Service Configuration
    AI_SERVICE_PROVIDER=claude # or openai, etc.
    AI_SERVICE_KEY=your_ai_service_key

    # GitHub OAuth (for Socialite)
    GITHUB_CLIENT_ID=your_github_client_id
    GITHUB_CLIENT_SECRET=your_github_client_secret
    GITHUB_REDIRECT_URI=${APP_URL}/auth/github/callback
    ```

4.  **Start the Development Server**:
    ```bash
    php artisan serve
    ```
    Alternatively, you can use the development script which includes `php artisan serve` and other development tools:
    ```bash
    composer run dev
    ```

    GitScribe will now be accessible in your web browser, typically at `http://localhost:8000`.

### Docker Setup

GitScribe includes a `Dockerfile` for easy deployment in a containerized environment. This setup uses Apache as the web server.

1.  **Build the Docker Image**:
    ```bash
    docker build -t gitscribe-ai .
    ```

2.  **Run the Docker Container**:
    You'll typically want to mount your application code and environment variables. For local development with Docker, Laravel Sail is recommended (see `composer.json` for `laravel/sail` dependency). If not using Sail, a basic run command might look like:

    ```bash
    docker run -p 80:80 \
      -e APP_KEY=base64:YOUR_GENERATED_APP_KEY \
      -e DB_CONNECTION=mysql \
      -e DB_HOST=your_db_host \
      -e DB_DATABASE=your_db_name \
      -e DB_USERNAME=your_db_user \
      -e DB_PASSWORD=your_db_password \
      -e AI_SERVICE_KEY=your_ai_service_key \
      gitscribe-ai
    ```
    > **Note**: For production, use Docker Compose or a Kubernetes setup for proper database linking and environment management. The `Dockerfile` itself includes `composer install --no-dev --optimize-autoloader` and `npm run build` for optimized production builds.

---

## Configuration

All core application configuration is managed via environment variables in the `.env` file. Key configurations include:

-   **Database**: `DB_CONNECTION`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`.
-   **AI Services**: `AI_SERVICE_PROVIDER`, `AI_SERVICE_KEY`. These will point to your chosen AI model provider (e.g., OpenAI, Claude).
-   **GitHub Integration**: `GITHUB_CLIENT_ID`, `GITHUB_CLIENT_SECRET`, `GITHUB_REDIRECT_URI` for OAuth authentication and interacting with the GitHub API.

> **Important**: Never commit your `.env` file to version control. Use `.env.example` as a template and populate `.env` on your deployment target.

---

## Usage

Once GitScribe is installed and configured, you can begin leveraging its AI capabilities. The primary workflow involves connecting your GitHub account and then configuring GitScribe to monitor your repositories for new Pull Requests.

1.  **Authenticate with GitHub**:
    Navigate to the application in your browser and follow the prompts to authenticate GitScribe with your GitHub account using OAuth. This grants GitScribe the necessary permissions to read your repository information and create Pull Requests.

2.  **Configure Monitored Repositories**:
    Within the GitScribe dashboard, specify which repositories you want GitScribe to manage. You can set rules for when a new README should be generated (e.g., on `main` branch merges, specific labels on PRs, or manually triggered).

3.  **Triggering README Generation**:
    -   **Via Pull Request**: When a new Pull Request is opened or updated in a monitored repository, GitScribe will analyze the changes and propose a new or updated `README.md` as a comment or a new PR itself.
    -   **Manual Trigger**: You may also have an option within the GitScribe interface to manually trigger a README generation for any linked repository.

GitScribe will analyze your project structure, `composer.json`, `package.json`, and potentially other key files to infer the project's purpose, technologies used, and installation steps. The generated README will then be presented for your review and approval.

---

## Folder Structure

The project follows a standard Laravel application structure, with a few custom additions:

```
.
├── .agents/                    # Custom AI agent definitions (e.g., for specific README sections)
├── .editorconfig
├── .env.example
├── .gitattributes
├── .gitignore
├── AGENTS.md                   # Documentation for custom AI agents
├── AI_DEVELOPMENT_GUIDE.md     # Guide for developing new AI features
├── CLAUDE.md                   # Specific documentation or notes related to Claude AI integration
├── Dockerfile                  # Docker build instructions
├── LICENSE
├── README.md                   # This file!
├── app/                        # Laravel application core (Models, Http, Providers, etc.)
├── artisan                     # Laravel CLI entry point
├── bootstrap/                  # Laravel framework bootstrap
├── composer.json               # PHP dependencies and scripts
├── composer.lock
├── config/                     # Laravel configuration files
├── database/                   # Database migrations, seeders, factories
├── package.json                # Node.js dependencies and scripts
├── phpunit.xml
├── public/                     # Web server document root
├── render-start.sh             # Custom script for Render.com deployment (example)
├── resources/                  # Frontend assets (views, JS, CSS)
├── routes/                     # API and web routes
├── sprint.md                   # Project sprint planning or notes
├── storage/                    # Application storage (logs, cache, uploads)
├── tests/                      # Automated tests
└── vite.config.js              # Vite frontend build configuration
```

---

## Contributing

We welcome contributions from the community! If you're interested in improving GitScribe, please refer to our [AI Development Guide](AI_DEVELOPMENT_GUIDE.md) and the general contribution guidelines:

1.  Fork the repository.
2.  Create a new branch for your feature or bug fix.
3.  Ensure your code adheres to our coding standards (use `php artisan pint`).
4.  Write clear, concise commit messages.
5.  Open a Pull Request with a detailed description of your changes.

---

## License

GitScribe is open-source software licensed under the [MIT license](LICENSE).

---
*Documentation automatically generated by GitScribe on 23-09-2026 (India).*
