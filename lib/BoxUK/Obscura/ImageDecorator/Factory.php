<?php

namespace BoxUK\Obscura\ImageDecorator;

use BoxUK\Obscura\ImageDecorator;
use BoxUK\Obscura\Exception;

/**
 * Creates image decorators to wrap images depending on their source/type/encoding.
 *
 * @copyright Copyright (c) 2010, Box UK
 * @license http://opensource.org/licenses/mit-license.php MIT License
 * @link http://github.com/boxuk/obscura
 * @since 1.0
 */
class Factory {

    /**
     * Creates a decorator for a GD resource of the given image type.
     *
     * @see http://uk2.php.net/manual/en/image.constants.php
     *
     * @param GD Resource $resource
     * @param int $imageType One of the IMAGETYPE_XXX constants
     *
     * @return BoxUK\Obscura\ImageDecorator
     */
    public function loadImageFromResource( $resource, $imageType ) {

        $decorator = $this->getDecoratorForImageType( $imageType );

        $decorator->load( $resource );

        return $decorator;

    }

    /**
     * Creates a decorator for the given image, which should be a file on disk
     *
     * @param string $pathToImage
     * 
     * @return BoxUK\Obscura\ImageDecorator
     *
     * @throws InvalidArgumentException if the file does not exist
     * @throws BoxUK\Obscura\Exception if unable to parse image data
     */
    public function loadImageFromFile( $pathToImage ) {

        if(! file_exists($pathToImage)) {
            throw new \InvalidArgumentException( "Image file does not exist at {$pathToImage}" );
        }

        $data = getimagesize( $pathToImage );

        if(! $data) {
            throw new Exception( 'Unable to parse image data' );
        }

        $imageType = $data[2];

        $decorator = $this->getDecoratorForImageType($imageType);

        $decorator->load($pathToImage);

        return $decorator;

    }

    /**
     * Factory method. Returns an image decorator appropriate for the image type.
     *
     * @see http://uk2.php.net/manual/en/image.constants.php
     *
     * @param int $imageType One of the IMAGETYPE_XXX constants
     *
     * @return BoxUK\Obscura\ImageDecorator | false
     *
     * @throws BoxUK\Obscura\Exception if an appropriate decorator does not exist.
     */
    protected function getDecoratorForImageType( $imageType ) {

        switch($imageType) {

            case \IMAGETYPE_JPEG:
                return new JPEG($this);

            case \IMAGETYPE_GIF:
                return new GIF($this);

            case \IMAGETYPE_PNG:
                return new PNG($this);

            default:
                throw new Exception( 'Image type "' . $imageType . '" is not currently supported');

        }
        
    }
    
}