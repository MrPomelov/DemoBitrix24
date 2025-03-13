<?php
use Bitrix\Main\EventManager;
//пользовательский тип Процедуры запись для свойства инфоблока Врачи
$eventManager = EventManager::getInstance();
$eventManager->AddEventHandler(
    'iblock',
    'OnIblockPropertyBuildList',
    [
        'UserTypes\FormatProceduresCustom',
        'GetUserTypeDescription',
    ]
);

// обработчик событий инфоблока
$eventManager->addEventHandler("iblock", "OnBeforeIBlockElementAdd", ['Events\IblockHandler', 'OnElementBeforeAdd']);
$eventManager->addEventHandler("iblock", "OnAfterIBlockElementAdd", ['Events\IblockHandler', 'OnElementAfterAdd']);
$eventManager->addEventHandler("iblock", "OnAfterIBlockElementUpdate", ['Events\IblockHandler', 'OnElementAfterUpdate']);
$eventManager->addEventHandler("iblock", "OnBeforeIBlockElementDelete", ['Events\IblockHandler', 'OnElementBeforeDelete']);


//обработчик событий CRM
$eventManager->addEventHandler("crm", "OnAfterCrmDealAdd", ['Events\CrmHandler', 'OnAfterDealAdd']);
$eventManager->addEventHandler("crm", "OnAfterCrmDealUpdate", ['Events\CrmHandler', 'OnAfterDealUpdate']);
// $eventManager->addEventHandler("crm", "OnBeforeCrmDealDelete", ['Events\CrmHandler', 'OnBeforeDealDelete']);