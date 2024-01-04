<?php

namespace Daxdoxsi\Abcphp\Libs;

class Debug
{
    public static function dump(...$vars):string
    {

        # Init vars
        $output = '';

        # Scanning all the method parameters
        foreach($vars as $name => $value){

            # Output wrapper
            $output .= '<div style="box-shadow: 15px 15px 15px gray; border-radius: 20px; padding: 20px; width:600px; height:400px; overflow: auto; margin: 30px auto; font-size: 0.9em; background: bisque; font-family: \'Courier New\';">';
            $output .= '<h3>Variable #'.strtoupper($name+1).'</h3><hr>';

            # Capturing output
            ob_start();
            var_dump($value);
            $output .= highlight_string(
                string: "<?php\n\n".ob_get_contents()."\n\n?>",
                return: true
            );
            ob_end_clean();

            # Close wrapper
            $output .= '</div><br><br>';
        }

        return $output;

    }

}