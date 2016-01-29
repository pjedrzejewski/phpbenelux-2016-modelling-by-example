<?php

namespace App\Domain;

interface LibraryInterface
{
    public function add(BookInterface $book);
    public function hasBookWithIsbn(Isbn $isbn);
}
