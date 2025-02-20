<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php"); 
$request = $context->getRequest();

if(CModule::IncludeModule('timeman') && $userID = $USER->GetID()){
    $TimemanUser = new CTimeManUser($userID);
    $userSettings = $TimemanUser->GetSettings();
    
    switch($request["action"]){
        case 'DAY_START':
            if($TimemanUser->isDayOpenedToday()){
                $TimemanUser->reopenDay();
                echo '<p>Рабочий день начат был сегодня ранее, возобновляем</p><p>Для проверки вы можете открыть страницу в новой вкладке и увидеть изменение статуса рабочего дня в панели.</p><p>Или воспользуйтесь кнопкой для проверки статуса ниже</p>';
            }else{
                $TimemanUser->openDay();
                echo '<p>Рабочий день успешно начат</p><p>Для проверки вы можете открыть страницу в новой вкладке и увидеть изменение статуса рабочего дня в панели.</p><p>Или воспользуйтесь кнопкой для проверки статуса ниже</p>';
            }
            break;
        case 'DAY_PAUSE':
            $TimemanUser->pauseDay();
            echo '<p>Рабочий день установлен на паузу</p><p>Для проверки вы можете открыть страницу в новой вкладке и увидеть изменение статуса рабочего дня в панели.</p><p>Или воспользуйтесь кнопкой для проверки статуса ниже</p>';
            break;
        case 'DAY_END':
            $TimemanUser->closeDay();
            echo 'Рабочий день завершен';
            break;
        case 'CHECK_DAY_STATUS':
            if($userSettings["UF_TIMEMAN"]){
                $status;
                if($TimemanUser->isDayOpenedToday()){$status = '<p>Рабочий день сегодня уже начинался</p>';};
                if($TimemanUser->isDayPaused()){$status .= '<p>установлен перерыв</p>';};
                if($TimemanUser->isDayOpen()){$status .='<p>рабочий день идет</p>';};
                if($TimemanUser->isDayExpired()){$status .='<p>рабочий день ИСТЕК</p>';};
                echo $status;
            }else{
                echo '<p>Не удалось получить данные рабочего дня у пользователя</p>';
            }
            break;
    }
    
}
