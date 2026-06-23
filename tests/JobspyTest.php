<?php

declare(strict_types=1);

namespace Freeworld\PhpJobspy\Tests;

use Freeworld\PhpJobspy\DTO\JobPostDTO;
use Freeworld\PhpJobspy\Jobspy;
use PHPUnit\Framework\TestCase;

class JobspyTest extends TestCase
{
    private Jobspy $jobspy;

    protected function setUp(): void
    {
        $this->jobspy = new Jobspy();
    }

    public function testScrapeJobsReturnsArrayOfDTOs(): void
    {
        // Use the 'mock' site_name to trigger the mock data scraper
        // so we don't hit live Indeed and cause rate limits or timeouts in tests.
        $jobs = $this->jobspy->scrapeJobs([
            'site_name' => ['mock'],
            'search_term' => 'PHP'
        ]);
        
        $this->assertIsArray($jobs);
        $this->assertNotEmpty($jobs);
        $this->assertInstanceOf(JobPostDTO::class, $jobs[0]);
        $this->assertEquals('MockProvider', $jobs[0]->site);
    }

    public function testExportToCsvCreatesFileAndWritesData(): void
    {
        $jobs = [
            new JobPostDTO(
                site: 'Indeed',
                title: 'Test Job',
                company: 'Test Co',
                company_url: '',
                job_url: 'https://test.com',
                location: 'Remote',
                is_remote: true,
                description: 'A test job'
            )
        ];
        
        $tempFile = sys_get_temp_dir() . '/test_jobs_' . uniqid() . '.csv';
        
        $result = $this->jobspy->exportToCsv($jobs, $tempFile);
        
        $this->assertTrue($result);
        $this->assertFileExists($tempFile);
        
        $content = file_get_contents($tempFile);
        $this->assertStringContainsString('site,title,company,company_url,job_url,location,is_remote,description', $content);
        $this->assertStringContainsString('Indeed,"Test Job","Test Co"', $content);
        
        unlink($tempFile);
    }
}
