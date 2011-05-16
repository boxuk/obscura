<?php

namespace BoxUK\Obscura\ImageDecorator;

use BoxUK\Obscura\ImageDecorator;

require_once 'tests/php/bootstrap.php';

class AbstractDecoratorTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var Obscura_ImageDecorator_Mock
     */
    protected $object;

    /**
     *
     */
    protected function setUp() {
        $this->object = new \Obscura_ImageDecorator_Mock( new Factory );
    }

    /**
     *
     */
    public function testGetImage() {

        $this->assertTrue(is_resource($this->object->getImage()));

    }

    /**
     *
     */
    public function testGetWidthAndGetHeight() {

        $this->assertEquals(400, $this->object->getWidth());
        $this->assertEquals(200, $this->object->getHeight());

    }

    /**
     *
     */
    public function testGettingAndSettingFactory() {

        $factory1 = new Factory();

        $this->object->setFactory($factory1);

        $factory2 = $this->object->getFactory();

        $this->assertTrue($factory2 instanceof Factory);
        $this->assertSame($factory1, $factory2);

    }

    /**
     *
     */
    public function testCreatesResizedImages() {

        // Resizing to new width and height
        $image = $this->object->resize(200, 100);

        $this->assertTrue($image instanceof ImageDecorator);

        $this->assertEquals(200, $image->getWidth());
        $this->assertEquals(100, $image->getHeight());

        // Resizing by width only, not auto scaling height
        $image = $this->object->resize(300);
        $this->assertEquals(300, $image->getWidth());
        $this->assertEquals(100, $image->getHeight());

        // Resizing by height only, not auto scaling width
        $image = $this->object->resize(null, 200);
        $this->assertEquals(300, $image->getWidth());
        $this->assertEquals(200, $image->getHeight());

        // Resizing by width only, auto scaling height
        $image = $this->object->resize(150, null, true);
        $this->assertEquals(150, $image->getWidth());
        $this->assertEquals(100, $image->getHeight());

        // Resizing by height only, auto scaling width
        $image = $this->object->resize(null, 200, true);
        $this->assertEquals(300, $image->getWidth());
        $this->assertEquals(200, $image->getHeight());

    }

    public function testCreatesCroppedImagesWithWidthAndHeightSpecified() {
        // Resizing to new width and height.
        $image = $this->object->crop(200, 100);

        $this->assertTrue($image instanceof ImageDecorator);

        $this->assertEquals(200, $image->getWidth());
        $this->assertEquals(100, $image->getHeight());
    }

    public function testCreatesCroppedImagesWithOnlyWidthSpecified() {
        // Resizing by width only, original height.
        $image = $this->object->crop(300);
        $this->assertEquals(300, $image->getWidth());
        $this->assertEquals(200, $image->getHeight());
    }

    public function testCreatesCroppedImagesWithOnlyHeightSpecified() {
        // Resizing by height only, original width.
        $image = $this->object->crop(null, 50);
        $this->assertEquals(400, $image->getWidth());
        $this->assertEquals(50, $image->getHeight());
    }

    /**
     * Crop a 400 x 200 test image to 100 x 100.  The test image is a centered 100 x 100 red square inside a blue rectangle: cropping it, we'd expect a 100 x 100
     * red square image.
     */
    public function testCropUsesCorrectPortionOfOriginalImage() {
        $this->object = new \Obscura_ImageDecorator_Mock(new Factory, 'tests/resources/test_jpeg_400_x_200_crop.jpg');
        $image = $this->object->crop(100, 100);

        $aPoints = array(
            'topLeft' => array(0, 0),
            'topRight' => array(99, 0),
            'bottomLeft' => array(0, 99),
            'bottomRight' => array(99, 99),
        );

        foreach ($aPoints as $name => $aXAndY) {
            $color = imagecolorat($image->getImage(), $aXAndY[0], $aXAndY[1]);
            $aIndexedColors = imagecolorsforindex($image->getImage(), $color);

            /*
             * Use assertLessThan() and assertGreaterThan() as due to JPEG compression, the colours may not be exactly right.
             * As long as each pixel is almost completely red, with very little blue, we have cropped correctly.
             */
            $this->assertGreaterThan(250, $aIndexedColors['red']);
            $this->assertLessThan(10, $aIndexedColors['blue']);
        }
    }

    /**
     *
     */
    public function testMountsImagesOntoBackground() {

        $mountedImage = $this->object->mount(600, 400, '#000000');

        $this->assertTrue($mountedImage instanceof ImageDecorator);
        $this->assertEquals(600, $mountedImage->getWidth());
        $this->assertEquals(400, $mountedImage->getHeight());

        // An exception should be thrown if the image size is too big for the mount
        try {
            $mountedImage = $this->object->mount(100, 100, '#000000');
            $this->fail('Mount size should exceed image size');
        }
        catch(\InvalidArgumentException $e) {
            // pass
        }

    }

    /**
     *
     */
    public function testCanDetermineOrientation() {

        // Landscape
        $image = new \Obscura_ImageDecorator_Mock(null, 'tests/resources/test_jpeg_400_x_200.jpg');
        $this->assertEquals(ImageDecorator::ORIENTATION_LANDSCAPE, $image->getOrientation());

        // Portrait
        $image = new \Obscura_ImageDecorator_Mock(null, 'tests/resources/test_jpeg_200_x_400.jpg');
        $this->assertEquals(ImageDecorator::ORIENTATION_PORTRAIT, $image->getOrientation());

        // Square
        $image = new \Obscura_ImageDecorator_Mock(null, 'tests/resources/test_jpeg_400_x_400.jpg');
        $this->assertEquals(ImageDecorator::ORIENTATION_SQUARE, $image->getOrientation());

    }

    /**
     *
     */
    public function testImageCanBeConvertedToAnArray() {

        $array = $this->object->toArray();

        $this->assertTrue(is_array($array));
        $this->assertEquals($this->object->getWidth(), $array['width']);
        $this->assertEquals($this->object->getHeight(), $array['height']);
        $this->assertEquals($this->object->getOrientation(), $array['orientation']);

    }

    /**
     *
     */
    public function testCanOutputImageToDifferentFormats() {

        $filename = 'tests/tmp/tmp.gif';
        $result = $this->object->output($filename, IMAGETYPE_GIF);

        $this->assertTrue($result);
        $this->assertFileExists($filename);

        $data = getimagesize($filename);
        $this->assertEquals('image/gif', strtolower($data['mime']));

        unlink($filename);

    }

    /**
     *
     */
    public function testCanCreateCanvasForModifyingImages() {

        $canvas = $this->object->createCanvas(100, 100);

        $this->assertTrue(is_resource($canvas));
        $this->assertEquals(100, imagesx($canvas));
        $this->assertEquals(100, imagesy($canvas));

    }

}
