# TODO — php-jobspy
> Spec: SPEC.md | Plan: tasks/plan.md
> This file covers ONLY php-jobspy tasks.

---

## Blocked — Answer Required Before Building

- [ ] **Q1:** Which scrape-friendly provider should we establish the baseline with? (A. Indeed, B. ZipRecruiter, C. Bayt, or other)
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

### P-02: Scrape-Friendly Provider Implementation
- [ ] `git checkout -b feat/p02-scraper-impl`
- [ ] Create `src/Scrapers/{Provider}Scraper.php`.
- [ ] Implement `symfony/http-client` HTTP request with user-agents.
- [ ] Implement `symfony/dom-crawler` parsing.
- [ ] Map output to `JobPostDTO`.
- [ ] Code comments & PER-CS formatting.
- [ ] Commit: `feat: implement initial {Provider} scraper using Symfony components`

### P-03: Jobspy Core Integration
- [ ] `git checkout -b feat/p03-jobspy-integration`
- [ ] Update `src/Jobspy.php` to instantiate the new provider.
- [ ] Refactor `exportToCsv()` to handle `JobPostDTO` property access.
- [ ] Code comments & PER-CS formatting.
- [ ] Commit: `feat: integrate provider and refactor Jobspy for DTO handling`

### P-04: Unit Test Suite & Code Quality
- [ ] `git checkout -b test/p04-scraper-tests`
- [ ] Create `tests/Scrapers/{Provider}ScraperTest.php`.
- [ ] Implement `MockHttpClient` returning static HTML fixture.
- [ ] Assert extraction logic correctly populates the DTO.
- [ ] Verify: `composer test` passes.
- [ ] Commit: `test: add provider unit tests and conform to PER-CS`
