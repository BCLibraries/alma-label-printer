<?php

namespace BCLib\AlmaPrinter;

class ItemTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->item = new Item('1234');
    }

    public function testSetLocation(): void
    {
        $this->item->setLocation('Stacks');
        $this->assertEquals('Stacks', $this->item->getLocation());
    }

    public function testSetTitle(): void
    {
        $this->item->setTitle("Fodor's Ireland.");
        $this->assertEquals('Fodor\'s Ireland.', $this->item->getTitle());
    }

    public function testSetCallNumber(): void
    {
        $this->item->setCallNumber('DA978 .I7');
        $this->assertEquals('DA978 .I7', $this->item->getCallNumber());
    }

    public function testGetParsedCallNumber(): void
    {
        $this->item->setCallNumber('PR6029.C33 Z7 1965 IRISH FOLDERS');
        $expected = [
            'PR6029',
            '.C33',
            'Z7',
            '1965',
            'IRISH',
            'FOLDERS'
        ];
        $this->assertEquals($expected, $this->item->getSpineLabelCallNumber());
    }

    public function testParsedCallNumberWithLongWords(): void
    {
        $this->item->setCallNumber('PR6029.C33 Z7 1965 IRISH NEWSPAPERS');
        $expected = [
            'PR6029',
            '.C33',
            'Z7',
            '1965',
            'IRISH',
            'NEWS-',
            'PAPERS'
        ];
        $this->assertEquals($expected, $this->item->getSpineLabelCallNumber(['NEWSPAPERS' => ['NEWS-', 'PAPERS']]));
    }
}
