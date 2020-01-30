<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase{

    public function testRedirectUrl()
    {
        $client = static::createClient();
        $client->request('GET','/6k1');
        $this->assertTrue($client->getResponse()->isRedirect());
    }
}