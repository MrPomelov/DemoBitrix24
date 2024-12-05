<?php
$APPLICATION->SetTitle($pageData['TITLE']);
$pageItem = $pageData["ELEMENTS"][0];
?>
<div class="wrapper">
    <div class="page detail-container">
        <div class="item item--<?=$pageData['ENITY']?>">
            <?if(!empty($pageItem['PREVIEW_PICTURE'])){
                $file = CFile::ResizeImageGet($pageItem['PREVIEW_PICTURE'], array('width'=>150, 'height'=>150), BX_RESIZE_IMAGE_PROPORTIONAL, true);                
                $img = '<img src="'.$file['src'].'" width="'.$file['width'].'" height="'.$file['height'].'" />';
                ?>
                <div class="item__image"><?=$img?></div>
            <?}?>
            <?if(!empty($pageItem['NAME'])){?>
                <div class="item__property"><?=$pageItem['NAME']?></div>
            <?}?>
            <?if($pageData['ENITY']=='doctors'){?>
                <?if(!empty($pageItem['POSITION'])){?>
                    <div class="item__property"><?=$pageItem['POSITION']?></div>
                <?}?>
                <?if(!empty($pageItem['EDUCATIONS'])){?>
                    <div class="item__property"><?=$pageItem['EDUCATIONS']?></div>
                <?}?>
                <?if(!empty($pageItem['RECEPTION'])){?>
                    <div class="item__property"><?=$pageItem['RECEPTION']?></div>
                <?}?>
                <?if(!empty($pageItem['CERTIFICATES'])){?>
                    <div class="item__property"><?=$pageItem['CERTIFICATES']?></div>
                <?}?>
                <?if(!empty($pageItem['PROCEDURES_VALUES'])){?>
                    <div class="item__property item__property--flex">
                        <?php foreach($pageItem['PROCEDURES_VALUES'] as $property){ ?>
                            <div class="item__property-value"><?=$property['NAME']?></div>
                            <?
                        }
                        ?>
                    </div>
                <?}?>
            <?}?>
            <?if($pageData['ENITY']=='doctors'){?>
                <?if(!empty($pageItem['COST'])){?>
                    <div class="item__property"><?=$pageItem['COST']?></div>
                <?}?>
            <?}?>
        </div>
        <div class="button-container">
            <a href="<?=$pageData['DETAIL_PAGE_URL']?>edit/" class="ui-btn ui-btn-light-border">Изменить <?=$pageData['ELEMENT_LABEL']?></a>
            <a href="<?=$pageData['LIST_PAGE_URL']?>" class="ui-btn ui-btn-link">Вернуться назад к списку</a>
        </div>
    </div>
</div>
