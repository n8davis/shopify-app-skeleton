<?php
/**
 * Created by PhpStorm.
 * User: nate
 * Date: 7/19/18
 * Time: 6:58 PM
 */

namespace Skeleton\Model;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
class ShopOwner extends Model
{

    protected $fillable = [ 'shop' , 'access_token' , 'key' , 'timezone' , 'email' ];
}