<?php

namespace FinalBytes\GoogleDistanceMatrix\Response;

class Address
{
    private $address;

    public function __construct($address)
    {
        $this->address = $address;
    }

    public function __toString() : string
    {
        return $this->address;
    }
}