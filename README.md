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

This application leverages cutting-edge AI models (Google Gemini and Groq’s LLaMA3‑8B) to understand your project's intent, dependencies, and structure, transforming raw code into polished, developer-friendly documentation that mirrors the quality of top-tier SaaS products.

![Project Preview](docs/preview.png)

---

## ✨ Key Features

*   **AI-Powered README Generation**: Utilizes advanced AI (Google Gemini and Groq’s LLaMA3‑8B) to create high-quality, professional `README.md` files.
*   **GitHub Webhook Integration**: Automatically triggers README generation on every `push` event to your repository’s default branch.
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
*   **AI API Key**: A Google Gemini API Key or Groq API Key (used with the LLaMA3‑8B model).

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

3.  **AI Service Key**:
    *   For **Google Gemini**: Obtain a Google Gemini API Key from the Google AI Studio or Google Cloud.

    ```dotenv
    GEMINI_API_KEY=your_gemini_api_key
    ```

    *   For **Groq LLaMA3‑8B**: Obtain a Groq API Key from the Groq platform.

    ```dotenv