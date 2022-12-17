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
                    "REQUIRED" => 'Y',
                    "TYPE" => 'textarea',
                    "ROWS" => 5,
                    //"PLACEHOLDER" => '',
                    "DEFAULT_VALUE" => '',
                    //"ID" => ,
                ),
                "USER" => array(
                    "NAME" => 'USER',
                    "TYPE" => 'hidden',
                    //"PLACEHOLDER" => '',
                    "DEFAULT_VALUE" => '',
                    //"ID" => ,
                ),
                "CURRENT_URL" => array(
                    "NAME" => 'CURRENT_URL',
                    "TYPE" => 'hidden',
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
                if (in_array($arField['TYPE'], array('text', 'hidden')))
                {
                    $prop[$arField['NAME']] = $arField['VALUE'];
                }
                elseif ($arField['TYPE'] == 'textarea')
                {
                    $prop[$arField['NAME']]['VALUE']['TYPE'] = 'text';
                    $prop[$arField['NAME']]['VALUE']['TEXT'] = $arField['VALUE'];
                }
            }

            $arLoadProductArray = Array(
                "IBLOCK_ID"      => $this->arParams['ID_IBLOCK'],
                "PROPERTY_VALUES"=> $prop,
                "NAME"           => $prop['NAME'] . ' ' . date("Y-m-d H:i:s"),
                "ACTIVE"         => "Y",            // активен
                "PREVIEW_TEXT"   => \Bitrix\Main\Web\Json::encode($this->arResult['FIELDS']),
            );

            echo '<pre>'.print_r($this->arResult['FIELDS'],true).'</pre>';
            echo '<pre>'.print_r($arLoadProductArray,true).'</pre>';

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
     * Формируем массив полей формы из инфоблока
     * @return bool
     */
    protected function getFormFieldsFromIblock()
    {
        /*
        array(
        "NAME" => 'NAME',
        "LABEL" => 'Имя',
        //"REQUIRED" => 'Y',
        "TYPE" => 'text',
        "PLACEHOLDER" => '',
        "DEFAULT_VALUE" => '',
        //"ID" => ,
        ),*/

        if ($this->arParams['ID_IBLOCK'] > 0)
        {
            $res = \CIBlock::GetById($this->arParams['ID_IBLOCK']);
            if ($arProps = $res->GetNext())
            {
                $this->arResult['IBLOCK']['GET'] = $arProps;
            }
            $this->arResult['IBLOCK']['INFO'] = \CIBlock::GetFields($this->arParams['ID_IBLOCK']);

            $properties = \CIBlockProperty::GetList(Array("sort"=>"asc"), Array("ACTIVE"=>"Y", "IBLOCK_ID"=>$this->arParams['ID_IBLOCK']));
            while ($propFields = $properties->GetNext())
            {
                if (substr($propFields['CODE'], 0,2) == 'H_')
                {
                    $this->arResult['FIELDS'][$propFields['CODE']] = array(
                        "NAME" => $propFields['CODE'],
                        "LABEL" => $propFields['NAME'],
                        "REQUIRED" => $propFields['IS_REQUIRED'],
                        "TYPE" => 'hidden',
                        "PLACEHOLDER" => $propFields['HINT'],
                        "DEFAULT_VALUE" => $propFields['DEFAULT_VALUE'],
                        "ID" => 'fields_' . $propFields['ID'],
                    );
                } elseif ($propFields['PROPERTY_TYPE'] == 'S') {
                    if ($propFields['USER_TYPE'] == 'HTML')
                    {
                        $this->arResult['FIELDS'][$propFields['CODE']] = array(
                            "NAME" => $propFields['CODE'],
                            "LABEL" => $propFields['NAME'],
                            "REQUIRED" => $propFields['IS_REQUIRED'],
                            "TYPE" => 'textarea',
                            "ROWS" => ($propFields['ROW_COUNT'] > 1) ? $propFields['ROW_COUNT'] : 5,
                            "PLACEHOLDER" => $propFields['HINT'],
                            "DEFAULT_VALUE" => $propFields['DEFAULT_VALUE']['TEXT'],
                            "ID" => 'fields_' . $propFields['ID'],
                        );
                    } else {
                        $this->arResult['FIELDS'][$propFields['CODE']] = array(
                            "NAME" => $propFields['CODE'],
                            "LABEL" => $propFields['NAME'],
                            "REQUIRED" => $propFields['IS_REQUIRED'],
                            "TYPE" => 'text',
                            "PLACEHOLDER" => $propFields['HINT'],
                            "DEFAULT_VALUE" => $propFields['DEFAULT_VALUE'],
                            "ID" => 'fields_' . $propFields['ID'],
                        );
                    }
                }
                //PROPERTY_TYPE
                $this->arResult['IBLOCK']['PROPERTIES'][] = $propFields;
            }



            return true;
        }
        return false;
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

        $this->getFormFieldsFromIblock();
        /*
        $this->arResult['FIELDS'] = array();
        foreach ($this->arParams['AR_FORM']['FIELDS'] as $name => $arField)
        {
            $this->arResult['FIELDS'][$name] = $arField;
        }*/

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
            //$this->getFormFieldsFromIblock();
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