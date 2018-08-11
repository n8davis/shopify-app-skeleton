<?php
/**
 * Created by PhpStorm.
 * User: nate
 * Date: 7/19/18
 * Time: 6:38 PM
 */

namespace Skeleton\Controller;

use \Monolog\Logger;
use Skeleton\Basic\Assist;
use Skeleton\Basic\Client;
use Skeleton\Model\ShopOwner;
use Skeleton\Shopify\Shop;
use Skeleton\Shopify\Shopify;
use Skeleton\Shopify\Auth;
use \Slim\Views\Twig;

class HomeController
{
    protected $view;
    protected $logger;

    public function __construct( Twig $view , Logger $logger ) {
        $this->view = $view;
        $this->logger = $logger ;
    }

    public function index( $request, $response, $args )
    {

        $shopOwner   = new ShopOwner();
        $shop        = \Skeleton\Basic\Assist::getParam( 'shop' );
        $owner       = $shopOwner->where( 'shop' , $shop )->first();

        $data = [
          'title' => 'Home',
          'shop'  => $shop ,
          'key'   => $owner->access_token
        ];

        return $this->view->render($response, 'home/index.php', $data );
    }
}