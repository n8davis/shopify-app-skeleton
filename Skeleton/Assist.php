<?php
/**
 * Created by PhpStorm.
 * User: nate
 * Date: 7/19/18
 * Time: 6:57 PM
 */

namespace Skeleton;

define( 'ISCLI', PHP_SAPI === 'cli' );

class Assist
{

    CONST GET_REQUEST    = 'GET';
    CONST POST_REQUEST   = 'POST';
    CONST PUT_REQUEST    = 'PUT' ;
    CONST DELETE_REQUEST = 'DELETE';


    public static function getParam( $param = '' ){
        if( array_key_exists( $param, $_REQUEST ) ){
            return $_REQUEST[ $param ];
        }
        return '';
    }

    public static function convertToArray( $object , $toJson = FALSE  ) {
        $send = [];
        if( is_object( $object ) ) {
            $newArray  = ( array ) $object ;
            foreach ($newArray  as $property => $values ) {
                if( ! is_null( $values ) ) :
                    $property =  preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F-\x9F\*]/u','', $property ) ;
                    if( is_array( $values ) ) :
                        foreach( $values as $index => $field ) :
                            if( is_numeric( $index ) ) :
                                $send[ $property ][] = self::convertToArray( $field );
                            else:
                                $send[ $property ][] = self::convertToArray( [ $index  =>  $field ] );
                            endif;
                        endforeach;
                    elseif ( $property === 'shop' || $property === 'access_token' ): continue;
                    else:
                        if( is_object( $values) ) {
                            $send[ $property ] = self::convertToArray( $values );
                        }
                        else{
                            $send[ $property ] = $values ;
                        }
                    endif;
                endif;
            }
        }
        else{
            return $object ;
        }
        if( $toJson == true ) return json_encode( $send ) ;
        return $send ;
    }

    public static function getProperty( $object , $property ){
        if( is_array( $object ) ) :
            return array_key_exists( $property , $object ) ? $object[ $property ] : null ;
        elseif( is_object( $object ) ) :
            return property_exists( $object , $property) ? $object->{ $property } : null;
        endif;
        return null;
    }

    public static function display( $data , $dump = false )
    {
        echo ISCLI ? "\r\n" : '<pre>';

        if ($dump){
            var_dump($data);
        } else {
            print_r($data);
        }

        echo ISCLI ? "\r\n" : '</pre>';

    }

    public static function endsWith($FullStr, $needle)
    {
        $StrLen     = strlen($needle);
        $FullStrEnd = substr($FullStr, strlen($FullStr) - $StrLen);
        return $FullStrEnd == $needle;
    }

    public static function getEnvValue( $key , $envContents ) {

    }

    /**
     * @param $string
     * @return bool
     */
    public static function isValidAppString( $string ) {
        return strlen( $string ) > 0 && ! is_null( $string ) ? $string : false;
    }

    /**
     * @param array $charsToReplace
     * @param array $charsToAdd
     * @param $string
     * @return bool|mixed
     */
    public static function replaceCharsInString( array $charsToReplace , array $charsToAdd , $string ){
        if( strlen( $string ) === 0 ) return false;
        if( empty( $charsToReplace ) ) return false;
        if( ! is_array( $charsToReplace ) ) return false;
        if( empty( $charsToAdd ) ) return false;
        if( ! is_array( $charsToAdd ) ) return false;
        return str_replace( $charsToReplace, $charsToAdd, $string );
    }

    /**
     * @param $string
     * @return string
     */
    public static function cleanString( $string ){
        $returnedString = filter_var( $string , FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $returnedString = htmlspecialchars( $returnedString , ENT_QUOTES, 'UTF-8');
        return trim( $returnedString );
    }
}