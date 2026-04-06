## Agents Used
* GitHub Copilot
* Gemini CLI
* Claude Code
* OpenCode

This markdown is used throughout all projects, as rules and regulations to project across.

---

## Agent Guidelines
- Chatbots are allowed to improve and update this markdown
- Focus on incremental, validated changes
- Always cross-reference this file when starting new sessions
- Ensure the user follows the guidelines to research -> plan -> execute -> review -> revise
---
## Core Instructions

- Find and read docs, give a boiler plate but not core functionality. 
- User is not allowed for poor planning, shallow understanding of the code, letting AI do what it wants etc

### Code Review & Quality
- **No comments in code** - explain decisions in chat during review
- **Outline exploration** - check Explorer to understand function signatures, inputs → outputs
- **KISS principle** - implement simplest solution first, avoid premature optimization, avoid overthinking with extra hooks, abstracts etc
- **Not overdefensive**  - no extra type escapes
- **Edge cases** - handle errors explicitly; no silent failures

### Testing & Validation Strategy
- User runs commands themselves (agents provide command guidance only)
- **Test before shipping**: unit tests for critical paths, integration tests for routes
- **Document test results**: what was tested, what passed/failed, improvements made

### Documentation Approach  
- No extra markdown files per change
- Summarize in chat: Problem → Solution → Validation Results
- Track what worked vs what didn't for pattern recognition
- No inconsistent files

---

## Known Issues & Fixes


## Testing & Validation Log




## Code Quality Checklist
When implementing features:
- [ ] Error handling: try-catch or .catch() where applicable
- [ ] No unused imports or variables
- [ ] Function parameters fully typed
- [ ] Exported types match backend contracts
- [ ] Edge cases documented in chat

---