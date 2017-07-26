<?php

namespace FinalBytes\GoogleDistanceMatrix\Response;

class Duration
{
    private $text;

    private $value;

    public function __construct(string $text, int $value)
    {
        $this->text = $text;
        $this->value = $value;
    }

    public function __toString() : string
    {
        return $this->text;
    }

    public function getText() : string
    {
        return $this->text;
    }

    public function getValue() : int
    {
        return $this->value;
    }
}