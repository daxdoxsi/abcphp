<?php

namespace Daxdoxsi\Devtool\Library;

use Daxdoxsi\Abcphp\Enum\DirectoriesAppEnum;

class DB
{
    private \PDO $db;
    private \PDOStatement $res;
    private array $dbCredentials;
    private bool $ProdEnvironment = false;

    /**
     * @return bool
     */
    public function isProdEnvironment(): bool
    {
        return $this->ProdEnvironment;
    }

    /**
     * @param bool $ProdEnvironment
     */
    public function setProdEnvironment(bool $ProdEnvironment): void
    {
        $this->ProdEnvironment = $ProdEnvironment;
    }

    private function getDBConfig():void
    {
        # Check for database configuration
        if (!file_exists(DirectoriesAppEnum::DB_CONFIG->value)) {
            die('Please set the database configuration');
        }

        # Reading configuration file
        $info = parse_ini_file(DirectoriesAppEnum::DB_CONFIG->value,true);

        # Check if the config file has valid format
        if (!isset($info['DB_CONFIG'])) {
            die('The Database configuration file is not valid.');
        }

        # Store the configuration in the class variable
        $this->dbCredentials = $info['DB_CONFIG'];

    }

    private function init():void {

        # Reading the database configuration file
        if (!isset($this->dbCredentials)) {
            $this->getDBConfig();
        }

        # Connecting DB configurations with local variable
        $cnf = $this->dbCredentials;

        $dsn = "{$cnf['driver']}:host={$cnf['host']};port={$cnf['port']};dbname={$cnf['name']}";
        $this->db = new \PDO($dsn,$cnf['user'],$cnf['pass'],[\PDO::FETCH_ASSOC]);

    }

    public function __construct()
    {

        # Establishing the database connection
        $this->init();

    }

    public function query(string $sql, array $placeholders = []):array|int|false {

        # Init params
        $return = [];

        # Processing the SQL sentences individually
        $code = explode(';', trim($sql,'; '));
        $code = array_map(function($stmt){return trim($stmt); }, $code);

        # Queue of SQL sentences
        foreach($code as $sql) {

            # Processing the SQL query
            $this->res = $this->db->prepare($sql, [\PDO::FETCH_ASSOC]);
            $this->res->execute($placeholders);

            # Insert sentence
            if (strtolower(substr($sql,0,strlen('insert'))) == 'insert') {
                $return[] = ['lastInsertId' => $this->db->lastInsertId()];
            }
            # Update sentence
            elseif (strtolower(substr($sql,0,strlen('update'))) == 'update') {
                $return[] = ['affectedRows' => $this->res->rowCount()];
            }
            # Any other type of sentence
            else {
                $return[] = $this->res->fetchAll(\PDO::FETCH_ASSOC);
            }
        }

        # Returns all the queries results
        return $return;

    }

}