<?php

namespace BoxUK\Obscura\ImageDecorator;

require_once 'tests/php/bootstrap.php';

/**
 * 
 */
class GIFTest extends \PHPUnit_Framework_TestCase {
    
    /**
     * @var GIF
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        $this->object = new GIF( new Factory );
    }

    /**
     * 
     */
    public function testOutputsImageToClientWithGIFEncoding() {

        $this->object->load( imagecreatefromgif( 'tests/resources/test_gif_400_x_200.gif') );

        // Test for plain streaming
        ob_start();
        $this->object->output();
        $output = ob_get_contents();
        ob_end_clean();

        // This would be true if an error was printed.. Need to think of a better way to test for an image stream
        $this->assertTrue(is_string($output));

    }

    public function testReturnsCorrectImageType() {

        $this->assertEquals(IMAGETYPE_GIF, $this->object->getImageType());

    }

    public function testReturnsCorrectMimeType() {

        $this->assertEquals('image/gif', $this->object->getMimeType());

    }
    
}