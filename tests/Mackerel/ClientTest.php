<?php
namespace Mackerel\Test;

use GuzzleHttp\Psr7\Request;
use Mackerel\Client;
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Middleware;

class ClientTest extends TestCase
{
    public function testPostServiceMetricSuccess()
    {
        $response = new Response(200, [], '{"success":true}', '1.1');
        $handler = HandlerStack::create(new MockHandler([$response]));
        $container = [];
        $history = Middleware::history($container);
        $handler->push($history);
        $httpClient = new HttpClient([
            'timeout' => 10,
            'connect_timeout' => 10,
            'handler' => $handler,
        ]);

        $client = new Client($httpClient, 'APIKEY-STRING');
        $result = $client->postServiceMetric(
            'SERVICENAME',
            [
                [
                    'name' => 'metric.name',
                    'time' => 1520544629,
                    'value' => 100,
                ],
            ]
        );
        $this->assertTrue($result);
        $this->assertCount(1, $container);
        /** @var $result Request */
        $result = $container[0]['request'];
        $this->assertEquals('POST', $result->getMethod());
        $this->assertEquals('https://api.mackerelio.com/api/v0/services/SERVICENAME/tsdb', $result->getUri());
        $this->assertEquals(['APIKEY-STRING'], $result->getHeader('X-Api-Key'));
        $this->assertEquals(['application/json'], $result->getHeader('Content-Type'));
        $this->assertEquals('[{"name":"metric.name","time":1520544629,"value":100}]', $result->getBody()->getContents());

        /** @var $result Response */
        $result = $container[0]['response'];
        $this->assertEquals(200, $result->getStatusCode());
    }

    public function testPostServiceMetricError()
    {
        $response = new Response(200, [], '{"success":false}', '1.1');
        $handler = HandlerStack::create(new MockHandler([$response]));
        $container = [];
        $history = Middleware::history($container);
        $handler->push($history);
        $httpClient = new HttpClient([
            'timeout' => 10,
            'connect_timeout' => 10,
            'handler' => $handler,
        ]);

        $client = new Client($httpClient, 'APIKEY-STRING');
        $result = $client->postServiceMetric(
            'SERVICENAME',
            [
                [
                    'name' => 'metric.name',
                    'time' => 1520544629,
                    'value' => 100,
                ],
            ]
        );
        $this->assertFalse($result);
        $this->assertCount(1, $container);
        /** @var $result Request */
        $result = $container[0]['request'];
        $this->assertEquals('POST', $result->getMethod());
        $this->assertEquals('https://api.mackerelio.com/api/v0/services/SERVICENAME/tsdb', $result->getUri());
        $this->assertEquals(['APIKEY-STRING'], $result->getHeader('X-Api-Key'));
        $this->assertEquals(['application/json'], $result->getHeader('Content-Type'));
        $this->assertEquals('[{"name":"metric.name","time":1520544629,"value":100}]', $result->getBody()->getContents());

        /** @var $result Response */
        $result = $container[0]['response'];
        $this->assertEquals(200, $result->getStatusCode());
    }

    public function testPostServiceMetric400()
    {
        $response = new Response(400, [], '{"success":false}', '1.1');
        $handler = HandlerStack::create(new MockHandler([$response]));
        $container = [];
        $history = Middleware::history($container);
        $handler->push($history);
        $httpClient = new HttpClient([
            'timeout' => 10,
            'connect_timeout' => 10,
            'handler' => $handler,
        ]);

        try {
            $client = new Client($httpClient, 'APIKEY-STRING');
            $result = $client->postServiceMetric(
                'SERVICENAME',
                [
                    [
                        'name' => 'metric.name',
                        'time' => 1520544629,
                        'value' => 100,
                    ],
                ]
            );
            $this->assertTrue(false);
        } catch (\Exception $e) {
            $this->assertEquals(400, $e->getCode());
            $this->assertCount(1, $container);
            /** @var $result Request */
            $result = $container[0]['request'];
            $this->assertEquals('POST', $result->getMethod());
            $this->assertEquals('https://api.mackerelio.com/api/v0/services/SERVICENAME/tsdb', $result->getUri());
            $this->assertEquals(['APIKEY-STRING'], $result->getHeader('X-Api-Key'));
            $this->assertEquals(['application/json'], $result->getHeader('Content-Type'));
            $this->assertEquals('[{"name":"metric.name","time":1520544629,"value":100}]', $result->getBody()->getContents());
        }

        /** @var $result Response */
        $result = $container[0]['response'];
        $this->assertEquals(400, $result->getStatusCode());
    }
}