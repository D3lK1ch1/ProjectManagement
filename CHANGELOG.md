# Changelog

All notable changes to this project are documented here.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/)

---

## [Unreleased]

Nothing shipped yet beyond the scaffold. See Roadmap in README.

---

## 2026-04-06 — Entities and migration

**Added**
- `Project` entity (`id`, `name`, `repoUrl`) with Doctrine ORM mapping
- `Activity` entity (`id`, `name`, `duration`) with ManyToOne relation to `Project`
- Doctrine migration generating `project` and `activity` tables in SQLite
- `orphanRemoval: true` on the `Project → activities` collection (deleting a project removes its activities)

**Not yet done** (tracked in Roadmap)
- `ActivityDependency` entity — required before any graph algorithm can run
- `CriticalPathService` — file exists, implementation not started
- Symfony 8 webapp scaffold
- SQLite configured as local development database (`var/data.db`)
- Doctrine ORM and Migrations bundles installed
- `var/data.db` added to `.gitignore`
