<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	"NAME" => GetMessage("KBNET_FORMS_VIEW_NAME"),
	"DESCRIPTION" => GetMessage("KBNET_FORMS_VIEW_DESCRIPTION"),
	"ICON" => "/images/forms_view.gif",
	"CACHE_PATH" => "Y",
	"PATH" => array(
		"ID" => "utility",
		//"CHILD" => array(
		//	"ID" => "navigation",
		//	"NAME" => GetMessage("MAIN_NAVIGATION_SERVICE")
		//)
	),
);

?>