<?php

require 'vendor/autoload.php';

use Freeworld\PhpJobspy\Jobspy;

$jobspy = new Jobspy();

echo "Running live scrape with Python backend...\n";

try {
    $jobs = $jobspy->scrapeJobs([
        'site_name' => ['indeed'],
        'search_term' => 'PHP developer',
        'location' => 'Remote',
        'use_python' => true,
        'results_wanted' => 2
    ]);

    echo "Found " . count($jobs) . " jobs.\n";
    foreach ($jobs as $job) {
        echo "- {$job->title} at {$job->company} ({$job->location})\n";
        echo "  URL: {$job->job_url}\n";
    }
} catch (\Throwable $e) {
    echo "Error during scraping: " . $e->getMessage() . "\n";
}
