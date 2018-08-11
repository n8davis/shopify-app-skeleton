<?php
/**
 * Created by PhpStorm.
 * User: nate
 * Date: 7/19/18
 * Time: 7:00 PM
 */

namespace Skeleton\Shopify;

use Dotenv\Dotenv;
use Skeleton\Basic\Assist;
use Skeleton\Basic\Client;

class Auth
{

    public static $shop;
    public static $access_token;
    public static $secret;
    public static $key;
    public static $state = 'random_string';

    public static function config( $data )
    {
        self::$shop         = $data[ 'ShopUrl' ];
        self::$key          = $data[ 'ApiKey' ];
        self::$secret       = $data[ 'SharedSecret' ];
        self::$access_token = $data[ 'AccessToken' ];
    }

    /**
     * @param array $config
     * @return null
     * @throws \Exception
     */
    public static function getAccessToken( $config = [] )
    {

        if( self::verifyShopifyRequest() ) {
            $env = new Dotenv( dirname( dirname( __DIR__ ) ) . DIRECTORY_SEPARATOR ) ;
            $env->load();
            $data = array(
                'client_id'     => getenv( 'SHOPIFY_KEY' ) ,
                'client_secret' => getenv( 'SHOPIFY_SECRET' ) ,
                'code'          => $_GET['code'],
            );

            $uri         = 'https://' . self::$shop . DIRECTORY_SEPARATOR . 'admin/oauth/access_token';
            $httpConnect = new Client();
            $response    = $httpConnect->request(
                $uri ,
                $data ,
                Client::POST ,
                [ "Content-Type: application/json" ]
            );
            $response = json_decode( $response ) ;
            return property_exists( $response , 'access_token') ? $response->access_token : null;
        } else {
            throw new \Exception("This request is not initiated from a valid shopify shop!");
        }
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public static function verifyShopifyRequest()
    {
        $data = $_GET;

        if( ! isset( self::$secret) ) {
            throw new \Exception( "Please provide SharedSecret while configuring the SDK client." );
        }


        $sharedSecret = self::$secret;

        //Get the hmac and remove it from array
        if (isset($data['hmac'])) {
            $hmac = $data['hmac'];
            unset($data['hmac']);
        } else {
            throw new \Exception("HMAC value not found in url parameters.");
        }
        //signature validation is deprecated
        if (isset($data['signature'])) {
            unset($data['signature']);
        }
        //Create data string for the remaining url parameters
        $dataString = http_build_query($data);

        $realHmac = hash_hmac('sha256', $dataString, $sharedSecret);

        //hash the values before comparing (to prevent time attack)
        if(md5($realHmac) === md5($hmac)) {
            return true;
        } else {
            return false;
        }
    }

    public static function authRequest( $scopes , $redirectUrl )
    {
        $oauthUrl = 'https://' . self::$shop . '/admin/oauth/authorize?client_id=' .
            self::$key . '&scope=' . $scopes . '&redirect_uri=' . $redirectUrl .
            '&state=' . self::$state;
        return $oauthUrl;
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public static function verify()
    {
        if (  is_null( self::$secret  ) && is_null( self::$access_token) ){
            throw new \Exception('Shopify Config not set');
        }
        $data   = $_GET;
        $params = array();

        foreach($data as $param => $value) {
            if ($param != 'signature' && $param != 'hmac' ) {
                $params[$param] = "{$param}={$value}";
            }
        }

        asort($params);
        $params = implode('&', $params);

        $hmac           = $data['hmac'];
        $calculatedHmac = hash_hmac('sha256', $params,  self::$secret );

        return ($hmac == $calculatedHmac);
    }

    public static function verifyWebhook( $data , $hmac_header ){
        $env = new Dotenv( dirname( dirname( __DIR__ ) ) );
        $env->load();
        $calculated_hmac = base64_encode(hash_hmac('sha256', $data, getenv( 'SHOPIFY_SECRET' ), true));
        return ($hmac_header == $calculated_hmac);
    }
}