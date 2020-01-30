<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UrlControllerTest extends WebTestCase{
    public function testUnauthorized()
    {
        $client = static::createClient();
        $client->request('GET', '/api/urls');
        $this->assertEquals(401, $client->getResponse()->getStatusCode());
    }

    public function testWrongCredintials()
    {
        $client = static::createClient();
        $client->request('GET','/api/urls',[],[],['PHP_AUTH_USER' => 'hassan', 'PHP_AUTH_PW' => '123ssdasds']);
        $this->assertEquals(401, $client->getResponse()->getStatusCode());
    }

    public function testCorrectCredintials()
    {
        $client = static::createClient();
        $client->request('GET','/api/urls',[],[],['PHP_AUTH_USER' => 'franco', 'PHP_AUTH_PW' => '123']);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testUrlNotFound()
    {
        $client = static::createClient();
        $client->request('GET','/api/urls/sdsad1easds6',[],[],['PHP_AUTH_USER' => 'franco', 'PHP_AUTH_PW' => '123']);
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    public function testCreateUrl()
    {
        $client = static::createClient();
        $client->request('POST','/api/urls',[],[],['PHP_AUTH_USER' => 'franco', 'PHP_AUTH_PW' => '123'],'{"url":"https://www.raisenow.com"}');
        $this->assertEquals(201, $client->getResponse()->getStatusCode());
    }

    public function testUpdateUrl()
    {
        $client = static::createClient();
        $client->request('PUT','/api/urls/6K1',[],[],['PHP_AUTH_USER' => 'franco', 'PHP_AUTH_PW' => '123'],'{"url":"https://www.symfony.com/"}');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testNoPermissionUrl()
    {
        $client = static::createClient();
        $client->request('DELETE','/api/urls/6K1',[],[],['PHP_AUTH_USER' => 'hassan', 'PHP_AUTH_PW' => '123']);
        $this->assertEquals(403, $client->getResponse()->getStatusCode());
    }

    public function testDeleteUrl()
    {
        $client = static::createClient();
        $client->request('DELETE','/api/urls/6K1',[],[],['PHP_AUTH_USER' => 'franco', 'PHP_AUTH_PW' => '123']);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}