<?php

namespace BoxUK\Obscura;

class ColorParserTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var ColorParser
     */
    protected $object;

    /**
     *
     */
    protected function setUp() {
        $this->object = new ColorParser();
    }

    /**
     *
     */
    public function valid_colors() {
        return array(
            "Correct format" => array( array(255, 120, 0, 0), array(255, 120, 0, 0) ),
            "No transparency (default to opaque)" => array(array(255, 120, 0), array(255,120, 0, 0) )
        );
    }

    /**
     * @dataProvider valid_colors
     */
    public function testParsesArraysToDetermineColor($color, $expected) {

        $array = $this->object->getRGBAValuesFromColor($color);

        $this->assertTrue(is_array($array));
        $this->assertTrue(count($array) === 4);
        $this->assertSame($expected, $array);

    }

    /**
     *
     */
    public function invalid_colors() {
        return array(
            "Too few arguments"  => array(array(10, 20)),
            "Too many arguments" => array(array(10, 20, 30, 40, 50)),
            "Nonsense string"    => array('somenonsensestring')
        );
    }

    /**
     * @dataProvider invalid_colors
     * @expectedException InvalidArgumentException
     */
    public function testFailsParsingInvalidColors($invalid_color) {

        $this->object->getRGBAValuesFromColor($invalid_color);

    }

    /**
     *
     */
    public function hexadecimal_colors() {
        return array(
            "Hexadecimal color"  => array( 'FF9900',  array(255, 153, 0, 0) ),
            "Web format"         => array( '#CCCCCC', array(204, 204, 204, 0) )
        );
    }

    /**
     * @dataProvider hexadecimal_colors
     */
    public function testParsesHexadecimalStringsAndDeterminesColor($color, $expected) {

        $array = $this->object->getRGBAValuesFromColor($color);

        $this->assertTrue(is_array($array));
        $this->assertTrue(count($array) === 4);
	$this->assertSame($expected, $array);

    }
}
