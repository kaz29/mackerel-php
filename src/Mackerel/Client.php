<?php
namespace Mackerel;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\ResponseInterface;

/**
 * Class Client
 * @package Mackerel
 */
class Client
{
    static private $baseURL = 'https://api.mackerelio.com/api/v0';
    private $apiKey = null;
    private $client = null;

    /**
     * Client constructor.
     *
     * @param HttpClient $client
     * @param string $apiKey API key string.
     */
    public function __construct(HttpClient $client, string $apiKey)
    {
        $this->client = $client;
        $this->apiKey = $apiKey;
    }

    /**
     * Send service metrics.
     *
     * @param string $serviceName Service Name.
     * @param array $metrics Service metric array.
     * @return bool
     * @throws \Exception
     */
    public function postServiceMetric(string $serviceName, array $metrics)
    {
        $url = self::$baseURL . "/services/{$serviceName}/tsdb";
        $response = $this->client->post(
            $url,
            [
                RequestOptions::JSON => $metrics,
                RequestOptions::HEADERS => [
                    'X-Api-Key' => $this->apiKey,
                    'Content-Type' => 'application/json',
                ],
            ]
        );
        if ($response->getStatusCode() != 200) {
            throw new \Exception("Request error({$url})", $response->getStatusCode());
        }

        return $this->isSuccess($response);
    }

    /**
     * Check response data.
     *
     * @param ResponseInterface $response
     * @return bool
     */
    private function isSuccess(ResponseInterface $response)
    {
        $_response = json_decode($response->getBody()->getContents(), true);
        if (!array_key_exists('success', $_response)) {
            return false;
        }

        return $_response['success'];
    }
}
