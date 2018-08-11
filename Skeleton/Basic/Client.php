<?php
/**
 * Created by PhpStorm.
 * User: nate
 * Date: 8/4/18
 * Time: 10:57 AM
 */

namespace Skeleton\Basic;


class Client
{

    protected $link_header ;
    public $http_code;
    public $response ;

    CONST GET   = 'GET';
    CONST POST  = 'POST';
    CONST PUT   = 'PUT' ;
    CONST PATCH = 'PATCH';

    function parseHeaders($response)
    {
        $headers = array();

        $header_text = substr($response, 0, strpos($response, "\r\n\r\n"));

        foreach (explode("\r\n", $header_text) as $i => $line)
            if ($i === 0)
                $headers['http_code'] = $line;
            else
            {
                list ($key, $value) = explode(': ', $line);

                $headers[$key] = $value;
            }
        if( array_key_exists( 'Link' , $headers ) ) {
            $linkHeader = $headers[ 'Link' ];
            $this->setLinkHeader( $linkHeader );
        }
        return $headers;
    }

    public function request($uri, $dataToPost, $curlTYPE, $headers)
    {
        $session = curl_init();
        $data = is_array( $dataToPost ) ?  json_encode($dataToPost) : $dataToPost;
        if( strlen( $data ) > 0 ) $headers[] = 'Content-Length: ' . strlen( $data ) ;
        curl_setopt($session, CURLOPT_URL, $uri);
        curl_setopt($session, CURLOPT_SSL_VERIFYHOST, 2);

        curl_setopt($session, CURLOPT_TIMEOUT, 20);

        curl_setopt($session, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt( $session, CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt($session, CURLOPT_RETURNTRANSFER, true);

        curl_setopt( $session, CURLOPT_HTTPAUTH, CURLAUTH_NONE );

        curl_setopt($session, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($session, CURLOPT_VERBOSE, 1);
        curl_setopt($session, CURLOPT_HEADER, 0);
        curl_setopt($session, CURLOPT_FOLLOWLOCATION, '0');
        curl_setopt( $session, CURLINFO_HEADER_OUT, true );

        switch ( strtoupper( $curlTYPE ) ){
            case 'PUT' :
                curl_setopt($session, CURLOPT_CUSTOMREQUEST, "PUT");
                break;
            case 'DELETE':
                curl_setopt($session, CURLOPT_CUSTOMREQUEST, "DELETE");
                break;
            case 'POST':
                curl_setopt($session, CURLOPT_POST, 1);
                curl_setopt($session, CURLOPT_POSTFIELDS, $data );
                break;
            case 'GET':
                curl_setopt($session, CURLOPT_POST, 0);
                break;
        }

        $results = curl_exec($session);
        $this->http_code = curl_getinfo($session, CURLINFO_HTTP_CODE);
        $info = curl_getinfo($session);
        $header_size = curl_getinfo($session, CURLINFO_HEADER_SIZE);
        $header      = substr($results, 0, $header_size);
        curl_close( $session );
        $this->response = $results;
        return $results;
    }

    /**
     * @return mixed
     */
    public function getLinkHeader()
    {
        return $this->link_header;
    }

    /**
     * @param mixed $link_header
     * @return Client
     */
    public function setLinkHeader($link_header)
    {
        $this->link_header = $link_header;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getHttpCode()
    {
        return $this->http_code;
    }

    /**
     * @param mixed $http_code
     * @return Client
     */
    public function setHttpCode($http_code)
    {
        $this->http_code = $http_code;
        return $this;
    }


}