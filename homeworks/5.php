<?php
// use Bitrix\Iblock\ElementTable;
// use Bitrix\Iblock\Elements;
// use Bitrix\Main\Application;

use Models\ClientsTable as ClientsTable;
use Models\BookingTable as BookingTable;
use Bitrix\Currency\CurrencyLangTable;
require $_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php';
$APPLICATION->SetTitle("Home work | 5 lesson");
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
	 Создать небольшую таблицу в базе данных, и модель к ней, которая будет связана с минимум 2мя инфоблоками.
</p>
<ol>
	<li>Написать собственный компонент.</li>
	<li>В настройках компонента один параметр: выпадающий список, для выбора валюты (из списка валют /bitrix/admin/currencies.php)</li>
	<li>Компонент должен выводить в шаблон курс по умолчанию выбранной валюты (курс брать в том же справочнике /bitrix/admin/currencies.php). </li>
	<li>Разместить компонент на странице /otus/currencies.php</li>
</ol>
<h2>Реализовано:</h2>
<ol>
    <li>Создан компонент</li>
    <li>Добавлено получение наименований валют для параметра компонента</li>
    <li>Добавлен параметр типа список, который принимает значение наименование валюты (множественное поле)</li>
    <li>В классе компонента настроено получение подробной информации о валюте(-тах), которая(-ые) указана(-ы) в параметрах компонента</li>
    <li>Настроен вывод данных в шаблоне</li>
    <li>Создан файл /otus/currencies.php, в нем подключен файл из директории с домашними работами /homeworks/5.php</li>
</ol>

<?$APPLICATION->IncludeComponent(
	"otus:table.hm", 
	".default", 
	array(
		"COMPONENT_TEMPLATE" => ".default",
		"CURRENCY_CODE" => array(
			0 => "BYN",
			1 => "EUR",
			2 => "RUB",
			3 => "UAH",
			4 => "USD",
			5 => "",
		),
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "3600"
	),
	false
);?>

<?php require $_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php';?>