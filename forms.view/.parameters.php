<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Loader;



$arComponentParameters = array(
    /*
    "GROUPS" => array(
        "BASE" => array(
            "NAME" => "Основные параметры",
        ),
    ),*/
    "PARAMETERS" => array(
        "NAME_COOKIE" => array(
            "PARENT" => "BASE",
            "NAME" => GetMessage("KBNET_POPUP_BANNER_NAME_COOKIE"),
            "TYPE" => "STRING",
            "DEFAULT" => '',
        ),

    ),
);

?>
