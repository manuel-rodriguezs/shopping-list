<?php

namespace App\Tests\Web;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomeControllerTest extends WebTestCase
{
    public function testIndex(): void
    {
        $client = static::createClient();

        $client->request('GET', '/');

        $response = $client->getResponse();

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('/items', $response->getTargetUrl());
    }
}