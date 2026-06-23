<?php

declare(strict_types=1);

namespace Freeworld\PhpJobspy\Tests\Fetchers;

use Freeworld\PhpJobspy\Fetchers\NativeHttpFetcher;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

class NativeHttpFetcherTest extends TestCase
{
    public function testGetHtmlReturnsContentOnSuccess(): void
    {
        $expectedHtml = '<html><body>Success</body></html>';
        $mockResponse = new MockResponse($expectedHtml);
        $mockClient = new MockHttpClient($mockResponse);

        $fetcher = new NativeHttpFetcher($mockClient);
        $html = $fetcher->getHtml('https://example.com');

        $this->assertSame($expectedHtml, $html);
    }

    public function testGetHtmlReturnsEmptyStringOnFailure(): void
    {
        $mockResponse = new MockResponse('', ['error' => 'Network error']);
        $mockClient = new MockHttpClient($mockResponse);

        $fetcher = new NativeHttpFetcher($mockClient);
        $html = $fetcher->getHtml('https://example.com');

        $this->assertSame('', $html);
    }
}
