<?php

namespace App\Tests\Rest;

namespace App\Tests\Web;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;


class PriceControllerTest extends WebTestCase
{
    function testGetPrice()
    {
        $client = static::createClient();

        $client->request('GET', '/api/price/9999999', [], [], ['HTTP_CONTENT_TYPE' => 'application/json']);
        $response = $client->getResponse();

        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());


        $client->request('GET', '/api/price/1', [], [], ['HTTP_CONTENT_TYPE' => 'application/json']);
        $response = $client->getResponse();

        $price = json_decode($response->getContent());

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(0.8, $price->price);
    }

    function testPostPrice()
    {
        $client = static::createClient();

        // Adding a price
        $client->request('POST', '/api/price', [
            'supermaket_id' => 1,
            'name' => 'meat',
            'price' => 6
        ], [], ['HTTP_CONTENT_TYPE' => 'application/json']);
        $response = $client->getResponse();

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());

        // Adding the same price again
        $client->request('POST', '/api/price', [
            'supermaket_id' => 1,
            'name' => 'meat',
            'price' => 6
        ], [], ['HTTP_CONTENT_TYPE' => 'application/json']);
        $response = $client->getResponse();

        $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $response->getStatusCode());

        // Adding a price with a no existent supermarket
        $client->request('POST', '/api/price', [
            'supermaket_id' => 99999,
            'name' => 'meat',
            'price' => 6
        ], [], ['HTTP_CONTENT_TYPE' => 'application/json']);
        $response = $client->getResponse();

        $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $response->getStatusCode());
    }

    function testDeletePrice()
    {
        $client = static::createClient();

        // Adding a price
        $client->request('POST', '/api/price', [
            'supermaket_id' => 1,
            'name' => 'DELETEME',
            'price' => 6
        ], [], ['HTTP_CONTENT_TYPE' => 'application/json']);
        $response = $client->getResponse();

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $price = json_decode($response->getContent());

        // Deleting that price
        $client->request('DELETE', "/api/price/{$price->id}", [], [], ['HTTP_CONTENT_TYPE' => 'application/json']);
        $response = $client->getResponse();

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

        // Deleting that price again
        $client->request('DELETE', "/api/price/{$price->id}", [], [], ['HTTP_CONTENT_TYPE' => 'application/json']);
        $response = $client->getResponse();

        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());

    }
}