<?php

namespace App\Infrastructure;

use App\Domain\LibraryInterface;
use App\Domain\BookInterface;
use App\Domain\Isbn;

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

    public function hasBookWithIsbn(Isbn $isbn)
    {
        return array_key_exists((string) $isbn, $this->books);
    }
}