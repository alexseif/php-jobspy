<?php

declare(strict_types=1);

namespace Freeworld\PhpJobspy\Scrapers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class LinkedInScraper
{
    private const BASE_URL = 'https://www.linkedin.com/jobs/search';

    /**
     * Scrapes public LinkedIn job listings.
     * Note: LinkedIn aggressively blocks basic scrapers with AuthWalls.
     * This is a baseline structural parser.
     */
    public function scrape(array $args): array
    {
        $searchTerm = urlencode($args['search_term'] ?? 'developer');
        $location = urlencode($args['location'] ?? 'worldwide');
        $limit = $args['results_wanted'] ?? 10;
        
        $url = sprintf('%s?keywords=%s&location=%s&f_TPR=r2592000', self::BASE_URL, $searchTerm, $location);
        
        $client = new Client([
            'timeout'  => 15.0,
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                'Accept-Language' => 'en-US,en;q=0.9',
                'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,*/*;q=0.8',
            ]
        ]);

        try {
            $response = $client->request('GET', $url);
            $html = (string) $response->getBody();
        } catch (GuzzleException $e) {
            return [];
        }

        if (!$html) {
            return [];
        }

        $dom = new \DOMDocument();
        // Suppress HTML5 parsing warnings common with imperfect DOMs
        @$dom->loadHTML($html);
        
        $xpath = new \DOMXPath($dom);
        
        // Target job cards in the public SERP
        $jobCards = $xpath->query("//div[contains(@class, 'base-search-card')] | //li[contains(@class, 'result-card')] | //div[contains(@class, 'job-search-card')]");
        
        $jobs = [];
        if ($jobCards) {
            foreach ($jobCards as $card) {
                if (count($jobs) >= $limit) {
                    break;
                }
                
                $titleNode = $xpath->query(".//h3[contains(@class, 'base-search-card__title')] | .//span[contains(@class, 'screen-reader-text')]", $card)->item(0);
                $companyNode = $xpath->query(".//h4[contains(@class, 'base-search-card__subtitle')] | .//a[contains(@class, 'hidden-nested-link')]", $card)->item(0);
                $locationNode = $xpath->query(".//span[contains(@class, 'job-search-card__location')]", $card)->item(0);
                $urlNode = $xpath->query(".//a[contains(@class, 'base-card__full-link')]", $card)->item(0);
                
                // Additional Metadata
                $timeNode = $xpath->query(".//time", $card)->item(0);
                $timePosted = $timeNode ? $timeNode->getAttribute('datetime') : '';
                $timeText = $timeNode ? trim($timeNode->textContent) : '';
                
                $benefitsNode = $xpath->query(".//span[contains(@class, 'result-benefits__text')]", $card)->item(0);
                $isEasyApply = $benefitsNode && stripos($benefitsNode->textContent, 'Easy Apply') !== false;

                $locationText = $locationNode ? trim($locationNode->textContent) : 'Unknown Location';
                $isRemote = stripos($locationText, 'Remote') !== false || stripos($titleNode?->textContent ?? '', 'Remote') !== false;

                // Normalize URL
                $rawUrl = $urlNode ? trim($urlNode->getAttribute('href')) : '';
                
                if (empty($rawUrl)) {
                    continue; // Skip invalid nodes that XPath accidentally matched
                }

                $cleanUrl = preg_replace('/https:\/\/[a-z]{2}\.linkedin\.com/', 'https://www.linkedin.com', $rawUrl);
                $cleanUrl = explode('?', $cleanUrl)[0];
                
                $jobs[] = [
                    'title' => $titleNode ? trim($titleNode->textContent) : 'Unknown Title',
                    'company' => $companyNode ? trim($companyNode->textContent) : 'Unknown Company',
                    'location' => $locationText,
                    'is_remote' => $isRemote ? 'Yes' : 'No',
                    'date_posted' => $timePosted,
                    'time_ago' => $timeText,
                    'is_easy_apply' => $isEasyApply ? 'Yes' : 'No',
                    'url' => $cleanUrl,
                    'description' => 'Description omitted in public SERP list. Requires deep fetching.',
                ];
            }
        }
        
        return $jobs;
    }
}
