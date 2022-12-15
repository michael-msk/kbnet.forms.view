<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Application,
    Bitrix\Main\Context,
    Bitrix\Main\Request,
    Bitrix\Main\Server,
    Bitrix\Sale,
    Bitrix\Iblock,
    Bitrix\CAtalog,
    Bitrix\Main\Loader;



class KbnetFormsView extends \CBitrixComponent
{

    public function onPrepareComponentParams($arParams)
    {
        //$arParams['WEB_FORM_ID'] = ;

        return $arParams;
    }
    /**
     * Определяет переменные шаблонов и шаблоны путей
     */
    protected function setSefDefaultParams()
    {
        //--
        $this->arParams['AR_FORM'] = array(
            "NAME" => 'test',
            "HEADER" => 'Сообщить об ошибке или возможном улучшении',
            "FIELDS" => array(
                "NAME" => array(
                    "NAME" => 'NAME',
                    "LABEL" => 'Имя',
                    "REQUIRED" => 'Y',
                    "TYPE" => 'text',
                    "PLACEHOLDER" => '',
                    "DEFAULT_VALUE" => '',
                    //"ID" => ,
                ),
                "DESCRIPTION" => array(
                    "NAME" => 'DESCRIPTION',
                    "LABEL" => 'Сообщение',
                    //"REQUIRED" => 'Y',
                    "TYPE" => 'textarea',
                    //"PLACEHOLDER" => '',
                    "DEFAULT_VALUE" => '',
                    //"ID" => ,
                ),
            ),
            "BUTTONS" => array(
                "SUBMIT" => array(
                    "TEXT" => 'Отправить',
                ),
            ),
        );
    }


    /**
     * Получение результатов
     */
    protected function getResult()
    {
        $this->arResult['NAME'] = $this->arParams['AR_FORM']['NAME'];
        $this->arResult['HEADER'] = $this->arParams['AR_FORM']['HEADER'];

        $this->arResult['FIELDS'] = array();
        foreach ($this->arParams['AR_FORM']['FIELDS'] as $name => $arField)
        {
            $this->arResult['FIELDS'][$name] = $arField;
        }

        $this->arResult['BUTTONS'] = $this->arParams['AR_FORM']['BUTTONS'];

    }


	/**
	 * Выполняет логику работы компонента
	 */
	public function executeComponent()
	{
		try
		{
			$this->setSefDefaultParams();
			$this->getResult();
            $this->includeComponentTemplate();
		}
		catch (Exception $e)
		{
			ShowError($e->getMessage());
		}
	}
}
?>