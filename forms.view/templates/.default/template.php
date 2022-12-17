<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/** @var array $arParams */
/** @var array $arResult */
?>

    <form class="mt-4 mb-4" name="<?=$arResult['NAME'];?>" action="<?=$arResult['ACTION'];?>" method="post">
        <?=bitrix_sessid_post()?>
        <h5><?=$arResult['HEADER'];?></h5>
        <?if (!empty($arResult['MESSAGE'])):?>
            <div class="alert alert-success" role="alert">
                <?=$arResult['MESSAGE'];?>
            </div>
        <?endif;?>
        <?if (!empty($arResult['ERROR_SAVE_RECORD'])):?>
            <div class="alert alert-danger" role="alert">
                <?=$arResult['ERROR_SAVE_RECORD'];?>
            </div>
        <?endif;?>
        <?foreach ($arResult['FIELDS'] as $arField):?>
            <?if($arField['TYPE'] == 'text'):?>
                <div class="mb-3">
                    <label for="<?=$arField['ID'];?>" class="form-label"><?=$arField['LABEL'];?></label>
                    <input name="<?=$arField['NAME'];?>" type="text" value="<?=$arField['VALUE'];?>" class="form-control" id="<?=$arField['ID'];?>"<?=($arField['REQUIRED'] == 'Y')?' required
    ':'';?>>
                </div>
            <?elseif($arField['TYPE'] == 'textarea'):?>
                <!-- Textarea -->
                <div class="mb-3">
                <label for="<?=$arField['ID'];?>" class="form-label"><?=$arField['LABEL'];?></label>
                <textarea name="<?=$arField['NAME'];?>" class="form-control" id="<?=$arField['ID'];?>" rows="<?=$arField['ROWS'];?>"<?=($arField['REQUIRED'] == 'Y')?' required
    ':'';?>><?=$arField['VALUE'];?></textarea>
                </div>
            <?elseif($arField['TYPE'] == 'hidden'):?>
                <input type="hidden" name="<?=$arField['NAME'];?>" value="<?=$arField['VALUE'];?>">
            <?else:?>
                <?//--если неопознанный тип... ?>
            <?endif;?>
        <?endforeach;?>
        <button type="submit" class="btn btn-primary"><?=$arResult['BUTTONS']['SUBMIT']['TEXT'];?></button>
    </form>

<?
echo '<pre>'.print_r($arParams,true).'</pre>';
echo '<pre>'.print_r($arResult,true).'</pre>';
?>