<?php

namespace Daxdoxsi\Abcphp\MVC\Controllers;

use Daxdoxsi\Abcphp\Libs\Debug;

class DefaultController extends BaseController
{
    public function home(array $params):void {
        echo 'Home method of DefaultController loaded';
        echo Debug::dump($params);
    }
}