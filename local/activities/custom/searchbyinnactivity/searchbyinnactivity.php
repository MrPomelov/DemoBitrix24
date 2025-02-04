<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
use Learning\Integrations\DaData;
use Learning\Helper\Company;
use Bitrix\Bizproc\Activity\BaseActivity;
use Bitrix\Bizproc\FieldType;
use Bitrix\Main\ErrorCollection;
use Bitrix\Main\Localization\Loc;
use Bitrix\Bizproc\Activity\PropertiesDialog;
class CBPSearchByInnActivity extends BaseActivity
{
    // protected static $requiredModules = ["crm"];
    
    /**
     * @see parent::_construct()
     * @param $name string Activity name
     */
    public function __construct($name)
    {
        parent::__construct($name);

        $this->arProperties = [
            'Inn' => '',
            'ID' => '', // потенциально можно было использовать только rootActivity, без нового параметра
        ];

        $this->SetPropertiesTypes([
            'Text' => ['Type' => FieldType::STRING],
        ]);
    }

    /**
     * Return activity file path
     * @return string
     */
    protected static function getFileName(): string
    {
        return __FILE__;
    }

    /**
     * @return ErrorCollection
     */
    protected function internalExecute(): ErrorCollection 
    {
        $errors = parent::internalExecute();
        global $USER;

        $companies = Company::findCompanyByInn($this->Inn);
        if(!empty($companies)){
            // компания с таким ИНН уже существует, запомним ее ID
            echo '<pre>';
            print_r($companies);
            echo '</pre>';
            die();
            $idCompany = $companies[0]['ID'];
            $titleCompany = $companies[0]['TITLE'];
            $textStatus = 'Компания с ИНН '.$this->Inn.' уже существует, это #'.$idCompany.' '.$titleCompany;
        }else{
            // компания с таким ИНН отсутствует, запросим в DaData информацию
            $dadata = new Dadata();
            $dadata->init();
    
            $fields = array("query" => $this->Inn, "count" => 5);
            $response = $dadata->suggest("party", $fields);
            
            if(!empty($response['suggestions'])){
                $dataCompany = [
                    "KPP" => $response['suggestions'][0]['data']['kpp'],
                    "FULL_TITLE" => $response['suggestions'][0]['data']['name']['full_with_opf'],
                    "SHORT_TITLE" => $response['suggestions'][0]['data']['name']['short_with_opf'],
                    "INN" => $response['suggestions'][0]['data']['inn'],
                    "OGRN" => $response['suggestions'][0]['data']['ogrn'],
                    "OKPO" => $response['suggestions'][0]['data']['okpo'],
                    "OKTMO" => $response['suggestions'][0]['data']['oktmo'],
                    "ADRESS" => $response['suggestions'][0]['data']['address']['unrestricted_value'],
                ];
                $idCompany = Company::createCompany($dataCompany);
                if($idCompany){
                    $titleCompany = $dataCompany["FULL_TITLE"];
                    $textStatus = 'Компания с ИНН '.$this->Inn.' усппешно добавлена. #'.$idCompany.' '.$titleCompany;
                }else{
                    $textStatus = 'Упс! Что-то пошло не так. Вероятно, компания или реквизиты не были добавлены :(';
                }
            }else{
                $textStatus = 'По ИНН '.$this->Inn.' компания в DaData не найдена, соответственно, привязывать к элементу нечего.';
            }
        }

        if($idCompany){
            CIBlockElement::SetPropertyValueCode($this->ID, "COMPANY", $idCompany);
            // $el = new CIBlockElement;
            // $PROP = array();
            // $PROP['COMPANY'] = $idCompany;
            // $PROP['INN'] = $this->Inn;
            // $arLoadProductArray = Array(
            //     "MODIFIED_BY"    => $USER->GetID(),
            //     "PROPERTY_VALUES"=> $PROP,
            //     "NAME"           => "Заказ для компании ".$titleCompany." от ".date("d.m.Y"),
            //     "ACTIVE"         => "Y",            // активен
            // );

            // $res = $el->Update($this->ID, $arLoadProductArray);
            // if($res){
            //     $textStatus .= ' Компания успешно привязана к элементу инфоблока Заказы.';
            // }else{
            //     $textStatus .= ' Ошибка обновления элемента инфоблока Заказы: '.$el->LAST_ERROR;
            // }
        }

        $this->preparedProperties['Text'] = $textStatus;
        $this->log($this->preparedProperties['Text']);
         
        // $rootActivity = $this->GetRootActivity(); // получаем объект активити
        // // сохранение полученных результатов работы активити в переменную бизнес процесса
        // $rootActivity->SetVariable("TEST", $this->preparedProperties['Text']); 

        // // получение значения полей документа в активити        
        // $documentType = $rootActivity->getDocumentType();
        // $documentId = $rootActivity->getDocumentId();
        // $documentService = CBPRuntime::GetRuntime(true)->getDocumentService();

        // // поля документа
        // $documentFields =  $documentService->GetDocumentFields($documentType); 
        // foreach ($documentFields as $key => $value) {
        //     if($key == 'UF_CRM_1718872462762'){ // поле номер ИНН
        //         $fieldValue = $documentService->getFieldValue($documentId, $key, $documentType);
        //         $this->log('значение поля Инн:'.' '.$fieldValue);
        //     }

        //     if($key == 'UF_CRM_TEST'){ // поле TEST
        //         $fieldValue = $documentService->getFieldValue($documentId, $key, $documentType);
        //         $this->log('значение поля TEST:'.' '.$fieldValue);
        //     }
        // }
      
        return $errors;
    }

    /**
     * @param PropertiesDialog|null $dialog
     * @return array[]
     */
    public static function getPropertiesDialogMap(?PropertiesDialog $dialog = null): array
    {
        $map = [
            'Inn' => [
                'Name' => Loc::getMessage('SEARCHBYINN_ACTIVITY_FIELD_SUBJECT'),
                'FieldName' => 'inn',
                'Type' => FieldType::STRING,
                'Required' => true,
                'Options' => [],
            ],
            'ID' => [
                'Name' => Loc::getMessage('SEARCHBYINN_ACTIVITY_FIELD_ID'),
                'FieldName' => 'ID',
                'Type' => FieldType::STRING,
                'Required' => true,
                'Options' => [],
            ],
        ];
        return $map;
    }




}