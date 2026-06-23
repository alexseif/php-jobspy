<?php

declare(strict_types=1);

namespace Freeworld\PhpJobspy\Tests\Scrapers;

use Freeworld\PhpJobspy\Scrapers\IndeedScraper;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

class IndeedScraperTest extends TestCase
{
    public function test_scrape_parses_html_into_job_post_dtos(): void
    {
        $dummyHtml = <<<HTML
        <html>
            <body>
                <div class="job_seen_beacon">
                    <h2 class="jobTitle"><span>Senior PHP Engineer</span></h2>
                    <span class="companyName">Tech Innovations Inc.</span>
                    <div class="companyLocation">Remote, NY</div>
                    <div class="jobMetaDataGroup">Full-time</div>
                    <div class="job-snippet"><li>Strong PHP skills required.</li></div>
                    <a class="jcs-JobTitle" href="/rc/clk?jk=12345">Link</a>
                </div>
            </body>
        </html>
        HTML;

        $mockResponse = new MockResponse($dummyHtml);
        $client = new MockHttpClient($mockResponse);
        
        $scraper = new IndeedScraper($client);
        
        $results = $scraper->scrape([
            'search_term' => 'PHP',
            'location' => 'Remote',
            'results_wanted' => 1
        ]);

        $this->assertCount(1, $results);
        $this->assertEquals('Indeed', $results[0]->site);
        $this->assertEquals('Senior PHP Engineer', $results[0]->title);
        $this->assertEquals('Tech Innovations Inc.', $results[0]->company);
        $this->assertEquals('Remote, NY', $results[0]->location);
        $this->assertTrue($results[0]->is_remote);
        $this->assertEquals('https://www.indeed.com/viewjob?jk=12345', $results[0]->job_url);
        $this->assertEquals('Strong PHP skills required.', trim($results[0]->description));
    }
}
