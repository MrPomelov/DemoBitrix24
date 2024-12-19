<?php
use \Bitrix\Main\Loader;
use \Bitrix\Main\Application;
use \Bitrix\Currency\CurrencyLangTable;
use \Bitrix\Currency\CurrencyTable;
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

class ExampleCompSimple extends CBitrixComponent {
    private $_request;

    /**
     * Проверка наличия модулей требуемых для работы компонента
     * @return bool
     * @throws Exception
     */
    private function _checkModules() {
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
    private function _app() {
        global $APPLICATION;
        return $APPLICATION;
    }

    /**
     * Обертка над глобальной переменной
     * @return CAllUser|CUser
     */
    private function _user() {
        global $USER;
        return $USER;
    }

    /**
     * Подготовка параметров компонента
     * @param $arParams
     * @return mixed
     */
    public function onPrepareComponentParams($arParams) {
        // тут пишем логику обработки параметров, дополнение параметрами по умолчанию
        // и прочие нужные вещи
        // if(empty($arParams['CURRENCY_CODE'])){
        //     throw new \Exception('Не выбрана валюта в настройках компонента');
        // }
        return $arParams;
    }

    /**
     * Точка входа в компонент
     * Должна содержать только последовательность вызовов вспомогательых ф-ий и минимум логики
     * всю логику стараемся разносить по классам и методам 
     */
    public function executeComponent() {
        $this->_checkModules();
        $this->_request = Application::getInstance()->getContext()->getRequest();
        
        $this->arResult['ITEMS'] = $this->_getCurrency();

        $this->includeComponentTemplate();
    }

    private function _getCurrency() {
        if(empty($this->arParams['CURRENCY_CODE'])){
            return false;
        }
        
        if (!Loader::includeModule('currency')) { return;}
        $arCurrency = [];
        $rows = CurrencyTable::query()
            ->setSelect([
                'CURRENCY',
                'AMOUNT',
                'AMOUNT_CNT',
                'CURRENT_BASE_RATE',
                'SORT',
                'DATE_UPDATE',
                'BASE',
                'NUMCODE',
                'CREATED_BY',
                'MODIFIED_BY',
            ])->setFilter([
                'CURRENCY' => $this->arParams['CURRENCY_CODE']
            ])
            ->exec();
        foreach ($rows as $row) {
            $arCurrency[$row['CURRENCY']] = $row;
        }
        return $arCurrency;
    }
    
}