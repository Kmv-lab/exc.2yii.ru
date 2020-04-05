<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$action = Url::to( 'request/manager_form', true);
?>

<div class="container">
    <h2 class="sec-subtitle callback-sec__title"><?=$text?></h2>
    <p class="sec-txt callback-sec__txt">Мы бесплатно подберем тур согласно вашим индивидуальным предпочтениям и состоянию здоровья</p>

    <?
    $form = ActiveForm::begin(['id' => 'form-manager',
        'enableAjaxValidation' => true,
        'action' => $action,
        'options' => [
            'class' => 'callback-form',
        ],
    ]);
    ?>

    <div class="callback-form__row">
        <?=$form->field($model, 'name')->label(false)->textInput([
            'class' => 'input callback-form__input',
            'placeholder' => 'Как к вам обращаться?'

        ])?>
        <?=$form->field($model, 'phone')->label(false)->textInput([
            'class' => 'input callback-form__input callback-form-phone',
            'placeholder' => 'Ваш телефон',
            'required',

        ])?>
        <?=Html::submitButton('Жду звонка', [
            'class' => 'btn btn_orange callback-form__submit'
        ]);?>
    </div>
    <small class="callback-form__sub">*нажимая на кнопку вы даете согласие на обработку своих персональных данных</small>
    <?
    $form::end();
    ?>

</div>
