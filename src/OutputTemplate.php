<?php

namespace BCLib\AlmaPrinter;

class OutputTemplate
{
    public $name;
    public $items_per_page;

    public function __construct(string $name, int $items_per_page)
    {
        $this->name = $name;
        $this->items_per_page = $items_per_page;
    }
}