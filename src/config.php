<?php
$dotenv = new \Dotenv\Dotenv( dirname( __DIR__ ) );
$dotenv->load();
return [
    'httpVersion'                       => '2.0',
    'determineRouteBeforeAppMiddleware' => true,
    'outputBuffering'                   => false,
    'displayErrorDetails'               => true,
    'db'                                => [  
        'driver'    => getenv( 'DB_DRIVER'),
        'host'      => getenv( 'DB_HOST'),
        'port'      => getenv( 'DB_PORT'),
        'database'  => getenv( 'DATABASE' ),
        'username'  => getenv( 'DB_USER' ),
        'password'  => getenv( 'DB_PASS' ),
        'charset'   => 'utf8',
        'collation' => 'utf8_unicode_ci',
    ]
];