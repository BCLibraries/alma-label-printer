<?php

namespace BCLib\AlmaPrinter;

class NullCache implements Cache
{

    public function add(Item $item): void
    {
        // TODO: Implement add() method.
    }

    public function contains(string $key): bool
    {
        return false;
    }

    public function get(string $key): ?Item
    {
        return null;
    }
}