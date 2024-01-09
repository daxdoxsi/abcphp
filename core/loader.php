<?php

use Daxdoxsi\Abcphp\Libs\Debug;
use Daxdoxsi\Abcphp\Libs\Lang;
use Daxdoxsi\Abcphp\Libs\Router;
use Daxdoxsi\Abcphp\Libs\SessionHandler as SessionHandlerAlias;

require __DIR__.'/../vendor/autoload.php';
session_set_save_handler(new SessionHandlerAlias());
session_start();

new class {

    private array $glob = [];

    public function __construct()
    {
        # Init params
        $ns = '\\Daxdoxsi\\Abcphp\\MVC\\Controllers\\';

        $routeInfo = Router::matchURIController();

        if ($routeInfo){

            try {
                $ctlNS = $ns . $routeInfo['config']['controller'];
                $ctlMethod = $routeInfo['config']['method'];
                $controller = (new $ctlNS())->$ctlMethod($routeInfo['config']['params']);
            }
            catch (Exception $e) {
                die($e->getMessage());
            }

        }
        else {

            try {
                $ctlNS = $ns . 'SystemPagesController';
                $ctlMethod = 'pageNotFound';
                $controller = (new $ctlNS())->$ctlMethod();
            }
            catch (Exception $e) {
                die($e->getMessage());
            }

        }

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