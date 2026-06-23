<?php

declare(strict_types=1);

namespace Freeworld\PhpJobspy\Scrapers;

use Freeworld\PhpJobspy\DTO\JobPostDTO;

class MockScraper implements ScraperInterface
{
    /**
     * @return JobPostDTO[]
     */
    public function scrape(array $args): array
    {
        return [
            new JobPostDTO(
                site: 'MockProvider',
                title: 'Senior PHP Developer',
                company: 'Tech Innovators B.V.',
                company_url: 'https://techinnovators.com',
                job_url: 'https://mock.com/job/1',
                location: 'Amsterdam, Netherlands',
                is_remote: false,
                description: 'We are looking for a PHP expert with Symfony experience...',
                date_posted: date('Y-m-d')
            ),
            new JobPostDTO(
                site: 'MockProvider',
                title: 'Backend Engineer (PHP)',
                company: 'Dutch FinTech',
                company_url: '',
                job_url: 'https://mock.com/job/2',
                location: 'Rotterdam, Netherlands',
                is_remote: true,
                description: 'Seeking a backend engineer to manage our MySQL databases...',
                date_posted: date('Y-m-d', strtotime('-1 day'))
            )
        ];
    }
}
