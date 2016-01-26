<?php

namespace App\Domain;

class Isbn
{
    private $number;

    public function __construct($number)
    {
        if (empty($number)) {
            throw new \InvalidArgumentException('ISBN number cannot be an empty string!');
        }

        if (!$this->isValidIsbn13($number)) {
            throw new \InvalidArgumentException('This is not a valid ISBN number.');
        }

        $this->number = $number;
    }

    public function __toString()
    {
        return $this->number;
    }

    private function isValidIsbn13($number)
    {
        $number = (string) $number;
        $number = str_replace('-', '', $number);

        if (!ctype_digit($number)) {
            return false;
        }

        $length = strlen($number);

        if ($length !== 13) {
            return false;
        }

        $checkSum = 0;

        for ($i = 0; $i < 13; $i += 2) {
            $checkSum += $number{$i};
        }
        for ($i = 1; $i < 12; $i += 2) {
            $checkSum += $number{$i} * 3;
        }

        if(0 !== $checkSum % 10) {
            return false;
        }

        return true;
    }
}
