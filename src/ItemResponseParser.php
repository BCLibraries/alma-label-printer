<?php

namespace BCLib\AlmaPrinter;

class ItemResponseParser
{
    public static function parse(string $json): Item
    {
        $decoded = json_decode($json);
        $item_data = $decoded->item_data;
        $holding_data = $decoded->holding_data;
        $bib_data = $decoded->bib_data;

        $item = new Item($item_data->barcode);
        $item->setCallNumber($holding_data->call_number);
        $item->setLocation($item_data->location->desc);
        $item->setTitle($bib_data->title);

        return $item;
    }
}