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
- [ ] `git checkout -b test/p04-scraper-tests`
- [ ] Create `tests/Scrapers/{Provider}ScraperTest.php`.
- [ ] Implement `MockHttpClient` returning static HTML fixture.
- [ ] Assert extraction logic correctly populates the DTO.
- [ ] Verify: `composer test` passes.
- [ ] Commit: `test: add provider unit tests and conform to PER-CS`
