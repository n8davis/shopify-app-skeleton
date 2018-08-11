<?php

namespace Skeleton;
class Security {

    const ENCRYPTION_KEY = '+ew/T+rZDRwCrzZ3dykEL8wUnlwk4ZNOPJRBH8lRM5w=';
    const SECRET_IV = '5nkyCr1wqN4FeDQW6rpxyaAaNQrfjU+ZGMiadxwpZJQ=';

    public static function encrypt( $content ) {
        $output = false;

        $encrypt_method = 'AES-256-CBC';
        // hash
        $key = hash( 'sha256', Security::ENCRYPTION_KEY );

        $iv = substr( hash( 'sha256', Security::SECRET_IV ), 0, 16 );

        $output = openssl_encrypt( $content, $encrypt_method, $key, 0, $iv );
        $output = base64_encode( $output );


        return $output;
    }

    public static function decryptRJ256( $encrypted ) {
        //PHP strips "+" and replaces with " ", but we need "+" so add it back in...
        $encrypted = str_replace( ' ', '+', $encrypted );

        //get all the bits
        $key       = base64_decode( self::ENCRYPTION_KEY );
        $iv        = base64_decode( self::SECRET_IV );
        $encrypted = base64_decode( $encrypted );

        $rtn = mcrypt_decrypt( MCRYPT_RIJNDAEL_256, $key, $encrypted, MCRYPT_MODE_CBC, $iv );
        $rtn = self::unpad( $rtn );

        return ( $rtn );
    }

    public static function unpad( $value ) {
        $blockSize = mcrypt_get_block_size( MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CBC );
        $packing   = ord( $value[ strlen( $value ) - 1 ] );
        if ( $packing && $packing < $blockSize ) {
            for ( $P = strlen( $value ) - 1; $P >= strlen( $value ) - $packing; $P -- ) {
                if ( ord( $value{$P} ) != $packing ) {
                    $packing = 0;
                }
            }
        }

        return substr( $value, 0, strlen( $value ) - $packing );
    }

    public static function decrypt( $content ) {

        $encrypt_method = 'AES-256-CBC';

        // hash
        $key = hash( 'sha256', Security::ENCRYPTION_KEY );

        $iv = substr( hash( 'sha256', Security::SECRET_IV ), 0, 16 );

        $output = openssl_decrypt( base64_decode( $content ), $encrypt_method, $key, 0, $iv );

        return $output;
    }

    public static function verify( $password, $encryptedPassword ) {
        $encryptedPassword = self::decrypt( $encryptedPassword );

        return $password === $encryptedPassword;
    }

    public static function serialize( $object ) {
        return base64_encode( serialize( $object ) );
    }

    /**
     * @param $object
     * @param array $allowedClasses
     *
     * @return mixed
     */
    public static function unserialize( $object, $allowedClasses = [] ) {
        if ( PHP_VERSION_ID >= 70000 ) {
            /* to forbid classes unserializing at all use this: array('allowed_classes' => false) */
            return unserialize( base64_decode( $object, array( 'allowed_classes' => $allowedClasses ) ) );
        } else {
            return unserialize( base64_decode( $object ) );
        }
    }

    public static function generateRandomKey( $sizeOf ) {
        if ( ! isset( $sizeOf ) ) {
            return false;
        } else {
            $chars    = "abcdefghjknpqrstwxyzABCDEFGHJKLMQSTUVWXYZ23456789";
            $finalKey = "";
            while ( strlen( $finalKey ) < $sizeOf ) {
                $finalKey .= $chars[ mt_rand( 0, strlen( $chars ) - 1 ) ];
            }

            return $finalKey;
        }
    }

    public static function validateHMAC( $query_params, $shared_secret ) {

        if ( ! isset( $query_params['timestamp'] ) ) {
            return false;
        }
        $seconds_in_a_day = 24 * 60 * 60;
        $older_than_a_day = $query_params['timestamp'] < ( time() - $seconds_in_a_day );
        if ( $older_than_a_day ) {
            return false;
        }

        $hmac = $query_params['hmac'];
        unset( $query_params['signature'], $query_params['hmac'] );

        foreach ( $query_params as $key => $val ) {
            $params[] = "{$key}={$val}";
        }
        sort( $params );

        return ( hash_hmac( 'sha256', implode( '&', $params ), $shared_secret ) === $hmac );
    }
}