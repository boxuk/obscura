<?php

namespace BoxUK\Obscura\ThumbnailFactory;

/**
 * @copyright Copyright (c) 2010, Box UK
 * @license http://opensource.org/licenses/mit-license.php MIT License
 * @link http://github.com/boxuk/obscura
 * @since 1.0
 */
class Config {

    /**
     * Desired width of the thumbnail.
     *
     * @var int
     */
    private $width;

    /**
     * Desired height of the thumbnail
     * 
     * @var int
     */
    private $height;

    /**
     * Whether to maintain the aspect ratio of the original image. Only takes effect if either a new width or height
     * is specified without a compliment for the opposite dimension. Defaults to true.
     *
     * @var boolean
     */
    private $aspectRatioLock = true;

    /**
     * A maximum size which will be applied to the longest dimension should it exceed this value. Useful for ensuring
     * the overall area of a thumbnail is consistent. Will take precedence over any size configuration for the longest
     * dimension.
     *
     * @var int
     */
    private $sizeConstraint;

    /**
     * Indicates that the thumbnail should be mounted onto a background.
     *
     * @var boolean
     */
    private $mountEnabled = false;

    /**
     * Desired width of the thumbnail mount.
     *
     * @var int
     */
    private $mountWidth;

    /**
     * Desired height of the thumbnail mount.
     *
     * @var int
     */
    private $mountHeight;

    /**
     * A colour for the image mount background.
     *
     * @var mixed
     */
    private $mountColor = array(255, 255, 255);

    /**
     * The input image.
     *
     * @var string
     */
    private $inputFilename;

    /**
     * Desired filename for the thumbnail.
     *
     * @var string
     */
    private $outputFilename;

    /**
     * Desired image type of the thumbnail. Must be one of the IMAGETYPE_XXX constants. Defaults to the same format as
     * the original image.
     *
     * @var int
     */
    private $imageType;

    /**
     * Sets the quality of the thumbnail. Does not apply to all image types. Defaults to 100% (i.e. no loss in quality).
     * 
     * @var int
     */
    private $imageQuality = 100;

    /**
     * Flag that indicates whether a thumbnail will not be generated if source image has not changed since a thumbnail
     * was generated for these config options. Defaults to false.
     *
     * @var boolean
     */
    private $cachingEnabled = false;

    /**
     * Applies to PNGs. Sets the compression of the thumbnail. Defaults to
     */

    /**
     * @return int
     */
    public function getWidth() {
        return $this->width;
    }

    /**
     * Sets the desired width of the thumbnail.
     *
     * @param int $width or null to unset the current value.
     *
     * @return BoxUK\Obscura\ThumbnailFactory\Config
     *
     * @throws InvalidArgumentException if the width is not an integer
     */
    public function setWidth($width) {
        if(! is_int($width) && ! is_null($width)) {
            throw new \InvalidArgumentException('Width must be an integer.');
        }

        $this->width = $width;

        return $this;
    }

    /**
     * @return int
     */
    public function getHeight() {
        return $this->height;
    }

