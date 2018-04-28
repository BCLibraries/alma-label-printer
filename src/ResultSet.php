<?php

namespace BCLib\AlmaPrinter;

class ResultSet
{
    private $items = [];

    public function __construct(array $items = null)
    {
        if (null !== $items) {
            $this->items = $items;
        }
    }

    public function addItem(Item $item): void
    {
        $this->items[] = $item;
    }

    public function getPages(int $items_per_page): array
    {
        return array_chunk($this->items, $items_per_page);
    }
}