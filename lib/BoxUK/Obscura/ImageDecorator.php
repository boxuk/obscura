<?php

namespace BoxUK\Obscura;

use BoxUK\Obscura\ImageDecorator\Factory;

/**
 * Defines the public interface for an image decorator.
 *
 * @copyright Copyright (c) 2010, Box UK
 * @license http://opensource.org/licenses/mit-license.php MIT License
 * @link http://github.com/boxuk/obscura
 * @since 1.0
 */
interface ImageDecorator {

    /**#@+
     * @var int
     */
    const ORIENTATION_LANDSCAPE = 0;
    const ORIENTATION_PORTRAIT = 1;
    const ORIENTATION_SQUARE = 2;
    /**#@-*/

    /**
     * Creates a new object instance
     *
     * @param BoxUK\Obscura\ImageDecorator\Factory $factory
     */
    public function __construct( Factory $factory = null );

    /**
     * Returns an array of this image's details
     *
     * @return array
     */
    public function toArray();

    /**
     * Loads the external image resource
     *
     * @param string $pathToImage
     */
    public function load( $pathToImage );

    /**
     * Sets the image resource that this object decorates
     *
     * @param GD Resource $resource
     */
    public function setImage( $resource );

    /**
     * Returns the image resource that this object decorates
     *
     * @return GD Resource
     */
    public function getImage();

    /**
     * Returns the image width
     *
     * @return int
     */
    public function getWidth();

    /**
     * Returns the image height
     *
     * @return int
     */
    public function getHeight();

    /**
     * Returns one of the IMAGETYPE_XXX constants.
     *
     * @see http://uk2.php.net/manual/en/image.constants.php
     *
     * @return int
     */
    public function getImageType();

    /**
     * Sets the factory that this object uses to create images
     *
     * @param BoxUK\Obscura\ImageDecorator\Factory $factory
     */
    public function setFactory( Factory $factory );

    /**
     * Returns the factory that this object uses to create images
     *
     * @return BoxUK\Obscura\ImageDecorator\Factory
     */
    public function getFactory();

    /**
     * Returns the mime-type identifier for the decorated image
     *
     * @return string
     */
    public function getMimeType();

    /**
     * Resizes the image to the given dimensions.
     *
     * Either the width or height can be ommitted, in which case if the third parameter is true the image will be
     * resized in such a way as the aspect ratio is maintained.
     *
     * @param int $width
     * @param int $height
     * @param boolean $preserveAspectRatio
     *
     * @return BoxUK\Obscura\ImageDecorator
     */
    public function resize($width=null, $height=null, $preserveAspectRatio=false);

    /**
     * Crops the image from the center to the given dimensions.
     *
     * @param int $width The width to resize to.  If omitted, original width is used.
     * @param int $height The height to resize to.  If omitted, original height is used.
     *
     * @return BoxUK\Obscura\ImageDecorator
     */
    public function crop($width=null, $height=null);

    /**
     * Mounts the image onto a colored background.
     *
     * @param int $width
     * @param int $height
     * @param mixed $color
     *
     * @return BoxUK\Obscura\ImageDecorator
     */
    public function mount($width, $height, $color);

    /**
     * Outputs the image
     *
     * @param string $filename Optional, set this to output the image to a file
     * @param int $imageType Optiona, set this to one of the IMAGETYPE_XXX constants to alter the image encoding. This
     * may have unpredictable results.
     * @param int $quality Optional, a percentage indicating the quality of the image. Defaults to 100%.
     *
     * @return boolean
     *
     * @throws InvalidArgumentException
     */
    public function output( $filename=null, $imageType=null, $quality=100 );

    /**
     * Returns the orientation for the image. Will be one of:
     *
     * BoxUK\Obscura\ImageDecorator::ORIENTATION_LANDSCAPE
     * BoxUK\Obscura\ImageDecorator::ORIENTATION_PORTRAIT
     * BoxUK\Obscura\ImageDecorator::ORIENTATION_SQUARE
     *
     * @return int
     */
    public function getOrientation();

    /**
     * Calculates a proportial length for a dimension to maintain the current aspect ratio.
     *
     * @param int $altDimensionNew
     * @param int $altDimensionCurrent
     * @param int $dimensionCurrent
     *
     * @return int
     */
    public function calculateProportionalSizeForDimension($altDimensionNew, $altDimensionCurrent, $dimensionCurrent);

}
