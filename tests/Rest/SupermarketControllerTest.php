<?php


namespace App\Tests\Rest;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class SupermarketControllerTest extends WebTestCase
{
    public function testGetSupermarkets()
    {
        $client = static::createClient();

        $client->request('GET', '/api/supermarkets', [], [], ['HTTP_CONTENT_TYPE' => 'application/json']);
        $response = $client->getResponse();

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testPostSupermarket()
    {
        $client = static::createClient();

        // A Supermarket
        $client->request('POST', '/api/supermarket', [
            'name' => 'A Supermarket'
        ], [], ['HTTP_CONTENT_TYPE' => 'application/json']);
        $response = $client->getResponse();

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());

        // A Supermarket again
        $client->request('POST', '/api/supermarket', [
            'name' => 'A Supermarket'
        ], [], ['HTTP_CONTENT_TYPE' => 'application/json']);
        $response = $client->getResponse();

        $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $response->getStatusCode());
    }

    public function testDeleteSupermarket()
    {
        $client = static::createClient();

        // A Supermarket
        $client->request('POST', '/api/supermarket', [
            'name' => 'A Supermarket'
        ], [], ['HTTP_CONTENT_TYPE' => 'application/json']);
        $response = $client->getResponse();

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());

        $supermarket = json_decode($response->getContent());

        // A price for the supermarket
        $client->request('POST', '/api/price', [
            'supermaket_id' => $supermarket->id,
            'name' => 'deleteme',
            'price' => 6
        ], [], ['HTTP_CONTENT_TYPE' => 'application/json']);
        $response = $client->getResponse();

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());


        // Deleting that supermarket
        $client->request('DELETE', "/api/supermarket/{$supermarket->id}", [], [], ['HTTP_CONTENT_TYPE' => 'application/json']);
        $response = $client->getResponse();

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

        // Deleting that supermarket again
        $client->request('DELETE', "/api/supermarket/{$supermarket->id}", [], [], ['HTTP_CONTENT_TYPE' => 'application/json']);
        $response = $client->getResponse();

        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }
}