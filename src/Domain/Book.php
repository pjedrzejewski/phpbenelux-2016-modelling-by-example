<?php

namespace App\Domain;

class Book implements BookInterface
{
    private $isbn;
    private $title;

    private function __construct(BookTitle $title, Isbn $isbn)
    {
        $this->isbn = $isbn;
        $this->title = $title;
    }

    public static function withTitleAndIsbn(BookTitle $title, Isbn $isbn)
    {
        return new self($title, $isbn);
    }

    public function title()
    {
        return $this->title;
    }

    public function isbn()
    {
        return $this->isbn;
    }
}
