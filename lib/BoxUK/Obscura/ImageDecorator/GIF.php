<?php

namespace BoxUK\Obscura\ImageDecorator;

use BoxUK\Obscura\ImageDecorator;

/**
 * @copyright Copyright (c) 2010, Box UK
 * @license http://opensource.org/licenses/mit-license.php MIT License
 * @link http://github.com/boxuk/obscura
 * @since 1.0
 */
class GIF extends AbstractDecorator {

    /**
     * @see BoxUK\Obscura\ImageDecorator::getImageType
     * 
     * @return int
     */
    public function getImageType() {

        return \IMAGETYPE_GIF;

    }

    /**
     * @see BoxUK\Obscura\ImageDecorator::getMimeType
     *
     * @return string
     */
    public function getMimeType() {

        return 'image/gif';

    }

    /**
     * @see BoxUK\Obscura\ImageDecorator\Abstract::readImage
     *
     * @param string $pathToImage
     */
    public function readImage( $pathToImage ) {
        
        return imagecreatefromgif($pathToImage);

    }

    /**
     * @see BoxUK\Obscura\ImageDecorator\Abstract::_output
     *
     * @param string $filename Optional, set this to output the image to a file.
     * @param int $quality Has no effect.
     *
     * @return boolean
     */
    protected function _output( $filename=null, $quality=100 ) {

        return imagegif($this->getImage(), $filename);

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

        $canvas = imagecreatetruecolor( $width, $height );
        $white = imagecolorallocate( $canvas, 255, 255, 255 );
        
        imagefill( $canvas, 0, 0, $white );
        imagecolortransparent( $canvas, $white );
        
        return $canvas;

    }

}
