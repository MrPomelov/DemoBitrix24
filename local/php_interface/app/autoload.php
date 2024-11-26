<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true )die();

spl_autoload_register(function ($class) {
    if(!str_contains($class,'models')){
        return;
    }

    $file = str_replace('\\', '/', $class).'.php';
    $file = __DIR__ . '/' . $file;
    
    if (is_file($file) && file_exists($file)) {
        echo 'Подключаем файл /n';
        echo $file;
        require_once $file;
    }else{
        echo 'Не смогли подключить файл /n';
        echo $file;
    }
});
    
