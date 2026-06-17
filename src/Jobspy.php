<?php

declare(strict_types=1);

namespace Freeworld\PhpJobspy;

class Jobspy
{
    /**
     * Scrapes jobs based on the provided parameters.
     * For now, returns mock data representing future scraped output.
     *
     * @param array $args
     * @return array
     */
    public function scrapeJobs(array $args): array
    {
        // Mock data to validate structural integrity and CSV generation
        return [
            [
                'title' => 'Senior PHP Developer',
                'company' => 'Tech Innovators B.V.',
                'location' => 'Amsterdam, Netherlands',
                'description' => 'We are looking for a PHP expert with Symfony experience...',
                'url' => 'https://example.com/job/1'
            ],
            [
                'title' => 'Backend Engineer (PHP)',
                'company' => 'Dutch FinTech',
                'location' => 'Rotterdam, Netherlands',
                'description' => 'Seeking a backend engineer to manage our MySQL databases...',
                'url' => 'https://example.com/job/2'
            ]
        ];
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
