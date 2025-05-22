<?php

namespace Tests\Unit;

use App\Services\ApiFreightClient;
use App\Services\HttpClientInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Exception\RequestException;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;

class ApiFreightClientTest extends TestCase
{
    public function test_successful_get_request_returns_array()
    {
        $mock = new MockHandler([
            new Response(200, [], json_encode(['price' => 99.99]))
        ]);
        $handlerStack = HandlerStack::create($mock);
        $guzzle = new Client(['handler' => $handlerStack]);

        $client = new ApiFreightClientForTest($guzzle);
        $result = $client->get('http://fakeapi.com/freight', ['foo' => 'bar']);
        $this->assertEquals(['price' => 99.99], $result);
    }

    public function test_invalid_json_throws_exception()
    {
        $mock = new MockHandler([
            new Response(200, [], 'not-json')
        ]);
        $handlerStack = HandlerStack::create($mock);
        $guzzle = new Client(['handler' => $handlerStack]);

        $client = new ApiFreightClientForTest($guzzle);
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Invalid JSON response');
        $client->get('http://fakeapi.com/freight');
    }

    public function test_api_error_retries_and_fails()
    {
        $mock = new MockHandler([
            new RequestException('Error', $this->createMock(RequestInterface::class)),
            new RequestException('Error', $this->createMock(RequestInterface::class)),
            new RequestException('Error', $this->createMock(RequestInterface::class)),
        ]);
        $handlerStack = HandlerStack::create($mock);
        $guzzle = new Client(['handler' => $handlerStack]);

        $client = new ApiFreightClientForTest($guzzle, 1, 2); // 2 retries
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('API request failed after retries');
        $client->get('http://fakeapi.com/freight');
    }
}

// Classe auxiliar para injetar o Guzzle mockado
class ApiFreightClientForTest extends ApiFreightClient {
    public function __construct($guzzle, $timeout = 3, $maxRetries = 2) {
        $this->client = $guzzle;
        $this->timeout = $timeout;
        $this->maxRetries = $maxRetries;
    }
} 