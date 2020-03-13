
<div class="table-adm-info">

<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

foreach ($model as $item){?>
        <div class="elem-adm-info">
            <div class="name-of-elem-adm-info"><?=$item->name?></div>
            <div class="content-of-elem-adm-info"><?=$item->content?></div>
            <div class="desc-of-elem-adm-info"><?=$item->desc?></div>
        </div>
    <?}


?>

</div>

<?php

if ($isAdmin){?>

    <div class="form-new-inform-block">

        <?$form = ActiveForm::begin([
            'options' => [
                'class' => 'adm-form-to-edit-main-page',
                'data-pjax' => '1',
            ],
        ]);?>
        <div class="flex-box-adm">
            <div class="name-of-elem-adm-info">
                <?=$form->field($newModel, 'name')->label('Название')->textInput(['class' => 'form-control']);?>
            </div>
            <div class="content-of-elem-adm-info">
                <?=$form->field($newModel, 'content')->label('Вызов')->textInput(['class' => 'form-control']);?>
            </div>
            <div class="desc-of-elem-adm-info">
                <?=$form->field($newModel, 'desc')->label('Описание')->textInput(['class' => 'form-control']);?>
            </div>
        </div>
        <?=Html::submitButton('Создать блок', [
            'class' => 'btn btn-lg btn-primary'
        ]);?>

        <? $form::end(); ?>
    </div>
<?}

//Any - 08754321
