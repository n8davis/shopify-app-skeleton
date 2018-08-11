<?php
/**
 * Created by PhpStorm.
 * User: nate
 * Date: 8/4/18
 * Time: 11:21 AM
 */

namespace Skeleton\Shopify;
use Skeleton\Basic\Assist;
use Skeleton\Basic\Client;

class Shop extends Shopify
{

    CONST NAME_SINGULAR = 'shop';
    CONST NAME_PLURAL   = 'shop';
    protected $address1;
    protected $address2;
    protected $city;
    protected $country;
    protected $country_code;
    protected $country_name;
    protected $created_at;
    protected $updated_at;
    protected $customer_email;
    protected $currency;
    protected $domain;
    protected $email;
    protected $google_apps_domain;
    protected $google_apps_login_enabled;
    protected $id;
    protected $latitude;
    protected $longitude;
    protected $money_format;
    protected $money_with_currency_format;
    protected $weight_unit;
    protected $myshopify_domain;
    protected $name;
    protected $plan_name;
    protected $has_discounts;
    protected $has_gift_cards;
    protected $plan_display_name;
    protected $password_enabled;
    protected $phone;
    protected $primary_locale;
    protected $province;
    protected $province_code;
    protected $shop_owner;
    protected $source;
    protected $force_ssl;
    protected $tax_shipping;
    protected $taxes_included;
    protected $county_taxes;
    protected $timezone;
    protected $iana_timezone;
    protected $zip;
    protected $has_storefront;
    protected $setup_required;

    public function getSingularName(){
        return self::NAME_SINGULAR;
    }

    public function getPluralName(){
        return self::NAME_PLURAL;
    }

