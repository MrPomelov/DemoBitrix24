<?

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php"); 
CModule::IncludeModule('iblock');
$request = $context->getRequest();
$el = new CIBlockElement;

$PROP = [
    "FULLNAME" => $request["NAME"],
    "DATETIME" => $request["DATETIME"],
    "PROCEDURE" => $request["PROCEDURE"],
    "DOCTOR" => $request["DOCTOR"],
];

$arLoadProductArray = array(
    "ACTIVE_FROM" => date('d.m.Y H:i:s'),
    "MODIFIED_BY" => $USER->GetID(),
    "IBLOCK_ID" => 23,
    "NAME" => "Бронирование услуги #".$request["PROCEDURE"]." от ".$request["NAME"]." на ".$request["DATETIME"],
    "ACTIVE" => "Y",
    "PROPERTY_VALUES" => $PROP,
);

if ($newElement = $el->Add($arLoadProductArray)) {
    echo "ID нового элемента: " . $newElement;
} else {
    echo "Ошибка добавления: " . $el->LAST_ERROR;
}