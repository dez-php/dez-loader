<?php

error_reporting( E_ALL );
ini_set( 'display_errors', 'On' );

include_once '../vendor/autoload.php';

use Dez\Loader\Loader as AutoLoader;

$loader     = new AutoLoader();

$loader->register();

$loader
    ->registerClasses( [
        'File3Test' => 'App/Component/Test/File3.php',
    ] )
    ->registerDirectories( [
        'App/Component/Test',
    ] )
    ->registerPrefixes( [
        'App_Component_Test' => __DIR__ . '/App/Component/Test',
    ] )
    ->registerNamespaces( [
        'App\\Component'  => __DIR__ . '/App/Component',
    ] );

$file1  = new \App\Component\Test\SubTest\File();

$file2  = new App_Component_Test_SubTest_File2;

die(var_dump( $file1, $file2, new File3Test, new SubTest_File3, new \SubTest\File4() ));

