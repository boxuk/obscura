<?php

namespace BoxUK\Obscura\ImageDecorator;

use BoxUK\Obscura\ImageDecorator;

/**
 * Decorates an image encoded with the PNG algorithm
 *
 * @copyright Copyright (c) 2010, Box UK
 * @license http://opensource.org/licenses/mit-license.php MIT License
 * @link http://github.com/boxuk/obscura
 * @since 1.0
 */
class PNG extends AbstractDecorator {
    
    /**
     * @see BoxUK\Obscura\ImageDecorator::getImageType
     * 
     * @return int
     */
    public function getImageType() {

        return \IMAGETYPE_PNG;

    }

    /**
     * @see BoxUK\Obscura\ImageDecorator::getMimeType
     *
     * @return string
     */
    public function getMimeType() {

        return 'image/png';

    }

    /**
     * @see BoxUK\Obscura\mageDecorator\Abstract::readImage
     *
     * @param string $pathToImage
     *
     * @return GD resource
     */
    public function readImage( $pathToImage ) {
        
        return imagecreatefrompng($pathToImage);

    }

    /**
     * @see BoxUK\Obscura\ImageDecorator\Abstract::_output
     *
     * @param string $filename Optional, set this to output the image to a file
     * @param int $quality Optional, a percentage indicating the quality of the image. Defaults to 100%.
     *
     * @return boolean
     */
    protected function _output( $filename=null, $quality=100 ) {

        // Convert the percentage quality into a value appropriate for the imagepng function
        // Taken from http://uk.php.net/manual/en/function.imagepng.php#97032
        $quality = ($quality - 100) / 11.111111;
        $quality = round(abs($quality));

        return imagepng($this->getImage(), $filename, $quality);

    }

    /**
     * @see BoxUK\Obscura\ImageDecorator\Abstract::createCanvas
     *
     * @param int $width
     * @param int $height
     *
     * @return GD Resource
     */
    public function createCanvas( $width, $height ) {

        $canvas = imagecreatetruecolor ($width, $height);

        // preserve transparency
        $image = $this->getImage();
        $transparentIndex = imagecolortransparent( $image );

        // Maintain transparency if it exists - otherwise default to a transparent background
        if ( $transparentIndex > -1 ) {

            $transparentColor = imagecolorsforindex( $image, $transparentIndex );

            imagecolortransparent( $canvas,
                imagecolorallocate( $canvas, $transparentColor['red'], $transparentColor['green'], $transparentColor['blue'] )
            );

        }
        else {

            imagealphablending($canvas, false);

            $color = imagecolorallocatealpha($canvas, 0, 0, 0, 127);

            imagefill($canvas, 0, 0, $color);

            imagesavealpha($canvas, true);
            
        }

        return $canvas;

    }

}