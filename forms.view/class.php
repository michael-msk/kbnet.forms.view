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
                    //"REQUIRED" => 'Y',
                    "TYPE" => 'text',
                    "PLACEHOLDER" => '',
                    "DEFAULT_VALUE" => '',
                    //"ID" => ,
                ),
                "EMAIL" => array(
                    "NAME" => 'EMAIL',
                    "LABEL" => 'Email',
                    //"REQUIRED" => 'Y',
                    "TYPE" => 'text',
                    "PLACEHOLDER" => '',
                    "DEFAULT_VALUE" => '',
                    //"ID" => ,
                ),
                "MESSAGE" => array(
                    "NAME" => 'MESSAGE',
                    "LABEL" => 'Сообщение',
                    //"REQUIRED" => 'Y',
                    "TYPE" => 'textarea',
                    "ROWS" => 5,
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

    protected function isFieldsCorrect()
    {
        return true;
    }

    /**
     * @return false|void
     * Пока запись в инфоблок только текстовых полей
     */
    protected function saveFormValues()
    {
        if ($this->arParams['ID_IBLOCK'] > 0)
        {
            $el = new \CIBlockElement;

            $prop = array();
            foreach ($this->arResult['FIELDS'] as $arField)
            {
                if ($arField['TYPE'] == 'text')
                {
                    $prop[$arField['NAME']] = $arField['VALUE'];
                }
            }

            $arLoadProductArray = Array(
                "IBLOCK_ID"      => $this->arParams['ID_IBLOCK'],
                "PROPERTY_VALUES"=> $prop,
                "NAME"           => $prop['NAME'] . ' ' . date("Y-m-d H:i:s"),
                "ACTIVE"         => "Y",            // активен
                "PREVIEW_TEXT"   => \Bitrix\Main\Web\Json::encode($this->arResult['FIELDS']),
            );

            if ($productId = $el->Add($arLoadProductArray))
            {
                $this->arResult['ID_NEW_ELEMENT'] = $productId;
                $this->arResult['MESSAGE'] = 'Ваше сообщение отправлено!';
                $this->clearFieldsValues();
                return true;
            }
            else
            {
                $this->arResult['ERROR_SAVE_RECORD'] = $el->LAST_ERROR;
                return false;
            }
        } else {
            return false;
        }
    }

    protected function clearFieldsValues()
    {
        foreach ($this->arResult['FIELDS'] as &$arField)
        {
            $arField['VALUE'] = '';
        }
    }

    protected function sendFormValues()
    {
    }

    /**
     * Проверяем был ли POST-запрос и обрабатываем его
     */
    protected function isPostRequest()
    {
        $request = Application::getInstance()->getContext()->getRequest();
        $this->arResult['IS_REQUEST'] = ($request->isPost())? "Y":"N";

        if (($this->arResult['IS_REQUEST'] == 'Y' && (check_bitrix_sessid())))
        {
            $arPost = $request->getPostList()->toArray();
            foreach ($this->arResult['FIELDS'] as &$arField)
            {
                if (array_key_exists($arField['NAME'], $arPost))
                {
                    $arField['VALUE'] = $arPost[$arField['NAME']];
                }
            }
            //-- проверяем корректность полей
            if ( $this->isFieldsCorrect() )
            {
                $this->saveFormValues();
                $this->sendFormValues();
            }
        }
        
        $this->arParams['REQUEST'] = $arPost;
    }


    /**
     * Получаем параметры формы
     */
    protected function getForm()
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
     * Получение результатов
     */
    protected function getResult()
    {

    }


	/**
	 * Выполняет логику работы компонента
	 */
	public function executeComponent()
	{
		try
		{
			$this->setSefDefaultParams();
            $this->getForm();
            $this->isPostRequest();
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