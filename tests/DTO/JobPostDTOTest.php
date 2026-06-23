<?php

declare(strict_types=1);

namespace Freeworld\PhpJobspy\Tests\DTO;

use Freeworld\PhpJobspy\DTO\JobPostDTO;
use PHPUnit\Framework\TestCase;

class JobPostDTOTest extends TestCase
{
    public function test_it_can_be_instantiated_with_all_properties(): void
    {
        $dto = new JobPostDTO(
            site: 'Indeed',
            title: 'Senior PHP Developer',
            company: 'Tech Corp',
            company_url: 'https://techcorp.com',
            job_url: 'https://indeed.com/job/123',
            location: 'Remote, US',
            is_remote: true,
            description: 'We are looking for a PHP dev...',
            job_type: 'fulltime',
            interval: 'yearly',
            min_amount: 100000,
            max_amount: 150000,
            currency: 'USD',
            date_posted: '2026-06-23'
        );

        $this->assertEquals('Indeed', $dto->site);
        $this->assertEquals('Senior PHP Developer', $dto->title);
        $this->assertEquals('Tech Corp', $dto->company);
        $this->assertEquals('https://techcorp.com', $dto->company_url);
        $this->assertEquals('https://indeed.com/job/123', $dto->job_url);
        $this->assertEquals('Remote, US', $dto->location);
        $this->assertTrue($dto->is_remote);
        $this->assertEquals('We are looking for a PHP dev...', $dto->description);
        $this->assertEquals('fulltime', $dto->job_type);
        $this->assertEquals('yearly', $dto->interval);
        $this->assertEquals(100000, $dto->min_amount);
        $this->assertEquals(150000, $dto->max_amount);
        $this->assertEquals('USD', $dto->currency);
        $this->assertEquals('2026-06-23', $dto->date_posted);
    }

    public function test_it_allows_nullable_fields(): void
    {
        $dto = new JobPostDTO(
            site: 'Indeed',
            title: 'Senior PHP Developer',
            company: 'Tech Corp',
            company_url: 'https://techcorp.com',
            job_url: 'https://indeed.com/job/123',
            location: 'Remote, US',
            is_remote: true,
            description: 'We are looking for a PHP dev...',
            job_type: null,
            interval: null,
            min_amount: null,
            max_amount: null,
            currency: null,
            date_posted: null
        );

        $this->assertNull($dto->job_type);
        $this->assertNull($dto->interval);
        $this->assertNull($dto->min_amount);
        $this->assertNull($dto->max_amount);
        $this->assertNull($dto->currency);
        $this->assertNull($dto->date_posted);
    }
}
