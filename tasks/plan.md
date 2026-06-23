# Plan — php-jobspy
> Spec: SPEC.md | Updated: 2026-06-23
> This file covers ONLY php-jobspy tasks.

---

## Dependency Order

```
[Q1] Provider Choice → P-01 DTO & Interface → P-02 Scraper Implementation → P-03 Jobspy Integration → P-04 Unit Tests
[Phase 2] P-05 Fetcher Abstraction → P-06 Panther Fetcher → P-07 ScraperAPI Fetcher → P-08 Refactor Scrapers
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

### P-05: Fetcher Abstraction
**Status:** Pending
**Branch:** `feat/p05-fetcher-abstraction`
- Create `src/Contracts/FetcherInterface.php` with `getHtml(string $url): string`.
- Implement `NativeHttpFetcher` using the existing `symfony/http-client` logic from `IndeedScraper`.
- Write `tests/Fetchers/NativeHttpFetcherTest.php` with `MockHttpClient`.
- **Commit:** `feat: introduce FetcherInterface and NativeHttpFetcher`

---

### P-06: Panther Fetcher & Cookie Injection
**Status:** Depends on P-05
**Branch:** `feat/p06-panther-fetcher`
- `composer require symfony/panther dbrekelmans/bdi`.
- Create `PantherFetcher` implementing `FetcherInterface`.
- Add a constructor arg to accept optional `$sessionCookies` array.
- Boot panther, inject cookies, navigate to URL, and return `client->getPageSource()`.
- Write `tests/Fetchers/PantherFetcherTest.php` (can be skipped or mocked in CI).
- **Commit:** `feat: implement PantherFetcher for local bypass`

---

### P-07: ScraperAPI Fetcher
**Status:** Depends on P-05
**Branch:** `feat/p07-scraperapi-fetcher`
- Create `ScraperApiFetcher` implementing `FetcherInterface`.
- Accept `$apiKey` via constructor (passed from config/env).
- Format the HTTP request to route through `http://api.scraperapi.com?api_key=...&url=...`.
- Return the response body.
- Write `tests/Fetchers/ScraperApiFetcherTest.php` to verify URL formatting.
- **Commit:** `feat: implement ScraperApiFetcher for SaaS deployments`

---

### P-08: Refactor Scrapers for Fetcher Injection
**Status:** Depends on P-07
**Branch:** `feat/p08-refactor-scrapers`
- Update `IndeedScraper` constructor to accept `FetcherInterface`.
- Replace inline `$this->client->request()` with `$html = $this->fetcher->getHtml($url)`.
- Update `Jobspy.php` factory logic to instantiate the correct Fetcher based on `$args` (e.g., `use_panther`, `scraper_api_key`).
- Update `IndeedScraperTest` to inject a mock `FetcherInterface`.
- **Commit:** `feat: refactor Scrapers to use injected Fetchers`

---

## Token Cost Estimate

| Task | Files | Claude Sonnet 4.5 | GPT-4o | Gemini 1.5 Pro | Aider + Ollama (local) |
|---|---|---|---|---|---|
| P-01 DTO/Interface | 2 PHP | ~1,000 tok / ~$0.01 | ~1,100 tok / ~$0.01 | ~1,000 tok / ~$0.00 | ~1,500 tok / $0 |
| P-02 Provider Impl | 1 PHP | ~2,500 tok / ~$0.04 | ~2,800 tok / ~$0.04 | ~2,500 tok / ~$0.02 | ~3,500 tok / $0 |
| P-03 Jobspy Integration| 1 PHP | ~1,500 tok / ~$0.02 | ~1,600 tok / ~$0.02 | ~1,500 tok / ~$0.01 | ~2,000 tok / $0 |
| P-04 Unit Tests | 1 PHP | ~2,500 tok / ~$0.04 | ~2,800 tok / ~$0.04 | ~2,500 tok / ~$0.02 | ~3,500 tok / $0 |
| P-05 Fetcher Abstraction| 2 PHP | ~1,500 tok / ~$0.02 | ~1,700 tok / ~$0.02 | ~1,500 tok / ~$0.01 | ~2,000 tok / $0 |
| P-06 Panther Fetcher | 2 PHP | ~2,000 tok / ~$0.03 | ~2,200 tok / ~$0.03 | ~2,000 tok / ~$0.01 | ~3,000 tok / $0 |
| P-07 ScraperAPI Fetcher| 2 PHP | ~1,500 tok / ~$0.02 | ~1,600 tok / ~$0.02 | ~1,500 tok / ~$0.01 | ~2,000 tok / $0 |
| P-08 Refactor Scrapers | 3 PHP | ~2,000 tok / ~$0.03 | ~2,200 tok / ~$0.03 | ~2,000 tok / ~$0.01 | ~3,000 tok / $0 |
| **Total** | **14 files** | **~14,500 / ~$0.21** | **~16,000 / ~$0.21**| **~14,500 / ~$0.09** | **~20,500 / $0** |

---

## Git Workflow & Commit Policy
- Create a dedicated branch for each task prefix.
- Wait for explicit user `/build` commands or permission before initiating any code changes.
- Ensure clean code comments (PHPDoc).
- `composer test` and formatting tools must pass locally before any branch merge.
