<div align="center">
  
# ✍️ GitScribe
**Automated, AI-Powered Documentation for GitHub Repositories**

[![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-563D7C?style=for-the-badge&logo=bootstrap&logoColor=white)](https://getbootstrap.com)
[![Gemini API](https://img.shields.io/badge/Gemini_AI-8E75B2?style=for-the-badge&logo=google&logoColor=white)](https://deepmind.google/technologies/gemini/)
[![License: MIT](https://img.shields.io/badge/License-MIT-green.svg?style=for-the-badge)](https://opensource.org/licenses/MIT)

</div>

## 📖 Overview
GitScribe is a SaaS application designed to bridge the gap between software engineering and documentation. By utilizing the GitHub REST API and Google's Gemini AI, GitScribe analyzes your repository's context and automatically generates structured, high-quality `README.md` files. The generated documentation is then seamlessly pushed back to your repository via an automated Pull Request.

## 📑 Table of Contents
- [Features](#-features)
- [System Architecture](#-system-architecture)
- [Tech Stack](#-tech-stack)
- [Prerequisites](#-prerequisites)
- [Installation & Setup](#-installation--setup)
- [Configuration Guide](#-configuration-guide)
- [Usage Workflow](#-usage-workflow)
- [Roadmap](#-roadmap)
- [Contributing](#-contributing)
- [License](#-license)

---

## ✨ Features
* **Secure Authentication:** Implements OAuth 2.0 via Laravel Socialite for secure, passwordless GitHub login.
* **Context-Aware AI Generation:** Feeds repository metadata (languages, file structures, descriptions) into Google Gemini to generate highly accurate documentation.
* **Automated Git Workflow:** Uses the GitHub REST API to programmatically create a new branch, author the commit, and open a Pull Request without manual intervention.
* **Responsive UI:** A clean, intuitive dashboard built with Bootstrap, keeping the codebase manageable and fast.
* **Cloud-Ready:** Architecture optimized for deployment on environments like Oracle Cloud or standard VPS hosting.

---

## 🏗 System Architecture
1. **Authentication:** User logs in via GitHub OAuth. An access token is securely stored in the session.
2. **Data Retrieval:** GitScribe fetches the user's repository list and relevant metadata using the GitHub REST API.
3. **AI Processing:** Selected repository data is structured into an optimized prompt and sent to the Gemini AI API.
4. **Git Operations:**
   - Fetches the SHA of the main branch.
   - Creates a new branch (e.g., `gitscribe/readme-update`).
   - Commits the AI-generated Markdown file.
   - Opens a Pull Request against the main branch.

---

## 💻 Tech Stack
* **Framework:** Laravel 12
* **Language:** PHP 8.2+
* **Database:** MySQL
* **Frontend:** Laravel Blade, Bootstrap 5
* **Integrations:** 
  * Laravel Socialite (OAuth)
  * GitHub REST API v3
  * Google Gemini API

---

## ⚙️ Prerequisites
Ensure your local machine or server meets the following requirements:
* [PHP 8.2+](https://www.php.net/downloads)
* [Composer](https://getcomposer.org/)
* [MySQL](https://www.mysql.com/) or MariaDB
* Node.js & NPM (for frontend asset compilation)

---

## 🚀 Installation & Setup

**1. Clone the repository**
```bash
git clone https://github.com/DhruvBamania/gitscribe-ai-readme-generator.git
cd gitscribe
```

**2. Install PHP and Node dependencies**
```bash
composer install
npm install
npm run build
```

**3. Configure the environment**
```bash
cp .env.example .env
php artisan key:generate
```

**4. Set up the database**

Update your .env file with your MySQL credentials, then run the migrations:
```bash
php artisan migrate
```
---

## 🔑 Configuration Guide
To run GitScribe locally, you need API keys from GitHub and Google. Add these to your .env file:

**GitHub OAuth App Setup**
1. Go to your GitHub Settings > Developer Settings > OAuth Apps.
2. Click New OAuth App.
3. Set the Homepage URL to ```http://localhost:8000 ```.
4. Set the Authorization callback URL to ```http://localhost:8000/auth/github/callback```.
5. Generate a client secret and add both keys to your ```.env```:
```bash
GITHUB_CLIENT_ID=your_client_id_here
GITHUB_CLIENT_SECRET=your_client_secret_here
GITHUB_REDIRECT_URI=http://localhost:8000/auth/github/callback
```

---

## Google Gemini API Setup
1. Visit Google AI Studio to get an API key.
2. Add it to your ```.env```:
```bash
GEMINI_API_KEY=your_gemini_api_key_here
```

---

## 🛠 Usage Workflow
1. Start the Laravel development server:
```bash
php artisan serve
```
2. Navigate to ```http://localhost:8000``` and click Sign in with GitHub.
3. Authorize the application to read and write to your repositories.
4. Select a repository from your dashboard.
5. Click Generate README.
6. Review the success message, navigate to your GitHub repository, and review the newly created Pull Request!

---

## 🤝 Contributing
1. Fork the Project
2. Create your Feature Branch (```git checkout -b feature/AmazingFeature```)
3. Commit your Changes (```git commit -m 'Add some AmazingFeature'```)
4. Push to the Branch (```git push origin feature/AmazingFeature```)
5. Open a Pull Request

---

## 📝 License 
Distributed under the MIT License. See LICENSE for more information.
