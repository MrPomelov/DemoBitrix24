<?php
use Bitrix\Iblock\ElementTable;
use Bitrix\Iblock\Elements;
use Bitrix\Main\Application;
use Bitrix\Main\Page\Asset;
use Bitrix\Iblock\PropertyEnumerationTable;

use Models\Lists\DoctorsPropertyValuesTable as DoctorsTable;
use Models\Lists\ProceduresPropertyValuesTable as ProceduresTable;

require $_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php';

Asset::getInstance()->addCss("/homeworks/3/templates/styles.css");

?>
<style>
    span.class{
        color:green;
    }
    span.method{
        color:orange;
    }
</style>

<h2>Реализовано домашнее задание:</h2>
<ol>
    <li>создано 2 списка с врачами и процедурами которые они выполняют;</li>
    <li>реализована привязка процедур к врачам через абстрактный класс, но таким способом не удалось получить значения для множественного свойства типа список</li>
    <li>создана страница где мы кликаем по врачу и видим процедуры которые он делает. Использовать абстрактный класс не удалось, D7 частично;</li>
    <li>реализована возможность добавления и изменения врача, процедуры, связи между ними (кроме файлов, с этим не заморачивался)</li>
    <li>логика страницы реализована по принципу универсальности - используются три шаблона страниц для всех сущностей, две функции по сохранению и получению информации</li>
</ol>
<br>

<?php 
// получим параметры страницы из url 
$request = Application::getInstance()->getContext()->getRequest();
$values = $request->getValues();

if(!empty($values['submit'])){
    $requestStatus = setData($values);
    require $_SERVER['DOCUMENT_ROOT'].'/homeworks/3/templates/requestStatus.php';
}
if(empty($values['path'])){
    localRedirect('/homeworks/3/doctors/');
}
$arPath = explode("/",$values['path']);


// определим сущность и шаблон страницы
$enity = $arPath[0]; //doctors or procedures
if((!empty($arPath[1]) && $arPath[1] == 'add') || (!empty($arPath[2]) && $arPath[2] == 'edit')){
    // страница редактирования
    $pageTemplate = 'Modify';
    if(!empty($arPath[1]) && $arPath[1] != 'add'){
        $elementID = $arPath[1];    
    }

}else if(!empty($arPath[1]) && $arPath[1] != 'add' && empty($arPath[2])){
    // детальная страница
    $pageTemplate = 'Detail';
    $elementID = $arPath[1];

}else{
    // страница списка
    $pageTemplate = 'List';
}

// получим при возможности данные элемента сущности для страницы добавления / изменения элемента
if($arPath[1] != 'add'){
    $pageData = getData($enity, $elementID);
}else{
    $pageData['ENITY'] = $enity;
    $pageData['LIST_PAGE_URL'] = '/homeworks/3/'.$enity.'/';
}


// получим для страницы редактирования все значения списочного свойства иб
if($pageTemplate == 'Modify'){
    $pageData['ALL_PROCEDURES'] = getData('procedures');
}

if(!empty($pageTemplate)){
    // если шаблон определен, подключим его
    require $_SERVER['DOCUMENT_ROOT'].'/homeworks/3/templates/pageTemplate'.$pageTemplate.'.php';
}

