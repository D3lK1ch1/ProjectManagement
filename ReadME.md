# Project Management Analytics System

A Symfony project for exploring project management analytics with PERT/CPM critical path analysis.

## Description

This is currently a scaffold/demo-stage Symfony REST API. The working core models projects, activities, and activity dependencies, then calculates critical path outputs from demo data. GitHub import is planned, but not implemented yet.

---

## Current Features

- Symfony 8.0 REST API scaffold with SQLite for local development
- `Project`, `Activity`, and `ActivityDependency` Doctrine ORM entities
- Database schema managed via Doctrine Migrations
- `CriticalPathService` for forward pass, backward pass, slack, and critical path output
- Project API endpoints for listing projects, critical path results, and network diagram data
- Demo fixture data for an eight-activity CPM example
- Static demo page for viewing project flow locally

---

## Built With

- **PHP 8.4** - language runtime
- **Symfony 8.0** - REST API framework, dependency injection, routing
- **Doctrine ORM** - entity mapping and migrations
- **SQLite** - local development database (`var/data.db`)
- **Mermaid.js** - diagram rendering for the local demo
- **GitHub REST API** - planned source of commit and branch data

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
php bin/console doctrine:fixtures:load
php -S localhost:8000 -t public public/index.php
```

Visit `http://localhost:8000/demo.html` to try the current demo flow.

Optional: add a GitHub token later to avoid API rate limits once GitHub import is implemented. Never commit this file:

```bash
# .env.local
GITHUB_TOKEN=your_personal_access_token
```

---

## Usage

Current scaffold/demo endpoints:

- `GET /api/projects`
- `GET /api/projects/{id}`
- `GET /api/projects/{id}/critical-path`
- `GET /api/projects/{id}/network-diagram`

---

## Roadmap

- [x] `ActivityDependency` entity for predecessor/successor graph edges
- [x] `CriticalPathService` for forward pass, backward pass, slack, and critical path
- [x] `ProjectController` for project and critical path API endpoints
- [x] Network diagram endpoint returning Mermaid-compatible data
- [ ] Route-level tests for project API endpoints
- [ ] Tighter dependency query scoping for selected projects
- [ ] `GitHubController` with `POST /api/github/import`
- [ ] Map commits and branches to activities and dependencies
- [ ] Azure App Service deployment
- [ ] Azure SQL or another production database option
- [ ] Application Insights or another monitoring option

---

## License

To be determined.
