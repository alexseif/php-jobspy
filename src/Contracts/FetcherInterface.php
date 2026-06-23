<?php

declare(strict_types=1);

namespace Freeworld\PhpJobspy\Contracts;

interface FetcherInterface
{
    /**
     * Fetches the HTML content of the given URL.
     *
     * @param string $url The URL to fetch.
     * @return string The HTML content.
     */
    public function getHtml(string $url): string;
}
