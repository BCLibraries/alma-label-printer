<?php

namespace BCLib\AlmaPrinter;

use Picqer\Barcode\BarcodeGeneratorPNG;


class Item
{
    private $accession_number = '';
    private $call_number = '';
    private $title = '';
    private $location = '';
    private $barcode = '';
    private $description = '';
    private $label_map;

    # Ugly hack to keep some collection names on one line.
    private static $rewrite_map = [
        'IRISH NEWSPAPERS' => 'IRISH_NEWSPAPERS',
        'LITURGY AND LIFE SERIALS' => 'LITURGY_AND_LIFE_SERIALS',
        'LITURGY AND LIFE' => 'LITURGY_AND_LIFE',
        'IRISH FOLDERS' => 'IRISH_FOLDERS',
        'JESUITICA FOLDERS' => 'JESUITICA_FOLDERS'
    ];

    public function __construct(string $barcode)
    {
        $this->barcode = $barcode;
        $this->label_map = $label_map;
    }

    public function getBarcodeText(): string
    {
        return $this->barcode;
    }

    public function getBarcodeData(): string
    {
        $generator = new BarcodeGeneratorPNG();
        $code = $generator->getBarcode($this->barcode, $generator::TYPE_CODE_39, 1, 40);
        return base64_encode($code);
    }

    public function getCallNumber(): string
    {
        return $this->call_number;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getAccessionNumber(): string
    {
        return $this->accession_number;
    }

    public function getSpineLabelCallNumber(): array
    {
        // Example call number: DA900 .P46 IRISH NEWSPAPERS

        $result = [];
        $split_class_regex = '/^([A-Z][A-Z]?[A-Z]?)(\d)/';
        $working_call_no = preg_replace($split_class_regex, '\1 \2', $this->call_number);

        // Extract collection names from call number to keep collection name on one line.
        if (preg_match('/[A-Z][A-Z]+[A-Z ]+$/', $working_call_no, $matches)) {
            $collection_title = $matches[0];
            $working_call_no = str_replace($collection_title, '', $working_call_no);
        }

        //
        $original_parts = explode(' ', $working_call_no);

        // Reattach the collection name if one exists.
        if (isset($collection_title)) {
            $original_parts[] = $collection_title;
        }

        if ($this->description) {
            $original_parts[] = $this->description;
        }
        foreach ($original_parts as $original_part) {
            $result[] = $this->label_map[$original_part] ?? [$original_part];
        }
        return array_merge(...$result);
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
        $this->call_number = preg_replace('/(\d)(\.[A-Z])/', '\1 \2', $call_number);
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function setLocation(string $location): void
    {
        $this->location = $location;
    }

    public function setAccessionNumber(string $accession_number): void
    {
        $this->accession_number = $accession_number;
    }
}