<?php

namespace Daxdoxsi\Devtool\Library;

use Daxdoxsi\Devtool\Enum\DirEnum;

class Router
{
    public static string $currentUri;
    public static string $currentLang;
    public static array $uriParams;

    public static function matchURIController():array|false {

        # Get the request information
        $uri = trim($_SERVER['REQUEST_URI'],' /');
        $method = strtolower($_SERVER['REQUEST_METHOD']);

        # Reading app and lang parameters from general configuration file
        $conf = INIReader::getGeneralConfig();
        $appConf = $conf['application'];
        $langConf = $conf['language'];

        # Validating if the language was indicated
        $langs = explode(',', $langConf['codeLabel']);
        $langDefault = $langConf['default'];

        # Sorting by language code
        $langCodes = [];
        foreach($langs as $langItem) {

            # Extracting the language details
            list($code, $label) = explode('|', $langItem);
            $langList[$code] = $label;
            $langCodes[] = $code;

        }

        # Checks if the lang code is indicated in the URI
        $uriSeg = explode('/',$uri);
        if ( ( strlen($uri) < 2 ) || ( count($uriSeg) > 0 && !in_array($uriSeg[0], $langCodes) ) ) {

            # if the lang cookie is defined perform a redirect
            if ( isset($_COOKIE['lang']) ){
                $sLang = $_COOKIE['lang'];
            }
            # Set the default language according to the configuration file
            else {
                $sLang = $langDefault;
                setcookie('lang', $sLang, time()+60*60*24*30,'/', $_SERVER['SERVER_NAME'], true, true);
            }

            # Forcing redirect to the default language
            header('Location: /'.$sLang.'/');
            exit;

        }

        # Getting the current lang from the URI
        $currentLang = substr($uri,0, 2);

        # Store the current application lang in a global variable
        static::$currentLang = $currentLang;

        # Checks if the lang is already defined in the cookie
        if ( isset($_COOKIE['lang']) && $_COOKIE['lang'] == $currentLang ) {

        }
        else {

            # Store the lang in the cookie var
            setcookie('lang', $currentLang, time()+60*60*24*30,'/', $_SERVER['SERVER_NAME'], true, true);

        }

        # Removing language from the URI
        $uri = substr($uri,3);
        $queryString = '';

        # Subtracts the query string section from the URI
        $splitQ = explode('?', $uri);
        if (count($splitQ) == 2) {
            $queryString = $splitQ[1];
            $uri = $splitQ[0];
        }

        # Storing currentURI without the lang prefix
        static::$currentUri = $uri;

        # Reading the routes configuration
        $data =  CSVReader::readCSV(DirEnum::CONFIG_ROUTES->value);

        # Matching the current URI with the configuration routes
        $located = 0;

        # Run across the config URIs
        foreach ($data as $idRecord => $record) {

            # Format the config URI
            $configUri = trim($record['uri'], ' /');
            $configMethod = strtolower($record['request_methods']);
            $condition = str_contains($configMethod, $method) && $record['status'] == 1;

            if ($configUri == $uri && $condition) {

                # Plain comparison of full URIs
                $located = 1;
                break;

            }

        }

        if (!$located) {

            # Invert the located status for the foreach loop
            $located = 0;
            $uriParams = [];

            foreach($data as $idRecord => $record) {

                # Format the config URI
                $configUri = trim($record['uri'], ' /');
                $configMethod = strtolower($record['request_methods']);
                $condition = str_contains($configMethod, $method) && $record['status'] == 1;

                # Regex comparison
                $configSegments = explode('/',$configUri);
                $segments = explode('/', $uri);

                if (count($configSegments) >= count($segments) && $condition) {

                    # Init params
                    $located = 0;

                    for ($i = 0; $i < count($configSegments); $i++) {

                        # If a regular expression is detected and makes match with configSegment
                        if (
                            strlen($configSegments[$i]) >= 3 &&
                            str_starts_with($configSegments[$i],'@') &&
                            str_ends_with($configSegments[$i],'@')
                        ) {


                            # Checks the regex
                            preg_match($configSegments[$i],$segments[$i] ?? '', $matches);

                            if (count($matches) == 0) {
                                $located = 0;
                                break;
                            }
                            else {
                                $uriParams[] = $matches[0];
                                $located = 1;
                            }

                        }
                        # Plain compare between segments
                        elseif ($configSegments[$i] != $segments[$i]) {
                            $located = 0;
                            break;
                        }
                        else {
                            $located = 1;
                        }

                    } // for


                }

                # Stop the revision since it found the record that match
                if ($located == 1) {
                    break;
                }

            }

        }

        # If the URI make match returns the controller name
        if( $located == 1 ) {

            # Segments from a Regex pattern
            if(isset($segments)) {
                $record['uri'] = implode('/', $segments);
            }

            # Regex Matches
            $record['params'] = $uriParams ?? [];
            static::$uriParams = $record['params'];

            return [
                'config' => $record,
                'uri' => $uri
            ];

        }

        # Return false if URI not found
        return false;

    }

}