<?php

declare(strict_types=1);

namespace Freeworld\PhpJobspy\Scrapers;

use Freeworld\PhpJobspy\DTO\JobPostDTO;
use Freeworld\PhpJobspy\Contracts\FetcherInterface;
use Freeworld\PhpJobspy\Fetchers\NativeHttpFetcher;
use Symfony\Component\DomCrawler\Crawler;

class IndeedScraper implements ScraperInterface
{
    private const BASE_URL = 'https://www.indeed.com';
    private FetcherInterface $fetcher;

    public function __construct(?FetcherInterface $fetcher = null)
    {
        $this->fetcher = $fetcher ?? new NativeHttpFetcher();
    }

    /**
     * @return JobPostDTO[]
     */
    public function scrape(array $args): array
    {
        $term = urlencode($args['search_term'] ?? '');
        $location = urlencode($args['location'] ?? '');
        $url = sprintf('%s/jobs?q=%s&l=%s', self::BASE_URL, $term, $location);

        try {
            $html = $this->fetcher->getHtml($url);
            if (empty($html)) {
                return [];
            }
        } catch (\Throwable $e) {
            // Anti-bot block or network error
            return [];
        }

        $crawler = new Crawler($html);
        $jobs = [];

        $crawler->filter('.job_seen_beacon')->each(function (Crawler $node) use (&$jobs) {
            $titleNode = $node->filter('.jobTitle span');
            $title = $titleNode->count() > 0 ? $titleNode->text() : '';

            $companyNode = $node->filter('.companyName');
            $company = $companyNode->count() > 0 ? $companyNode->text() : '';

            $locationNode = $node->filter('.companyLocation');
            $locationTxt = $locationNode->count() > 0 ? $locationNode->text() : '';

            $snippetNode = $node->filter('.job-snippet');
            $description = $snippetNode->count() > 0 ? $snippetNode->text() : '';

            $linkNode = $node->filter('.jcs-JobTitle');
            $jobUrl = '';
            if ($linkNode->count() > 0) {
                $href = $linkNode->attr('href');
                if ($href) {
                    // Extract job key
                    parse_str(parse_url($href, PHP_URL_QUERY) ?? '', $queryVars);
                    $jk = $queryVars['jk'] ?? '';
                    if ($jk) {
                        $jobUrl = sprintf('%s/viewjob?jk=%s', self::BASE_URL, $jk);
                    } else {
                        $jobUrl = self::BASE_URL . $href;
                    }
                }
            }

            $isRemote = stripos($locationTxt, 'remote') !== false;

            $jobs[] = new JobPostDTO(
                site: 'Indeed',
                title: $title,
                company: $company,
                company_url: '',
                job_url: $jobUrl,
                location: $locationTxt,
                is_remote: $isRemote,
                description: trim($description)
            );
        });

        // Limit results if requested
        if (isset($args['results_wanted']) && $args['results_wanted'] > 0) {
            $jobs = array_slice($jobs, 0, (int)$args['results_wanted']);
        }

        return $jobs;
    }
}
