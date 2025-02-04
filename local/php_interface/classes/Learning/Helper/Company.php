<?php
namespace Learning\Helper;
use \Bitrix\Main\Loader;
use Bitrix\Crm\Requisite\EntityLink;
use Bitrix\Crm\EntityRequisite;
use CCrmCompany;

class Company{
    
    static public function createCompany($data){
        Loader::requireModule('crm');
        global $USER;
        $responsible = $USER->GetID();
        $arNewCompany = array(
            "TITLE" => $data['FULL_TITLE'],
            "OPENED" => "Y",
            "COMPANY_TYPE" => "CUSTOMER",
            "ASSIGNED_BY_ID" => $responsible,
            "UF_COMPANY_INN" => $data['INN']
        );
        $company = new \CCrmCompany(false);
        $companyID = $company->Add($arNewCompany);
        
        // добавим реквизиты для компании
        $requisite = new \Bitrix\Crm\EntityRequisite();
        $rs = $requisite->getList([
            "filter" => ["ENTITY_ID" => $companyID, "ENTITY_TYPE_ID" => \CCrmOwnerType::Company]
        ]);
        $reqData = $rs->fetchAll();
        if(!empty($reqData[0]['ENTITY_ID'])){
            //если реквизиты существуют то сначала их удаляем, а потом добавляем, иначе будет несколько инн-ов
            $requisite->deleteByEntity(\CCrmOwnerType::Company, $reqData[0]['ENTITY_ID']);
        }
        $fields['ENTITY_ID'] = $companyID;
        $fields['ENTITY_TYPE_ID'] = \CCrmOwnerType::Company;
        $fields['PRESET_ID'] = 1;
        $fields['NAME'] = $data['SHORT_TITLE'];
        $fields['SORT'] = 500;
        $fields['ACTIVE'] = 'Y';
        $fields['RQ_COMPANY_NAME'] = $data['SHORT_TITLE'];
        $fields['RQ_COMPANY_FULL_NAME'] = $data['FULL_TITLE'];
        $fields['RQ_INN'] = $data['INN']; //инн
        $fields['RQ_KPP'] = $data['KPP'];  //кпп
        $fields['RQ_OGRN'] = $data['OGRN']; //огрн
        $fields['RQ_OKTMO'] = $data['OKTMO']; //октмо

        $res = $requisite->add($fields);

        return $companyID;
    }
    static public function findCompanyByInn($inn){
        Loader::requireModule('crm');

        // получение название компаний по значению инн в реквизитах
        if (\Bitrix\Main\Loader::includeModule('socialservices')) {
            $client = new \Bitrix\socialservices\properties\Client;
            $arRequisite = $client->getByInn($inn);
            $arRequisiteNameCompany = $arRequisite['NAME'];
        }
         
        // получение команий по полю ИНН
        $dbCompany=\CCrmCompany::GetListEx(
            [],
            [
            'CHECK_PERMISSIONS'=>'N',
            [
                "LOGIC"=>"OR",
                ["TITLE"=>$arRequisiteNameCompany],
                ["UF_COMPANY_INN"=>$inn],
            ],
            ],
            false,
            false,
            [
                '*',
            ]
        );
         
        while($arCompany=$dbCompany->fetch()){
            $companyList[]=$arCompany;
        }

        return $companyList;
    }
}