<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?if(!empty($arResult['ITEMS'])){?>
<table class="main-grid-table">
    <thead class="main-grid-header">
        <tr class="main-grid-row-head">
            <th class="main-grid-cell-head main-grid-cell-left">#</th>
            <th class="main-grid-cell-head main-grid-cell-left">Код валюты</th>
            <th class="main-grid-cell-head main-grid-cell-left">Курс валюты по-умолчанию.</th>
            <th class="main-grid-cell-head main-grid-cell-left">Количество единиц валюты по-умолчанию,</th>
            <th class="main-grid-cell-head main-grid-cell-left">Текущий курс валюты.</th>
            <th class="main-grid-cell-head main-grid-cell-left">Порядок сортировки</th>
            <th class="main-grid-cell-head main-grid-cell-left">Трехзначный цифровой код валюты.</th>
        </tr>
    </thead>
    <tbody>
    <?foreach($arResult['ITEMS'] as $key => $item){?>
        <tr class="main-grid-row main-grid-row-body">
            <td class="main-grid-cell main-grid-cell-left"><?=$num?></td>
            <td class="main-grid-cell main-grid-cell-left"><?=$item['CURRENCY']?></td>
            <td class="main-grid-cell main-grid-cell-left"><?=$item['AMOUNT']?></td>
            <td class="main-grid-cell main-grid-cell-left"><?=$item['AMOUNT_CNT']?></td>
            <td class="main-grid-cell main-grid-cell-left"><?=$item['CURRENT_BASE_RATE']?></td>
            <td class="main-grid-cell main-grid-cell-left"><?=$item['SORT']?></td>
            <td class="main-grid-cell main-grid-cell-left"><?=$item['NUMCODE']?></td>
        </tr>
        <?
    }?>
    </tbody>
</table>
<?}?>