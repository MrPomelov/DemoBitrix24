<?php
// use Bitrix\Iblock\ElementTable;
// use Bitrix\Iblock\Elements;
// use Bitrix\Main\Application;

use Models\ClientsTable as ClientsTable;
use Models\BookingTable as BookingTable;
use Bitrix\Currency\CurrencyLangTable;
require $_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php';
$APPLICATION->SetTitle("Home work | 6 lesson | Написание своего модуля");
//Loader::includeModule('iblock');
global $USER;
?><style>
    span.class{
        color:green;
    }
    span.method{
        color:orange;
    }
</style>
<h2>Описание/Пошаговая инструкция выполнения домашнего задания:</h2>
<p>
    Разработка модуля для расширения стандартного модуля CRM
</p>
<ol>
    <li>создать собственный модуль;</li>
    <li>научится работать с внешними таблицами;</li>
    <li>сделать собственный компонент как элемент модуля;</li>
    <li>работа со стандартными GRID компонентами.</li>
</ol>
<h2>Реализовано:</h2>
<ol>
    <li>Создан собственный модуль</li>
    <li>Создан компонент, который устанавливается и удаляется через модуль</li>
    <li>Реализована работа со стандартными GRID компонентами.</li>
</ol>

<h2>Для проверки выполненного ДЗ:</h2>
<ol>
    <li>Перейти на страницу списка модулей для его установки: <a href="https://cs23196.tw1.ru/bitrix/admin/partner_modules.php?lang=ru">Перейти</a></li>
    <li>Проверить появившийся компонент в файловой структуре проекта: <a href="https://cs23196.tw1.ru/bitrix/admin/fileman_admin.php?PAGEN_1=1&SIZEN_1=20&lang=ru&site=s1&path=%2Fbitrix%2Fcomponents%2Fotus.homework%2Fotus.grid&show_perms_for=0&check_for_file=Y&fu_action=">Перейти</a></li>
    <li>Проверить созданный инфоблок "Тестовый универсальный список" можно <a href="https://cs23196.tw1.ru/bitrix/admin/iblock_list_admin.php?IBLOCK_ID=22&type=lists&lang=ru&find_section_section=0">здесь</a></li>
    <li>Проверить работу компонента в табах сделки можно <a href="https://cs23196.tw1.ru/crm/deal/details/9/">здесь</a></li>
</ol>

<?php require $_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php';?>