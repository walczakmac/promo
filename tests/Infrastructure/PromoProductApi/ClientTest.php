<?php

namespace App\Tests\Infrastructure\PromoProductApi;

use App\Infrastructure\PromoProductApi;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Psr\Log\Test\TestLogger;

class ClientTest extends TestCase
{
    public function testGetProductByIdSuccess()
    {
        $body = [['colors' => ['red'], 'sizes' => ['s', 'm']]];
        $mock = new MockHandler([new Response(200, [], json_encode($body))]);
        $client = new Client(['handler' => HandlerStack::create($mock)]);
        $logger = new TestLogger();

        $promoProductApi = new PromoProductApi\Client($client, 'token', $logger);
        $this->assertSame($body, $promoProductApi->getProductById('xxx'));
    }

    public function testGetProductByIdServerException()
    {
        $mock = new MockHandler([
            new ServerException(
                'server exception',
                new Request('GET', 'cities'),
                new Response(500)
            ),
        ]);
        $client = new Client(['handler' => HandlerStack::create($mock)]);
        $logger = new TestLogger();

        $promoProductApi = new PromoProductApi\Client($client, 'token', $logger);
        $product = $promoProductApi->getProductById('xxx');

        $this->assertEmpty($product);
        $this->assertTrue($logger->hasErrorThatContains('There was an error when trying to fetch product data from PromoProductApi: server exception'));
    }

    public function testGetProductByIdWrongStatusCode()
    {
        $mock = new MockHandler([new Response(204)]);
        $client = new Client(['handler' => HandlerStack::create($mock)]);
        $logger = new TestLogger();

        $promoProductApi = new PromoProductApi\Client($client, 'token', $logger);
        $product = $promoProductApi->getProductById('xxx');

        $this->assertEmpty($product);
        $this->assertTrue($logger->hasErrorThatContains('PromoProductApi responded with status code 204 when trying to fetch product data'));
    }
}
