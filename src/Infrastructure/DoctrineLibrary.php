<?php

namespace App\Infrastructure;

use App\Domain\BookInterface;
use App\Domain\Isbn;
use App\Domain\LibraryInterface;
use App\Domain\SearchResults;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;

class DoctrineLibrary implements LibraryInterface
{
    private $doctrineManager;
    private $doctrineRepository;

    public function __construct(ObjectManager $doctrineManager, ObjectRepository $doctrineRepository)
    {
        $this->doctrineManager = $doctrineManager;
        $this->doctrineRepository = $doctrineRepository;
    }

    public function add(BookInterface $book)
    {
        $isbn = $book->isbn();

        if ($this->hasBookWithIsbn($isbn)) {
            throw new \InvalidArgumentException(sprintf('Book with ISBN "%s" already exists!', $isbn));
        }

        $this->doctrineManager->persist($book);
        $this->doctrineManager->flush();
    }

    public function remove(Isbn $isbn)
    {
        if (!$this->hasBookWithIsbn($isbn)) {
            throw new \InvalidArgumentException(sprintf('Book with ISBN "%s" does not exist!', $isbn));
        }

        $book = $this->doctrineRepository->findOneBy(array('isbn.number' => $isbn));

        $this->doctrineManager->remove($book);
        $this->doctrineManager->flush();
    }

    public function hasBookWithIsbn(Isbn $isbn)
    {
        return null !== $this->doctrineRepository->findOneBy(array('isbn.number' => $isbn));
    }

    public function searchByIsbn(Isbn $isbn)
    {
        $book = $this->doctrineRepository->findOneBy(array('isbn.number' => $isbn));

        if (null === $book) {
            return SearchResults::asEmpty();
        }

        return SearchResults::fromArrayOfBooks(array($book));
    }
}
