<?php

namespace BoxUK\Obscura;

use BoxUK\Obscura\ImageDecorator;
use BoxUK\Obscura\ImageDecorator\Factory;
use BoxUK\Obscura\ThumbnailFactory\Config;

/**
 * Creates thumbnails for images
 *
 * @copyright Copyright (c) 2010, Box UK
 * @license http://opensource.org/licenses/mit-license.php MIT License
 * @link http://github.com/boxuk/obscura
 * @since 1.0
 */
class ThumbnailFactory {

    /**
     * @var BoxUK\Obscura\ImageDecorator\Factory
     */
    private $factory;

    /**
     * The root location from which images will be loaded.
     *
     * @var string
     */
    private $inputDirectory;

    /**
     * The root location at which thumbnails will be saved
     *
     * @var string
     */
    private $outputDirectory;

    /**
     * Constructs a new instance of this class
     *
     * @param BoxUK\Obscura\ImageDecorator\Factory $factory
     */
    public function __construct( Factory $factory ) {

        $this->factory = $factory;

    }

    /**
     * Returns the path to the directory from which input images will be loaded.
     *
     * @return string
     */
    public function getInputDirectory() {

        return $this->inputDirectory;

    }

    /**
     * Sets the root directory from which images will be read.
     *
     * @param string $directory
     *
     * @throws InvalidArgumentException If the directory does not exist.
     */
    public function setInputDirectory( $directory ) {

        if(! is_dir($directory) ) {
            throw new \InvalidArgumentException( 'The given path is not a directory at "' . $directory . '"' );
        }

        $this->inputDirectory = $directory;

    }

    /**
     * Returns the path to the directory at which thumbnails will be saved.
     *
     * @return string
     */
    public function getOutputDirectory() {

        return $this->outputDirectory;

    }

    /**
     * Sets the root directory at which thumbnails will be output.
     *
     * @param string $directory
     *
     * @throws InvalidArgumentException If the directory does not exist or is not writable.
     */
    public function setOutputDirectory ( $directory ) {

        if(! is_dir($directory) || ! is_writable($directory)) {
            throw new \InvalidArgumentException( 'The destination directory is not writable at "' . $directory . '"' );
        }

        $this->outputDirectory = $directory;

    }

    /**
     * Creates a thumbnail for the given image according to the configuration object supplied.
     *
     * @param BoxUK\Obscura\ThumbnailFactory\Config $config
     *
     * @return string | boolean
     *
     * @throws BoxUK\Obscura\Exception if an output directory has not been set
     */
    public function createThumbnail(Config $config) {

        $pathToInputImage = $this->getPathToInputImage( $config );

        if(! strlen($this->outputDirectory)) {
            throw new Exception( 'The output directory has not been set' );
        }

        $image = $this->factory->loadImageFromFile( $pathToInputImage );

        $outputFilename = $this->getFilenameForThumbnail($config);
        $pathToThumbnail = $this->outputDirectory . DIRECTORY_SEPARATOR . $outputFilename;

        // If a thumbnail already exists for the configuration, do nothing
        if($config->getCachingEnabled() && $this->thumbnailExistsAndIsFresh($pathToInputImage, $pathToThumbnail)) {

            return $outputFilename;

        }

        if ($config->getCrop()) {
            $this->cropThumbnail($image, $config);
        }
        else {
            $this->resizeThumbnail($image, $config);
        }

        $this->mountThumbnail($image, $config);

        $success = $image->output($pathToThumbnail, $config->getImageType(), $config->getImageQuality());

        if(! $success) {
            return false;
        }

        return $outputFilename;

    }

    /**
     * Resizes the input image according to the user's configuration.
     *
     * @param BoxUK\Obscura\ImageDecorator $image
     * @param BoxUK\Obscura\ThumbnailFactory\Config $config
     *
     * @return ImageDecorator
     */
    private function resizeThumbnail(ImageDecorator $image, Config $config) {

        $width = $config->getWidth();
        $height = $config->getHeight();
        $constraint = $config->getSizeConstraint();

        // Nothing to do
        if(! $width && ! $height && ! $constraint) {
            return;
        }

        // If both width and height are set, then resize to those values
        if($width && $height) {
            return $image->resize($width, $height);
        }

        $aspectRatioLock = $config->getAspectRatioLock();

        // The user wants the image with the longest dimension to get this length
        if( $constraint ) {

            switch($image->getOrientation()) {
                case ImageDecorator::ORIENTATION_LANDSCAPE:
                    $width = $constraint;
                    $height = null;
                    $aspectRatioLock = true;
                    break;

                case ImageDecorator::ORIENTATION_PORTRAIT:
                    $height = $constraint;
                    $width = null;
                    $aspectRatioLock = true;
                    break;

                case ImageDecorator::ORIENTATION_SQUARE:
                default:
                    $width = $height = $constraint;

                    break;
            }

        }

        if($aspectRatioLock) {

            if($height) {
                $width = $image->calculateProportionalSizeForDimension($height, $image->getHeight(), $image->getWidth());
            }
            else {
                $height = $image->calculateProportionalSizeForDimension($width, $image->getWidth(), $image->getHeight());
            }

        }

        return $image->resize($width, $height);

    }

