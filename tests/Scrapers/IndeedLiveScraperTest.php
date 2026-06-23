<?php

declare(strict_types=1);

namespace Freeworld\PhpJobspy\Tests\Scrapers;

use Freeworld\PhpJobspy\Scrapers\IndeedScraper;
use PHPUnit\Framework\TestCase;

/**
 * @group live
 */
class IndeedLiveScraperTest extends TestCase
{
    public function test_scrape_returns_real_job_post_dtos(): void
    {
        $scraper = new IndeedScraper();
        $jobs = $scraper->scrape([
            'search_term' => 'PHP',
            'location' => 'Remote',
            'results_wanted' => 2
        ]);

        $this->assertIsArray($jobs);
        
        // Indeed may block CI/Automated requests, so we conditionally assert
        if (count($jobs) > 0) {
            $this->assertLessThanOrEqual(2, count($jobs));
            $this->assertEquals('Indeed', $jobs[0]->site);
            $this->assertNotEmpty($jobs[0]->title);
            $this->assertNotEmpty($jobs[0]->job_url);
        } else {
            $this->markTestSkipped('Live Indeed scraping returned 0 jobs. Likely blocked by anti-bot.');
        }
    }
}
