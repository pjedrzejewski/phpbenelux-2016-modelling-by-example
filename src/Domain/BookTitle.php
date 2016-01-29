<?php

namespace App\Domain;

class BookTitle
{
    private $title;

    public function __construct($title)
    {
        if (empty(trim($title))) {
            throw new \InvalidArgumentException('Book title cannot be empty.');
        }

        $this->title = $title;
    }

    public function __toString()
    {
        return $this->title;
    }
}
