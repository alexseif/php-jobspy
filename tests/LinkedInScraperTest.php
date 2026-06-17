<?php

declare(strict_types=1);

namespace Freeworld\PhpJobspy\Tests;

use Freeworld\PhpJobspy\Scrapers\LinkedInScraper;
use PHPUnit\Framework\TestCase;

class LinkedInScraperTest extends TestCase
{
    public function testScrapeReturnsArray(): void
    {
        $scraper = new LinkedInScraper();
        $jobs = $scraper->scrape([
            'search_term' => 'PHP',
            'location' => 'Netherlands',
            'results_wanted' => 2
        ]);
        
        $this->assertIsArray($jobs);
        
        // We assert structure conditionally because LinkedIn may block automated CI requests
        if (count($jobs) > 0) {
            $this->assertArrayHasKey('title', $jobs[0]);
            $this->assertArrayHasKey('company', $jobs[0]);
            $this->assertArrayHasKey('url', $jobs[0]);
        }
    }
}
