<?php

namespace Events;
use Bitrix\Main\Loader;
use Bitrix\Crm\DealTable;

class CrmHandler
{
    public static $iBlockID = 25;
    public static function OnAfterDealAdd(&$arFields) 
    {
        global $USER;
        
        Loader::requireModule('crm');
        Loader::requireModule('iblock');
        
        if($arFields["BLOCK_HANDLER"] == true){
            return $arFields;
        }

        if(!self::CheckExistElement($arFields['ID'])){
            $el = new \CIBlockElement;
            $PROP = [
                "DEAL" => $arFields['ID'],
                "SUMM" =>  $arFields['OPPORTUNITY'],
                "RESPONSIBLE" => $arFields['ASSIGNED_BY_ID'],
                "CONTACT" => $arFields['CONTACT_ID'],
                "PRODUCT" => $arFields['PRODUCT_ROWS']['0']['PRODUCT_ID'],
            ];
            $arLoadProductArray = Array(
                "MODIFIED_BY"    => $USER->GetID(),
                "IBLOCK_SECTION_ID" => false,
                "IBLOCK_ID"      => self::$iBlockID,
                "PROPERTY_VALUES"=> $PROP,
                "BLOCK_HANDLER" => true,
                "NAME"           => 'Заявка созданная на основании сделки: '.$arFields['TITLE'].' от '. date("d.m.Y H:i:s") ,
            );
            $elementID = $el->Add($arLoadProductArray);
            $deal = new \CCrmDeal(false);
            $arDealParams['UF_CRM_1740222819'] = $elementID;
            $deal->Update($arFields['ID'], $arDealParams, $bCompare = true, $bUpdateSearch = true, $options = array('CURRENT_USER'=> $USER->GetID()));
            return false;
        }
    }

    public static function CheckExistElement($dealID){
        Loader::requireModule('iblock');
        $arSelect = Array("ID", "IBLOCK_ID");
        $arFilter = Array("IBLOCK_ID"=>self::$iBlockID, "PROPERTY_DEAL"=>$dealID);
        $res = \CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize"=>1), $arSelect);
        if($res->GetNextElement()){ 
            return true;
        }else{
            return false;
        }
    }

    public static function OnAfterDealUpdate(&$arFields) 
    {
        global $USER;

        Loader::requireModule('crm');
        Loader::requireModule('iblock');

        if(!empty($arFields['UF_CRM_1740222819'])){
            $el = new \CIBlockElement;
            $PROP = [
                "DEAL" => $arFields['ID'],
                "SUMM" =>  $arFields['OPPORTUNITY'],
                "RESPONSIBLE" => $arFields['ASSIGNED_BY_ID'],
                "CONTACT" => $arFields['CONTACT_ID'],
                "PRODUCT" => $arFields['PRODUCT_ROWS']['0']['PRODUCT_ID'],
            ];
            $arLoadProductArray = Array(
                "MODIFIED_BY"    => $USER->GetID(),
                "PROPERTY_VALUES"=> $PROP,
                "BLOCK_HANDLER" => true,
            );

            $res = $el->Update($arFields['UF_CRM_1740222819'], $arLoadProductArray);
        }
    }

    // public static function OnBeforeDealDelete($enityID){
    //     $elementID = DealTable::GetList([
    //         'filter' => [
    //             'ID' => $enityID
    //         ],
    //         'select' => [
    //             'UF_CRM_1740222819'
    //         ]
    //     ])->fetch()['UF_CRM_1740222819'];
    //     \CIBlockElement::Delete($elementID);
    
    //     return true;
    // }
}
