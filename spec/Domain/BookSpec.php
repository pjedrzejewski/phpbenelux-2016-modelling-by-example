<?php

namespace spec\App\Domain;

use App\Domain\BookInterface;
use App\Domain\BookTitle;
use App\Domain\Isbn;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class BookSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedThrough('withTitleAndIsbn', array(new BookTitle('Winds of Winter'), new Isbn('978-1-56619-909-4')));
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('App\Domain\Book');
    }

    function it_is_a_book()
    {
        $this->shouldImplement(BookInterface::class);
    }

    function it_has_a_title()
    {
        $this->title()->shouldBeLike(new BookTitle('Winds of Winter'));
    }

    function it_has_an_isbn_number()
    {
        $this->isbn()->shouldBeLike(new Isbn('978-1-56619-909-4'));
    }
}
