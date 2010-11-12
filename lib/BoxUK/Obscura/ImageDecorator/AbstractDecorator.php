<?php

namespace BoxUK\Obscura\ImageDecorator;

use BoxUK\Obscura\ColorParser;
use BoxUK\Obscura\ImageDecorator;
use BoxUK\Obscura\Exception;

/**
 * Abstract image decorator that defines basic functionality consisitent across all image types.
 *
 * @copyright Copyright (c) 2010, Box UK
 * @license http://opensource.org/licenses/mit-license.php MIT License
 * @link http://github.com/boxuk/obscura
 * @since 1.0
 */
abstract class AbstractDecorator implements ImageDecorator {

    /**
     * The original image decorated by this object
     *
     * @var GD Resource
     */
    protected $image;

    /**
     * The width of the decorated image
     *
     * @var int
     */
    protected $width;

    /**
     * The height of the decorated image
     *
     * @var int
     */
    protected $height;

    /**
     * Factory to create additional images
     *
     * @var BoxUK\Obscura\ImageDecorator\Factory
     */
    protected $factory;

    /**
     * @param BoxUK\Obscura\ImageDecorator_Factory $factory
     */
    public function __construct( Factory $factory = null ) {

        $this->factory = $factory;

    }

    /**
     * @see BoxUK\Obscura\ImageDecorator::load
     *
     * @param mixed $imageToLoad
     *
     * @return BoxUK\Obscura\ImageDecorator
     *
     * @throws BoxUK\Obscura\Exception if the GD extension is not available.
     */
    public function load( $imageToLoad ) {

        if(! extension_loaded('GD')) {
            throw new Exception('The PHP GD extension was not found. See http://www.php.net/gd for more information.');
        }

        $image = is_resource($imageToLoad) ? $imageToLoad : $this->readImage( $imageToLoad );

        $this->setImage($image);

        $this->width = imagesx( $image );
        $this->height = imagesy( $image );

        return $this;

    }

    /**
     * Inheriting classes should implement this method to read an image resource in a relevant manner
     *
     * @param string $pathToImage
     *
     * @return GD Resource
     */
    abstract protected function readImage($pathToImage);

    /**
     * @see BoxUK\Obscura\ImageDecorator::setImage
     *
     * @param GD Resource
     *
     * @throws InvalidArgumentException if an image resource is not supplied.
     */
    public function setImage( $resource ) {

        if(! is_resource($resource) ) {
            throw new \InvalidArgumentException( 'Expected an image resource' );
        }

        $this->image = $resource;

    }

    /**
     * @see BoxUK\Obscura\ImageDecorator::getImage
     *
     * @return GD Resource
     */
    public function getImage() {

        return $this->image;

    }

    /**
     * @see BoxUK\Obscura\ImageDecorator::getWidth
     *
     * @return int
     */
    public function getWidth() {

        return $this->width;

    }

    /**
     * @see BoxUK\Obscura\ImageDecorator::getHeight
     *
     * @return int
     */
    public function getHeight() {

        return $this->height;

    }

    /**
     * @see BoxUK\Obscura\ImageDecorator::setFactory
     *
     * @param BoxUK\Obscura\ImageDecorator\Factory $factory
     */
    public function setFactory( Factory $factory ) {

        $this->factory = $factory;

    }

    /**
     * @see BoxUK\Obscura\ImageDecorator::getFactory
     *
     * @return BoxUK\Obscura\ImageDecorator\Factory
     */
    public function getFactory() {

        return $this->factory;

    }

    /**
     * Returns a blank image resource for use with the current image.
     *
     * @param int $width
     * @param int $height
     *
     * @return GD Resource
     */
    public function createCanvas( $width, $height ) {

        return imagecreatetruecolor ($width, $height);

    }

    /**
     * @see BoxUK\Obscura\ImageDecorator::resize
     *
     * @param int $width
     * @param int $height
     * @param boolean $preserveAspectRatio
     *
     * @return BoxUK\Obscura\ImageDecorator
     */
    public function resize($width=null, $height=null, $preserveAspectRatio=false) {

        if($width === null) {
            if(! $preserveAspectRatio || intval($height) == 0 || intval($this->getHeight()) == 0) {
                $width = $this->getWidth();
            }
            else {
                $width = $this->calculateProportionalSizeForDimension($height, $this->getHeight(), $this->getWidth());
            }
        }

        if($height === null) {
            if(! $preserveAspectRatio || intval($width) == 0 || intval($this->getWidth()) == 0) {
                $height = $this->getHeight();
            }
            else {
                $height = $this->calculateProportionalSizeForDimension($width, $this->getWidth(), $this->getHeight());
            }
        }

        $image = $this->createCanvas( $width, $height );

        // Use imagecopyresampled because it maintains image clarity
        imagecopyresampled(
            $image,
            $this->getImage(),
            0,
            0,
            0,
            0,
            $width,
            $height,
            $this->getWidth(),
            $this->getHeight()
        );

        return $this->load($image);

    }

