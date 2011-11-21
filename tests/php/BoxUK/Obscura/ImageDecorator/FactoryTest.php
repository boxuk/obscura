<?php

namespace BoxUK\Obscura\ImageDecorator;

require_once 'tests/php/bootstrap.php';

class FactoryTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var Factory
     */
    public $object;

    public function setUp() {
        
        $this->object = new Factory();
    
    }

    public function testCreatesAppropriateDecoratorForImageSource() {

        // JPEG
        $image = $this->object->loadImageFromFile('tests/resources/test_jpeg_400_x_200.jpg');

        $this->assertTrue($image instanceOf JPEG);

        // GIF
        $image = $this->object->loadImageFromFile('tests/resources/test_gif_400_x_200.gif');

        $this->assertTrue($image instanceOf GIF);

        // PNG
        $image = $this->object->loadImageFromFile('tests/resources/test_png_400_x_200.png');

        $this->assertTrue($image instanceOf PNG);

    }

    public function testLoadsImageGivenFormatAndExistingResource() {

        $image = $this->object->loadImageFromFile('tests/resources/test_jpeg_400_x_200.jpg');

        $image2 = $this->object->loadImageFromResource($image->getImage(), $image->getImageType());

        $this->assertTrue($image instanceof JPEG);

    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testThrowsExceptionWhenLoadingFromNonexistentFile() {

        $this->object->loadImageFromFile('nonexistentfile');

    }

    /**
     * @expectedException BoxUK\Obscura\Exception
     */
    public function testThrowsExceptionWhenCannotDetermineImageFormat() {

        $this->object->loadImageFromFile('tests/resources/non_image_file.txt');

    }

    /**
     * @expectedException BoxUK\Obscura\Exception
     */
    public function testThrowsExceptionWhenUnknownFormatIsSupplied() {

        $this->object->loadImageFromResource('invalidformat', null);

    }

}
