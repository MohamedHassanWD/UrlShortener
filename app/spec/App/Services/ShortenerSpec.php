<?php

namespace spec\App\Services;

use App\Services\Shortener;
use PhpSpec\ObjectBehavior;

class ShortenerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(Shortener::class);
    }

    function it_can_validate_url_formate()
    {
        $validUrl = 'http://google.com';
        $invalidUrl = 'google.com';

        $this->validateUrlFormat($validUrl)->shouldReturn(TRUE);
        $this->validateUrlFormat($invalidUrl)->shouldReturn(FALSE);
    }

    function it_can_verify_url()
    {
        $validUrl = 'http://google.com';
        $invalidUrl = 'seqqowemasadpjkm.com';

        $this->verifyUrlExists($validUrl)->shouldReturn(TRUE);
        $this->verifyUrlExists($invalidUrl)->shouldReturn(FALSE);
    }

    function it_can_generate_short_unique_code_for_id()
    {
        $this->generateShortCode(0)->shouldReturn('1');
        $this->generateShortCode(1)->shouldReturn('2');
        $this->generateShortCode(1332)->shouldReturn('xF');
        $this->generateShortCode(138961332)->shouldReturn('sdHxF');
    }
}
