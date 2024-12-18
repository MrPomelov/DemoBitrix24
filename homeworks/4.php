<?php
// use Bitrix\Iblock\ElementTable;
// use Bitrix\Iblock\Elements;
// use Bitrix\Main\Application;

use Models\ClientsTable as ClientsTable;
use Models\BookingTable as BookingTable;

require $_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php';
$APPLICATION->SetTitle("Home work | 4 lesson");
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

<h2>Описание/Пошаговая инструкция выполнения домашнего задания:</h2>
<p>Создать небольшую таблицу в базе данных, и модель к ней, которая будет связана с минимум 2мя инфоблоками.</p>
<ol>
    <li>создайте таблицу в базе данных со следующими типами данных: числовой, строковый, связываемый;</li>
    <li>создайте 2-3 инфоблока, опишите для них модели данных и свяжите позиции по первичному ключу с созданной таблицей в БД;</li>
    <li>создайте тестовую страницу для выборки и вывода данных в любом формате. При выборке из таблицы БД, выберете также свойства из элементов инфоблоков.</li>
    <li>установить кэширование для выборки;</li>
    <li>использовать в запросах registerRuntimeField;</li>\
    <li>ссылка на репозиторий GitHub.</li>
</ol>
<br>
<h2>Реализовано:</h2>
<ol>
    <li>Создана таблица Clients</li>
    <li>Создана таблица Booking</li>
    <li>Созданы модели для таблиц Clients и Booking</li>
    <li>Настроены связи между таблицами</li>
    <li>Настроен вывод данных из:
        <ul>
            <li>Таблицы Clients</li>
            <li>Таблицы Booking</li>
            <li>Карточки контакта в CRM</li>
            <li>Инфоблока врачи</li>
            <li>Инфоблока процедуры</li>
        </ul>
    </li>
</ol>

<h2>Вопросы / проблемы:</h2>
<ul>
    <li>Если получать данные из таблицы Clients через ее ORM-модель, то удается корректно получить полный набор данных по связанному контакту (crm). Однако если же обратиться к ORM модели Clients через получение данных таблицы Booking, то данных нет, приводит к ошибке. Почему?</li>
    <li>Для получения данных воспользовался ORM списков, подготовленными ранее. Однако, насколько понимаю, они позволяют работать только со свойствами элемента ИБ (видимо из-за того, что унаследованы от кастомного класса). Что нужно сделать, чтобы при обращении к данным моделям получить не только свойства но и поля элементов?</li>
    <li>Почему при подключении класса ClientsTable автозагрузка пытается подключить несуществующий класс Clients.php? (лог видно на странице)</li>
    <li>Что за подключаемые классы, к наименованию которых добавляется префикс "EO_"? Также видно на странице</li>
</ul>
<?php 

    // $clientsCollection = ClientsTable::getList([
    //     'select' => [
    //         'id',
    //         'name',
    //         'sex',
    //         'birth_date',
    //         'inn',
    //         'contact_id',
    //         'CONTACT.*'
    //     ],
    // ])->fetchCollection();

    // foreach ($clientsCollection as $key=>$record){
    //     echo $record->getId().' '.$record->getName().' '.$record->getContact_id();
    //     echo $record->getContact()->getPost().' ';
    //     echo $record->getContact()->getCompanyId().' ';
    //     echo '</br>';
    // }

    $bookingCollection = BookingTable::getList([
        'select' => [
            'id',
            'date',
            'client_id',
            'CLIENT.*', // из своей таблицы контакты
            'doctor_id',
            'DOCTOR.*', // из иб Врачи
            'procedur_id',
            'PROCEDUR.*' // из иб процедуры
        ],
    ])->fetchCollection();
    ?>
    <h2>Таблица Бронирований</h2>
    <p>Вывод данных из разных источников</p>
    <table class="main-grid-table">
        <thead class="main-grid-header">
            <tr class="main-grid-row-head">
                <th class="main-grid-cell-head main-grid-cell-left">ID<br> (таблица бронирования)</th>
                <th class="main-grid-cell-head main-grid-cell-left">Дата записи<br>(таблица бронирования)</th>
                <th class="main-grid-cell-head main-grid-cell-left">ФИО клиента<br>(таблица клиенты)</th>
                <th class="main-grid-cell-head main-grid-cell-left">ИНН клиента<br>(таблица клиенты)</th>
                <th class="main-grid-cell-head main-grid-cell-left">Должность клиента<br>(из контакта, crm)</th>
                <th class="main-grid-cell-head main-grid-cell-left">Должность Врача<br>(из иб врачей)</th>
                <th class="main-grid-cell-head main-grid-cell-left">Стоимость<br>(из иб процедур)</th>
            </tr>
        </thead>
        <tbody>
    <?
    foreach ($bookingCollection as $key=>$record){
        ?>
        <tr class="main-grid-row main-grid-row-body">
            <td class="main-grid-cell main-grid-cell-left"><?=$record->getId()?></td>
            <td class="main-grid-cell main-grid-cell-left"><?=$record->getDate()?></td>
            <td class="main-grid-cell main-grid-cell-left"><?=$record->getClient()->getName()?></td>
            <td class="main-grid-cell main-grid-cell-left"><?=$record->getClient()->getInn()?></td>
            <!-- <td class="main-grid-cell main-grid-cell-left"><?//=$record->getClient()->getContact()->getPost()?></td> -->
            <td class="main-grid-cell main-grid-cell-left"><?=$record->getClient()->getContact_id()?></td>
            <td class="main-grid-cell main-grid-cell-left"><?=$record->getDoctor()->getPosition()?></td>
            <td class="main-grid-cell main-grid-cell-left"><?=$record->getProcedur()->getCost()?></td>
        </tr>
        <?
    }
    ?>
        </tbody>
    </table>
    <?

?>
<?php require $_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php';?>