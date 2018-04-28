<?php

namespace BCLib\AlmaPrinter;

interface Cache
{
    public function add(Item $item): void;

    public function contains(string $key): bool;

    public function get(string $key): ?Item;
}