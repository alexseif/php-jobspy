# SPEC.md — php-jobspy

> **Version:** 1.0
> **Date:** 2026-06-23
> **Purpose:** Defines the goals, scope, constraints, and quality standards for the `php-jobspy` package. Agnostic to any specific LLM — readable by Aider, Cursor, Claude, or any assistant.

---

## 1. What This Package Is

`php-jobspy` is a **standalone, publishable PHP library** for aggregating job postings from public job boards into structured local data. It is inspired by the Python `jobspy` package and is designed to be:

- Dependency-light (Guzzle for HTTP, DOMDocument for parsing — no bloat)
- Pipeline-ready (outputs arrays consumable by downstream screeners)
- Agnostic to the job-finding orchestrator that calls it

It is **not** a web app, not a scraping service, and not an AI pipeline. It is a library.

### Relationship to Other Projects
- **freeworld-job-finder:** The orchestrator that uses this library. Lives in the parent directory.
- **alexseif.com:** Unrelated. This package reflects Alex's engineering skill but has no code connection to the portfolio site.

---

## 2. Target API & Data Schema (Python Parity)

To maintain parity with `python-jobspy`, the `Jobspy` class must support the following input schema and output a standard `JobPost` structure.

### 2.1 Input Parameters

```php
public function scrapeJobs(array $args): array
{
    // Target Parameter Schema:
    // 'site_name' => array|string, // e.g. ['linkedin', 'indeed', 'zip_recruiter', 'glassdoor']
    // 'search_term' => string,
    // 'location' => string,
    // 'distance' => int, // in miles
    // 'job_type' => string, // fulltime, parttime, internship, contract
    // 'proxies' => array, // ['user:pass@host:port', 'localhost']
    // 'is_remote' => bool,
    // 'results_wanted' => int,
    // 'easy_apply' => bool,
    // 'offset' => int,
    // 'hours_old' => int,
    // 'linkedin_fetch_description' => bool,
}
```

### 2.2 Output Structure (`JobPostDTO`)

To enforce strict type safety and adhere to the `php-modernization-skill`, the returned array of jobs must be hydrated into a strongly typed DTO (`readonly class` or similar) matching this schema:

```php
readonly class JobPostDTO {
    public string $site;
    public string $title;
    public string $company;
    public string $company_url;
    public string $job_url;
    public string $location; // format: "City, State, Country"
    public bool $is_remote;
    public string $description;
    public ?string $job_type;
    public ?string $interval; // yearly, monthly, weekly, daily, hourly
    public int|float|null $min_amount;
    public int|float|null $max_amount;
    public ?string $currency;
    public ?string $date_posted; // string/datetime
}
```

## 3. Current Capabilities vs. Roadmap

| Feature | Status |
|---|---|
| Job list export to CSV | ✅ Implemented |
| Output matching JobPost Schema | ❌ Partial (needs alignment) |
| Scrape-friendly Provider (Indeed/ZipRecruiter) | ❌ Immediate Focus |
| Pagination & Proxies | ❌ Roadmap |
| LinkedIn public SERP scraping | ✅ Implemented (auth-wall prone, paused) |
| Glassdoor / Google Jobs / Bayt | ❌ Roadmap |
| Unit tests for scraper | ❌ Not yet written |

---

## 4. Immediate Work Item — Scrape-Friendly Provider Integration

### Problem
The current implementation started with LinkedIn, which is notoriously hostile to automated scraping (aggressive rate limiting, auth-walls, and forced CAPTCHAs). Continuing to build the core architecture around LinkedIn's quirks will slow down development and require complex proxy management immediately.

### Solution
Pivot the initial development focus to a more scrape-friendly provider (e.g., Indeed, ZipRecruiter, or Bayt). Python `jobspy` explicitly notes that Indeed is currently the best scraper with minimal rate limiting, making it an ideal candidate to establish the core pipeline.

**Action Plan:**
1. Choose the most permissive job board from the expected provider list.
2. Implement the `ScraperInterface` for this new provider.
3. Establish the HTTP request flow, HTML parsing (or JSON API interception), and map the output precisely to the `JobPost` schema defined in Section 2.2.
4. Ensure the downstream `JobScreener` pipeline can cleanly receive this data without needing a "deep-fetch" workaround (as scrape-friendly sites often include the description in the initial payload or a simple secondary open request).

Once the core architecture and output schema are solidified with a stable provider, we can re-introduce LinkedIn behind a proxy rotation manager in Phase 2.

---

## 5. Tech Stack

| Layer | Technology |
|---|---|
| Language | PHP 8.1+ (strict_types) |
| HTTP Client | `symfony/http-client` (PSR-18 compatible, natively async) |
| HTML Parser | `symfony/dom-crawler` + `symfony/css-selector` |
| Config | `symfony/yaml` |
| Testing | PHPUnit |
| Package Manager | Composer |

**Constraint:** Stick to Symfony components to maintain enterprise stability and avoid bloat.

---

## 6. Testing Standards

| Type | Command | Required For |
|---|---|---|
| Unit tests | `composer test` | Every commit |
| Static analysis | (future) PHPStan | Roadmap |

Tests use mock HTTP clients (`Symfony\Component\HttpClient\MockHttpClient`) — no live network calls in the test suite.

---

## 7. Code Style & Git Workflow

- **PSR & PER-CS**: Code must strictly conform to PER-CS (superseding PSR-12).
- **Type Safety**: `declare(strict_types=1)` on every file. Methods return typed values. Use DTOs instead of raw associative arrays (`php-modernization-skill`).
- **Interfaces**: Code against PSR interfaces (e.g., `Psr\Http\Client\ClientInterface`) instead of concrete classes where possible.
- **Git Workflow**: Use proper branch structures (`feat/`, `fix/`, `test/`) and semantic commit messages for all tasks. Commits must pass tests.
- **Error Handling**: Silent failure over exception propagation for network-layer errors.

---

## 8. Boundaries

### Always
- Keep this as a library — no HTTP endpoints, no CLI commands in this package
- Silent fallback on all network failures (never crash the calling pipeline)
- Polite rate limiting: 500ms minimum between HTTP requests

### Ask First
- Adding a new job board scraper (affects package scope)
- Changing the job array schema (breaks downstream CSV and screener)

### Never
- Store credentials or API keys in source code
- Add HTTP server capabilities
- Couple this package to `freeworld-job-finder` internal classes
