<?php

require_once __DIR__ . '/../../lib/bootstrap.php';

define( 'DIR_DATA', __DIR__ . '/data/' );
define( 'DIR_TEMP', __DIR__ . '/tmp/' );

use BoxUK\Obscura\ThumbnailFactory;
use BoxUK\Obscura\ImageDecorator\Factory;
use BoxUK\Obscura\ThumbnailFactory\Config;

/**
 * Resizes a GIF with transparency.
 *
 */
function testTransparentGif( ThumbnailFactory $factory ) {
    
    $config = new Config();
    $config->setCachingEnabled( false )
            ->setInputFilename( $_GET['file'] . '.gif' )
            ->setAspectRatioLock( true )
            ->setWidth( 100 );
    
    $filename = $factory->createThumbnail( $config );
    
    header( 'Content-type: image/gif' );
    die(file_get_contents( DIR_TEMP .  $filename ));

}

/**
 * Tries to run the specified test method
 * 
 * @throws Exception
 */
function runTest() {

    $factory = new ThumbnailFactory( new Factory() );
    $factory->setInputDirectory( DIR_DATA );
    $factory->setOutputDirectory( DIR_TEMP );

    $function = sprintf( 'test%s', ucfirst($_GET['test']) );

    if ( function_exists($function) ) {
        call_user_func_array( $function, array($factory) );
        return true;
    }
    
    throw new Exception( 'Invalid (or no) test specified' );

}

runTest();
