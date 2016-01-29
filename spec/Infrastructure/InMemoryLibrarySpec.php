<?php

namespace spec\App\Infrastructure;

use App\Domain\BookInterface;
use App\Domain\Isbn;
use App\Domain\LibraryInterface;
use App\Domain\SearchResults;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class InMemoryLibrarySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('App\Infrastructure\InMemoryLibrary');
    }

    function it_is_a_library()
    {
        $this->shouldImplement(LibraryInterface::class);
    }

    function it_adds_book_via_isbn_number(BookInterface $book)
    {
        $isbn = new Isbn('978-1-56619-909-4');
        $book->isbn()->willReturn($isbn);

        $this->hasBookWithIsbn($isbn)->shouldReturn(false);
        $this->add($book);
        $this->hasBookWithIsbn($isbn)->shouldReturn(true);
    }

    function it_throws_exception_when_book_with_given_isbn_already_exists(BookInterface $book)
    {
        $isbn = new Isbn('978-1-56619-909-4');
        $book->isbn()->willReturn($isbn);

        $this->add($book);

        $this
            ->shouldThrow(new \InvalidArgumentException('Book with ISBN "978-1-56619-909-4" already exists!'))
            ->duringAdd($book)
        ;
    }

    function it_removes_book_via_isbn_number(BookInterface $book)
    {
        $isbn = new Isbn('978-1-56619-909-4');
        $book->isbn()->willReturn($isbn);

        $this->add($book);
        $this->remove($isbn);
        $this->hasbookWithIsbn($isbn)->shouldReturn(false);
    }

    function it_throws_exception_if_book_with_given_isbn_does_not_exist()
    {
        $this
            ->shouldThrow(new \InvalidArgumentException('Book with ISBN "978-1-56619-909-4" does not exist!'))
            ->duringRemove(new Isbn('978-1-56619-909-4'))
        ;
    }

    function it_returns_0_results_when_searching_by_isbn_and_empty()
    {
        $this->searchByIsbn(new Isbn('978-1-56619-909-4'))->shouldHaveCount(0);
    }

    function it_returns_exactly_one_result_when_searching_by_exact_isbn_and_it_matches(BookInterface $book)
    {
        $isbn = new Isbn('978-1-56619-909-4');
        $book->isbn()->willReturn($isbn);

        $this->add($book);

        $this->searchByIsbn($isbn)->shouldBeLike(SearchResults::fromArrayOfBooks(array($book->getWrappedObject())));
    }
}
