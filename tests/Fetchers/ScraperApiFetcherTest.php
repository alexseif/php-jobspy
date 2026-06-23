<?php

declare(strict_types=1);

namespace Freeworld\PhpJobspy\Tests\Fetchers;

use Freeworld\PhpJobspy\Fetchers\ScraperApiFetcher;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

class ScraperApiFetcherTest extends TestCase
{
    public function testGetHtmlFormatsUrlCorrectlyAndReturnsContent(): void
    {
        $expectedHtml = '<html><body>Success</body></html>';
        $targetUrl = 'https://example.com/jobs';
        $apiKey = 'dummy_api_key';

        $mockResponse = new MockResponse($expectedHtml);
        $mockClient = new MockHttpClient($mockResponse);

        $fetcher = new ScraperApiFetcher($apiKey, $mockClient);
        $html = $fetcher->getHtml($targetUrl);

        $this->assertSame($expectedHtml, $html);

        // Verify the requested URL matches ScraperAPI format
        $requestUrl = $mockResponse->getRequestUrl();
        $expectedRequestUrl = sprintf(
            'http://api.scraperapi.com/?api_key=%s&url=%s',
            $apiKey,
            urlencode($targetUrl)
        );

        $this->assertSame($expectedRequestUrl, $requestUrl);
    }

    public function testGetHtmlReturnsEmptyStringOnFailure(): void
    {
        $mockResponse = new MockResponse('', ['error' => 'Network error']);
        $mockClient = new MockHttpClient($mockResponse);

        $fetcher = new ScraperApiFetcher('dummy_api_key', $mockClient);
        $html = $fetcher->getHtml('https://example.com');

        $this->assertSame('', $html);
    }
}
