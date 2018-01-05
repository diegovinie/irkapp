<?php

spl_autoload_register(function($class){

    if(!strpos(get_include_path(), PATH_SEPARATOR .LIB_DIR)){
        set_include_path(get_include_path() .PATH_SEPARATOR .LIB_DIR);
    }

    $fileName = str_replace('\\', DIRECTORY_SEPARATOR, $class);

    \hlp\logger("Autoload Clase: " .$fileName.".php", true);

    include_once $fileName .'.php';
});
