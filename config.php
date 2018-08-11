<?php
/**
 * Created by PhpStorm.
 * User: nate
 * Date: 8/7/18
 * Time: 7:09 PM
 */
$turnOnErrors = true;
$basePath = dirname( __DIR__ ) . DIRECTORY_SEPARATOR . 'skeleton/' ;

require $basePath . 'vendor/autoload.php';
$config = include( $basePath. 'src/config.php');
$app    = new \Slim\App( [ 'settings' => $config ] );

// Set up dependencies
require $basePath . 'src/dependencies.php';
// Register middleware
require $basePath . 'src/middleware.php';

// Register routes
require $basePath . 'src/routes.php';