    /**
     * @return $this|bool
     */
    public function find()
    {
        if( is_null( $this->getShop() ) || is_null( $this->getAccessToken() ) ) return false;
        $uri = 'https://' . $this->getShop() . '/admin/' . $this->getPluralName()  . '.json';
        $httpConnect    = new Client();
        $httpConnect->request( $uri ,'', 'GET' , $this->headers() ) ;

        $shopifyResponse = json_decode( $httpConnect->response );
        $this->setResults( $shopifyResponse ) ;
        if( Assist::getProperty( $shopifyResponse  , $this->getSingularName()) ) :
            $this->setResults( $shopifyResponse->{ $this->getSingularName() } );
            $this->setEmail( $shopifyResponse->{ $this->getSingularName() }->email );
            $this->setTimezone( $shopifyResponse->{ $this->getSingularName() }->timezone );
            $this->setIanaTimezone( $shopifyResponse->{ $this->getSingularName() }->iana_timezone );
        endif;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAddress1()
    {
        return $this->address1;
    }

    /**
     * @param mixed $address1
     * @return Shop
     */
    public function setAddress1($address1)
    {
        $this->address1 = $address1;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAddress2()
    {
        return $this->address2;
    }

    /**
     * @param mixed $address2
     * @return Shop
     */
    public function setAddress2($address2)
    {
        $this->address2 = $address2;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param mixed $city
     * @return Shop
     */
    public function setCity($city)
    {
        $this->city = $city;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param mixed $country
     * @return Shop
     */
    public function setCountry($country)
    {
        $this->country = $country;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCountryCode()
    {
        return $this->country_code;
    }

    /**
     * @param mixed $country_code
     * @return Shop
     */
    public function setCountryCode($country_code)
    {
        $this->country_code = $country_code;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCountryName()
    {
        return $this->country_name;
    }

    /**
     * @param mixed $country_name
     * @return Shop
     */
    public function setCountryName($country_name)
    {
        $this->country_name = $country_name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * @param mixed $created_at
     * @return Shop
     */
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * @param mixed $updated_at
     * @return Shop
     */
    public function setUpdatedAt($updated_at)
    {
        $this->updated_at = $updated_at;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCustomerEmail()
    {
        return $this->customer_email;
    }

    /**
     * @param mixed $customer_email
     * @return Shop
     */
    public function setCustomerEmail($customer_email)
    {
        $this->customer_email = $customer_email;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param mixed $currency
     * @return Shop
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * @param mixed $domain
     * @return Shop
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     * @return Shop
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getGoogleAppsDomain()
    {
        return $this->google_apps_domain;
    }

    /**
     * @param mixed $google_apps_domain
     * @return Shop
     */
    public function setGoogleAppsDomain($google_apps_domain)
    {
        $this->google_apps_domain = $google_apps_domain;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getGoogleAppsLoginEnabled()
    {
        return $this->google_apps_login_enabled;
    }

    /**
     * @param mixed $google_apps_login_enabled
     * @return Shop
     */
    public function setGoogleAppsLoginEnabled($google_apps_login_enabled)
    {
        $this->google_apps_login_enabled = $google_apps_login_enabled;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return Shop
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @param mixed $latitude
     * @return Shop
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * @param mixed $longitude
     * @return Shop
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMoneyFormat()
    {
        return $this->money_format;
    }

    /**
     * @param mixed $money_format
     * @return Shop
     */
    public function setMoneyFormat($money_format)
    {
        $this->money_format = $money_format;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMoneyWithCurrencyFormat()
    {
        return $this->money_with_currency_format;
    }

    /**
     * @param mixed $money_with_currency_format
     * @return Shop
     */
    public function setMoneyWithCurrencyFormat($money_with_currency_format)
    {
        $this->money_with_currency_format = $money_with_currency_format;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getWeightUnit()
    {
        return $this->weight_unit;
    }

    /**
     * @param mixed $weight_unit
     * @return Shop
     */
    public function setWeightUnit($weight_unit)
    {
        $this->weight_unit = $weight_unit;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMyshopifyDomain()
    {
        return $this->myshopify_domain;
    }

    /**
     * @param mixed $myshopify_domain
     * @return Shop
     */
    public function setMyshopifyDomain($myshopify_domain)
    {
        $this->myshopify_domain = $myshopify_domain;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     * @return Shop
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPlanName()
    {
        return $this->plan_name;
    }

    /**
     * @param mixed $plan_name
     * @return Shop
     */
    public function setPlanName($plan_name)
    {
        $this->plan_name = $plan_name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getHasDiscounts()
    {
        return $this->has_discounts;
    }

    /**
     * @param mixed $has_discounts
     * @return Shop
     */
    public function setHasDiscounts($has_discounts)
    {
        $this->has_discounts = $has_discounts;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getHasGiftCards()
    {
        return $this->has_gift_cards;
    }

    /**
     * @param mixed $has_gift_cards
     * @return Shop
     */
    public function setHasGiftCards($has_gift_cards)
    {
        $this->has_gift_cards = $has_gift_cards;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPlanDisplayName()
    {
        return $this->plan_display_name;
    }

    /**
     * @param mixed $plan_display_name
     * @return Shop
     */
    public function setPlanDisplayName($plan_display_name)
    {
        $this->plan_display_name = $plan_display_name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPasswordEnabled()
    {
        return $this->password_enabled;
    }

    /**
     * @param mixed $password_enabled
     * @return Shop
     */
    public function setPasswordEnabled($password_enabled)
    {
        $this->password_enabled = $password_enabled;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param mixed $phone
     * @return Shop
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPrimaryLocale()
    {
        return $this->primary_locale;
    }

    /**
     * @param mixed $primary_locale
     * @return Shop
     */
    public function setPrimaryLocale($primary_locale)
    {
        $this->primary_locale = $primary_locale;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getProvince()
    {
        return $this->province;
    }

    /**
     * @param mixed $province
     * @return Shop
     */
    public function setProvince($province)
    {
        $this->province = $province;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getProvinceCode()
    {
        return $this->province_code;
    }

    /**
     * @param mixed $province_code
     * @return Shop
     */
    public function setProvinceCode($province_code)
    {
        $this->province_code = $province_code;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getShopOwner()
    {
        return $this->shop_owner;
    }

    /**
     * @param mixed $shop_owner
     * @return Shop
     */
    public function setShopOwner($shop_owner)
    {
        $this->shop_owner = $shop_owner;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @param mixed $source
     * @return Shop
     */
    public function setSource($source)
    {
        $this->source = $source;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getForceSsl()
    {
        return $this->force_ssl;
    }

    /**
     * @param mixed $force_ssl
     * @return Shop
     */
    public function setForceSsl($force_ssl)
    {
        $this->force_ssl = $force_ssl;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTaxShipping()
    {
        return $this->tax_shipping;
    }

    /**
     * @param mixed $tax_shipping
     * @return Shop
     */
    public function setTaxShipping($tax_shipping)
    {
        $this->tax_shipping = $tax_shipping;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTaxesIncluded()
    {
        return $this->taxes_included;
    }

    /**
     * @param mixed $taxes_included
     * @return Shop
     */
    public function setTaxesIncluded($taxes_included)
    {
        $this->taxes_included = $taxes_included;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCountyTaxes()
    {
        return $this->county_taxes;
    }

    /**
     * @param mixed $county_taxes
     * @return Shop
     */
    public function setCountyTaxes($county_taxes)
    {
        $this->county_taxes = $county_taxes;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTimezone()
    {
        return $this->timezone;
    }

    /**
     * @param mixed $timezone
     * @return Shop
     */
    public function setTimezone($timezone)
    {
        $this->timezone = $timezone;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIanaTimezone()
    {
        return $this->iana_timezone;
    }

    /**
     * @param mixed $iana_timezone
     * @return Shop
     */
    public function setIanaTimezone($iana_timezone)
    {
        $this->iana_timezone = $iana_timezone;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getZip()
    {
        return $this->zip;
    }

    /**
     * @param mixed $zip
     * @return Shop
     */
    public function setZip($zip)
    {
        $this->zip = $zip;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getHasStorefront()
    {
        return $this->has_storefront;
    }

    /**
     * @param mixed $has_storefront
     * @return Shop
     */
    public function setHasStorefront($has_storefront)
    {
        $this->has_storefront = $has_storefront;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSetupRequired()
    {
        return $this->setup_required;
    }

    /**
     * @param mixed $setup_required
     * @return Shop
     */
    public function setSetupRequired($setup_required)
    {
        $this->setup_required = $setup_required;
        return $this;
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
     * @return Shop
     */
    public function setShop($shop)
    {
        $this->shop = $shop;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAccessToken()
    {
        return $this->access_token;
    }

    /**
     * @param mixed $access_token
     * @return Shop
     */
    public function setAccessToken($access_token)
    {
        $this->access_token = $access_token;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @param mixed $errors
     * @return Shop
     */
    public function setErrors($errors)
    {
        $this->errors = $errors;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getResults()
    {
        return $this->results;
    }

    /**
     * @param mixed $results
     * @return Shop
     */
    public function setResults($results)
    {
        $this->results = $results;
        return $this;
    }

}