<?php

use Daxdoxsi\Abcphp\Libs\Debug;
use Daxdoxsi\Abcphp\Libs\Lang;
use Daxdoxsi\Abcphp\Libs\Router;

require __DIR__.'/../vendor/autoload.php';
session_start();

new class {

    private array $glob = [];

    public function __construct()
    {

        $routeInfo = Router::matchURIController();

        echo '<h1>'.Lang::t('Hello Mundo').'</h1>';

        echo Debug::dump($routeInfo);



    }

    /**
     * @return array
     */
    public function getGlob(): array
    {
        return $this->glob;
    }

    /**
     * @param array $glob
     */
    public function setGlob(array $glob): void
    {
        $this->glob = $glob;
    }


};