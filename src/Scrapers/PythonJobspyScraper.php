<?php

declare(strict_types=1);

namespace Freeworld\PhpJobspy\Scrapers;

use Freeworld\PhpJobspy\DTO\JobPostDTO;
use Symfony\Component\Process\Process;

class PythonJobspyScraper implements ScraperInterface
{
    private string $pythonPath;
    private string $scriptPath;

    public function __construct(string $pythonPath = '.venv/bin/python', string $scriptPath = 'src/python/scrape.py')
    {
        $this->pythonPath = $pythonPath;
        $this->scriptPath = $scriptPath;
    }

    /**
     * @return JobPostDTO[]
     */
    public function scrape(array $args): array
    {
        $process = new Process([$this->pythonPath, $this->scriptPath]);
        
        $process->setInput(json_encode($args));
        $process->run();

        if (!$process->isSuccessful()) {
            error_log("Python scraper failed: " . $process->getErrorOutput());
            return [];
        }

        $output = $process->getOutput();
        $data = json_decode($output, true);

        if (!is_array($data)) {
            return [];
        }

        $jobs = [];
        foreach ($data as $item) {
            $jobs[] = new JobPostDTO(
                site: $item['site'] ?? 'Unknown',
                title: $item['title'] ?? '',
                company: $item['company'] ?? '',
                company_url: $item['company_url'] ?? '',
                job_url: $item['job_url'] ?? '',
                location: $item['location'] ?? '',
                is_remote: (bool) ($item['is_remote'] ?? false),
                description: $item['description'] ?? '',
                date_posted: $item['date_posted'] ?? ''
            );
        }

        return $jobs;
    }
}
