<?php

function autoloader($classname){
    $fileName = str_replace('\\', '/', $classname) . '.php';
    $file = __DIR__ . '/../classes/' . $fileName ;
    include $file ;
}
spl_autoload_register('autoloader');