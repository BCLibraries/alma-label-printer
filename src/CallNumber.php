<?php

namespace BCLib\AlmaPrinter;

class CallNumber
{
    /**
     * @var string
     */
    private $call_number;

    private $class_letters;

    public function __construct(string $call_number)
    {
        $this->call_number = $call_number;
    }

    public function getFull(): string
    {
        return $this->call_number;
    }

}