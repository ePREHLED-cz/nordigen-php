<?php

namespace Nordigen\NordigenPHP\API;

use GuzzleHttp\Client;
use Nordigen\NordigenPHP\Http\RequestHandlerTrait;
use GuzzleHttp\ClientInterface;

class RequestHandler
{
    use RequestHandlerTrait;

    private ?string $accessToken = null;
    private array $authentication;

    public function __construct(string $baseUri, string $secretId, string $secretKey, ?ClientInterface $client)
    {
        $this->authentication = [$secretId, $secretKey];
        $this->baseUri = $baseUri;
        $this->httpClient = $client ?? $this->createHttpClient();
    }


    /**
     * Set headers for HttpClient
     *
     * @param array<string,string> $headers
     * @return Client
     */
    public function createHttpClient(array $headers = []): Client
    {
        $headers['accept'] = 'application/json';
        $headers['User-Agent'] = "Nordigen-PHP-v2";
        if ($this->accessToken !== null) {
            $headers['Authorization'] = 'Bearer ' . $this->accessToken;
        }
        return new Client([
            "base_uri" => $this->baseUri,
            "headers" => $headers,
            "timeout" => 10,
            "connect_timeout" => 10,
        ]);
    }


    /**
     * Get access token
     *
     * @return string
     */
    public function getAccessToken(): ?string
    {
        return $this->accessToken;
    }

    /**
     * Set existing access token.
     * @param string|null $accessToken
     *
     * @return void
     */
    public function setAccessToken(?string $accessToken): void
    {
        $this->accessToken = $accessToken;
        $this->httpClient = $this->createHttpClient();
    }

    /**
     * Get authentication.
     *
     * @return array
     */
    public function getAuthentication(): array
    {
        return $this->authentication;
    }
}
