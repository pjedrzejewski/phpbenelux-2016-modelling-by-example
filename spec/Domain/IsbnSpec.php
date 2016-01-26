<?php

namespace spec\App\Domain;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class IsbnSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('978-1-56619-909-4');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('App\Domain\Isbn');
    }

    function it_cannot_be_empty()
    {
        $this->shouldThrow(new \InvalidArgumentException('ISBN number cannot be an empty string!'))->during('__construct', array(''));
    }

    function it_has_to_respect_isbn10_or_isbn13_standard()
    {
        $this->shouldThrow(new \InvalidArgumentException('This is not a valid ISBN number.'))->during('__construct', array('12345'));
    }

    function it_can_be_represented_as_string()
    {
        $this->__toString()->shouldReturn('978-1-56619-909-4');
    }
}