// функция для получения данных элемента (элементов)
function getData($enity, $elementID = false){
    
    $arFilter = [
        'ACTIVE' => 'Y'
    ];
    if(!empty($elementID)){
        $arFilter['ID'] = $elementID;
    }
    
    if($enity == 'doctors'){
        $pageItems = \Bitrix\Iblock\Elements\ElementdoctorsTable::getList([
            'select' => [
                'ID',
                'NAME',
                'PREVIEW_PICTURE',
                'POSITION',
                'EDUCATIONS',
                'RECEPTION',
                'CERTIFICATES',
                'PROCEDURES.ELEMENT',
            ],
            'filter' => $arFilter
        ])->fetchCollection();
    }else if($enity == 'procedures'){
        $pageItems = \Bitrix\Iblock\Elements\ElementproceduresTable::getList([
            'select' => [
                'ID',
                'NAME',
                'COST',
            ],
            'filter' => $arFilter
        ])->fetchCollection();
    }
    
    $pageData = [];
    $pageData['ENITY'] = $enity;
    $pageData['ELEMENT_LABEL'] = ($enity == 'doctors') ? 'доктора' : 'специализацию';
    $pageData['LIST_PAGE_URL'] = '/homeworks/3/'.$enity.'/';
    //$pageData['FORM_ACTION'] = '/homeworks/3/index.php';
    
    foreach ($pageItems as $pageItem){
        
        $pageElement = [
            'ID' => $pageItem->get('ID'),
            'NAME' => $pageItem->get('NAME'),
            'PREVIEW_TEXT' => $pageItem->get('PREVIEW_TEXT'),
            'PREVIEW_PICTURE' => $pageItem->get('PREVIEW_PICTURE'),
            'DETAIL_PAGE_URL'=> '/homeworks/3/'.$enity.'/'.$pageItem->get('ID').'/',
        ];

        if($enity == 'doctors'){
            $pageElement['POSITION'] = $pageItem->getPosition()->getValue();
            $pageElement['EDUCATIONS'] = unserialize($pageItem->getEducations()->getValue());
            $pageElement['EDUCATIONS'] = $pageElement['EDUCATIONS']["TEXT"] ?? $pageElement['EDUCATIONS'];
            $pageElement['RECEPTION'] = $pageItem->getReception()->getValue();
            $pageElement['CERTIFICATES'] = unserialize($pageItem->getCertificates()->getValue());
            $pageElement['CERTIFICATES'] = $pageElement['CERTIFICATES']["TEXT"] ?? $pageElement['CERTIFICATES'];
            
            foreach ($pageItem->getProcedures()->getAll() as $prItem){
                $pageElement['PROCEDURES_VALUES'][] = [
                    'ID' => $prItem->getElement()->getId(),
                    'NAME' => $prItem->getElement()->getName(),
                ];
                $pageElement['PROCEDURES_VALUES_ID'][] = $prItem->getId();
            }
            if(!empty($elementID)){
                $pageData['TITLE'] = 'Врач | '.$pageItem->get('NAME');
            }else{
                $pageData['TITLE'] = 'Список докторов';
            }
            
        }else if($enity == 'procedures'){
            $pageElement['COST'] = $pageItem->getCost()->getValue();

            if(!empty($elementID)){
                $pageData['TITLE'] = 'Информация о процедуре | '.$pageItem->get('NAME');
            }else{
                $pageData['TITLE'] = 'Список процедур';
            }
        }
        $pageData['ELEMENTS'][] = $pageElement;
    }

    if(!empty($pageData)){
        return $pageData;
    }else{
        return false;
    }
}

function setData($values){
    if(!empty($values)){
        $arElementProps = $values;
        $arIblockFields = [
            'IBLOCK_ID' => ($values["ENITY"]=='doctors') ? '16' : '17',
            'NAME' => $values["NAME"],
            'PROPERTY_VALUES' => $arElementProps
        ];
        $objIblockElement = new \CIBlockElement();
        if(!empty($values['ELEMENT_ID'])){
            // update
            if(!$objIblockElement->Update($values['ELEMENT_ID'], $arIblockFields)){
                $response = [
                    'STATUS'=>'ERROR',
                    'TEXT'=>$objIblockElement->LAST_ERROR
                ];
            }else{
                $response = [
                    'STATUS'=>'SUCCESS',
                    'TEXT'=>'Элемент с ID '.$values['ELEMENT_ID'].' успешно обновлен'
                ];
            }
        }else{
            // добавим новый элемент
            if(!$objIblockElement->Add($arIblockFields)){
                $response = [
                    'STATUS'=>'ERROR',
                    'TEXT'=>$objIblockElement->LAST_ERROR
                ];
            }else{
                $response = [
                    'STATUS'=>'SUCCESS',
                    'TEXT'=>'Элемент успешно добавлен'
                ];
            }
        }

        return $response;
    }
}

// ВОПРОС - как с помощью своего класса DoctorsTable получить все значения множественного свойства типа список - PROCEDURES?
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

// На текущий момент в PROCEDURES_DATA информация только по первому значению свойства
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
    

?>
<?php require $_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php';?>