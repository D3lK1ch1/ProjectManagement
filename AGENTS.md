## Agents Used
* GitHub Copilot · Gemini CLI · Claude Code · OpenCode

---

## Agent Guidelines
- Boilerplate and syntax help only — user writes core logic
- Workflow: research → plan → execute → review → revise
- No extra markdown files per change; summarise decisions in chat

## Code Review & Quality
- No comments in code — explain decisions in chat
- KISS: simplest solution first, no premature abstractions
- Parameters fully typed; edge cases handled explicitly, no silent failures

## Testing & Validation
- User runs all commands; agents provide commands only
- Test before shipping: unit first, integration for routes
- Document results here: what tested, passed/failed, improvements

---

## Project: ProjectManagement (Symfony 8.0 / PHP 8.4 / SQLite)

### Stack
- Symfony 8.0, PHP 8.4, SQLite local via Doctrine ORM
- Test runner: `php bin/phpunit`

### Key Entities
- `Project` → `Activity` (duration) → linked by `ActivityDependency` (predecessor_id, successor_id)
- ES/EF/LS/LF/slack are **computed outputs** — never stored as DB columns

### CriticalPathService contract
- Input: `$activities [{id, duration}]`, `$dependencies [{predecessor, successor}]`
- Output: `{project_duration, critical_path[], activities{id: {es,ef,ls,lf,slack,is_critical}}}`
- Pipeline: `topologicalSort → forwardPass → backwardPass → buildResult`

---

## Known Issues & Fixes
- `docs/activity-network-analysis.md` typo: D slack shown as 6, correct is 9 (LS 12 − ES 3)

## Testing & Validation Log
- Step 7 ✅ `CriticalPathServiceTest::testEightActivityExample` — 4 assertions pass
