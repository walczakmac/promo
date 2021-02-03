<?php

namespace App\Infrastructure\PromoProductApi;

use App\Infrastructure\PromoProductApi;
use GuzzleHttp;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;

class Client implements PromoProductApi
{
    /**
     * @var GuzzleHttp\Client
     */
    private $client;

    /**
     * @var string
     */
    private $token;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(GuzzleHttp\Client $client, string $token, LoggerInterface $logger)
    {
        $this->client = $client;
        $this->token = $token;
        $this->logger = $logger;
    }

    public function getProductById(string $id) : array
    {
        try {
            $response = $this->client->get(sprintf('product/%s', $id), [
                'headers' => ['X-token' => $this->token],
            ]);
        } catch (GuzzleHttp\Exception\GuzzleException $e) {
            $this->logger->error(sprintf(
                'There was an error when trying to fetch product data from PromoProductApi: %s',
                $e->getMessage()
            ));

            return [];
        }

        if ($response->getStatusCode() !== Response::HTTP_OK) {
            $this->logger->error(sprintf(
                'PromoProductApi responded with status code %d when trying to fetch product data',
                $response->getStatusCode()
            ));

            return [];
        }

        return json_decode($response->getBody(), true);

    }
}