    /**
     * Crops the thumbnail according to the user's configuration.
     *
     * @param BoxUK\Obscura\ImageDecorator $image
     * @param BoxUK\Obscura\Config $config
     *
     * @return BoxUK\Obscura\ImageDecorator
     */
    private function cropThumbnail(ImageDecorator $image, Config $config) {
        $width = $config->getWidth();
        $height = $config->getHeight();

        // If both width and height are set, then crop to those values.
        if ($width && $height) {
            return $image->crop($width, $height);
        }
        else if ($width) {
            return $image->crop($width);
        }
        else if ($height) {
            return $image->crop(null, $height);
        }
        return;
    }

    /**
     * Mounts the thumbnail onto a background image according to the user's configuration.
     *
     * @param BoxUK\Obscura\ImageDecorator $image
     * @param BoxUK\Obscura\Config $config
     *
     * @return BoxUK\Obscura\ImageDecorator
     */
    private function mountThumbnail(ImageDecorator $image, Config $config) {

        $mountEnabled = $config->getMountEnabled();

        if(! $mountEnabled) {
            return $image;
        }

        $mountWidth = $config->getMountWidth();
        $mountHeight = $config->getMountHeight();
        $mountColor = $config->getMountColor();

        // If both a width and a height have been specified, use those
        if($mountWidth && $mountHeight) {
            return $image->mount($mountWidth, $mountHeight, $mountColor);
        }

        // Else if neither have been specified, create a square using the length of the longest dimension
        else if(! $mountWidth && ! $mountHeight) {
            if($image->getOrientation() == ImageDecorator::ORIENTATION_LANDSCAPE) {
                $mountWidth = $mountHeight = $image->getWidth();
            }
            else if($image->getOrientation() == ImageDecorator::ORIENTATION_PORTRAIT) {
                $mountWidth = $mountHeight = $image->getHeight();
            }
        }

        // If either the width or height of the mount remain unset, set them to equal their corresponding dimension
        if(! $mountWidth) {
            $mountWidth = $image->getWidth();
        }

        if(! $mountHeight) {
            $mountHeight = $image->getHeight();
        }

        $image->mount($mountWidth, $mountHeight, $mountColor);

    }

    /**
     * Returns the path to the input image
     *
     * @param BoxUK\Obscura\Config $config
     *
     * @return string
     *
     * @throws BoxUK\Obscura\Exception if the input image does not exist or is not readable
     */
    private function getPathToInputImage(Config $config) {

        $inputFilename = $config->getInputFilename();

        if(! strlen($inputFilename)) {
            throw new Exception( 'Expected an input filename' );
        }

        // The user may have specified an explicit path
        if(file_exists($inputFilename) && is_readable($inputFilename)) {
            return $inputFilename;
        }

        // Try to find the image relative to the input directory
        $pathToImage = $this->getInputDirectory() . \DIRECTORY_SEPARATOR . $inputFilename;

        if(file_exists($pathToImage) && is_readable($pathToImage)) {
            return $pathToImage;
        }

        throw new Exception('Input image does not exist.');

    }

    /**
     * Returns true if the thumbnail already exists and is more recent than the original image
     *
     * @param string $pathToImage
     * @param string $pathToThumbnail
     *
     * @return boolean
     */
    private function thumbnailExistsAndIsFresh($pathToImage, $pathToThumbnail) {

        return (file_exists($pathToThumbnail) && filemtime($pathToImage) < filemtime($pathToThumbnail));

    }

    /**
     * Creates a unique filename for a thumbnail based on the input parameters
     *
     * @param BoxUK\Obscura\ThumbnailFactory\Config $config
     *
     * @return string
     */
    private function getFilenameForThumbnail(Config $config) {

        $inputFilename = $config->getInputFilename();
        $outputFilename = $config->getOutputFilename();

        if(strlen($outputFilename)) {
            return $outputFilename;
        }

        $extension = $this->getExtensionForThumbnail($config);

        return sprintf(
            'thumb-%s%s',
            md5(serialize($config)),
            $extension
        );

    }

    /**
     * Returns a file extension appropriate for the image type.
     *
     * @param BoxUK\Obscura\ThumbnailFactory\Config $config
     *
     * @return string
     */
    private function getExtensionForThumbnail(Config $config) {

        $imageType = $config->getImageType();

        // If the image type is changing, use an appropriate extension
        if($imageType) {
            switch ($imageType) {
                case \IMAGETYPE_JPEG:
                    return '.jpg';

                case \IMAGETYPE_GIF:
                    return '.gif';

                case \IMAGETYPE_PNG:
                    return '.png';
            }
        }

        // Default to using the same extension as the input filename
        $inputFilename = $config->getInputFilename();

        if(stristr($inputFilename, '.')) {
            return substr($inputFilename, strrpos($inputFilename, '.'));
        }

        return null;

    }

}