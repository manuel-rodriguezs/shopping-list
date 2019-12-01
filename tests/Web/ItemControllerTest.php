<?php

namespace App\Tests\Web;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ItemControllerTest extends WebTestCase
{
    public function testIndex()
    {
        // Items
        $client = static::createClient();

        $client->request('GET', '/items');

        $response = $client->getResponse();

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

        // Items with supermarket
        $client->request('GET', '/items/1');

        $response = $client->getResponse();

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testSave()
    {
        $client = static::createClient();

        // No data
        $crawler = $client->request('GET', '/items');
        $form = $crawler->filter('button[type=submit]')->form();
        $client->submit($form);
        $crawler = $client->followRedirect();

        $errors = $crawler->filter("div.alert-danger");

        $this->assertGreaterThan(0, count($errors));

        // Carrots
        $crawler = $client->request('GET', '/items');
        $form = $crawler->filter('button[type=submit]')->form();
        $form['description'] = 'Carrots';
        $client->submit($form);
        $crawler = $client->followRedirect();

        $errors = $crawler->filter("div.alert-danger");

        $this->assertEquals(0, count($errors));

        // Carrots again
        $crawler = $client->request('GET', '/items');
        $form = $crawler->filter('button[type=submit]')->form();
        $form['description'] = 'Carrots';
        $client->submit($form);
        $crawler = $client->followRedirect();

        $errors = $crawler->filter("div.alert-danger");

        $this->assertGreaterThan(0, count($errors));
    }

    public function testDelete()
    {
        $client = static::createClient();

        // Add Carrots
        $crawler = $client->request('GET', '/items');
        $form = $crawler->filter('button[type=submit]')->form();
        $form['description'] = 'Carrots';
        $client->submit($form);
        $crawler = $client->followRedirect();
        $errors = $crawler->filter("div.alert-danger");
        $this->assertEquals(0, count($errors));


        // Delete Carrots
        $client->request('GET', '/items');

        $link = $crawler->filter('a.btn-danger');
        $uri = $link->link()->getUri();

        $client->request('GET', $uri);

        $crawler = $client->followRedirect();

        $errors = $crawler->filter("div.alert-danger");
        $warnings = $crawler->filter("div.alert-warning");

        $this->assertEquals(0, count($errors) + count($warnings));

        // Delete Carrotr again
        $client->request('GET', $uri);

        $crawler = $client->followRedirect();

        $warnings = $crawler->filter("div.alert-warning");

        $this->assertGreaterThan(0, count($warnings));
    }
}