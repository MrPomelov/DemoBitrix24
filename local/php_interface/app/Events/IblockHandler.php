<?php

namespace Events;

use Bitrix\Main\EventManager;
use Bitrix\Main\Loader;

class IblockHandler
{
    public static $restrictHour = 21;
    public static $iBlockID = 25;

    // перед созданием заявки в инфоблоке, создадим сделку и ее ID укажем в свойстве создаваемого элемента ИБ
    public static function OnElementBeforeAdd(&$arFields)
    {
        if ($arFields["IBLOCK_ID"] != self::$iBlockID || $arFields["BLOCK_HANDLER"] == true) 
            return $arFields;

        $arFields = self::saveDeal($arFields);
    }

    // после создания заявки в элементе инфоблока, обновим связанную сделку - сохраним в пользовательском свойстве ID элемента заявки
    public static function OnElementAfterAdd(&$arFields)
    {
        if ($arFields["IBLOCK_ID"] != self::$iBlockID || $arFields["BLOCK_HANDLER"] == true)
            return $arFields;

        Loader::requireModule('crm');
        global $USER;
        
        $deal = new \CCrmDeal(false);
        if($dealID = $arFields['PROPERTY_VALUES']['81']['n0']['VALUE']){
            $arDealParams['UF_CRM_1740222819'] = $arFields['ID'];
            $deal->Update($dealID, $arDealParams, $bCompare = true, $bUpdateSearch = true, $options = array('CURRENT_USER'=> $USER->GetID()));
        }
    }

    // при обновлении элемента заявки вызывается универсальный метод для обновления сделки
    public static function OnElementAfterUpdate(&$arFields)
    {
        if ($arFields["IBLOCK_ID"] != self::$iBlockID || $arFields["BLOCK_HANDLER"] == true)
            return $arFields;
        $arFields = self::SaveDeal($arFields);
    }

    public static function OnElementBeforeDelete($enityID)
    {
        $db_props = \CIBlockElement::GetProperty(self::$iBlockID, $enityID, array("sort" => "asc"), Array("CODE"=>"DEAL"));
        if($ar_props = $db_props->Fetch())
            $dealID = IntVal($ar_props["VALUE"]);

        if($dealID){
            $bCheckRight = true;
            $entityObject = new \CCrmDeal( $bCheckRight );

            $deleteResult = $entityObject->Delete(
                $dealID,
                [
                    'CURRENT_USER' => \CCrmSecurityHelper::GetCurrentUserID(),
                    'PROCESS_BIZPROC' => true,
                    'ENABLE_DEFERRED_MODE' => \Bitrix\Crm\Settings\DealSettings::getCurrent()->isDeferredCleaningEnabled(),
                    'REGISTER_STATISTICS' => true,
                ]
            );

            if ( !$deleteResult )
            {
                // Операция не удалась
                var_dump($entityObject->LAST_ERROR);
            }
        }
    }

    // метод для создания и обновления полей сделки из полей элемента инфоблока заявки
    public static function SaveDeal($arFields){
        global $USER;
        Loader::requireModule('crm');

        $arСomparison = [
            '84' => 'CONTACT_IDS',
            '83' => 'CURRENT_USER',
            '82' => 'OPPORTUNITY',
            '81' => 'DEAL',
            '85' => 'PRODUCT_ID',
        ];
        
        foreach ($arFields["PROPERTY_VALUES"] as $key => $property){
            if($arСomparison[$key] == 'ASSIGNED_BY_ID'){
                $arDealParams[$arСomparison[$key]] = [$property['n0']];
            }else if($arСomparison[$key] != 'DEAL'){
                $arDealParams[$arСomparison[$key]] = $property['n0'];
            }
        }

        if(!empty($arDealParams)){
            $arDealParams['TITLE'] = 'Сделка по заявке: '.$arFields["NAME"] .' от '. date("d.m.Y H:i:s");
            $arDealParams['SOURCE_ID'] = 'SELF';
            $arDealParams['BLOCK_HANDLER'] = true;
            $arDealParams['UF_CRM_1740222819'] = $arFields['ID'];
            $products[] = [
                'PRODUCT_ID' => $arFields['PROPERTY_VALUES']['85']['n0']['VALUE'],
                'MEASURE_CODE' => 796,
                'MEASURE_NAME' => 'шт',
                'PRICE' => $arFields['PROPERTY_VALUES']['82']['n0']['VALUE'] ? $arFields['PROPERTY_VALUES']['82']['n0']['VALUE'] * 10 : '',
                'OPPORTUNITY_ACCOUNT'=>$arFields['PROPERTY_VALUES']['82']['n0']['VALUE'] ? $arFields['PROPERTY_VALUES']['82']['n0']['VALUE'] * 10 : '',
                //'DISCOUNT_TYPE_ID' => 2,
                // 'DISCOUNT_RATE' => ,
                'DISCOUNT_SUM' => $arFields['PROPERTY_VALUES']['82']['n0']['VALUE'],
                'QUANTITY' => 1,
                'ACCOUNT_CURRENCY_ID' => 'RUB',
                'TAX_VALUE_ACCOUNT' => 0,
            ];
            
            $deal = new \CCrmDeal(false);
            if($dealID = $arFields['PROPERTY_VALUES']['81']['n0']['VALUE']){
                $deal->Update($dealID, $arDealParams, $bCompare = true, $bUpdateSearch = true, $options = array('CURRENT_USER'=>1));
                $arParam = [
                    'MESSAGE' => 'Сделка обновлена',
                    'LINK' => [
                        "NAME" => "Обновлена сделка №".$dealID,
                        "DESC" => "Сделка обновлена на основании изменения заявки (элемента ИБ)",
                        "LINK" => "https://cs23196.tw1.ru/crm/deal/details/" . $dealID . "/",
                    ],
                ];
                self::SendNotify($arParam);

            }else if($dealID = $deal->Add($arDealParams)){
                $arFields['PROPERTY_VALUES']['81']['n0']['VALUE'] = $dealID;
                $arParam = [
                    'MESSAGE' => 'Создана новая сделка!',
                    'LINK' => [
                        "NAME" => "Создана сделка №".$dealID,
                        "DESC" => "Сделка создана на основании заявки (элемента ИБ)",
                        "LINK" => "https://cs23196.tw1.ru/crm/deal/details/" . $dealID . "/",
                    ],
                ];
                self::SendNotify($arParam);
            }
            \CAllCrmDeal::SaveProductRows($dealID, $products, true, true, false);
        }
        return $arFields;
    }

    // метод отправки уведомлений. Вызывается при работе заявками
    public static function SendNotify($arParam){
        if (!Loader::includeModule('im')) // отправляем пользователю сообщение в чат
            return;

        global $USER;

        $attach = new \CIMMessageParamAttach(null, "#95c255");
        $attach->AddLink($arParam['LINK']);
        
        $arMessageFields = array(
            "TO_USER_ID" => $USER->GetID(),
            "FROM_USER_ID" => '1',
            "NOTIFY_TYPE" => IM_NOTIFY_SYSTEM,
            "MESSAGE" => $arParam['MESSAGE'],
            "ATTACH" => Array(
                $attach
            )
        );
        
        $mess = \CIMNotify::Add($arMessageFields);
    }
}