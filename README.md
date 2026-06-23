# php-jobspy

A standalone PHP library inspired by [python-jobspy](https://github.com/Bunsly/JobSpy). This package aggregates job postings across multiple platforms cleanly into structured local data without heavy web overhead.

## Capabilities

- Scrape job boards seamlessly.
- Target specific niches or geographical areas.
- Export results cleanly into arrays, objects, or CSV files for downstream processing.

## Usage

```php
use Freeworld\PhpJobspy\Jobspy;

$jobspy = new Jobspy();

$jobs = $jobspy->scrapeJobs([
    'site_name' => ['linkedin', 'indeed'],
    'search_term' => 'Senior PHP Developer',
    'location' => 'Netherlands',
    'results_wanted' => 20
]);

// Export to a CSV in an outside folder
$jobspy->exportToCsv($jobs, '/path/to/outside/folder/jobs.csv');
```
