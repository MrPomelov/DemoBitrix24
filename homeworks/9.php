<?php
// use Bitrix\Iblock\ElementTable;
// use Bitrix\Iblock\Elements;
// use Bitrix\Main\Application;

use Models\ClientsTable as ClientsTable;
use Models\BookingTable as BookingTable;
use Bitrix\Currency\CurrencyLangTable;
require $_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php';
$APPLICATION->SetTitle("Home work | 9 lesson | Модификация интерфейса на стороне клиента");
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
<h2>Модификация интерфейса на стороне клиента</h2>
<p>
закрепить способы подключения произвольного JS кода, без редактирования шаблона;
научиться отслеживать системные JS события и реагировать на них.
</p>
<p>
    При нажатии на кнопку “Начать рабочий день” (в правом верхнем углу экрана) показывать модальное окно с произвольным текстом. В модальном окне показывать кнопку. Рабочий день стартовать только при нажатии на эту кнопку, а при закрытии окна, отменять начало рабочего дня.
</p>
<h2>Реализовано:</h2>
<ol>
    <li>Написано js расширение</li>
    <li>Определено событие: открытия модального окна по управлению рабочим днем</li>
    <li>Расширение подключено через \Bitrix\Main\UI\Extension::load в Init</li>
    <li>при нажатии на кнопку “Начать рабочий” показывается модальное окно, стандартное при этом скрыто;</li>
    <li>при нажатии на одну кнопку в модальном окне - стартует рабочий день, на другую - останавливается (перерыв), на третью - происходит запрос текущего статуса рабочего дня.</li>
    <li>при закрытии попапа рабочий день завершается</li>
</ol>


<?php require $_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php';?>