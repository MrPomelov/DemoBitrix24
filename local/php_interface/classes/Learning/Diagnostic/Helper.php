<?php
namespace Learning\Diagnostic;
use \Bitrix\Main\Diag\Debug;

class Helper{
    public static function customDebugWrapper($array, $arrayName, $action = 'start'){
        global $USER;
        // проставим дату и время начала
        Debug::writeToFile(date('l jS \of F Y h:i:s A'), ' --- Текущая дата начала --- ');
        Debug::writeToFile("[".$USER->GetID()."] (".$USER->GetLogin().") ".$USER->GetFullName());
        // выведем оригинальный массив на страницу
        Debug::dump($array);
        // выведем оригинальный массив в файл лога
        Debug::writeToFile($array, $arrayName);

        if(!empty($action)){
            if($action == 'start'){
                // устанавливаем временную метку начала
                Debug::startTimeLabel($arrayName);
            }else if($action == 'end'){
                // устанавливаем временную метку завершения
                Debug::endTimeLabel($arrayName);
                // выведем в файл и на страницу временные метки
                Debug::dump(Debug::getTimeLabels());
                Debug::writeToFile(Debug::getTimeLabels());
            }
        }

        // проставим дату и время завершения
        Debug::writeToFile(date('l jS \of F Y h:i:s A'), ' --- Текущая дата завершения --- ');
        return true;
    }

    public static function quickSort($array) {
        $length = count($array);
    
        if ($length <= 1) {
            return $array;
        } else {
            $pivot = $array[0];
            $left = $right = array();
    
            for ($i = 1; $i < $length; $i++) {
                if ($array[$i] < $pivot) {
                    $left[] = $array[$i];
                } else {
                    $right[] = $array[$i];
                }
            }
    
            return array_merge(
                self::quickSort($left), 
                array($pivot), 
                self::quickSort($right)
            );
        }
    }
}