<?php

define( 'VERSION', '1.1.0' );

require_once( 'PEAR/PackageFileManager2.php' );

$packagexml = new PEAR_PackageFileManager2;
$packagexml->setOptions(array(
    'packagedirectory' => 'lib',
    'baseinstalldir' => '/'
));
        
$packagexml->setPackage( 'obscura' );
$packagexml->setSummary( 'Image Library' );
$packagexml->setDescription( 'A modern, license friendly PHP Image / Thumbnail library.' );
$packagexml->setChannel( 'pear.boxuk.com' );
$packagexml->setAPIVersion( VERSION );
$packagexml->setReleaseVersion( VERSION );
$packagexml->setReleaseStability( 'stable' );
$packagexml->setAPIStability( 'stable' );
$packagexml->setNotes( "-" );
$packagexml->setPackageType( 'php' );
$packagexml->setPhpDep( '5.3.0' );
$packagexml->setPearinstallerDep( '1.3.0' );
$packagexml->addMaintainer( 'lead', 'support', 'support', 'support@boxuk.com' );
$packagexml->setLicense( 'MIT License', 'http://opensource.org/licenses/mit-license.php' );
$packagexml->generateContents();
$packagexml->writePackageFile();
