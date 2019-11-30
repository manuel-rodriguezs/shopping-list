<?php

namespace App\Tests\Web;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ItemControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $client->request('GET', '/items');

        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
    }
}