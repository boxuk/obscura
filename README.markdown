# Obscura

Obscura is an object-oriented wrapper for the PHP GD image library. It makes common tasks like image thumbnailing
easier by providing a simple interface that disguises the subtle differences between image formats.

Requirements:

 * PHP 5.3+
 * GD extension

The following image types are currently supported:

 * GIF
 * JPEG
 * PNG

Library features:

 * Image resizing (thumbnailing)
 * Aspect ratio locking
 * Resizing to a dimension constraint
 * Mounting an image onto a background

## Examples

### Decorating an image

Obscura provides a set of classes that follow the decorator pattern. These classes implement an ImageDecorator interface,
so developers can work with images using a single API regardless of the image type. The recommended way to create an
image decorator is to use an instance of a ImageDecoratorFactory:

<pre>

use BoxUK\Obscura\ImageDecorator\Factory;

$factory = new Factory();
$image = $factory->loadImageFromFile('image.jpeg');

</pre>

The decorator will provide you with useful data about the image:

<pre>

$width = $image->getWidth();
$height = $image->getHeight();
$imageType = $image->getImageType();
$orientation = $image->getOrientation();

</pre>

It's possible to manually manipulate the image through an ImageDecorator, though it's recommended that this is achieved
through a ThumbnailFactory. Nevertheless, here are some examples.

Resizing an image:

<pre>

// Resize to 100 by 200 pixels
$image->resize(100, 200);

// Resize to 100 pixels wide, preserving the aspect ration
$image->resize(100, null, true);

// Resize to 200 pixels high, preserving the aspect ratio
$image->resize(null, 200, true);

</pre>

One can also mount the image onto a background using the mount() method:

<pre>

// Mounting the image onto a red background 200 pixels wide by 200 pixels high:
$image->mount(200, 200, '#FF0000');

</pre>

To output an image, use the output() method:

<pre>

// Outputting an image to a file named 'foo.jpg'
$image->output('foo.jpg');

// Outputting an image straight to the client in the GIF format
$image->output(null, IMAGETYPE_GIF);

// Outputting an image to a file named 'foo.jpg' with 50% quality
$image->output('foo.jpg', null, 50);

</pre>

### Working with a Thumbnail Factory

The ThumbnailFactory class is the best way to manipulate images. It has been designed to allow images to be altered with
the minimum amount of configuration, especially when altering a large batch of images in a consistent manner.

A ThumbnailFactory must be constructed with an instance of an ImageDecorator Factory:

<pre>

use BoxUK\Obscura\ThumbnailFactory;
use BoxUK\Obscura\ImageDecorator\Factory;

// Create a thumbnail factory
$factory = new ThumbnailFactory( new Factory() );

</pre>

The next step is to create a ThumbnailFactory Config object which details how thumbnails are to be created. Here are
some configuration examples - please see the class documentation for a complete list of options. An
InvalidArgumentException will be thrown if the object is supplied with an invalid configuration value.

<pre>

use BoxUK\Obscura\ThumbnailFactory\Config;

$config = new Config();

// Resize foo.jpg to 100 pixel width, maintaining aspect ratio
try {
    $config->setInputFilename('foo.jpg')->setWidth(100)->setAspectRatioLock(true);
}
catch(\InvalidArgumentException $e) {
    // handle error
}

// Resize foo.jpg to 200 pixels high and mount onto a 250x250 black background, convert to GIF and save as 'bar.gif'

$config = new Config();

try {
    $config->setInputFilename('foo.jpg')
    ->setHeight(200)
    ->setMountEnabled(true)
    ->setMountWidth(250)
    ->setMountHeight(250)
    ->setMountColor('#000000')
    ->setImageType(IMAGETYPE_GIF)
    ->setOutputFilename('bar.gif');
}
catch(\InvalidArgumentException $e) {
    // handle error
}

</pre>

A nice feature is the constraining of a thumbnail's longest dimension to a certain size. This is helpful when displaying
a set of images in a grid, since it ensures that a thumbnail never exceeds its bounds:

<pre>

// Ensure that the thumbnail never exceeds 200 pixels wide. A unique filename will be generated automatically.
$config->setInputFilename('foo.jpg')->setSizeConstraint(200);

</pre>

Once the object has been configured to taste, it is passed to the thumbnail factory which parses the options and
creates a thumbnail, returning the filename of the new image. An exception will be thrown if the factory is unable
to create the thumbnail.

<pre>

try {
    $filename = $factory->createThumbnail($config);
}
catch(BoxUK\Obscura\Exception $e) {
    // Handle error
}

</pre>

If so desired, the factory can be told to not create a thumbnail if one already exists for the source image and the
given configuration. This is enabled through a configuration setting:

<pre>

// Generate a thumbnail only if the source image has changed
$config->setInputFilename('foo.jpg')->setWidth(100)->setCachingEnabled(true);

try {
    $filename = $factory->createThumbnail($config);
}
catch(BoxUK\Obscura\Exception $e) {
    // Handle error
}

</pre>

### Unit Testing

Obscura is substantially unit tested. To run the tests, you'll need PHPUnit installed and in your path. Then you can run

<pre>
> phpunit tests/php
</pre>

or, if you have phing installed,

<pre>
> phing test
</pre>
