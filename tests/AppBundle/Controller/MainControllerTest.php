<?php

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MainControllerTest extends WebTestCase
{
    public function testIndex ()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $this->assertEquals(1, $crawler->filter('h1:contains("Accueil")')->count());
    }

    public function testRedirectIfNoSession () {
        $client = static::createClient();

        $client->request('GET', '/command');

        $crawler = $client->followRedirect();

        $this->assertEquals(1, $crawler->filter('h1:contains("Accueil")')->count());
    }
}