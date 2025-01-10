<?php
use \Bitrix\Main\Loader;
use \Bitrix\Main\Application;
use \Bitrix\Currency\CurrencyLangTable;
use \Bitrix\Currency\CurrencyTable;
use Bitrix\Main\Grid\Options as GridOptions;
use Bitrix\Main\UI\PageNavigation;
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

class ExampleCompSimple extends CBitrixComponent {
    private $_request;

    /**
     * Проверка наличия модулей требуемых для работы компонента
     * @return bool
     * @throws Exception
     */
    private function checkModules() {
        if (   !Loader::includeModule('iblock')
            || !Loader::includeModule('crm')
        ) {
            throw new \Exception('Не загружены модули необходимые для работы модуля');
        }

        return true;
    }

    /**
     * Обертка над глобальной переменной
     * @return CAllMain|CMain
     */
    private function app() {
        global $APPLICATION;
        return $APPLICATION;
    }

    public function executeComponent() {
        $this->checkModules();
        $this->_request = Application::getInstance()->getContext()->getRequest();
        
        $this->arResult = $this->getData();

        $this->includeComponentTemplate();
    }

    private function getData() {
        $arResult['LIST_ID'] = 'example_list';

        $grid_options = new GridOptions($arResult['LIST_ID']);
        $sort = $grid_options->GetSorting(['sort' => ['DATE_CREATE' => 'DESC'], 'vars' => ['by' => 'by', 'order' => 'order']]);
        $nav_params = $grid_options->GetNavParams();
        $nav_params['nPageSize'] = 3;
        $arResult['NAV'] = new PageNavigation($arResult['LIST_ID']);
        $arResult['NAV']->allowAllRecords(true)
            ->setPageSize($nav_params['nPageSize'])
            ->initFromUri();
        if ($arResult['NAV']->allRecordsShown()) {
            $nav_params = false;
        } else {
            $nav_params['iNumPage'] = $arResult['NAV']->getCurrentPage();
        }

        $arResult['FILTER'] = [
            ['id' => 'NAME', 'name' => 'Название', 'type'=>'text', 'default' => true],
            ['id' => 'DATE_CREATE', 'name' => 'Дата создания', 'type'=>'date', 'default' => true],
        ];
        
        $filterOption = new Bitrix\Main\UI\Filter\Options($arResult['LIST_ID']);
        $filterData = $filterOption->getFilter([]);

        foreach ($filterData as $k => $v) {
            $filterData['NAME'] = "%".$filterData['FIND']."%";
        }
        
        $filterData['IBLOCK_CODE'] = "test_list";
        $filterData['ACTIVE'] = "Y";
        $arResult['FILTERDATA'] = $filterData;

        $arResult['COLUMNS'] = [];
        $arResult['COLUMNS'][] = ['id' => 'ID', 'name' => 'ID', 'sort' => 'ID', 'default' => true];
        $arResult['COLUMNS'][] = ['id' => 'NAME', 'name' => 'Название', 'sort' => 'NAME', 'default' => true];
        $arResult['COLUMNS'][] = ['id' => 'DATE_CREATE', 'name' => 'Создано', 'sort' => 'DATE_CREATE', 'default' => true];

        $res = \CIBlockElement::GetList($sort['sort'], $filterData, false, $nav_params,
            ["ID", "IBLOCK_ID", "NAME", "CODE", "PROPERTY_MANAGER", "PROPERTY_AIM_OF_REQUEST", "DATE_CREATE",
                "PROPERTY_LAST_NAME", "PROPERTY_E_MAIL", "PROPERTY_FIRST_NAME", "PROPERTY_STATUS_OF_REQUEST"]
        );
        $arResult['NAV']->setRecordCount($res->selectedRowsCount());
        while($row = $res->GetNext()) {
            $arResult['LIST'][] = [
                'data' => [
                    "ID" => $row['ID'],
                    "NAME" => $row['NAME'],
                    "DATE_CREATE" => $row['DATE_CREATE'],
                ],
                'actions' => [
                    [
                        'text'    => 'Просмотр',
                        'default' => true,
                        'onclick' => 'document.location.href="https://cs23196.tw1.ru/bitrix/admin/iblock_element_edit.php?IBLOCK_ID=19&type=lists&lang=ru&ID='.$row['ID'].'&find_section_section=0&WF=Y"'
                    ]
                ]
            ];
        }
        return $arResult;
    }
}