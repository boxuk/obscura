<?php

namespace BoxUK\Obscura\ImageDecorator;

require_once 'tests/php/bootstrap.php';

/**
 * 
 */
class JPEGTest extends \PHPUnit_Framework_TestCase {
    
    /**
     * @var JPEG
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        $this->object = new JPEG( new Factory );
    }

    /**
     * 
     */
    public function testOutputsImageToClientWithJPEGEncoding() {

        $this->object->load( imagecreatefromjpeg( 'tests/resources/test_jpeg_400_x_200.jpg') );

        // Test for plain streaming
        ob_start();
        $result = $this->object->output();
        $output = ob_get_contents();
        ob_end_clean();

        // This would be true if an error was printed.. Need to think of a better way to test for an image stream
        $this->assertTrue($result);
        $this->assertTrue(is_string($output));

    }

    public function testReturnsCorrectImageType() {

        $this->assertEquals(IMAGETYPE_JPEG, $this->object->getImageType());

    }

    public function testReturnsCorrectMimeType() {

        $this->assertEquals('image/jpeg', $this->object->getMimeType());

    }
    
}