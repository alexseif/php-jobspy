# Plan — php-jobspy
> Spec: SPEC.md | Updated: 2026-06-23
> This file covers ONLY php-jobspy tasks.

---

## Dependency Order

```
[Q1] Provider Choice → P-01 DTO & Interface → P-02 Scraper Implementation → P-03 Jobspy Integration → P-04 Unit Tests
[Phase 2] P-05 Panther Installation → P-06 Auth/Cookie Interface → P-07 Panther Scraper Implementation
```

---

## Tasks

### P-01: JobPostDTO & ScraperInterface
**Status:** Blocked on Q1 (User confirmation of starting provider)
**Branch:** `feat/p01-dto-interface`
- Generate a strongly typed `JobPostDTO` using `readonly class` (PHP 8.2+) standardizing output.
- Refactor `ScraperInterface` to enforce `/** @return JobPostDTO[] */`.
- Update composer dependencies: `composer require symfony/http-client symfony/dom-crawler symfony/css-selector`
- **Commit:** `feat: implement JobPostDTO and ScraperInterface with Symfony dependencies`

---

### P-02: Scrape-Friendly Provider Implementation
**Status:** Depends on P-01
**Branch:** `feat/p02-scraper-impl`
- Create `src/Scrapers/{Provider}Scraper.php` implementing `ScraperInterface`.
- Leverage `symfony/http-client` for network requests (handling timeouts gracefully).
- Leverage `symfony/dom-crawler` and `symfony/css-selector` to parse HTML.
- **Commit:** `feat: implement initial {Provider} scraper using Symfony components`

---

### P-03: Jobspy Core Integration
**Status:** Depends on P-02
**Branch:** `feat/p03-jobspy-integration`
- Refactor `Jobspy::scrapeJobs()` to map incoming parameters correctly.
- Ensure instantiation of the new Provider Scraper.
- Update `Jobspy::exportToCsv()` to handle extracting data from `JobPostDTO` instead of arrays.
- **Commit:** `feat: integrate provider and refactor Jobspy for DTO handling`

---

### P-04: Unit Test Suite & Code Quality
**Status:** Depends on P-03
**Branch:** `test/p04-scraper-tests`
- Create `tests/Scrapers/{Provider}ScraperTest.php`.
- Use `Symfony\Component\HttpClient\MockHttpClient` to serve offline HTML fixtures.
- Assert correct extraction and hydration into `JobPostDTO`s.
- Run `composer test` and code style fixers (`PER-CS`).
- **Commit:** `test: add provider unit tests and conform to PER-CS`

---

### P-05: Symfony Panther Integration
**Status:** Pending
**Branch:** `feat/p05-panther-integration`
- Update `composer.json` to require `symfony/panther` and `dbrekelmans/bdi`.
- Add a configuration block or `.env` variable setup to manage the local ChromeDriver/GeckoDriver installation.
- Verify Panther can boot up locally via a basic assertion.
- **Commit:** `feat: integrate symfony/panther for browser automation`

---

### P-06: Authentication & Cookie Interface
**Status:** Depends on P-05
**Branch:** `feat/p06-cookie-interface`
- Update `ScraperInterface` and/or `Jobspy` argument schema to accept a new `session_cookies` array.
- Create a reusable `PantherClientFactory` that:
  1. Boots Panther.
  2. Navigates to the target domain's homepage.
  3. Injects the provided `session_cookies` (e.g., `['name' => 'li_at', 'value' => '...', 'domain' => '.linkedin.com']`).
- **Commit:** `feat: implement PantherClientFactory with cookie injection`

---

### P-07: Panther Scraper Implementation (Indeed/LinkedIn)
**Status:** Depends on P-06
**Branch:** `feat/p07-panther-scrapers`
- Refactor `IndeedScraper` (or create `LinkedInScraper`) to conditionally use the `PantherClientFactory` if the HTTP client fails or if `use_panther` is true.
- Execute DOM parsing using Panther's native crawler.
- Map the authenticated DOM to `JobPostDTO`.
- **Commit:** `feat: implement authenticated scraper using Panther`

---

## Token Cost Estimate

| Task | Files | Claude Sonnet 4.5 | GPT-4o | Gemini 1.5 Pro | Aider + Ollama (local) |
|---|---|---|---|---|---|
| P-01 DTO/Interface | 2 PHP | ~1,000 tok / ~$0.01 | ~1,100 tok / ~$0.01 | ~1,000 tok / ~$0.00 | ~1,500 tok / $0 |
| P-02 Provider Impl | 1 PHP | ~2,500 tok / ~$0.04 | ~2,800 tok / ~$0.04 | ~2,500 tok / ~$0.02 | ~3,500 tok / $0 |
| P-03 Jobspy Integration| 1 PHP | ~1,500 tok / ~$0.02 | ~1,600 tok / ~$0.02 | ~1,500 tok / ~$0.01 | ~2,000 tok / $0 |
| P-04 Unit Tests | 1 PHP | ~2,500 tok / ~$0.04 | ~2,800 tok / ~$0.04 | ~2,500 tok / ~$0.02 | ~3,500 tok / $0 |
| **Total** | **5 files** | **~7,500 / ~$0.11** | **~8,300 / ~$0.11** | **~7,500 / ~$0.05** | **~10,500 / $0** |

---

## Git Workflow & Commit Policy
- Create a dedicated branch for each task prefix.
- Wait for explicit user `/build` commands or permission before initiating any code changes.
- Ensure clean code comments (PHPDoc).
- `composer test` and formatting tools must pass locally before any branch merge.
