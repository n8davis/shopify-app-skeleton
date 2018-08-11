<?php
/*
    DIC configuration
    Slim has a default DiC which is Pimple.
    It comes with all installations and is enabled by default.
    A DiC hold the configuration for every class your application has.
    The DiC's power comes from the ability to only initialize classes that get called [lazy loading].
    That means if your application only executes 1 controller,
    then only that controller and its dependencies get created.
    Proper utilization of the DiC makes Slim applications super fast and responsive.
 */

$container = $app->getContainer();

$capsule   = new \Illuminate\Database\Capsule\Manager;
$capsule->addConnection($container['settings']['db']);
$capsule->setAsGlobal();
$capsule->bootEloquent();

$capsule->getContainer()->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    \Skeleton\Exception::class
);

// Register Twig View helper
// Instantiate and add Slim specific extension
$container['view'] = function ($c) {
     $view = new \Slim\Views\Twig(dirname(__DIR__). DIRECTORY_SEPARATOR . 'views/');
     $view->addExtension(new \Slim\Views\TwigExtension( $c['router'], Skeleton\Skeleton::baseUrl() . DIRECTORY_SEPARATOR . 'views/' ) );
    return $view;
};
// monolog
$container['logger'] = function ($c) {
    $name         = \Skeleton\Skeleton::nameOfApp();
    $logger       = new \Monolog\Logger( $name ) ;
    $file_handler = new \Monolog\Handler\StreamHandler(dirname( __DIR__ ) . "/logs/" . $name . '_' . date('Y-m-d' ) . '.log' );
    $logger->pushHandler($file_handler);

    return $logger;
};

$container['Skeleton\Controller\HomeController'] = function($c) {
    return new Skeleton\Controller\HomeController( $c->get("view") , $c->get( 'logger' ) );
};
$container['Skeleton\Controller\ShopifyController'] = function($c) {
    return new Skeleton\Controller\ShopifyController( $c->get("view") , $c->get( 'logger' ) );
};