    /**
     * @see BoxUK\Obscura\ImageDecorator::mount
     *
     * @param int $width
     * @param int $height
     * @param mixed $color
     *
     * @return BoxUK\Obscura\ImageDecorator
     *
     * @throws InvalidArgumentException if either the supplied width or height exceed their respective dimensions in
     * the original image, or if an invalid color is supplied.
     */
    public function mount( $width, $height, $color ) {

        if($width < $this->width || $height < $this->height) {
            throw new \InvalidArgumentException('Cannot mount image: mount dimensions must exceed image dimensions');
        }

        // Create the mount
        $mount = imagecreatetruecolor($width, $height);

        $colorParser = new ColorParser();

        // Determine the color of the mount, register it, draw it
        $rgba = $colorParser->getRGBAValuesFromColor($color);

        $allocatedColor = imagecolorallocatealpha($mount, $rgba[0], $rgba[1], $rgba[2], $rgba[3]);

        imagefill($mount, 0, 0, $allocatedColor);

        // We want a consistent border, so we determine co-ordinates to place the image based on its dimensions
        $posX = ($width - $this->width) / 2;
        $posY = ($height - $this->height) / 2;

        // Copy the image on to the mount at those co-ordinates
        imagecopy(
            $mount,
            $this->getImage(),
            $posX,
            $posY,
            0,
            0,
            $this->getWidth(),
            $this->getHeight()
        );

        return $this->load($mount);

    }

    /**
     * @see BoxUK\Obscura\ImageDecorator::output
     *
     * @param string $filename Optional, set this to output the image to a file
     * @param int $imageType Optional, set this to one of the IMAGETYPE_XXX constants to alter the image encoding. This
     * may have unpredictable results.
     * @param int $quality Optional, a percentage indicating the quality of the image. Defaults to 100%.
     *
     * @return boolean
     *
     * @throws InvalidArgumentException
     */
    public function output( $filename=null, $imageType=null, $quality=100 ) {

        if(! is_null($imageType) && $imageType != $this->getImageType()) {
            $image = $this->getFactory()->loadImageFromResource($this->getImage(), $imageType);

            return $image->output($filename, $imageType, $quality);
        }

        return $this->_output( $filename, $quality );

    }

    /**
     * Should be implemented by inheriting classes to handle format-specific output options
     *
     * @param string $filename Optional, set this to output the image to a file
     * @param int $quality Optional, a percentage indicating the quality of the image. Defaults to 100%.
     *
     * @return boolean
     */
    abstract protected function _output( $filename=null, $quality=100 );

    /**
     * @see BoxUK\Obscura\ImageDecorator::getOrientation
     *
     * @return int
     */
    public function getOrientation() {

        if($this->getWidth() > $this->getHeight()) {
            return ImageDecorator::ORIENTATION_LANDSCAPE;
        }
        else if($this->getWidth() < $this->getHeight()) {
            return ImageDecorator::ORIENTATION_PORTRAIT;
        }

        return ImageDecorator::ORIENTATION_SQUARE;

    }

    /**
     * @see BoxUK\Obscura\ImageDecorator::toArray
     *
     * @return array
     */
    public function toArray() {

        return array(
            'width' => $this->getWidth(),
            'height' => $this->getHeight(),
            'orientation' => $this->getOrientation()
        );

    }

    /**
     * Calculates a proportial length for a dimension to maintain the current aspect ratio.
     *
     * @param int $altDimensionNew
     * @param int $altDimensionCurrent
     * @param int $dimensionCurrent
     *
     * @return int
     */
    public function calculateProportionalSizeForDimension($altDimensionNew, $altDimensionCurrent, $dimensionCurrent) {

        return ceil(($altDimensionNew / $altDimensionCurrent) * $dimensionCurrent);

    }

}