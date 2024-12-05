<?php
$APPLICATION->SetTitle($pageData['TITLE']);
?>
<div class="wrapper">
    <div class="button-container">
        <a href="<?=$pageData['LIST_PAGE_URL']?>add/" class="ui-btn ui-btn-success">Добавить <?=$pageData['ELEMENT_LABEL']?></a>
        <a href="/homeworks/3/<?=($pageData['ENITY']=='doctors') ? 'procedures' : 'doctors'?>/" class="ui-btn ui-btn-link">Список <?=($pageData['ENITY']=='doctors') ? 'процедур' : 'врачей'?></a>
    </div>
    <div class="page">
        <div class="items__list items__list--<?=$pageData['ENITY']?>">
            <?php foreach ($pageData['ELEMENTS'] as $pageItem){?>
                <div class="item">
                    <a href="<?=$pageItem['DETAIL_PAGE_URL']?>">
                        <?if(!empty($pageItem['PREVIEW_PICTURE'])){
                            $file = CFile::ResizeImageGet($pageItem['PREVIEW_PICTURE'], array('width'=>150, 'height'=>150), BX_RESIZE_IMAGE_PROPORTIONAL, true);                
                            $img = '<img src="'.$file['src'].'" width="'.$file['width'].'" height="'.$file['height'].'" />';
                            ?>
                            <div class="item__image"><?=$img?></div>
                        <?}?>
                        <?if(!empty($pageItem['NAME'])){?>
                            <div class="item__name"><?=$pageItem['NAME']?></div>
                        <?}?>
                        <?if($pageData['ENITY']=='doctors'){?>
                            <?if(!empty($pageItem['POSITION'])){?>
                                <div class="item__position"><?=$pageItem['POSITION']?></div>
                            <?}?>
                        <?}?>
                        <?if($pageData['ENITY']=='procedures'){?>
                            <?if(!empty($pageItem['COST'])){?>
                                <div class="item__property"><?=$pageItem['COST']?></div>
                            <?}?>
                        <?}?>
                    </a>
                </div>
            <?php } ?>
        </div>
    </div>
</div>
