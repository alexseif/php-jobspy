# php-jobspy Roadmap

This package is designed to be a standalone, deployable PHP library for aggregating job postings across multiple platforms cleanly into structured local data. It strictly mimics the feature set and schema of `python-jobspy`.

## Phase 1: Core Architecture & Scrape-Friendly Provider
- [x] Define Target API & `JobPostDTO` Schema
- [x] CSV Export pipeline
- [ ] Implement robust `symfony/http-client` with user-agent rotation
- [ ] Implement a scrape-friendly primary provider (e.g., ZipRecruiter or Indeed)
- [ ] Unit test suite with `MockHttpClient`

## Phase 2: Input Parameters & Pagination Parity
- [ ] Pagination handling (`offset`, `results_wanted`)
- [ ] Proxy rotation manager (`proxies` array)
- [ ] Search filters (`distance`, `is_remote`, `job_type`, `hours_old`)
- [ ] YAML configuration for safe operations and preferences

## Phase 3: Job Board Expansions (Expected Providers)
Expand the library to support the full suite of providers targeted by python-jobspy:
- [ ] **Indeed**: High priority target (often noted as most reliable for volume scraping).
- [ ] **ZipRecruiter**: Target for US/Canada focused job searches.
- [ ] **Google Jobs**: Implement SERP parsing for jobs.
- [ ] **Glassdoor**: Implement location-specific searching and payload extraction.
- [ ] **Bayt / Naukri / Bdjobs**: International and regional job boards.
- [ ] **LinkedIn**: (Partially implemented) Deprioritize deep-fetch until proxy rotation is established due to aggressive auth-walls.

## Phase 4: Output & Optimization
- [ ] Concurrent/Asynchronous HTTP requests leveraging native `symfony/http-client` async capabilities
- [ ] Forked/Parallel searches (scraping multiple job boards simultaneously) instead of sequential execution
- [ ] JSON & direct Database (SQLite/MySQL) export features
- [ ] Salary formatting and extraction (min_amount, max_amount, interval)
