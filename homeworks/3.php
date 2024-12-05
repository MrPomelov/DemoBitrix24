<?php
// use Bitrix\Main\Diag\Debug;
// use Bitrix\Main\Loader;
use Bitrix\Iblock\ElementTable;
use Bitrix\Iblock\Elements;
use Bitrix\Main\Application;
// use Bitrix\Main\Application;
// use Bitrix\Crm\DealTable;

// use Learning\Diagnostic\Helper;

use Models\Lists\DoctorsPropertyValuesTable as DoctorsTable;
use Models\Lists\ProceduresPropertyValuesTable as ProceduresTable;

require $_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php';
$APPLICATION->SetTitle("Home work | second-lesson");
//Loader::includeModule('iblock');
global $USER;
?>
<style>
    span.class{
        color:green;
    }
    span.method{
        color:orange;
    }
</style>
<?/*
<h2>Описание/Пошаговая инструкция выполнения домашнего задания:</h2>
<ol>
    <li>создать 2 списка с врачами и процедурами которые они выполняют;</li>
    <li>привязать процедуры к врачам;</li>
    <li>создать пустую страницу где мы кликаем по врачу и видим процедуры которые он делает. Использовать абстрактный класс из предыдущих занятий и D7;</li>
    <li>со звездочкой: реализовать возможность добавления врача, процедуры, связи между ними.</li>
</ol>
<br>
<h2>Реализовано:</h2>
<ol>
    <li></li>
    <li></li>
</ol>
*/?>
<?php 

    // $doctors = DoctorsTable::getList([
    //     'select' => [
    //         'ID' => 'IBLOCK_ELEMENT_ID',
    //         'NAME' => 'ELEMENT.NAME',
    //         'PROCEDURES' => 'PROCEDURES',
    //         'PROCEDURES_DATA_'=>'PROCEDURES_DATA',
    //     ]
    // ])->fetchAll();

    // echo '<pre>';
    // print_r($doctors);
    // echo '</pre>';

    // Array
    // (
    //     [0] => Array
    //         (
    //             [ID] => 30
    //             [NAME] => Коробицына Ксения Николаевна
    //             [PROCEDURES] => Array
    //                 (
    //                     [0] => 36
    //                     [1] => 33
    //                     [2] => 37
    //                     [3] => 34
    //                 )

    //             [PROCEDURES_DATA_IBLOCK_ELEMENT_ID] => 36
    //             [PROCEDURES_DATA_COST] => 1500
    //         )

    //     [1] => Array
    //     ...
    $request = Bitrix\Main\Application::getInstance()->getContext()->getRequest();
    
    // echo '<pre>request';
    // print_r($request);
    // echo '</pre>';
    $values = $request->getValues();
    
    // echo '<pre>values';
    // print_r($values);
    // echo '</pre>';

    $doctorsNew = \Bitrix\Iblock\Elements\ElementdoctorsTable::getList([
        'select' => [
            'ID',
            'NAME',
            'PROCEDURES.ELEMENT'
        ],
        'filter' => [
            //'ID' => $doctorID,
            'ACTIVE' => 'Y'
        ]
    ])->fetchCollection();

    foreach ($doctorsNew as $doctor){
        echo '<pre>';
        print_r($doctor->get('NAME'));
        echo '</pre>';

        foreach ($doctor->getProcedures()->getAll() as $prItem){
            echo '<pre>';
            print_r($prItem->getId().' - '.$prItem->getElement()->getName());
            echo '</pre>';
        }
    }

?>
<?php require $_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php';?>