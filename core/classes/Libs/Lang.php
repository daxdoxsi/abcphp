<?php

namespace Daxdoxsi\Abcphp\Libs;

class Lang
{
    public static array $dictionary = [];
    public static DB $db;

    public static function readDB():void {

        if (!isset(static::$db)) {
            static::$db = new DB();
        }

        # Init vars
        static::$dictionary = [];

        # Extract database information
        $sql = 'SELECT * FROM abcphp_translations WHERE 1';
        $results = static::$db->query($sql);

        # Formatting results
        foreach( $results[0] as $result ) {

            static::$dictionary[$result['text_code']] = $result;

        }

    }


    public static function t(string $text):string
    {

        # Reading the lang translation information
        if (count(static::$dictionary) == 0) {
            static::readDB();
        }

        # Checking if vocabulary
        if ( !isset(static::$dictionary[$text]) ) {

            # Verify if the text code already exists into the database
            $sqlA = 'SELECT * FROM abcphp_translations WHERE text_code = :text';
            $resA = static::$db->query($sqlA, [':text' => $text]);

            # If text code does not exists then insert a new record into the database
            if (count($resA[0]) == 0) {

                $sql = "INSERT INTO `abcphp_translations` (`id_abcphp_translations`, `text_code`, `es`, `en`, `pt`, `it`, `fr`) VALUES (NULL, :text, NULL, NULL, NULL, NULL, NULL);";
                static::$db->query($sql, [':text' => $text]);
                static::$dictionary[$text] = [];

            }

        }

        # Sending the text translation
        return static::$dictionary[$text][Router::$currentLang] ?? '(('.$text.'))';

    }

}