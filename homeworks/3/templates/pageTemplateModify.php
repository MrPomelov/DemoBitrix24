<?php
$APPLICATION->SetTitle($pageData['TITLE'] ? $pageData['TITLE'] : 'Страница добавления');
$pageItem = $pageData["ELEMENTS"][0];
$procedureList = $pageData["ALL_PROCEDURES"]["ELEMENTS"];
?>
<div class="wrapper">
    <div class="page detail-container">
        <form action="<?=$pageData['LIST_PAGE_URL']?>" method="POST" name="main-form" id="mainForm">
            <input type="hidden" name="ELEMENT_ID" value="<?=$pageItem['ID']?>">
            <input type="hidden" name="ENITY" value="<?=$pageData['ENITY']?>">
            <div class="item item--<?=$pageData['ENITY']?>">
                <?if(!empty($pageItem['PREVIEW_PICTURE'])){
                    $file = CFile::ResizeImageGet($pageItem['PREVIEW_PICTURE'], array('width'=>150, 'height'=>150), BX_RESIZE_IMAGE_PROPORTIONAL, true);                
                    $img = '<img src="'.$file['src'].'" width="'.$file['width'].'" height="'.$file['height'].'" />';
                    ?>
                    <div class="item__image">
                        <?=$img?>
                        <input type="file" name="PREVIEW_PICTURE" placeholder="Загрузите файл">
                    </div>
                <?}?>
                <div class="item__property">
                    <input type="text" placeholder="Укажите имя (название)" name="NAME" value="<?=$pageItem['NAME']?>" />
                </div>
                <?if($pageData['ENITY']=='doctors'){?>
                    <div class="item__property">
                        <input type="text" placeholder="Укажите должность" name="POSITION" value="<?=$pageItem['POSITION']?>"/>
                    </div>
                    
                    <div class="item__property">
                        <textarea name="EDUCATIONS" placeholder=""><?=$pageItem['EDUCATIONS']?></textarea>
                    </div>
                    
                    <div class="item__property"><input type="text" placeholder="Укажите время приема" name="RECEPTION" value="<?=$pageItem['RECEPTION']?>"/></div>
                    
                    <div class="item__property">
                        <textarea name="CERTIFICATES" placeholder="Укажите сертификаты"><?=$pageItem['CERTIFICATES']?></textarea>
                    </div>
                    
                    <div class="item__property item__property--flex">
                        <select name="PROCEDURES[]" id="PROCEDURES" multiple>
                        <?foreach($procedureList as $procedureItem){
                            ?>
                            <option value="<?=$procedureItem["ID"]?>" <?if(!empty($pageItem['PROCEDURES_VALUES_ID']) && in_array($procedureItem["ID"],$pageItem['PROCEDURES_VALUES_ID'])){?>selected<?}?>><?=$procedureItem["NAME"]?></option>
                            <?
                        }?>
                        </select>
                    </div>
                <?}?>
                <?if($pageData['ENITY']=='procedures'){?>
                    <div class="item__property">
                        <input type="text" placeholder="Укажите стоимость" name="COST" value="<?=$pageItem['COST']?>"/>
                    </div>
                <?}?>
            </div>
            <div class="button-container">
                <input type="submit" class="ui-btn ui-btn-success" name="submit" value="<?=($pageItem['ID']) ? 'Сохранить '.$pageData['ELEMENT_LABEL'] : 'Создать элемент';?>">
                <a href="<?=$pageData['LIST_PAGE_URL']?>" class="ui-btn ui-btn-link">Вернуться назад к списку</a>    
            </div>
        </form>
    </div>
</div>
