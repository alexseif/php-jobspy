<?php

declare(strict_types=1);

namespace Freeworld\PhpJobspy\Fetchers;

use Freeworld\PhpJobspy\Contracts\FetcherInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpClient\HttpClient;

class ScraperApiFetcher implements FetcherInterface
{
    private const API_BASE_URL = 'http://api.scraperapi.com/';
    private HttpClientInterface $client;

    public function __construct(
        private string $apiKey,
        ?HttpClientInterface $client = null
    ) {
        $this->client = $client ?? HttpClient::create();
    }

    public function getHtml(string $url): string
    {
        $requestUrl = sprintf(
            '%s?api_key=%s&url=%s',
            self::API_BASE_URL,
            urlencode($this->apiKey),
            urlencode($url)
        );

        try {
            $response = $this->client->request('GET', $requestUrl);
            return $response->getContent();
        } catch (\Throwable $e) {
            return '';
        }
    }
}
