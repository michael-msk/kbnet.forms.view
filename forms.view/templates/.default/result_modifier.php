<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */


$arResult['FIELDS']['H_CURRENT_URL']['VALUE'] = $request = Bitrix\Main\Context::getCurrent()->getRequest()->getRequestUri();

$isAuthorized = ($idUser = \Bitrix\Main\Engine\CurrentUser::get()->getId()) ? true : false;
if ($isAuthorized)
{
    $isUserOrderPanel = (\Kbnet\Catalog2\Tools::isUserOrderPanel()) ? 'USER_PANEL'  : 'USER_NOT_PANEL';
    $userName = \Bitrix\Main\Engine\CurrentUser::get()->getFullName();

    $arResult['FIELDS']['H_USER']['VALUE'] = 'ID = ' .$idUser. ' / '.$userName . ' / ' . $isUserOrderPanel;
}

