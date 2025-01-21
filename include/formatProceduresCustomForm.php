<?php 
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
$request = $context->getRequest();
$selectedID = $request->get("ID");

\Bitrix\Main\Loader::includeModule('iblock');
$res = \Bitrix\Iblock\ElementTable::getList(array(
    'order' => array('SORT' => 'ASC'), 
    'select' => array('ID', 'NAME', 'IBLOCK_ID', 'SORT', 'TAGS'), 
    'filter' => array('IBLOCK_ID' => 17), 
    // группировка по полю, order должен быть пустой
    'limit' => 1000, 
    'cache' => array( 
        'ttl' => 3600,
        'cache_joins' => true
    ),
));
$items = $res->fetchAll();
?>
<form action="/local/php_interface/ajax/formControllerProcedurCustom.php" id="myForm" method="POST">
    <input type="hidden" name="DOCTOR" value="<?=$request["DOCTOR"]?>">
    <div>
        <div class="ui-ctl ui-ctl-textbox">
            <input type="text" name="NAME" class="ui-ctl-element" placeholder="ФИО">
        </div>
    </div>
    <div>
        <div class="ui-ctl ">
            <span tabindex="0" class="main-ui-date-button"></span>
            <input type="text" value="<?=date("d.m.Y")?> 10:00" name="DATETIME" onclick="BX.calendar({node: this, field: this, bTime: true});" class="main-ui-control-input main-ui-date-input">
        </div>
    </div>
    <div>
        <div class="ui-ctl ui-ctl-after-icon ui-ctl-dropdown">
            <div class="ui-ctl-after ui-ctl-icon-angle"></div>
            <select class="ui-ctl-element" name="PROCEDURE">
                <?php
                foreach($items as $item){?>
                    <option value="<?=$item["ID"]?>" <?= ($item["ID"] == $selectedID) ? 'selected' : ''?>><?=$item["NAME"]?></option>  
                <?}?>
            </select>
        </div>
    </div>
</form>