# TODO — php-jobspy
> Spec: SPEC.md | Plan: tasks/plan.md
> This file covers ONLY php-jobspy tasks.

---

## Blocked — Answer Required Before Building

- [x] **Q1:** Which scrape-friendly provider should we establish the baseline with? **(Indeed)**
- [ ] **AWAITING `/build` COMMAND**

---

## Pending Execution

### P-01: JobPostDTO & ScraperInterface
- [x] `git checkout -b feat/p01-dto-interface`
- [x] `composer require symfony/http-client symfony/dom-crawler symfony/css-selector`
- [x] Create `src/DTO/JobPostDTO.php` as a `readonly class`.
- [x] Update `src/Scrapers/ScraperInterface.php` to return `JobPostDTO[]`.
- [x] Code comments & PER-CS formatting.
- [x] Commit: `feat: implement JobPostDTO and ScraperInterface with Symfony dependencies`

### P-02: Scrape-Friendly Provider Implementation (Indeed)
- [x] `git checkout -b feat/p02-scraper-impl`
- [x] Create `src/Scrapers/IndeedScraper.php`.
- [x] Implement `symfony/http-client` HTTP request with user-agents.
- [x] Implement `symfony/dom-crawler` parsing.
- [x] Map output to `JobPostDTO`.
- [x] Code comments & PER-CS formatting.
- [x] Commit: `feat: implement initial Indeed scraper using Symfony components`

### P-03: Jobspy Core Integration
- [x] `git checkout -b feat/p03-jobspy-integration`
- [x] Update `src/Jobspy.php` to instantiate the new provider.
- [x] Refactor `exportToCsv()` to handle `JobPostDTO` property access.
- [x] Code comments & PER-CS formatting.
- [x] Commit: `feat: integrate provider and refactor Jobspy for DTO handling`

### P-04: Unit Test Suite & Code Quality
- [x] `git checkout -b test/p04-scraper-tests`
- [x] Create `tests/Scrapers/{Provider}ScraperTest.php`.
- [x] Implement `MockHttpClient` returning static HTML fixture.
- [x] Assert extraction logic correctly populates the DTO.
- [x] Verify: `composer test` passes.
- [x] Commit: `test: add provider unit tests and conform to PER-CS`

---

## Phase 2: Fetcher Abstraction & Anti-Bot Architecture

### P-05: Fetcher Abstraction
- [x] `git checkout -b feat/p05-fetcher-abstraction`
- [x] Create `Contracts/FetcherInterface.php`.
- [x] Create `NativeHttpFetcher.php` (move HttpClient logic here).
- [x] Write `NativeHttpFetcherTest.php`.
- [x] Commit: `feat: introduce FetcherInterface and NativeHttpFetcher`

### P-06: Panther Fetcher & Cookie Injection
- [x] `git checkout -b feat/p06-panther-fetcher`
- [x] `composer require symfony/panther dbrekelmans/bdi`
- [x] Create `PantherFetcher.php`.
- [x] Inject cookies and return Panther's DOM source.
- [x] Write `PantherFetcherTest.php`.
- [x] Commit: `feat: implement PantherFetcher for local bypass`

### P-07: ScraperAPI Fetcher
- [x] `git checkout -b feat/p07-scraperapi-fetcher`
- [x] Create `ScraperApiFetcher.php`.
- [x] Accept API key and route URL via `api.scraperapi.com`.
- [x] Write `ScraperApiFetcherTest.php`.
- [x] Commit: `feat: implement ScraperApiFetcher for SaaS deployments`

### P-08: Refactor Scrapers for Fetcher Injection
- [x] `git checkout -b feat/p08-refactor-scrapers`
- [x] Refactor `IndeedScraper` to use `FetcherInterface->getHtml()`.
- [x] Update `Jobspy.php` to map `$args` to the correct Fetcher instance.
- [x] Refactor `IndeedScraperTest.php` to inject mock fetcher.
- [x] Commit: `feat: refactor Scrapers to use injected Fetchers`
