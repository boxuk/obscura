<?php

namespace BoxUK\Obscura\ImageDecorator;

require_once 'tests/php/bootstrap.php';

/**
 * 
 */
class PNGTest extends \PHPUnit_Framework_TestCase {
    
    /**
     * @var PNG
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        $this->object = new PNG( new Factory );
    }

    /**
     * 
     */
    public function testOutputsImageToClientWithPNGEncoding() {

        $this->object->load( imagecreatefrompng( 'tests/resources/test_png_400_x_200.png') );

        // Test for plain streaming
        ob_start();
        $this->object->output();
        $output = ob_get_contents();
        ob_end_clean();

        // This would be true if an error was printed.. Need to think of a better way to test for an image stream
        $this->assertTrue(is_string($output));

    }

    /**
     *
     */
    public function testOutputsImageToFileWithPNGEncoding() {

        $this->object->load( imagecreatefrompng( 'tests/resources/test_png_400_x_200.png') );

        $filename = 'tests/tmp/temp.png';

        $this->object->output($filename);

        $this->assertFileExists($filename);

        $data = getimagesize($filename);

        $this->assertEquals('image/png', strtolower($data['mime']));

        unlink($filename);

    }

    public function testReturnsCorrectImageType() {

        $this->assertEquals(IMAGETYPE_PNG, $this->object->getImageType());

    }

    public function testReturnsCorrectMimeType() {

        $this->assertEquals('image/png', $this->object->getMimeType());

    }
    
}