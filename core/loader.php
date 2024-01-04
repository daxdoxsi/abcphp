<?php

use Daxdoxsi\Abcphp\Libs\Debug;
use Daxdoxsi\Abcphp\Libs\DB;

require __DIR__.'/../vendor/autoload.php';
session_start();

new class {

    private array $glob = [];

    public function __construct()
    {
        echo "App loader ready";
        $db = new DB();
        $res = $db->query('SELECT * FROM abcphp_routes WHERE 1');
        echo Debug::dump($res);

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