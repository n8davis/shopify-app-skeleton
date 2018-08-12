<?php

/**
 * @param \Slim\Http\Request $request
 * @param \Slim\Http\Response $response
 * @param $next
 * @return mixed
 */
$checkHmac = function ($request, $response, $next) {
    $params = $request->getQueryParams();
    if( array_key_exists( 'hmac' , $params ) && ! \Skeleton\Shopify\Auth::verify() ){
        exit( 'not allowed' ) ;
    }
    $response = $next( $request , $response );
    return $response;
};

/**
 * @param \Slim\Http\Request $request
 * @param \Slim\Http\Response $response
 * @param $next
 * @return \Slim\Http\Response
 */
$shopExists = function ($request, $response, $next) {

    $currentShop = \Skeleton\Basic\Assist::getParam( 'shop' );
    if( strlen( $currentShop ) === 0 ) exit( 'not allowed' ) ;

    $shop = \Skeleton\Model\ShopOwner::where( 'shop', $currentShop )->first();
    if( is_null( $shop ) ){
        $scopes      = 'read_shipping,write_shipping';
        $redirectUrl =  rawurlencode( \Skeleton\Skeleton::baseUrl(). 'redirect'  );
        $authUrl     = \Skeleton\Shopify\Auth::authRequest( $scopes , $redirectUrl );
        return $response->withRedirect(  $authUrl );
    }

    $response = $next( $request , $response );
    return $response;
};
