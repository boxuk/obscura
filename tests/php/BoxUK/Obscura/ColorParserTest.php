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
    public function testParsesArraysToDetermineColor() {

        // Pass in an array in the correct format
        $color = array( 255, 120, 0, 0 );

        $array = $this->object->getRGBAValuesFromColor($color);

        $this->assertTrue(is_array($array));
        $this->assertTrue(count($array) === 4);
        $this->assertSame($color, $array);

        // Pass in an array without a transparency parameter - we should default to opaque
        $color = array( 255, 120, 0 );

        $array = $this->object->getRGBAValuesFromColor($color);

        $this->assertTrue(is_array($array));
        $this->assertTrue(count($array) === 4);
        $this->assertEquals(0, $array[3]);

        // Pass in an array with too few arguments - exception should be thrown
        try {
            $color = array(10, 20);
            $this->object->getRGBAValuesFromColor($color);
            $this->fail();
        }
        catch(\InvalidArgumentException $expected) {
            // pass
        }

        // Pass in an array with too many arguments - exception should be thrown
        try {
            $color = array(10, 20, 30, 40, 50);
            $this->object->getRGBAValuesFromColor($color);
            $this->fail();
        }
        catch(\InvalidArgumentException $expected) {
            //pass
        }

    }

    /**
     *
     */
    public function testParsesHexadecimalStringsAndDeterminesColor() {

        // Test parsing a hexadecimal color
        $color = 'FF9900';

        $array = $this->object->getRGBAValuesFromColor($color);

        $this->assertTrue(is_array($array));
        $this->assertTrue(count($array) === 4);
        $this->assertEquals(255, $array[0]);
        $this->assertEquals(153, $array[1]);
        $this->assertEquals(0, $array[2]);
        $this->assertEquals(0, $array[3]); // No way to specify transparency, should default to opaque

        // Some people may use web-format
        $color = '#CCCCCC';

        $array = $this->object->getRGBAValuesFromColor($color);

        $this->assertTrue(is_array($array));
        $this->assertTrue(count($array) === 4);
        $this->assertEquals(204, $array[0]);
        $this->assertEquals(204, $array[1]);
        $this->assertEquals(204, $array[2]);
        $this->assertEquals(0, $array[3]);

        // Try some nonsense
        try {
            $color = 'somenonsensestring';
            $this->object->getRGBAValuesFromColor($color);
            $this->fail();
        }
        catch(\InvalidArgumentException $expected) {}

    }
}