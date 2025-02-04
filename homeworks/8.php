<?php
// use Bitrix\Iblock\ElementTable;
// use Bitrix\Iblock\Elements;
// use Bitrix\Main\Application;

use Models\ClientsTable as ClientsTable;
use Models\BookingTable as BookingTable;
use Bitrix\Currency\CurrencyLangTable;
require $_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php';
$APPLICATION->SetTitle("Home work | 8 lesson | Бизнес-процесс для обработки элементов инфоблока при создании");
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
<h2>Бизнес-процесс для обработки элементов инфоблока при создании:</h2>
<p>
Создать активити для бизнес-процесса, который при создании элементов инфоблока, будет брать значение из свойства элемента инфоблока и проверять его в сервисе Dadata, на основе полученных из сервиса данных БП будет создавать компанию.
</p>
<ol>
    <li>создать инфоблок у элементов которого будут свойства: "Сумма, Заказчик ИНН, Заказчик, Вид работ";</li>
    <li>создать БП, который будет запускаться при создании элемента инфоблока;</li>
    <li>создать активити, который будет проверять значение свойства Заказчик ИНН в сервисе Dadata, на основе полученных из сервиса данных БП будет создавать компанию и обновлять свойство Заказчик элемента, добавляя в него ID компании</li>
</ol>
<h2>Реализовано:</h2>
<ol>
    <li>Создан инфоблок Заказы <a href="https://cs23196.tw1.ru/bitrix/admin/iblock_list_admin.php?IBLOCK_ID=24&type=bitrix_processes&lang=ru&find_section_section=0&SECTION_ID=0&apply_filter=Y">Перейти</a> или в публичном пространстве <a href="https://cs23196.tw1.ru/bizproc/processes/24/view/0/?list_section_id=">Перейти</a></li>
    <li>Подключен класс сервиса DaData</li>
    <li>Добавлено пользовательское поле UF_COMPANY_INN (предполагалось использовать привязку только по нему, но затем реализовал с реквизитами туже логику)</li>
    <li>Разработан класс для работы с компаниями и реквизитами (для использования в активити: реализует поиск компании и реквизитов по ИНН, добавление компании и реквизитов по набору данных)</li>
    <li>Создан бизнес-процесс для списка Заказы, который при создании нового элемента инфоблока проверяет существование компании в случае отсутствия запрашивает данные в сервисе DaData после чего создает компанию и привязывает ее к элементу инфоблока Заказы. Просмотреть можно <a href="https://cs23196.tw1.ru/bizproc/processes/24/bp_edit/21/">здесь</a></li>
</ol>


<?php require $_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php';?>