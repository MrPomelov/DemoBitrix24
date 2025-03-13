<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true )die();

spl_autoload_register(function ($class) {
    if(!str_contains($class,'Models') && !str_contains($class,'UserTypes') && !str_contains($class,'Events')){
        return;
    }
    $file = str_replace('\\', '/', $class).'.php';
    $file = __DIR__ . '/' . $file;
    
    if (is_file($file) && file_exists($file)) {
        require_once $file;
    }
});
    
