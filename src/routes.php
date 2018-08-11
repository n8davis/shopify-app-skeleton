<?php

$app->get('/', \Skeleton\Controller\HomeController::class . ':index')
    ->setName( 'home' )
    ->add($checkHmac)
    ->add($shopExists);


$app->get('/redirect', \Skeleton\Controller\ShopifyController::class . ':install');
$app->post('/webhooks', \Skeleton\Controller\ShopifyController::class . ':webhooks');

