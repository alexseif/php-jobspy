<?php

declare(strict_types=1);

namespace Freeworld\PhpJobspy;

use Freeworld\PhpJobspy\Scrapers\LinkedInScraper;

class Jobspy
{
    /**
     * Scrapes jobs based on the provided parameters.
     * Integrates with individual platform scrapers based on 'site_name'.
     *
     * @param array $args
     * @return array
     */
    public function scrapeJobs(array $args): array
    {
        $sites = $args['site_name'] ?? ['linkedin'];
        $jobs = [];

        if (in_array('linkedin', $sites, true)) {
            $linkedInScraper = new LinkedInScraper();
            $linkedinJobs = $linkedInScraper->scrape($args);
            $jobs = array_merge($jobs, $linkedinJobs);
        }

        if (empty($jobs)) {
            echo "Warning: Live scraping returned 0 jobs (AuthWall/RateLimit?). Falling back to mock data for pipeline testing.\n";
            return [
                [
                    'title' => 'Senior PHP Developer',
                    'company' => 'Tech Innovators B.V.',
                    'location' => 'Amsterdam, Netherlands',
                    'is_remote' => 'No',
                    'date_posted' => date('Y-m-d'),
                    'time_ago' => '1 hour ago',
                    'is_easy_apply' => 'Yes',
                    'url' => 'https://www.linkedin.com/job/1',
                    'description' => 'We are looking for a PHP expert with Symfony experience...',
                ],
                [
                    'title' => 'Backend Engineer (PHP)',
                    'company' => 'Dutch FinTech',
                    'location' => 'Rotterdam, Netherlands',
                    'is_remote' => 'Yes',
                    'date_posted' => date('Y-m-d', strtotime('-1 day')),
                    'time_ago' => '1 day ago',
                    'is_easy_apply' => 'No',
                    'url' => 'https://www.linkedin.com/job/2',
                    'description' => 'Seeking a backend engineer to manage our MySQL databases...',
                ]
            ];
        }

        return $jobs;
    }

    /**
     * Exports an array of jobs to a CSV file.
     *
     * @param array $jobs The scraped jobs array.
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

        // Write headers
        fputcsv($file, array_keys($jobs[0]));

        // Write rows
        foreach ($jobs as $job) {
            fputcsv($file, $job);
        }

        fclose($file);
        return true;
    }
}
