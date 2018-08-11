<?php
/**
 * Created by PhpStorm.
 * User: nate
 * Date: 7/19/18
 * Time: 6:36 PM
 */

namespace Skeleton;
use Dotenv\Dotenv;
use Skeleton\Shopify\Auth;

class Skeleton
{
    protected $shop;
    protected $appApiKey;
    protected $appSharedSecret;
    protected $config;
    protected $shopifyToken;

    function __construct( $shop )
    {
        $dotenv = new Dotenv( dirname( __DIR__ ) );
        $dotenv->load();

        $this->setShop( $shop );
        $this->setAppApiKey( getenv('SHOPIFY_KEY') );
        $this->setAppSharedSecret( getenv('SHOPIFY_SECRET') );
        $this->setConfig([
            'ShopUrl'      => $this->getShop(),
            'ApiKey'       => $this->getAppApiKey(),
            'SharedSecret' => $this->getAppSharedSecret(),
            'AccessToken'  => $this->getShopifyToken(),
        ]);
        Auth::config($this->getConfig());
    }

    public function webhookTopics()
    {
        return [ 'app/uninstalled' ];
    }

    public static function baseUrl()
    {
        return getenv( 'BASE_URL' ) ;
    }

    public static function nameOfApp()
    {
        return getenv( 'NAME_OF_APP' ) ;
    }

    /**
     * @return mixed
     */
    public function getShop()
    {
        return $this->shop;
    }

    /**
     * @param mixed $shop
     * @return Skeleton
     */
    public function setShop($shop)
    {
        $this->shop = $shop;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAppApiKey()
    {
        return $this->appApiKey;
    }

    /**
     * @param mixed $appApiKey
     * @return Skeleton
     */
    public function setAppApiKey($appApiKey)
    {
        $this->appApiKey = $appApiKey;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAppSharedSecret()
    {
        return $this->appSharedSecret;
    }

    /**
     * @param mixed $appSharedSecret
     * @return Skeleton
     */
    public function setAppSharedSecret($appSharedSecret)
    {
        $this->appSharedSecret = $appSharedSecret;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param mixed $config
     * @return Skeleton
     */
    public function setConfig($config)
    {
        $this->config = $config;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getShopifyToken()
    {
        return $this->shopifyToken;
    }

    /**
     * @param mixed $shopifyToken
     * @return Skeleton
     */
    public function setShopifyToken($shopifyToken)
    {
        $this->shopifyToken = $shopifyToken;
        return $this;
    }


}