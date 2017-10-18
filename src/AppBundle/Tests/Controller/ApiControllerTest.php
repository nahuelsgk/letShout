<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiControllerTest extends WebTestCase
{
    public function testGetlastntwitter()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/api/{user}/{n}');
    }

}
