<?php

if (file_exists(__DIR__ . '/classes/autoload.php')) {
    require_once __DIR__ . '/classes/autoload.php';
}
if (file_exists(__DIR__ . '/app/autoload.php')) {
    require_once __DIR__ . '/app/autoload.php';
}
if (file_exists(__DIR__ . '/events.php')) {
    require_once __DIR__ . '/events.php';
}

$arJsConfig = array( 
	'formatProceduresCustom' => array( 
		'js' => '/local/js/formatProceduresCustom.js', 
	) 
); 
foreach ($arJsConfig as $ext => $arExt) { 
	\CJSCore::RegisterExt($ext, $arExt); 
}

\Bitrix\Main\UI\Extension::load(['popup', 'crm.currency', 'timeman.custom']);