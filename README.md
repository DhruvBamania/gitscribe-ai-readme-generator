# GitScribe AI README Generator

> Revolutionize your GitHub documentation with AI-powered, automated README generation directly from your Pull Requests.

---

[![Build Status](https://img.shields.io/badge/build-passing-brightgreen?style=for-the-badge)](https://github.com/your-org/gitscribe-ai-readme-generator/actions)
[![License](https://img.shields.io/github/license/laravel/laravel?color=blue&style=for-the-badge)](LICENSE)
[![Powered by PHP](https://img.shields.io/badge/PHP-8.2+-8892BF?style=for-the-badge&logo=php&logoColor=white)](https://www.php.net/)
[![Framework: Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com/)

## Table of Contents

*   [🌟 About](#-about)
*   [✨ Key Features](#-key-features)
*   [🚀 Getting Started](#-getting-started)
    *   [Prerequisites](#prerequisites)
    *   [Installation](#installation)
    *   [Environment Configuration](#environment-configuration)
    *   [Running the Application](#running-the-application)
    *   [Docker (Optional)](#docker-optional)
*   [💡 Usage](#-usage)
*   [🛠️ Tech Stack](#️-tech-stack)
*   [📂 Project Structure](#-project-structure)
*   [🤝 Contributing](#-contributing)
*   [📜 License](#-license)

---

## 🌟 About

`gitscribe-ai-readme-generator` is an innovative, AI-powered Laravel application designed to elevate your open-source projects and internal repositories with automatically generated, professional README files. By integrating directly with GitHub via webhooks, GitScribe intelligently analyzes your project's codebase on every push to the default branch, then drafts comprehensive READMEs as pull requests. Say goodbye to outdated or incomplete documentation – GitScribe ensures your project always presents its best face.

This application leverages cutting-edge AI models (like Google Gemini) to understand your project's intent, dependencies, and structure, transforming raw code into polished, developer-friendly documentation that mirrors the quality of top-tier SaaS products.

![Project Preview](docs/preview.png)

---

## ✨ Key Features

*   **AI-Powered README Generation**: Utilizes advanced AI (Google Gemini and potentially Claude) to create high-quality, professional README.md files, now with enhanced API reliability including automatic retries for rate limits.
*   **GitHub Webhook Integration**: Automatically triggers README generation on every `push` event to your repository's default branch.
*   **Pull Request Automation**: Drafts new READMEs or updates existing ones as pull requests, allowing for easy review and merging.
*   **Intelligent Code Analysis**: Deeply analyzes file contents and directory structures to extract accurate dependencies, installation steps, and architectural insights.
*   **Laravel Framework**: Built on the robust and expressive Laravel 12 framework, ensuring scalability and maintainability.
*   **GitHub OAuth Authentication**: Securely log in and authorize repositories using your GitHub account.
*   **Wiki Generation**: Beyond READMEs, extends capabilities to generate project wikis (planned/in progress).
*   **Customizable AI Agents**: Supports defining custom AI behaviors and prompts for tailored documentation.

---

## 🚀 Getting Started

Follow these steps to get `gitscribe-ai-readme-generator` up and running on your local machine.

### Prerequisites

Ensure you have the following installed:

*   **PHP**: `^8.2`
*   **Composer**: [Download Composer](https://getcomposer.org/download/)
*   **Node.js & npm**: [Download Node.js](https://nodejs.org/en/download/) (includes npm)
*   **Git**: [Download Git](https://git-scm.com/downloads)
*   **Database**: MySQL or PostgreSQL (e.g., via Docker or XAMPP/WAMP/MAMP)
*   **GitHub Account**: For OAuth and repository integration.
*   **AI API Key**: A Google Gemini API Key.

### Installation

1.  **Clone the Repository**:
    ```bash
    git clone https://github.com/your-org/gitscribe-ai-readme-generator.git
    cd gitscribe-ai-readme-generator
    ```

2.  **Install PHP & JavaScript Dependencies**:
    The project includes a convenient `setup` script in `composer.json` that handles most of the initial setup.

    ```bash
    composer setup
    ```

    This script will:
    *   Run `composer install` to fetch PHP dependencies.
    *   Copy `.env.example` to `.env` if it doesn't exist.
    *   Generate a new application key.
    *   Run database migrations (`php artisan migrate --force`).
    *   Run `npm install` to fetch JavaScript dependencies.
    *   Run `npm run build` to compile assets.

    > If you encounter issues, you can run these steps manually:
    > ```bash
    > composer install
    > cp .env.example .env
    > php artisan key:generate
    > php artisan migrate
    > npm install
    > npm run build
    > ```

### Environment Configuration

Open your newly created `.env` file and update the following variables:

1.  **Database Configuration**:
    Configure your database connection (e.g., MySQL):

    ```dotenv
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=gitscribe
    DB_USERNAME=root
    DB_PASSWORD=
    ```

    Or for PostgreSQL:

    ```dotenv
    DB_CONNECTION=pgsql
    DB_HOST=127.0.0.1
    DB_PORT=5432
    DB_DATABASE=gitscribe
    DB_USERNAME=postgres
    DB_PASSWORD=
    ```

2.  **GitHub OAuth Credentials**:
    Register a new OAuth Application on GitHub: `Settings > Developer settings > OAuth Apps > New OAuth App`.
    *   **Homepage URL**: `http://localhost:8000` (or your application's public URL)
    *   **Authorization callback URL**: `http://localhost:8000/auth/github/callback`

    Copy the `Client ID` and `Client Secret` into your `.env`:

    ```dotenv
    GITHUB_CLIENT_ID=your_github_client_id
    GITHUB_CLIENT_SECRET=your_github_client_secret
    GITHUB_REDIRECT_URI=http://localhost:8000/auth/github/callback
    ```

3.  **AI Service (Gemini) Key**:
    Obtain a Google Gemini API Key from the Google AI Studio or Google Cloud.

    ```dotenv
    GEMINI_API_KEY=your_gemini_api_key
    ```

4.  **GitHub Webhook Secret**:
    Generate a random, strong secret for GitHub webhook verification.

    ```dotenv
    GITHUB_WEBHOOK_SECRET=your_strong_random_secret
    ```

### Running the Application

1.  **Start the Laravel Development Server**:
    ```bash
    php artisan serve
    ```

2.  **Start the Queue Worker**:
    AI generation can take time, so it's processed in the background.

    ```bash
    php artisan queue:work
    ```

3.  **Access the Application**:
    Open your web browser and navigate to `http://localhost:8000`. Log in with your GitHub account to start managing your repositories and generating READMEs!

### Docker (Optional)

A `Dockerfile` is provided for containerized deployment, particularly for production environments like Render.com.

To build and run with Docker locally:

1.  **Build the Docker Image**:
    ```bash
    docker build -t gitscribe-ai .
    ```

2.  **Run the Docker Container**:
    ```bash
    docker run -p 80:80 -d --name gitscribe-app gitscribe-ai
    ```

    > Remember to configure your `.env` file for database connections that are accessible from within the Docker container, and adjust port mappings if necessary. The `Dockerfile` includes `pdo_mysql` and `pdo_pgsql` to support various database providers.

---

## 💡 Usage

After installation and configuration:

1.  **Login with GitHub**: Access the application and authenticate using your GitHub account.
2.  **Dashboard**: On the dashboard, you'll see a list of your repositories.
3.  **Enable Webhooks**: For any repository you want GitScribe to manage, toggle the "Enable Webhook" option. This will create a GitHub webhook that notifies GitScribe on every `push` event.
4.  **Push to Repository**: Make a code change and push it to the default branch of a webhook-enabled repository.
5.  **Automated PR**: GitScribe will process the push, generate a new README.md (or update an existing one) using AI, and open a Pull Request in your repository with the proposed documentation.
6.  **Review and Merge**: Review the generated README in GitHub, make any desired manual edits, and merge the PR.

You can also manually trigger README generation for a specific repository from the dashboard.

---

## 🛠️ Tech Stack

`gitscribe-ai-readme-generator` is built on a modern and robust technology stack:

*   **Backend**:
    *   [PHP 8.2+](https://www.php.net/)
    *   [Laravel 12](https://laravel.com/) - The core framework.
    *   [Composer](https://getcomposer.org/) - PHP package manager.
    *   [Laravel Socialite](https://laravel.com/docs/12.x/socialite) - GitHub OAuth authentication.
    *   [Spatie Laravel Markdown](https://spatie.be/docs/laravel-markdown/v2/introduction) - Markdown parsing and rendering.
    *   **AI Integration**:
        *   Google Gemini API (via custom service)
        *   (Future: Claude API, other LLMs)
    *   **Database**: MySQL or PostgreSQL (configured via `.env`)

*   **Frontend**:
    *   [Vite 7.x](https://vitejs.dev/) - Frontend build tool.
    *   [Tailwind CSS 4.x](https://tailwindcss.com/) - Utility-first CSS framework.
    *   [Node.js & npm](https://nodejs.org/) - JavaScript runtime and package manager.

*   **Infrastructure & Tools**:
    *   [GitHub API](https://docs.github.com/en/rest) - For repository interaction, webhooks, and pull requests.
    *   [Docker](https://www.docker.com/) - Containerization for easy deployment.
    *   [Apache](https://httpd.apache.org/) - Web server (as configured in Dockerfile).

---

## 📂 Project Structure

A simplified overview of the key directories and files:

```
gitscribe-ai-readme-generator/
├── .agents/                 # AI agent configuration/prompt files
├── .env.example             # Example environment variables
├── AGENTS.md                # Documentation on AI agents
├── AI_DEVELOPMENT_GUIDE.md  # Guide for developing AI features
├── CLAUDE.md                # Notes on Claude AI integration
├── Dockerfile               # Docker build instructions
├── LICENSE                  # Project license file (MIT)
├── README.md                # This README file
├── app/                     # Laravel application source code
│   ├── Http/                # HTTP controllers and middleware
│   │   ├── Controllers/     # Application logic
│   │   └── Middleware/      # Custom middleware (e.g., webhook verification)
│   ├── Jobs/                # Queueable jobs for background processing (e.g., AI generation)
│   ├── Models/              # Eloquent models
│   └── Services/            # Service classes for external APIs (GitHub, Gemini)
├── artisan                  # Laravel command-line interface
├── bootstrap/               # Framework bootstrapping
├── composer.json            # PHP dependencies and scripts
├── config/                  # Configuration files
├── database/                # Database migrations, seeders, factories
├── docs/                    # Additional documentation (e.g., project preview)
├── package.json             # Node.js dependencies and scripts
├── public/                  # Publicly accessible files
├── render-start.sh          # Script for Render deployment
├── resources/               # Views, language files, assets
├── routes/                  # Web, API, and console routes
├── scratch/                 # Temporary files and scripts for testing/development
├── sprint.md                # Sprint planning/notes
├── storage/                 # Application generated files
├── tests/                   # Automated tests
└── vite.config.js           # Vite configuration for asset bundling
```

---

## 🤝 Contributing

We welcome contributions to `gitscribe-ai-readme-generator`! Whether it's bug reports, feature requests, or pull requests, your help makes this project better.

Please refer to `AI_DEVELOPMENT_GUIDE.md` and `sprint.md` for more details on contributing to the AI components and project roadmap.

---

## 📜 License

This project is open-source software licensed under the [MIT license](LICENSE).

---

*Documentation automatically generated by GitScribe on 24-09-2026 (India).*