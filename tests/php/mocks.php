<?php

use BoxUK\Obscura\ImageDecorator;
use BoxUK\Obscura\ImageDecorator\AbstractDecorator;
use BoxUK\Obscura\ImageDecorator\Factory;

class Obscura_ImageDecorator_Mock extends AbstractDecorator {

    public function __construct( Factory $factory = null, $pathToImage = null ) {

        parent::__construct( $factory );

        if(! $pathToImage) {
            $pathToImage = __DIR__ . '/../resources/test_jpeg_400_x_200.jpg';
        }

        $this->load($pathToImage);

    }

    /**
     * @see Obscura_ImageDecorator::getMimeType
     *
     * @return string
     */
    public function getMimeType() {

        return 'image/jpeg';

    }

    public function getImageType() {

        return IMAGETYPE_JPEG;

    }

    protected function readImage( $pathToImage ) {

        return imagecreatefromjpeg( $pathToImage );

    }

    protected function _output( $filename=null, $quality=null ) {

        return true;
        
    }

}