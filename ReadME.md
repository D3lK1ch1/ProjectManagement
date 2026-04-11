# Project Management Analytics System
A web-app taking GitHub projects for analysis in order to plan project management.

## Description

A Symfony REST API that takes a GitHub repository URL, maps its commit history into activities, and runs PERT/CPM critical path analysis to generate project management insights and network diagrams.

---

## Current Features

- Symfony 8.0 REST API scaffold with SQLite (local dev)
- `Project` and `Activity` Doctrine ORM entities
- Database schema managed via Doctrine Migrations

---

## Built With

- **PHP 8.4** — language runtime
- **Symfony 8.0** — REST API framework, dependency injection, routing
- **Doctrine ORM** — entity mapping and migrations
- **SQLite** — local development database (`var/data.db`)
- **GitHub REST API** — planned source of commit and branch data
- **Mermaid.js** — planned diagram rendering

---

## Getting Started

### Prerequisites

- PHP 8.4+
- Composer

### Installation

```bash
git clone <repo-url>
cd ProjectManagement
composer install
php bin/console doctrine:migrations:migrate
php -S localhost:8000 -t public public/index.php
```

Visit `http://localhost:8000` to confirm the Symfony welcome page loads.

Optional — add a GitHub token to avoid API rate limits (never commit this file):

```bash
# .env.local
GITHUB_TOKEN=your_personal_access_token
```

---

## Usage

_To be documented once API endpoints are implemented._

---

## Roadmap
- [ ] `ActivityDependency` entity (predecessor/successor graph edges)
- [ ] `CriticalPathService` — forward pass, backward pass, slack, critical path
- [ ] `ProjectController` — `GET /api/projects`, `GET /api/projects/{id}/critical-path`
- [ ] `GitHubController` — `POST /api/github/import`
- [ ] Map commits and branches to activities and dependencies
- [ ] Network diagram endpoint returning Mermaid-compatible data
- [ ] Azure App Service (PHP 8.4)
- [ ] Azure SQL or SQLite-on-disk for production
- [ ] Application Insights for monitoring

---

## License

To be determined.
