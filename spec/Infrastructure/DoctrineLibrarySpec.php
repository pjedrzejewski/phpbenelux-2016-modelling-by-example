<?php

namespace spec\App\Infrastructure;

use App\Domain\BookInterface;
use App\Domain\Isbn;
use App\Domain\LibraryInterface;
use App\Domain\SearchResults;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class DoctrineLibrarySpec extends ObjectBehavior
{
    function let(ObjectManager $doctrineManager, ObjectRepository $doctrineRepository)
    {
        $this->beConstructedWith($doctrineManager, $doctrineRepository);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('App\Infrastructure\DoctrineLibrary');
    }

    function it_is_a_library()
    {
        $this->shouldImplement(LibraryInterface::class);
    }

    function it_adds_and_persists_a_new_book(BookInterface $book, ObjectManager $doctrineManager, ObjectRepository $doctrineRepository)
    {
        $isbn = new Isbn('978-1-56619-909-4');
        $book->isbn()->willReturn($isbn);

        $doctrineRepository->findOneBy(array('isbn.number' => $isbn))->willReturn(null);

        $doctrineManager->persist($book)->shouldBeCalled();
        $doctrineManager->flush()->shouldBeCalled();

        $this->add($book);
    }

    function it_throws_an_exception_if_book_with_given_isbn_already_exists(BookInterface $book, ObjectManager $doctrineManager, ObjectRepository $doctrineRepository)
    {
        $isbn = new Isbn('978-1-56619-909-4');
        $book->isbn()->willReturn($isbn);

        $doctrineRepository->findOneBy(array('isbn.number' => $isbn))->willReturn($book);

        $doctrineManager->persist($book)->shouldNotBeCalled();
        $doctrineManager->flush()->shouldNotBeCalled();

        $this
            ->shouldThrow(new \InvalidArgumentException('Book with ISBN "978-1-56619-909-4" already exists!'))
            ->duringAdd($book)
        ;
    }

    function it_removes_a_book_based_on_isbn_number(BookInterface $book, ObjectManager $doctrineManager, ObjectRepository $doctrineRepository)
    {
        $isbn = new Isbn('978-1-56619-909-4');

        $doctrineRepository->findOneBy(array('isbn.number' => $isbn))->willReturn($book);

        $doctrineManager->remove($book)->shouldBeCalled();
        $doctrineManager->flush()->shouldBeCalled();

        $this->remove($isbn);
    }

    function it_throws_an_exception_if_book_with_given_isbn_does_not_exist(BookInterface $book, ObjectManager $doctrineManager, ObjectRepository $doctrineRepository)
    {
        $isbn = new Isbn('978-1-56619-909-4');

        $doctrineRepository->findOneBy(array('isbn.number' => $isbn))->willReturn(null);

        $this
            ->shouldThrow(new \InvalidArgumentException('Book with ISBN "978-1-56619-909-4" does not exist!'))
            ->duringRemove($isbn)
        ;
    }

    function it_returns_true_if_book_exists_in_the_catalog(BookInterface $book, ObjectRepository $doctrineRepository)
    {
        $isbn = new Isbn('978-1-56619-909-4');
        $doctrineRepository->findOneBy(array('isbn.number' => $isbn))->willReturn($book);

        $this->hasBookWithIsbn($isbn)->shouldReturn(true);
    }

    function it_returns_false_if_book_does_not_exist_in_the_catalog(Isbn $isbn, $doctrineRepository)
    {
        $isbn = new Isbn('978-1-56619-909-4');

        $doctrineRepository->findOneBy(array('isbn.number' => $isbn))->willReturn(null);

        $this->hasBookWithIsbn($isbn)->shouldReturn(false);
    }

    function it_searches_book_by_isbn_number(BookInterface $book, ObjectRepository $doctrineRepository)
    {
        $isbn = new Isbn('978-1-56619-909-4');

        $doctrineRepository->findOneBy(array('isbn.number' => $isbn))->willReturn($book);
        $this->searchByIsbn($isbn)->shouldBeLike(SearchResults::fromArrayOfBooks(array($book->getWrappedObject())));

        $doctrineRepository->findOneBy(array('isbn.number' => $isbn))->willReturn(null);
        $this->searchByIsbn($isbn)->shouldBeLike(SearchResults::asEmpty());
    }
}
