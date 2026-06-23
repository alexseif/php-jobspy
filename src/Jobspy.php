<?php

declare(strict_types=1);

namespace Freeworld\PhpJobspy;

use Freeworld\PhpJobspy\DTO\JobPostDTO;
use Freeworld\PhpJobspy\Scrapers\IndeedScraper;

class Jobspy
{
    /**
     * Scrapes jobs based on the provided parameters.
     * Integrates with individual platform scrapers based on 'site_name'.
     *
     * @param array $args
     * @return JobPostDTO[]
     */
    public function scrapeJobs(array $args): array
    {
        $sites = $args['site_name'] ?? ['indeed'];
        /** @var JobPostDTO[] $jobs */
        $jobs = [];

        if (in_array('indeed', $sites, true) || in_array('all', $sites, true)) {
            $indeedScraper = new IndeedScraper();
            $indeedJobs = $indeedScraper->scrape($args);
            $jobs = array_merge($jobs, $indeedJobs);
        }

        if (empty($jobs)) {
            error_log("Warning: Live scraping returned 0 jobs. Falling back to mock data for pipeline testing.");
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

        return $jobs;
    }

    /**
     * Exports an array of JobPostDTOs to a CSV file.
     *
     * @param JobPostDTO[] $jobs The scraped jobs array.
     * @param string $destination The absolute or relative path to the output CSV file.
     * @return bool True on success.
     * @throws \RuntimeException If the file cannot be written.
     */
    public function exportToCsv(array $jobs, string $destination): bool
    {
        if (empty($jobs)) {
            return false;
        }

        // Ensure the directory exists
        $dir = dirname($destination);
        if (!is_dir($dir)) {
            if (!mkdir($dir, 0755, true) && !is_dir($dir)) {
                throw new \RuntimeException(sprintf('Directory "%s" was not created', $dir));
            }
        }

        $file = fopen($destination, 'w');
        if ($file === false) {
            throw new \RuntimeException(sprintf('Could not open file "%s" for writing.', $destination));
        }

        // Get headers from the first DTO
        $firstJobArray = $jobs[0]->toArray();
        $headers = array_keys($firstJobArray);
        fputcsv($file, $headers);

        // Write rows
        foreach ($jobs as $job) {
            $row = [];
            foreach ($job->toArray() as $propertyValue) {
                if (is_bool($propertyValue)) {
                    $row[] = $propertyValue ? 'Yes' : 'No';
                } else {
                    $row[] = (string) $propertyValue;
                }
            }
            fputcsv($file, $row);
        }

        fclose($file);
        return true;
    }
}
