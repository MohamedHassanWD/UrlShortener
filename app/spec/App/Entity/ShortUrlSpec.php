<?php

namespace spec\App\Entity;

use App\Entity\ShortUrl;
use App\Repository\ShortUrlRepository;
use PhpSpec\ObjectBehavior;

class ShortUrlSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(ShortUrl::class);
    }

    function it_can_get_int_user_id()
    {
        $this->getUser()->shouldBe(null);
    }
}
