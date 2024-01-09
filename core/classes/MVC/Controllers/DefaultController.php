<?php

namespace Daxdoxsi\Abcphp\MVC\Controllers;

use Daxdoxsi\Abcphp\Libs\View;

class DefaultController extends BaseController
{
    public function home(array $params):void {
        View::load('Home',[],'Bootstrap');
    }
}