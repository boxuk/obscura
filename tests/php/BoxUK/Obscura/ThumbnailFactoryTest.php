<?php

namespace BoxUK\Obscura;

use BoxUK\Obscura\ImageDecorator\Factory;
use BoxUK\Obscura\ThumbnailFactory\Config;

require_once 'tests/php/bootstrap.php';

class ThumbnailFactoryTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var BoxUK\Obscura\ImageDecorator\Factory
     */
    private $object;

    public function setUp() {

        $tf = new ThumbnailFactory( new Factory );
        $tf->setInputDirectory('tests/resources');
        $tf->setOutputDirectory('tests/tmp');
        $this->object = $tf;

    }

    public function testCanSetAndGetInputAndOutputDirectories() {

        $this->assertEquals('tests/resources', $this->object->getInputDirectory());
        $this->assertEquals('tests/tmp', $this->object->getOutputDirectory());

    }

    /**
     * Set only a width - height should be automatically set in proportion
     */
    public function testCreatesAThumbnailResizedToANewWidthWithAspectRatioLocked() {

        $outputFilename = 'thumbnail.jpg';

        $config = new Config;
        $config->setInputFilename('test_jpeg_400_x_200.jpg')
               ->setWidth(200)
               ->setOutputFilename($outputFilename);

        // Test with only a width - height should be automatically set in proportion
        $filename = $this->object->createThumbnail($config);

        $path = 'tests/tmp/' . $filename;

        $this->assertTrue( file_exists($path) );
        $this->assertEquals( $outputFilename, $filename );

        $dimensions = getimagesize($path);

        $this->assertEquals( 200, $dimensions[0] );
        $this->assertEquals( 100, $dimensions[1] );

        unlink($path);
    }

    /**
     * Set only a height - width should be automatically set in proportion
     */
    public function testCreatesAThumbnailResizedToANewHeightWithAspectRatioLocked() {

        $outputFilename = 'thumbnail.jpg';
        
        $config = new Config;
        $config->setInputFilename('test_jpeg_400_x_200.jpg')
               ->setHeight(100)
               ->setOutputFilename($outputFilename);

        $filename = $this->object->createThumbnail($config);

        $path = 'tests/tmp/' . $filename;

        $this->assertTrue( file_exists($path) );
        $this->assertEquals( $outputFilename, $filename );

        $dimensions = getimagesize($path);

        $this->assertEquals( 200, $dimensions[0] );
        $this->assertEquals( 100, $dimensions[1] );

        unlink($path);
    }

    public function testCreatesAThumbnailResizedToANewWidthAndHeightWithAGeneratedFilename() {

        $config = new Config;
        $config->setInputFilename('test_jpeg_400_x_200.jpg')
               ->setWidth(100)
               ->setHeight(300);

        // Create one with height and width but with no filename
        $config->setWidth(100)->setHeight(300)->setOutputFilename(null);
        $filename = $this->object->createThumbnail($config);

        $path = 'tests/tmp/' . $filename;

        $this->assertTrue( file_exists($path) );
        $this->assertEquals( 'thumb-'.md5(serialize($config)).'.jpg', $filename);

        $dimensions = getimagesize($path);

        $this->assertEquals( 100, $dimensions[0] );
        $this->assertEquals( 300, $dimensions[1] );

        unlink($path);

    }

    public function testCreatesThumbnailsConstrainedToGivenSize() {

        $config = new Config;
        $config->setInputFilename('test_jpeg_400_x_200.jpg')
               ->setSizeConstraint(200);

        // Landscape
        $filename = $this->object->createThumbnail($config);

        $path = 'tests/tmp/' . $filename;

        $this->assertTrue( file_exists($path) );

        $dimensions = getimagesize($path);

        $this->assertEquals( 200, $dimensions[0] );
        $this->assertEquals( 100, $dimensions[1] );

        unlink($path);

        // Portrait
        $config->setInputFilename('test_jpeg_200_x_400.jpg');
        $filename = $this->object->createThumbnail($config);

        $path = 'tests/tmp/' . $filename;

        $this->assertTrue( file_exists($path) );

        $dimensions = getimagesize($path);

        $this->assertEquals( 100, $dimensions[0] );
        $this->assertEquals( 200, $dimensions[1] );

        unlink($path);

    }

    public function testCanMountAThumbnailWithArbitraryWidthAndHeight() {

        $config = new Config;
        $config->setInputFilename('test_jpeg_400_x_200.jpg')
               ->setMountEnabled(true)
               ->setMountWidth(600)
               ->setMountHeight(400);

        // Landscape
        $filename = $this->object->createThumbnail($config);

        $path = 'tests/tmp/' . $filename;

        $this->assertTrue( file_exists($path) );

        $dimensions = getimagesize($path);

        $this->assertEquals( 600, $dimensions[0] );
        $this->assertEquals( 400, $dimensions[1] );

        unlink($path);

    }

    public function testCanMountingAThumbnailSupplyingOnlyAMountWidthDefaultsTheMountHeightToThatOfTheThumbnail() {

        $config = new Config;
        $config->setInputFilename('test_jpeg_400_x_200.jpg')
               ->setMountEnabled(true)
               ->setMountWidth(600);

        $filename = $this->object->createThumbnail($config);

        $path = 'tests/tmp/' . $filename;

        $this->assertTrue( file_exists($path) );

        $dimensions = getimagesize($path);

        $this->assertEquals( 600, $dimensions[0] );
        $this->assertEquals( 200, $dimensions[1] );

        unlink($path);

    }

    public function testCanMountingAThumbnailSupplyingOnlyAMountHeightDefaultsTheMountWidthToThatOfTheThumbnail() {

        $config = new Config;
        $config->setInputFilename('test_jpeg_400_x_200.jpg')
               ->setMountEnabled(true)
               ->setMountHeight(400);

        $filename = $this->object->createThumbnail($config);

        $path = 'tests/tmp/' . $filename;

        $this->assertTrue( file_exists($path) );

        $dimensions = getimagesize($path);

        $this->assertEquals( 400, $dimensions[0] );
        $this->assertEquals( 400, $dimensions[1] );

        unlink($path);

    }

    public function testCanMountingAThumbnailWithoutSpecifyingWidthOrHeightCreatesASquareMount() {

        // Test portrait and landscape
        $images = array('test_jpeg_400_x_200.jpg', 'test_jpeg_200_x_400.jpg');
        
        $config = new Config;
        $config->setMountEnabled(true);

        foreach($images as $image) {
            $config->setInputFilename($image);

            $filename = $this->object->createThumbnail($config);

            $path = 'tests/tmp/' . $filename;

            $this->assertTrue( file_exists($path) );

            $dimensions = getimagesize($path);

            $this->assertEquals( 400, $dimensions[0] );
            $this->assertEquals( 400, $dimensions[1] );

            unlink($path);
        }
        
    }

    public function testDoesntCreateThumbnailsIfOneExistsAndImageHasntChanged() {

        $config = new Config;
        $config->setInputFilename('test_jpeg_400_x_200.jpg')->setWidth(200)->setCachingEnabled(true);

        $filename = $this->object->createThumbnail($config);

        $path = 'tests/tmp/' . $filename;

        $this->assertTrue( file_exists($path) );
        $thumbnail1Time = filemtime($path);

        $filename2 = $this->object->createThumbnail($config);

        $this->assertEquals( $filename, $filename2 );
        $this->assertEquals( $thumbnail1Time, filemtime($path) );

        // Update the image modified time - should result in the creation of a new thumbnail
        touch('tests/resources/test_jpeg_400_x_200.jpg');
        clearstatcache();
        sleep(1);
        
        $filename3 = $this->object->createThumbnail($config);

        clearstatcache();

        $this->assertEquals( $filename, $filename3 );
        $this->assertNotEquals( $thumbnail1Time, filemtime($path) );

        unlink($path);

    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testThrowsExceptionIfInputDirectoryIsNotValid() {

        $this->object->setInputDirectory('tests/doesntexist');

    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testThrowsExceptionIfOutputDirectoryIsNotValid() {

        $this->object->setOutputDirectory('tests/doesntexist');

    }

    /**
     * @expectedException Exception
     */
    public function testThrowsExceptionIfOutputDirectoryIsNotSet() {

        $tf = new ThumbnailFactory( new Factory );
        $tf->setInputDirectory('tests/resources');

        $config = new Config;
        $config->setInputFilename('test_jpeg_400_x_200.jpg')->setWidth(200);
        $filename = $tf->createThumbnail($config);

    }

    /**
     * @expectedException Exception
     */
    public function testThrowsExceptionIfInputImageIsNotSet() {

        $tf = new ThumbnailFactory( new Factory );
        
        $config = new Config;
        $filename = $tf->createThumbnail($config);

    }

}
