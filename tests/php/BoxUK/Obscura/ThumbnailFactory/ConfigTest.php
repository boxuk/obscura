<?php

namespace BoxUK\Obscura\ThumbnailFactory;

require_once 'tests/php/bootstrap.php';

/**
 * Test class for Config
 */
class ConfigTest extends \PHPUnit_Framework_TestCase {
    
    /**
     * @var Config
     */
    private $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        $this->object = new Config;
    }

    /**
     * 
     */
    public function testCanGetAndSetThumbnailWidth() {

        $validWidths = array(100, 200, null);

        foreach($validWidths as $width) {
            $this->object->setWidth($width);
            $this->assertEquals($width, $this->object->getWidth());
        }

    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testSettingAnInvalidWidthThrowsAnException() {

        $this->object->setWidth('foo');
        
    }

    /**
     * 
     */
    public function testCanGetAndSetThumbnailHeight() {

        $validHeights = array(100, 200, null);

        foreach($validHeights as $height) {
            $this->object->setHeight($height);
            $this->assertEquals($height, $this->object->getHeight());
        }

    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testSettingAnInvalidHeightThrowsAnException() {

        $this->object->setHeight('foo');

    }

    public function testAspectRatioLockIsOnByDefault() {

        $this->assertTrue($this->object->getAspectRatioLock());

    }

    /**
     * 
     */
    public function testCanGetAndSetAspectRatioLock() {

        $this->object->setAspectRatioLock(true);

        $this->assertTrue($this->object->getAspectRatioLock());

        $this->object->setAspectRatioLock(false);

        $this->assertFalse($this->object->getAspectRatioLock());
        
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testSettingAnInvalidAspectRatioLockThrowsAnException() {

        $this->object->setAspectRatioLock('foo');

    }

    /**
     * 
     */
    public function testCanGetAndSetSizeConstraint() {

        $validSizes = array(100, 200, null);

        foreach($validSizes as $size) {
            $this->object->setSizeConstraint($size);
            $this->assertEquals($size, $this->object->getSizeConstraint());
        }

    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testSettingAnInvalidSizeConstraintThrowsAnException() {

        $this->object->setSizeConstraint('foo');

    }

    /**
     *
     */
    public function testMountIsDisabledByDefault() {

        $this->assertFalse($this->object->getMountEnabled());

    }

    /**
     * 
     */
    public function testCanGetAndSetMountEnabled() {

        $this->object->setMountEnabled(true);

        $this->assertTrue($this->object->getMountEnabled());

        $this->object->setMountEnabled(false);

        $this->assertFalse($this->object->getMountEnabled());

    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testSettingAnInvalidMountEnabledValueThrowsAnException() {

        $this->object->setMountEnabled('foo');

    }

    /**
     * 
     */
    public function testCanGetAndSetMountWidth() {

        $this->object->setMountWidth(100);

        $this->assertEquals(100, $this->object->getMountWidth());

        $this->object->setMountWidth(50);

        $this->assertEquals(50, $this->object->getMountWidth());

    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testSettingAnInvalidMountWidthThrowsAnException() {

        $this->object->setMountWidth('foo');

    }

    /**
     *
     */
    public function testCanGetAndSetMountHeight() {

        $this->object->setMountHeight(100);

        $this->assertEquals(100, $this->object->getMountHeight());

        $this->object->setMountHeight(50);

        $this->assertEquals(50, $this->object->getMountHeight());

    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testSettingAnInvalidMountHeightThrowsAnException() {

        $this->object->setMountHeight('foo');

    }

    /**
     * 
     */
    public function testCanGetAndSetMountColor() {

        $validColors = array('#000000', '000000', array(0, 0, 0));

        foreach($validColors as $color) {
            $this->object->setMountColor($color);
            $this->assertEquals($color, $this->object->getMountColor());
        }

    }

    /**
     * 
     */
    public function testSettingAnInvalidMountColorThrowsAnException() {

        $invalidColors = array(false, '', null, array());

        foreach($invalidColors as $color) {
            try {
                $this->object->setMountColor($color);
                $this->fail('Should not be able to set an invalid color');
            }
            catch(\InvalidArgumentException $e) {
                // pass
            }
        }

    }

    /**
     * 
     */
    public function testCanGetAndSetAnInputFilename() {
        $this->object->setInputFilename('foo.jpg');

        $this->assertEquals('foo.jpg', $this->object->getInputFilename());

        $this->object->setInputFilename('bar.jpg');

        $this->assertEquals('bar.jpg', $this->object->getInputFilename());
    }

    /**
     *
     */
    public function testSettingAnIllegalInputFilenameThrowsAnException() {

        $invalidFilenames = array(null, false, '');

        foreach($invalidFilenames as $filename) {
            try {
                $this->object->setInputFilename($filename);
            }
            catch(\InvalidArgumentException $e) {
                // pass
            }
        }

    }

    /**
     *
     */
    public function testCanGetAndSetAnOutputFilename() {
        $this->object->setOutputFilename('foo.jpg');

        $this->assertEquals('foo.jpg', $this->object->getOutputFilename());

        $this->object->setOutputFilename('bar.jpg');

        $this->assertEquals('bar.jpg', $this->object->getOutputFilename());
    }

    /**
     *
     */
    public function testSettingAnIllegalOutputFilenameThrowsAnException() {

        $invalidFilenames = array(null, false, '');

        foreach($invalidFilenames as $filename) {
            try {
                $this->object->setOutputFilename($filename);
            }
            catch(\InvalidArgumentException $e) {
                // pass
            }
        }

    }

    /**
     * 
     */
    public function testCanGetAndSetImageType() {

        $validImageTypes = array(\IMAGETYPE_GIF, \IMAGETYPE_JPEG, \IMAGETYPE_PNG);

        foreach($validImageTypes as $imageType) {
            $this->object->setImageType($imageType);
            $this->assertEquals($imageType, $this->object->getImageType());
        }
        
    }

    /**
     * 
     */
    public function testSettingAnInvalidImageTypeThrowsAnException() {

        $invalidImageTypes = array(false, null, \IMAGETYPE_BMP);

        foreach($invalidImageTypes as $invalidImageType) {
            try {
                $this->object->setImageType($invalidImageType);
                $this->fail('Should not be able to set an invalid image type');
            }
            catch(\InvalidArgumentException $e) {
                // pass
            }
        }

    }

    /**
     * 
     */
    public function testImageQualityDefaultsTo100Percent() {

        $this->assertEquals(100, $this->object->getImageQuality());

    }

    /**
     * 
     */
    public function testCanGetAndSetImageQuality() {

        $validImageQualityValues = array(0, 50, 100);

        foreach($validImageQualityValues as $validImageQualityValue) {
            $this->object->setImageQuality($validImageQualityValue);
            $this->assertEquals($validImageQualityValue, $this->object->getImageQuality());
        }

    }

    /**
     * 
     */
    public function testSettingAnInvalidImageQualityThrowsAnException() {

        $invalidImageQualityValues = array(-1, false, null, 'foo');

        foreach($invalidImageQualityValues as $invalidImageQualityValue) {
            try {
                $this->object->setImageQuality($invalidImageQualityValue);
                $this->fail('Should not be able to set an invalid image quality');
            }
            catch(\InvalidArgumentException $e) {
                // pass
            }
        }

    }

    /**
     *
     */
    public function testCanGetAndSetCachingEnabledFlag() {

        $validCachingFlags = array(true, false);

        foreach($validCachingFlags as $validCachingFlag) {
            $this->object->setCachingEnabled($validCachingFlag);
            $this->assertEquals($validCachingFlag, $this->object->getCachingEnabled());
        }

    }

    /**
     *
     */
    public function testSettingAnInvalidCachingEnabledFlagThrowsAnException() {

        $invalidCachingEnabledFlags = array(-1, null, 'foo');

        foreach($invalidCachingEnabledFlags as $invalidCachingFlag) {
            try {
                $this->object->setImageQuality($invalidCachingFlag);
                $this->fail('Should not be able to set an invalid caching flag');
            }
            catch(\InvalidArgumentException $e) {
                // pass
            }
        }

    }
}