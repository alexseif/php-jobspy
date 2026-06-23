<?php

declare(strict_types=1);

namespace Freeworld\PhpJobspy\Tests\Integration;

use Freeworld\PhpJobspy\Scrapers\PythonJobspyScraper;
use PHPUnit\Framework\TestCase;

/**
 * @group live
 */
class PythonScraperIntegrationTest extends TestCase
{
    public function test_scrape_returns_live_jobs(): void
    {
        $scraper = new PythonJobspyScraper();
        $jobs = $scraper->scrape([
            'site_name' => ['indeed'],
            'search_term' => 'Software Engineer',
            'location' => 'Remote',
            'results_wanted' => 2
        ]);

        $this->assertNotEmpty($jobs, 'Expected live scraping to return jobs via python-jobspy');
        $this->assertLessThanOrEqual(5, count($jobs));
        $this->assertEquals('indeed', strtolower($jobs[0]->site));
        $this->assertNotEmpty($jobs[0]->title);
        $this->assertNotEmpty($jobs[0]->company);
    }
}
