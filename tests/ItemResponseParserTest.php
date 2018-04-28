<?php

namespace BCLib\AlmaPrinter;

class ItemResponseParserTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function testParse(): void
    {
        $expected = new Item('39031028676716');
        $expected->setTitle("Fodor's Ireland.");
        $expected->setLocation('Stacks');
        $expected->setCallNumber('DA978 .I7');
        $json = file_get_contents(__DIR__ . '/response-01.json');
        $this->assertEquals($expected, ItemResponseParser::parse($json));
    }
}
