# Contributing to php-jobspy

Thank you for contributing to `php-jobspy`, the underlying data ingestion engine for the Freeworld Job Finder!

## Core Principles
* **Ethical Scraping:** Always respect `robots.txt`, implement strict rate limiting, and use transparent User-Agents where possible.
* **Resilience:** Expect job board DOMs to change rapidly. Use graceful error handling, fallback logic, and never allow a scraper crash to break the entire pipeline.
* **Testing:** All new scrapers MUST include PHPUnit tests. Mock the HTML responses or allow for empty-array validation to prevent CI blocking due to AuthWalls.

## 🤖 AI-Agent Friendly Repository
We highly encourage the use of Agentic AI (like **Aider**, **GitHub Copilot**, or **Claude**) to resolve issues. If you are using an AI to solve an issue:
1. Look for issues on our tracker.
2. Feed the issue description directly into your agent (e.g., Aider or Cursor).
3. Ensure the AI writes PHPUnit tests for the generated code before submitting your PR!

## Development Workflow
1. Read the parent `CONTRIBUTING.md` for branch/PR standards.
2. Ensure you have run `composer install` inside the package directory.
3. Run tests using `./vendor/bin/phpunit tests/` before submitting any PR.
