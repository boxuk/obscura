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

    public function thumbnail_configurations() {

	$inputFilename          = 'test_jpeg_400_x_200.jpg';
	$landscapeInputFilename = 'test_jpeg_200_x_400.jpg';

	$outputFilename = 'thumbnail.jpg';

        $fixed_width = new Config;
        $fixed_width->setInputFilename($inputFilename)
                    ->setWidth(200)
                    ->setOutputFilename($outputFilename);

        $fixed_height = new Config;
        $fixed_height->setInputFilename($inputFilename)
                     ->setHeight(100)
                     ->setOutputFilename($outputFilename);

        $generated_filename = new Config;
        $generated_filename->setInputFilename($inputFilename)
                           ->setWidth(100)
                           ->setHeight(300);

        $constrained = new Config;
        $constrained->setInputFilename($inputFilename)
                    ->setSizeConstraint(200)
                    ->setOutputFilename($outputFilename);

        $arbitrary = new Config;
        $arbitrary->setInputFilename($inputFilename)
                  ->setMountEnabled(true)
                  ->setMountWidth(600)
                  ->setMountHeight(400)
                  ->setOutputFilename($outputFilename);

        $mount_fixed_width = new Config;
        $mount_fixed_width->setInputFilename($inputFilename)
                          ->setMountEnabled(true)
                          ->setMountWidth(600)
                          ->setOutputFilename($outputFilename);

        $mount_fixed_height = new Config;
        $mount_fixed_height->setInputFilename($inputFilename)
                           ->setMountEnabled(true)
                           ->setMountHeight(400)
                           ->setOutputFilename($outputFilename);

        $mount_square_portrait = new Config;
        $mount_square_portrait->setInputFilename($inputFilename)
                              ->setMountEnabled(true)
                              ->setOutputFilename($outputFilename);

        $mount_square_landscape = new Config;
        $mount_square_landscape->setInputFilename($landscapeInputFilename)
                               ->setMountEnabled(true)
                               ->setOutputFilename($outputFilename);

        return array(
            "Creates thumbnail with height in proportion when only width given"
                    => array( $fixed_width,  $outputFilename, 200, 100 ),
            "Creates thumbnail with width in proportion when only height given"
                    => array( $fixed_height, $outputFilename, 200, 100 ),
            "Creates thumbnail with generated filename if no one given"
                    => array( $generated_filename, 
                              'thumb-'.md5(serialize($generated_filename)).'.jpg', 
                              100, 300 ),
            "Creates thumbnail constrained to given size"
                    => array( $constrained,  $outputFilename, 200, 100 ),
            "Can mount thumbnail with arbitrary size"
                    => array( $arbitrary,    $outputFilename, 600, 400 ),
            "Can mount thumbnail with only width given (defaults height to thumbnail's)"
                    => array( $mount_fixed_width,  $outputFilename, 600, 200 ),
            "Can mount thumbnail with only height given (defaults width to thumbnail's)"
                    => array( $mount_fixed_height, $outputFilename, 400, 400 ),
            "Mounting thumbnail with no width nor height creates square mount (portrait)" 
                   => array( $mount_square_portrait,  $outputFilename, 400, 400 ),
            "Mounting thumbnail with no width nor height creates square mount (landscape)" 
                   => array( $mount_square_landscape, $outputFilename, 400, 400 ),
        );
    }

    /**
     * @dataProvider thumbnail_configurations
     */
    public function testCreatesAThumbnail($config, $outputFilename, $expected_width, $expected_height) {

        $filename = $this->object->createThumbnail($config);

        $path = 'tests/tmp/' . $filename;

        $this->assertTrue( file_exists($path) );
        $this->assertEquals( $outputFilename, $filename );

        $dimensions = getimagesize($path);

        $this->assertEquals( $expected_width,  $dimensions[0] );
        $this->assertEquals( $expected_height, $dimensions[1] );

        unlink($path);
    }

    public function testDoesntCreateThumbnailsIfOneExistsAndImageHasntChanged() {

        $config = new Config;
        $config->setInputFilename('test_jpeg_400_x_200.jpg')
               ->setWidth(200)
               ->setCachingEnabled(true);

        $filename = $this->object->createThumbnail($config);

        $path = 'tests/tmp/' . $filename;

        $this->assertTrue( file_exists($path) );
        $thumbnail1Time = filemtime($path);

        $filename2 = $this->object->createThumbnail($config);

        $this->assertEquals( $filename, $filename2 );
        $this->assertEquals( $thumbnail1Time, filemtime($path) );

        // Update the image modified time - should result in the creation of a new thumbnail
        touch($path, time() + 3600);
        clearstatcache();
        
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
     * @expectedException BoxUK\Obscura\Exception
     */
    public function testThrowsExceptionIfOutputDirectoryIsNotSet() {

        $tf = new ThumbnailFactory( new Factory );
        $tf->setInputDirectory('tests/resources');

        $config = new Config;
        $config->setInputFilename('test_jpeg_400_x_200.jpg')->setWidth(200);
        $filename = $tf->createThumbnail($config);

    }

    /**
     * @expectedException BoxUK\Obscura\Exception
     */
    public function testThrowsExceptionIfInputImageIsNotSet() {

        $tf = new ThumbnailFactory( new Factory );
        
        $config = new Config;
        $filename = $tf->createThumbnail($config);

    }

}
