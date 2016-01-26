<?php

namespace spec\App\Domain;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class BookTitleSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('Winds of Winter');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('App\Domain\BookTitle');
    }

    function it_cannot_be_empty()
    {
        $this->shouldThrow(new \InvalidArgumentException('Book title cannot be empty.'))->during('__construct', array(''));
    }

    function it_can_be_represented_as_string()
    {
        $this->__toString()->shouldReturn('Winds of Winter');
    }
}
