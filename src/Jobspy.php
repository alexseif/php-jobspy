<?php

declare(strict_types=1);

namespace Freeworld\PhpJobspy;

use Freeworld\PhpJobspy\DTO\JobPostDTO;
use Freeworld\PhpJobspy\Fetchers\NativeHttpFetcher;
use Freeworld\PhpJobspy\Fetchers\PantherFetcher;
use Freeworld\PhpJobspy\Fetchers\ScraperApiFetcher;
use Freeworld\PhpJobspy\Scrapers\IndeedScraper;
use Symfony\Component\Yaml\Yaml;

class Jobspy
{
    private array $config = [];

    public function __construct(string $configPath = 'config.yml')
    {
        if (file_exists($configPath)) {
            $this->config = Yaml::parseFile($configPath) ?? [];
        }
    }

    /**
     * Scrapes jobs based on the provided parameters.
     * Integrates with individual platform scrapers based on 'site_name'.
     *
     * @param array $args
     * @return JobPostDTO[]
     */
    public function scrapeJobs(array $args = []): array
    {
        $sites = $args['site_name'] ?? $this->config['default_sites'] ?? ['indeed'];
        /** @var JobPostDTO[] $jobs */
        $jobs = [];

        if (in_array('indeed', $sites, true) || in_array('all', $sites, true)) {
            $fetcherType = $this->config['fetcher'] ?? 'native';
            $apiKey = $args['scraper_api_key'] ?? $this->config['scraper_api_key'] ?? null;
            $usePanther = !empty($args['use_panther']) || $fetcherType === 'panther';
            $usePython = !empty($args['use_python']) || $fetcherType === 'python';

            if ($usePython) {
                $pythonScraper = new \Freeworld\PhpJobspy\Scrapers\PythonJobspyScraper();
                $indeedJobs = $pythonScraper->scrape($args);
            } else {
                if (!empty($apiKey) || $fetcherType === 'scraper_api') {
                    $fetcher = new ScraperApiFetcher($apiKey ?? '');
                } elseif ($usePanther) {
                    $fetcher = new PantherFetcher();
                } else {
                    $fetcher = new NativeHttpFetcher();
                }

                $indeedScraper = new IndeedScraper($fetcher);
                $indeedJobs = $indeedScraper->scrape($args);
            }
            
            $jobs = array_merge($jobs, $indeedJobs);
        }

        $useMock = !empty($args['use_mock']) || !empty($this->config['use_mock']) || in_array('mock', $sites, true);
        if ($useMock) {
            $mockScraper = new \Freeworld\PhpJobspy\Scrapers\MockScraper();
            $mockJobs = $mockScraper->scrape($args);
            $jobs = array_merge($jobs, $mockJobs);
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
