<?php

declare(strict_types=1);

namespace Freeworld\PhpJobspy\Tests;

use Freeworld\PhpJobspy\Jobspy;
use PHPUnit\Framework\TestCase;

class JobspyTest extends TestCase
{
    private Jobspy $jobspy;

    protected function setUp(): void
    {
        $this->jobspy = new Jobspy();
    }

    public function testScrapeJobsReturnsArray(): void
    {
        $jobs = $this->jobspy->scrapeJobs(['search_term' => 'PHP']);
        $this->assertIsArray($jobs);
        $this->assertNotEmpty($jobs);
        $this->assertArrayHasKey('title', $jobs[0]);
    }

    public function testExportToCsvCreatesFileAndWritesData(): void
    {
        $jobs = [
            ['title' => 'Test Job', 'company' => 'Test Co']
        ];
        
        $tempFile = sys_get_temp_dir() . '/test_jobs_' . uniqid() . '.csv';
        
        $result = $this->jobspy->exportToCsv($jobs, $tempFile);
        
        $this->assertTrue($result);
        $this->assertFileExists($tempFile);
        
        $content = file_get_contents($tempFile);
        $this->assertStringContainsString('title,company', $content);
        $this->assertStringContainsString('"Test Job","Test Co"', $content);
        
        unlink($tempFile);
    }
}
