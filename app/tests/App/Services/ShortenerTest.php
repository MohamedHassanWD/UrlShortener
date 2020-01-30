<?php

namespace App\Tests;

use App\Services\Shortener;
use PHPUnit\Framework\TestCase;

class ShortenerTest extends TestCase{

    public function testValidUrl()
    {
        $shortener = new Shortener();
        $this->assertTrue($shortener->validateUrlFormat('http://google.com'),'Should return True');
    }

    public function testInValidUrl()
    {
        $shortener = new Shortener();
        $this->assertFalse($shortener->validateUrlFormat('http://--goog.0-le.com'),'Should return False');
    }

    public function testValidLiveUrl()
    {
        $shortener = new Shortener();
        $this->assertTrue($shortener->verifyUrlExists('http://google.com'),'Should return True');
    }

    public function testInValidLiveUrl()
    {
        $shortener = new Shortener();
        $this->assertFalse($shortener->verifyUrlExists('http://goososdsndsdlngle.com'),'Should return False');
    }

    public function testGenerateShortUrl()
    {
        $shortener = new Shortener();
        $this->assertEquals('2KQ',$shortener->generateShortCode(4341),'Should return 2KQ');
        $this->assertEquals('4Xr',$shortener->generateShortCode(9871),'Should return 4Xr');
        $this->assertEquals('7gC14Vr',$shortener->generateShortCode(98000009771),'Should return 7gC14Vr');
        $this->assertEquals('61T147kQzN',$shortener->generateShortCode(9800000977103939),'Should return 61T147kQzN');
    }
}