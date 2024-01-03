<?php

require __DIR__.'/../vendor/autoload.php';

new class {

    private array $glob = [];

    public function __construct()
    {
        echo "App loader ready";
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