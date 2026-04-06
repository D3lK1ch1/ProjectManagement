# Project Management Analytics System

Web application that analyzes GitHub repositories and turns them into **project management insights**:
- PERT/CPM critical path analysis
- Activity network and workflow diagrams (Mermaid.js)
- Basic statistics about development patterns across your projects

---

## Getting Started
  **Requirements:** PHP 8.4, Composer       

  ```bash
  composer install
  php bin/console
  doctrine:migrations:migrate
  php -S localhost:8000 -t public
  public/index.php

  ```

## Features

- **Input**: A GitHub repository URL (e.g. `Bank-Transaction`, `NavMelb`, `AccountabilityApp`)
- **Processing**:
  - Call the GitHub API
  - Map commits and branches to activities and dependencies
  - Run PERT/CPM to find the critical path, duration, and slack
  - Generate workflow/network diagram data (Mermaid.js)
- **Output**:
  - JSON APIs for project, activities, and analysis
  - Diagrams embedded in docs or a UI

  (Following what I have learned in Project Management Essentials)

 ## Tech Stack

- **PHP 8.4 / Symfony 8.0** — REST API, GitHub integration, PERT/CPM service layer
- **Azure App Service** — host the Symfony API
- **Azure SQL or SQLite** — store projects, activities, and dependencies
- **Azure Blob Storage (optional)** — store exported diagrams/reports
- **GitHub API** — source of commit and repo data

---

## High-Level Architecture

- **API layer (Symfony)**
  - Controllers under `src/Controller/`
  - Services (e.g. `CriticalPathService`) for PERT/CPM logic under `src/Service/`
  - Doctrine entities for `Project`, `Activity`, `ActivityDependency` under `src/Entity/`
  - Repositories for database queries under `src/Repository/`

- **Database**
  - Local: SQLite for easy development (`var/data.db`)
  - Cloud: Azure SQL when deploying

- **Integrations**
  - GitHub REST API for repository and commit history (via Symfony HttpClient)
  - Mermaid.js or similar for diagram rendering on the front end

Directory overview (target state):

```text
ProjectManagement/
├── src/
│   ├── Controller/     # API controllers (routing via PHP attributes)
│   ├── Entity/         # Doctrine entities (Project, Activity, ActivityDependency)
│   ├── Repository/     # Entity repositories
│   └── Service/        # Business logic (CriticalPathService, GitHubService)
├── config/             # Symfony bundle and service configuration
├── migrations/         # Doctrine database migrations
├── docs/               # Concept and Azure notes
├── var/
│   └── data.db         # SQLite database (local dev only)
└── ReadME.md           # This file
```

---

