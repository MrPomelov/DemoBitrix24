<?php
use Bitrix\Main\Diag\Debug;
use Bitrix\Main\Loader;
use Bitrix\Iblock\ElementTable;
use Bitrix\Main\Application;
use Bitrix\Crm\DealTable;

use Learning\Diagnostic\Helper;

require $_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php';
$APPLICATION->SetTitle("Home work | second-lesson");
Loader::includeModule('iblock');
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

<h2>Описание/Пошаговая инструкция выполнения домашнего задания:</h2>
<ol>
    <li>Создать php файл /otus/debug.php. В нем написать код, который, при обращении к нему по HTTP, будет записывать в файл текущие дату и время.</li>
    <li>Написать и подключить собственный класс системного логгера, который будет переопределять форматирование строк лога - добавлять слово OTUS в каждую строку.</li>
</ol>
<br>
<h2>Реализовано:</h2>
<ol>
    <li>Подготовлен автозагрузчик классов /local/php_interface/classes/autoload.php;</li>    
    <li>Создан init.php, подключен автозагрузчик классов</li>
    <li>Создан метод <span class="class">Helper::</span><span class="method">customDebugWrapper</span> котороый отображает на странице и записывает в файл информацию</li>
    <li>Класс <span class="class">Helper::</span><span class="method">customDebugWrapper</span> на текущей странице используется для:
        <ul>
            <li>результата сортировки массива</li>
            <li>отладки sql-запроса получения элментов инфоблоков</li>
            <li>отладки sql-запроса получения информации о сделках</li>
        </ul>
    </li>
    <li>Функция быстрой сортировки перенесена также в класс <span class="class">Helper::</span><span class="method">quickSort</span></li>
    <li>Создан файл /otus/debug.php который подключает файл с домашним заданием /hopmeworks/2.php</li>
    <li>Написан и подключен класс системного логгера <span class="class">Learning\Diagnostic\LearningFileExceptionHandlerLog::</span><span class="method">write</span>, который переопределяет форматирование строк лога - добавляет OTUS в каждую строку. <br>Формируется по пути: <a href="/logs/learning-exceptions__2024-11-16.log" target="_blank">/logs/learning-exceptions__2024-11-16.log</a> (файл целенаправлено не закрыт для доступа по http)</li>
</ol>
<?php
// Пример 1. Логирование состояния массива
$data = [3, 1, 4, 1, 5, 9, 2, 6, 5, 3, 5];

// кастомная обертка и на страницу выведет лог и в файл запишет
Helper::customDebugWrapper($data, 'OriginalData', 'start');
$data = Helper::quickSort($data);
Helper::customDebugWrapper($data, 'SortedlData', 'end');


// Пример 2. Логирование sql-запроса при выборе элементов иб
Debug::startTimeLabel('sql-query-iblock');
Application::getConnection()->startTracker();
$query = ElementTable::getList([
    'filter' => [
        'IBLOCK_ID' => 1,
    ],
    'select' => [
        'ID'
    ],
]);
Application::getConnection()->stopTracker();
Debug::endTimeLabel('sql-query-iblock');
Helper::customDebugWrapper($query->getTrackerQuery()->getSql(), 'sql-query-iblock');
// Bitrix\Main\Diag\Helper::getBackTrace($limit = 0, $options = null);
// почему ничего не выводит getBackTrace?


// Пример 3. Логирование sql-запроса при выборе элементов иб
Debug::startTimeLabel('sql-query-deals');
Application::getConnection()->startTracker(true);
$arSelect = ['ID'];
$arFilter = [
    'CATEGORY_ID' => 0,
    '<=DATE_CREATE' => \Bitrix\Main\Type\DateTime::createFromTimestamp(strtotime("-1 month")),
];
$arDeals = DealTable::getList([
    'filter'=>$arFilter,
    'select'=>$arSelect,
]);
Application::getConnection()->stopTracker();
Debug::endTimeLabel('sql-query-deals');
Helper::customDebugWrapper($arDeals->getTrackerQuery()->getSql(), 'sql-query-deals');

require $_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php';