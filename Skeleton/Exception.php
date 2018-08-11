<?php
/**
 * Created by PhpStorm.
 * User: nate
 * Date: 7/19/18
 * Time: 6:32 PM
 */

namespace Skeleton;

use \Illuminate\Contracts\Debug\ExceptionHandler;

class Exception implements ExceptionHandler
{
    public function report(\Exception $e) {
        throw $e;
    }
    public function render($request, \Exception $e) {
        throw $e;
    }
    public function renderForConsole($output, \Exception $e) {
        throw $e;
    }
}