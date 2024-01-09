<?php

namespace Daxdoxsi\Abcphp\Libs;

class View
{
    public static function load(string $view, array $vars = [], string $layout = '<none>'):void
    {

        # Init params
        $file = __DIR__.'/../MVC/Views/'.$view.'.phtml';
        $fileLayout = __DIR__.'/../MVC/Views/Layouts/'.$layout.'.phtml';
        Mem::set('viewVars', $vars);

        # Check if view exists
        if (!file_exists($file) || !file_exists($fileLayout)){
            die("The view (($view)) is not defined");
        }

        # Rendering and capturing view output
        ob_start();
        require $file;
        Mem::set('viewContent', ob_get_contents());
        ob_end_clean();

        # Render the layout with the view rendered
        if ($layout != '<none>') {
            require $fileLayout;
        }
        else {
            echo Mem::get('viewContent');
        }

    }
}