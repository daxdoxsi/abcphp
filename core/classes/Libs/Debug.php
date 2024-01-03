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
            $output .= '<div style="padding: 20px; background: bisque; font-family: \'Courier New\';>';
            $output .= '<h3>'.strtoupper($name).'</h3><hr>';

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