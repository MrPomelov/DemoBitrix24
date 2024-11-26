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

<?php require $_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php';?>