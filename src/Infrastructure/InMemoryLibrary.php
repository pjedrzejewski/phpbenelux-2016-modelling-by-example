<?php

namespace App\Infrastructure;

use App\Domain\BookInterface;
use App\Domain\Isbn;
use App\Domain\LibraryInterface;
use App\Domain\SearchResults;

class InMemoryLibrary implements LibraryInterface
{
    private $books = array();

    public function add(BookInterface $book)
    {
        $isbn = $book->isbn();

        if ($this->hasBookWithIsbn($isbn)) {
            throw new \InvalidArgumentException(sprintf('Book with ISBN "%s" already exists!', $isbn));
        }

        $this->books[(string) $isbn] = $book;
    }

    public function remove(Isbn $isbn)
    {
        if (!$this->hasBookWithIsbn($isbn)) {
            throw new \InvalidArgumentException(sprintf('Book with ISBN "%s" does not exist!', $isbn));
        }

        unset($this->books[(string) $isbn]);
    }

    public function hasBookWithIsbn(Isbn $isbn)
    {
        return array_key_exists((string) $isbn, $this->books);
    }

    public function searchByIsbn(Isbn $isbn)
    {
        $books = $this->hasBookWithIsbn($isbn) ? array($this->books[(string) $isbn]) : array();

        return SearchResults::fromArrayOfBooks($books);
    }
}
