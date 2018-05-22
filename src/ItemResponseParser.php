<?php

namespace BCLib\AlmaPrinter;

class ItemResponseParser
{
    /**
     * @var array
     */
    private $label_map;

    public function __construct(array $label_map)
    {
        $this->label_map = $label_map;
    }

    public function parse(string $json): Item
    {
        $decoded = json_decode($json);
        $item_data = $decoded->item_data;
        $holding_data = $decoded->holding_data;
        $bib_data = $decoded->bib_data;

        $item = new Item($item_data->barcode, $this->label_map);
        $item->setCallNumber($holding_data->call_number);
        $item->setLocation($item_data->location->desc);
        $item->setTitle($bib_data->title);
        $item->setAccessionNumber($holding_data->accession_number);

        return $item;
    }
}