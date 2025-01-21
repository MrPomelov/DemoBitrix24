<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true )die();

spl_autoload_register(function ($class) {
    if(!str_contains($class,'Models') && !str_contains($class,'UserTypes')){
        return;
    }
    //echo 'autoload start';
    $file = str_replace('\\', '/', $class).'.php';
    $file = __DIR__ . '/' . $file;
    
    if (is_file($file) && file_exists($file)) {
        // echo '<span style="color:green;">Подключаем файл </span>'. $file . '<br>';
        require_once $file;
    }/*else{
        echo '<span style="color:red;">Не смогли подключить файл </span>'. $file .' <br>';
    }*/
});
    
