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