<?php
/**
 * Package file for creating PEAR packages. This file defines how the PEAR
 * package should be constructed.
 *
 * Before a new tag is made, VERSION should be incremented to the new tag identifier.
 *
 * @author    Box UK <opensource@boxuk.com>
 * @copyright Copyright (c) 2011, Box UK
 * @license   http://opensource.org/licenses/mit-license.php MIT License and http://www.gnu.org/licenses/gpl.ht$
 * @link      http://github.com/boxuk/describr
 * @since     1.0.0
 */

define( 'VERSION', '1.1.2' );
define( 'BOXUK_PEAR_CHANNEL', 'pear.boxuk.net' );

require_once( 'PEAR/PackageFileManager2.php' );
require_once( 'PEAR/PackageFileManager/File.php' );

$aFilesToIgnore = array();
$aFilesToIgnore[] = 'bootstrap.php';

$packagexml = new PEAR_PackageFileManager2;
$packagexml->addPackageDepWithChannel('package', 'Autoload', BOXUK_PEAR_CHANNEL, '1.0.0');

$packagexml->setOptions(array(
    'packagedirectory' => 'lib',
    'baseinstalldir' => '/',
    'ignore' => $aFilesToIgnore
));
        
$packagexml->setPackage( 'obscura' );
$packagexml->setSummary( 'Image Library' );
$packagexml->setDescription( 'A modern, license friendly PHP Image / Thumbnail library.' );
$packagexml->setChannel( BOXUK_PEAR_CHANNEL );
$packagexml->setAPIVersion( VERSION );
$packagexml->setReleaseVersion( VERSION );
$packagexml->setReleaseStability( 'stable' );
$packagexml->setAPIStability( 'stable' );
$packagexml->setNotes( "-" );
$packagexml->setPackageType( 'php' );
$packagexml->setPhpDep( '5.3.0' );
$packagexml->setPearinstallerDep( '1.3.0' );
$packagexml->addMaintainer( 'lead', 'boxuk', 'boxuk', 'opensource@boxuk.com' );
$packagexml->setLicense( 'MIT License', 'http://opensource.org/licenses/mit-license.php' );
$packagexml->generateContents();
$packagexml->writePackageFile();
