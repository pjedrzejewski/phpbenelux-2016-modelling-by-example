<?php

namespace App\Domain;

class SearchResults implements \Countable
{
    private $books = array();

    private function __construct(array $books)
    {
        foreach ($books as $book) {
            $this->assertIsBook($book);
        }

        $this->books = $books;
    }

    public static function fromArrayOfBooks(array $books)
    {
        return new self($books);
    }

    public static function asEmpty()
    {
        return new self(array());
    }

    public function count()
    {
        return count($this->books);
    }

    public function isEmpty()
    {
        return 0 === $this->count();
    }

    public function books()
    {
        return $this->books;
    }

    private function assertIsBook($argument)
    {
        if (!$argument instanceof BookInterface) {
            throw new \InvalidArgumentException(sprintf('Results can only contain instances of "%s".', BookInterface::class));
        }
    }
}
