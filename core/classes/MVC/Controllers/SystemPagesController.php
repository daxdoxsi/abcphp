<?php

namespace Daxdoxsi\Abcphp\MVC\Controllers;

use Daxdoxsi\Abcphp\MVC\Controllers\BaseController;

class SystemPagesController extends BaseController
{

    public function pageNotFound():void
    {
        die('Page Not Found');
    }
}