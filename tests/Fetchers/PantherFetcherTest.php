<?php

declare(strict_types=1);

namespace Freeworld\PhpJobspy\Tests\Fetchers;

use Freeworld\PhpJobspy\Fetchers\PantherFetcher;
use PHPUnit\Framework\TestCase;

class PantherFetcherTest extends TestCase
{
    public function testGetHtmlReturnsContent(): void
    {
        $this->markTestSkipped('PantherFetcher requires a real browser environment, skipping in unit tests.');
    }
}
