<?php

declare(strict_types=1);

namespace Freeworld\PhpJobspy\Fetchers;

use Freeworld\PhpJobspy\Contracts\FetcherInterface;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Panther\Client;

class PantherFetcher implements FetcherInterface
{
    private ?Client $client = null;

    /**
     * @param array<string, string> $sessionCookies Optional cookies [name => value]
     */
    public function __construct(private array $sessionCookies = [])
    {
    }

    public function getHtml(string $url): string
    {
        if ($this->client === null) {
            $this->client = Client::createChromeClient();
        }

        // We first need to visit the domain to set cookies for it
        $this->client->request('GET', $url);

        if (!empty($this->sessionCookies)) {
            $cookieJar = $this->client->getCookieJar();
            $domain = parse_url($url, PHP_URL_HOST) ?? '';
            foreach ($this->sessionCookies as $name => $value) {
                // Cookie($name, $value, $expires, $path, $domain, $secure, $httponly)
                $cookieJar->set(new Cookie($name, $value, null, '/', $domain));
            }
            // Reload with cookies
            $this->client->request('GET', $url);
        }

        $source = $this->client->getPageSource();
        return is_string($source) ? $source : '';
    }
}
