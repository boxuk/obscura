<?php

/**
 * Include this file to bootstrap the library. Registers an SPL autoloader to automatically detect and load library
 * class files at runtime.
 *
 * @copyright Copyright (c) 2010, Box UK
 * @license http://opensource.org/licenses/mit-license.php MIT License
 * @link http://github.com/boxuk/obscura
 * @since 1.0
 */

function boxuk_autoload( $rootDir ) {
    spl_autoload_register(function( $className ) use ( $rootDir ) {
        $file = sprintf(
            '%s/%s.php',
            $rootDir,
            str_replace( '\\', '/', $className )
        );
        if ( file_exists($file) ) {
            require $file;
        }
    });
}

boxuk_autoload( __DIR__ );