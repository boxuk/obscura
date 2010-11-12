<?php

/**
 * Sandbox for testing Obscura features
 *
 * @copyright Copyright (c) 2010, Box UK
 * @license http://opensource.org/licenses/mit-license.php MIT License
 * @link http://github.com/boxuk/obscura
 * @since 1.0
 */

require_once __DIR__ . '/../../lib/bootstrap.php';

use BoxUK\Obscura\ThumbnailFactory;
use BoxUK\Obscura\ThumbnailFactory\Config;
use BoxUK\Obscura\ImageDecorator\Factory;

$factory = new ThumbnailFactory(new Factory());

$config = new Config();

$config->setInputFilename('foo.jpg')
    ->setHeight(200)
    ->setMountEnabled(true)
    ->setMountWidth(250)
    ->setMountHeight(250)
    ->setMountColor('#000000')
    ->setImageType(IMAGETYPE_GIF)
    ->setOutputFilename('bar.gif');

$filename = $factory->createThumbnail($config);

