<?php

declare(strict_types=1);

namespace Freeworld\PhpJobspy\Scrapers;

use Freeworld\PhpJobspy\DTO\JobPostDTO;

interface ScraperInterface
{
    /**
     * Scrapes job postings based on the provided parameters.
     *
     * @param array $args The search parameters (e.g. search_term, location, results_wanted).
     * @return JobPostDTO[] List of jobs matching the standard DTO schema.
     */
    public function scrape(array $args): array;
}
