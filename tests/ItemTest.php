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
}
