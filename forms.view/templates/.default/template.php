<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/** @var array $arParams */
/** @var array $arResult */
?>

    <form class="mt-4 mb-4" name="<?=$arResult['NAME'];?>" action="<?=$arResult['ACTION'];?>">
        <h5><?=$arResult['HEADER'];?></h5>
        <?foreach ($arResult['FIELDS'] as $arField):?>
            <?if($arField['TYPE'] == 'text'):?>
                <div class="mb-3">
                    <label for="<?=$arField['ID'];?>" class="form-label"><?=$arField['LABEL'];?></label>
                    <input name="<?=$arField['NAME'];?>" type="text" class="form-control" id="<?=$arField['ID'];?>"<?=($arField['REQUIRED'] == 'Y')?' required
    ':'';?>>
                </div>
            <?elseif ($arField['TYPE'] == 'textarea'):?>
                <!-- Textarea -->
                <div class="mb-3">
                <label for="<?=$arField['ID'];?>" class="form-label"><?=$arField['LABEL'];?></label>
                <textarea class="form-control" id="<?=$arField['ID'];?>" rows="<?=$arField['ROWS'];?>"><?=$arField['VALUE'];?></textarea>
                </div><?endif;?>
        <?endforeach;?>
        <button type="submit" class="btn btn-primary"><?=$arResult['BUTTONS']['SUBMIT']['TEXT'];?></button>
    </form>

<?
echo '<pre>'.print_r($arParams,true).'</pre>';
echo '<pre>'.print_r($arResult,true).'</pre>';
?>