<?php

namespace BoxUK\Obscura\ImageDecorator;

use BoxUK\Obscura\ImageDecorator;

/**
 * Decorates an image encoded with the JPEG algorithm
 *
 * @copyright Copyright (c) 2010, Box UK
 * @license http://opensource.org/licenses/mit-license.php MIT License
 * @link http://github.com/boxuk/obscura
 * @since 1.0
 */
class JPEG extends AbstractDecorator {

    /**
     * @see BoxUK\Obscura\ImageDecorator::getImageType
     * 
     * @return int
     */
    public function getImageType() {

        return \IMAGETYPE_JPEG;

    }

    /**
     * @see BoxUK\Obscura\ImageDecorator::getMimeType
     *
     * @return string
     */
    public function getMimeType() {

        return 'image/jpeg';

    }

    /**
     * @see BoxUK\Obscura\mageDecorator\Abstract::readImage
     *
     * @param string $pathToImage
     */
    public function readImage( $pathToImage ) {
        
        return imagecreatefromjpeg($pathToImage);

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

        return imagejpeg($this->getImage(), $filename, $quality);

    }

}