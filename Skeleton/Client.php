<?php

namespace Skeleton;
class Client
{
    CONST GET = 'GET';
    CONST POST = 'POST';
    CONST PUT = 'PUT' ;
    CONST PATCH = 'PATCH';
    public $api_url = '';
    public $api_password = '';
    public $api_key = '';
    public $api_shopifyToken = '';
    public $httpCode;

    // LIVE app
    public $clientSecret;
    public $client_id   ;


    public $curlHeader;
    public $fullresults;
    public $response;

    public $timeOfLastShopifyCall = 0;
    public $api_calls = 0;
    public $api_max_calls = 35;
    public $retryCount = 0;



    public static function verifyHmac( $req , $secret , $hmac ) {
        $params = [];

        foreach($req as $param => $value) {
            if ($param != 'signature' && $param != 'hmac') {
                $params[$param] = "{$param}={$value}";
            }
        }

        asort($params);

        $params = implode('&', $params);
        $calculatedHmac = hash_hmac('sha256', $params, $secret );
        return $calculatedHmac === $hmac;
    }

    function setShopifyKeys($appMember)
    {
        $this->api_url          = $appMember['shop'];
        $this->api_shopifyToken = $appMember['shopifyAC'];
    }

    function sleepIfNeeded()
    {


        $currTime       = microtime(true);
        if ($this->timeOfLastShopifyCall == 0){
            $this->timeOfLastShopifyCall = $currTime;
        }
        $timeDifference = $currTime - $this->timeOfLastShopifyCall;

        /*
        echo '<br>api_calls:'.$this->api_calls;
        echo '<br>api_max_calls:'.$this->api_max_calls;
        echo '<br>$timeDifference:'.$timeDifference;
*/

        if ($this->api_calls >= $this->api_max_calls || $this->httpCode == 429) {
            $minWaitTime = 550000;
            if ($timeDifference < $minWaitTime) {
                $timeToSleep = $minWaitTime - $timeDifference;
                echo '<div style="color:orange">Sleeping for '.$timeToSleep.'</div>';
                usleep($timeToSleep);
            }
        }

        $this->timeOfLastShopifyCall = microtime(true);
    }


    /**
     * @param $uri
     * @param $dataToPost
     * @param $curlTYPE
     * @return array|string
     */
    function request($uri, $dataToPost, $curlTYPE,$headers)
    {
        $this->sleepIfNeeded();

        if ( ! $uri || strlen( $uri ) === 0 ) {
            return 'ERROR' ;
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $uri);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers );
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, '0');
        if ($curlTYPE == 'PUT') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        }
        if ($curlTYPE == 'DELETE') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        }
        if ($curlTYPE == 'POST') {
            curl_setopt($ch, CURLOPT_POST, 1);
        }
        if ($curlTYPE == 'GET') {
            curl_setopt($ch, CURLOPT_POST, 0);
        }
        if ($dataToPost) {
            if( ! is_string( $dataToPost ) ) {
                $dataToPost = json_encode($dataToPost);
            }
            curl_setopt($ch, CURLOPT_POSTFIELDS, $dataToPost);
        }
        $results           = curl_exec($ch);
        $this->fullresults = $results;
        $this->httpCode    = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $returnedInfo      = explode("\r\n\r\n", $results);
        $this->curlHeader  = $returnedInfo[0];

        /// Get the headers for the core data
        foreach (explode("\r\n", $this->curlHeader) as $key => $name) {
            //echo '<br>'.$key;
            //echo ' => '.$name;
            $explodedName = explode(":", $name);
            if ($explodedName[0] == 'X-Shopify-Shop-Api-Call-Limit') {
                $callsInfo           = explode('/', $explodedName[1]);
                $this->api_calls     = $callsInfo[0];
                $this->api_max_calls = $callsInfo[1];
            }
        }

        curl_close($ch);
        //print_r($returnedInfo[1]);
        if ($this->httpCode == 429) {
            if ($this->retryCount > 10){
                echo '<div style="color:red;">429 - FAILING:TOO MANY RETRIES</div>';
            } else {
                echo '<div style="color:red;">429 - RETRYING</div>';
                $this->request($uri, $dataToPost, $curlTYPE);
                $this->retryCount++;
            }
        } else {
            $this->retryCount = 0;
            $this->response   = array_key_exists( 1 , $returnedInfo ) && strlen( $returnedInfo[ 1 ] ) > 0 ? $returnedInfo[1] : $returnedInfo;
            return array_key_exists( 1 , $returnedInfo ) ? $returnedInfo[1] : $returnedInfo;
        }

    }




}
