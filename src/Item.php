<?php

namespace BCLib\AlmaPrinter;


class Item
{
    private $call_number = '';
    private $title = '';
    private $location = '';
    private $barcode = '';

    public function __construct(string $barcode)
    {
        $this->barcode = $barcode;
    }

    public function getBarcode(): string
    {
        return $this->barcode;
    }

    public function getCallNumber(): string
    {
        return $this->call_number;
    }

    public function getTitle(): string
    {
        return $this->title;
    }


    public function getLocation(): string
    {
        return $this->location;
    }


    public function setCallNumber(string $call_number): void
    {
        $this->call_number = $call_number;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function setLocation(string $location): void
    {
        $this->location = $location;
    }
}