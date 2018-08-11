<?php
/**
 * Created by PhpStorm.
 * User: nate
 * Date: 7/19/18
 * Time: 9:14 PM
 */

namespace Skeleton\Controller;

use \Monolog\Logger;
use Skeleton\Basic\Assist;
use Skeleton\Model\ShopOwner;
use Skeleton\Security;
use Skeleton\Shopify\Auth;
use Skeleton\Shopify\Shop;
use Skeleton\Shopify\Webhooks;
use Skeleton\Skeleton;
use \Slim\Views\Twig;

class ShopifyController
{
    protected $view;
    protected $logger;

    public function __construct( Twig $view , Logger $logger ) {
        $this->view = $view;
        $this->logger = $logger ;
    }
    public function getHeaders()
    {
        $shop        = null;
        $topic       = null;
        $hmac_header = null;

        foreach (getallheaders() as $name => $value) {

            if ( strtoupper( $name ) === 'X-SHOPIFY-SHOP-DOMAIN') {
                $shop   =  trim($value);
            }
            if ( strtoupper( $name ) === 'X-SHOPIFY-TOPIC') {
                $topic = trim($value);
            }
            if ( strtoupper( $name ) === 'X-SHOPIFY-HMAC-SHA256') {
                $hmac_header = trim($value);
            }
        }

        return [
            'shop'  => $shop,
            'topic' => $topic ,
            'hmac'  => $hmac_header
        ];
    }

    public function install($request, $response, $args)
    {
        $currentShop = Assist::getParam( 'shop');
        $shipFree    = new Skeleton( $currentShop );
        $shop_key    = Security::generateRandomKey( 75 );

        Auth::config( $shipFree->getConfig() );

        try{$accessToken  = Auth::getAccessToken();}
        catch ( \Exception $exception ){
            $accessToken = null;
            $this->logger->addInfo( $exception->getMessage() ) ;
        }

        if( is_null( $accessToken ) ) exit( 'not allowed' ) ;
        $shopifyStore = new Shop();
        $shopifyStore->setShop( $currentShop )
            ->setAccessToken( $accessToken ) ;

        $storeInfo = $shopifyStore->find();

        ShopOwner::firstOrCreate([
            "shop"             => $currentShop,
            "access_token"     => $accessToken ,
            'key'              => $shop_key ,
            'timezone'         => $storeInfo->getIanaTimezone(),
            'email'            => $storeInfo->getEmail()
        ]);

        // create webhooks
        foreach ( $shipFree->webhookTopics() as $topic ) {
            $webhook = new Webhooks();
            $webhook->setShop( $currentShop )->setAccessToken( $accessToken );
            $webhook->setTopic( $topic )
                ->setAddress( Skeleton::baseUrl() . 'webhooks' )
                ->setFormat( 'json' )
                ->insert();
        }

        $redirectTo = 'https://' . $currentShop . '/admin/apps/ship-free';

        return $response->withStatus(302)->withHeader('Location' , $redirectTo );

    }


    public function webhooks($request, $response, $args)
    {
        $data        = file_get_contents('php://input');
        $shopifyData = json_decode( trim( $data ) );
        $header      = $this->getHeaders();
        $hmac        = $header[ 'hmac' ];
        $shop        = $header[ 'shop' ];
        $topic       = $header[ 'topic' ];

        $this->logger->addInfo( $data ) ;
        $this->logger->addInfo( json_encode( $header ) ) ;

        // TODO auth webhook
        if( Auth::verifyWebhook( trim( $data ) , $hmac ) == false ) return $response->withStatus( 200 )->withHeader('Content-Type', 'application/json') ;

        switch ( $topic ){
            case 'app/uninstalled':
                $shopOwner = ShopOwner::where( 'shop' , $shop )->first();

                // remove member
                if( $shopOwner != null ) {
                    $memberToDelete = ShopOwner::find($shopOwner->id);
                    $memberToDelete->delete();
                }
                break;
        }
        return $response->withStatus( 200 )->withHeader('Content-Type', 'application/json');
    }
}