    /**
     * Sets the desired height of the thumbnail.
     *
     * @param int $height or null to unset the current value.
     *
     * @return BoxUK\Obscura\ThumbnailFactory\Config
     *
     * @throws InvalidArgumentException if the height is not an integer
     */
    public function setHeight($height) {
        if(! is_int($height) && ! is_null($height)) {
            throw new \InvalidArgumentException('Height must be an integer.');
        }

        $this->height = $height;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getAspectRatioLock() {
        return $this->aspectRatioLock;
    }

    /**
     * Sets a preference which indicates whether the aspect ratio should be maintained if either a new width or height
     * is specified without a compliment for the opposite dimension.
     *
     * @param boolean $aspectRatioLock
     *
     * @return BoxUK\Obscura\ThumbnailFactory\Config
     *
     * @throws InvalidArgumentException if the preference is not a boolean
     */
    public function setAspectRatioLock($aspectRatioLock) {
        if(! is_bool($aspectRatioLock)) {
            throw new \InvalidArgumentException('Aspect ratio lock must be a boolean.');
        }

        $this->aspectRatioLock = $aspectRatioLock;

        return $this;
    }

    /**
     * @return int
     */
    public function getSizeConstraint() {
        return $this->sizeConstraint;
    }

    /**
     * Sets a size which will be applied to the longest dimension. Useful for ensuring the overall area of the
     * thumbnail is consistent. Will take precedence over any existing size configuration for the longest dimension.
     *
     * @param int $constraint
     *
     * @return BoxUK\Obscura\ThumbnailFactory\Config
     *
     * @throws InvalidArgumentException if the size is not a integer
     */
    public function setSizeConstraint($constraint) {
        if(! is_int($constraint) && ! is_null($constraint)) {
            throw new \InvalidArgumentException('Constraint size must be an integer or null.');
        }

        $this->sizeConstraint = $constraint;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getMountEnabled() {
        return $this->mountEnabled;
    }

    /**
     * Enables a mount for the thumbnail.
     *
     * @param boolean $mount Set to true to mount the thumbnail on a background
     *
     * @return BoxUK\Obscura\ThumbnailFactory\Config
     *
     * @throws InvalidArgumentException
     */
    public function setMountEnabled($mount) {
        if(! is_bool($mount)) {
            throw new \InvalidArgumentException('Parameter must be an boolean.');
        }

        $this->mountEnabled = $mount;

        return $this;
    }

    /**
     * @return integer
     */
    public function getMountWidth() {
        return $this->mountWidth;
    }

    /**
     * Sets the width of the mount for the thumbnail.
     *
     * @param int $width or null to unset the current value
     *
     * @return BoxUK\Obscura\ThumbnailFactory\Config
     *
     * @throws InvalidArgumentException
     */
    public function setMountWidth($width) {

        if(! is_null($width) && ! is_int($width)) {
            throw new \InvalidArgumentException('Mount width must be an integer or null.');
        }
        
        $this->mountWidth = $width;

        return $this;
    }

    /**
     * @return integer
     */
    public function getMountHeight() {
        return $this->mountHeight;
    }

    /**
     * Sets the height of the mount for the thumbnail.
     *
     * @param int $height or null to unset the current value
     *
     * @return BoxUK\Obscura\ThumbnailFactory\Config
     *
     * @throws InvalidArgumentException
     */
    public function setMountHeight($height) {
        if(! is_null($height) && ! is_int($height)) {
            throw new \InvalidArgumentException('Mount height must be an integer or null.');
        }

        $this->mountHeight = $height;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getMountColor() {
        return $this->mountColor;
    }

    /**
     * Sets a color to be used as the background of the thumbnail mount.
     *
     * @param mixed color an array of RGB coordinates or a hexidecimal color string
     *
     * @return BoxUK\Obscura\ThumbnailFactory\Config
     *
     * @throws InvalidArgumentException if an invalid color is submitted
     */
    public function setMountColor($color) {
        if((! is_array($color) && ! is_string($color)) || empty($color)) {
            throw new \InvalidArgumentException('Mount color must be an non-empty array or string');
        }
        
        $this->mountColor = $color;

        return $this;
    }

    /**
     * @return string
     */
    public function getInputFilename() {
        return $this->inputFilename;
    }

    /**
     * Sets the output filename of the thumbnail
     *
     * @param string $filename
     *
     * @return $this
     */
    public function setInputFilename($filename) {
        if(! is_string($filename) || empty($filename)) {
            throw new \InvalidArgumentException('Filename must be a non-empty string.');
        }

        $this->inputFilename = $filename;

        return $this;
    }

    /**
     * @return string
     */
    public function getOutputFilename() {
        return $this->outputFilename;
    }

    /**
     * Sets the output filename of the thumbnail.
     *
     * @param string $filename or null to unset the current value.
     *
     * @return $this
     */
    public function setOutputFilename($filename) {
        if(! is_null($filename) && ! is_string($filename) && (is_string($filename) && empty($filename))) {
            throw new \InvalidArgumentException('Filename must be a non-empty string or null.');
        }

        $this->outputFilename = $filename;

        return $this;
    }

    /**
     * @return int
     */
    public function getImageType() {
        return $this->imageType;
    }

    /**
     * Sets the image format of the output thumbnail. This needs only be set if you wish a different format to that
     * of the original image.
     *
     * @see http://uk2.php.net/manual/en/image.constants.php
     *
     * @param int $imageType
     *
     * @return BoxUK\Obscura\ThumbnailFactory\Config
     *
     * @throws InvalidArgumentException if the type is not a valid GD image type
     */
    public function setImageType($imageType) {

        $validImageTypes = array(\IMAGETYPE_GIF, \IMAGETYPE_JPEG, \IMAGETYPE_PNG);

        if(! is_int($imageType) || ! in_array($imageType, $validImageTypes)) {
            throw new \InvalidArgumentException('Image type must be a valid, supported, GD image type.');
        }

        $this->imageType = $imageType;

        return $this;
    }

    /**
     * @return int
     */
    public function getImageQuality() {
        return $this->imageQuality;
    }

    /**
     * Sets the image quality of the thumbnail. Does not apply to all image types. Defaults to 100% (i.e. no loss in
     * quality). Should be expressed as a percentage.
     * 
     * @param int $imageQuality
     *
     * @return BoxUK\Obscura\ThumbnailFactory\Config
     *
     * @throws InvalidArgumentException if the type is not an integer or a percentage
     */
    public function setImageQuality($imageQuality) {
        if((! is_int($imageQuality)) || $imageQuality < 0 || $imageQuality > 100) {
            throw new \InvalidArgumentException('Image quality must be expressed as a percentage integer.');
        }

        $this->imageQuality = $imageQuality;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getCachingEnabled() {
        return $this->cachingEnabled;
    }

    /**
     * Sets a flag that determines whether thumbnail caching should be applied.
     *
     * @param int $cachingEnabled
     *
     * @return BoxUK\Obscura\ThumbnailFactory\Config
     *
     * @throws InvalidArgumentException if the argument type is not a boolean
     */
    public function setCachingEnabled($cachingEnabled) {
        if((! is_bool($cachingEnabled))) {
            throw new \InvalidArgumentException('Caching flag must be expressed as a boolean.');
        }

        $this->cachingEnabled = $cachingEnabled;

        return $this;
    }

}