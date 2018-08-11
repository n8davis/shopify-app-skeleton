<?php
require 'config.php';

$skeleton = new \Skeleton\Skeleton( Skeleton\Basic\Assist::getParam( 'shop' ) );
$app->run();
