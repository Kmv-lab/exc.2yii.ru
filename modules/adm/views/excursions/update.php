<?php

use app\models\SansPrev;
use app\modules\adm\models\Excursions;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->registerJsFile('/js/jcrop/jquery.Jcrop.min.js', ['depends'=>['yii\web\JqueryAsset']]);
$this->registerCssFile('/js/jcrop/jquery.Jcrop.min.css');

$guides = [
    1 => 'Алексеев Никита Хрушёвич',
    2 => 'Николаев Игорь Игорьевич',
    3 => 'Брюзга Василий Петрович',
    4 => 'Дуркин Дурак Дуракович'
];

$towns = [
    1 => 'Ессентуки',
    2 => 'Пятигорск',
    3 => 'Железноводск'
];

$sanId = '1';

?>

<div class="container">

    <div class="new-elem-craete">

        <?
        $form = ActiveForm::begin([
            'options' => [
                'class' => 'sanatorium-page form-horizontal',
            ],
        ]);
        ?>

        <?=$form->field($model, 'name')->label('Название')->textInput();?>

        <span style="font-weight: bold">Описание</span>
        <?=$form->field($model, 'desc',  [
            'inputOptions' => ['class' => 'ckeditor'],
            'labelOptions' => ['class' => 'col-sm-3 control-label']
        ])->textArea(['id' => 'wysiwyg1'])->label(false);?>

        <?=$form->field($model, 'main_photo')->fileInput([
            'multiple' => false,
            'id' => "main_photo",
        ])->label('Главная картинка санатория');?>
        <?php

        if (is_file(Excursions::DIR().'original/'.$model['main_photo'])){?>

            <div class="form-group image_thumb">
                <div class="col-sm-12">
                    <div class="col-sm-9">
                        <div class="form-group">
                            <div class="col-sm-12">
                                <div class="col-sm-4">
                                    <?=Html::img(Excursions::DIRview().Yii::$app->params['resolution_main_excursion_photo'].'/'.$model['main_photo'], ['class'=> "img-thumbnail img-responsive",'data-ratio'=> Yii::$app->params['resolution_main_excursion_photo']]); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3" style="float: right;padding-top: 20px;">
                        <div class="form-group">
                            <select style="display: none" class="is_main_sanatorium_photo form-control input-sm" name="Photo_ratio_<?=1?>" id="Photo_ratio_<?=1?>">
                                <option value="<?=Yii::$app->params['resolution_main_excursion_photo']?>" selected="selected">Все разрешения</option>
                                <option value="<?=Yii::$app->params['resolution_main_excursion_photo']?>">...</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <a href="#" class="btn btn-primary btn-xs show-dialog-thumb" data-id="<?=1?>" data-name="main_photo" data-file_name="<?=$model['main_photo']?>" >
                                Редактировать миниатюру <?='<span class="glyphicon glyphicon-pencil"></span>';?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        <?}?>

        <?=$form->field($model, 'video_src')->label('Ссылка на видео(YouTube.com)')->textInput()?>

        <!--Выбор гида-->
        <?=$form->field($model, 'id_guide')->label('Выбор экскурсовода')->dropDownList($guides, ['prompt' => 'Экскурсовод не выбран!']);?>

        <?=$form->field($model, 'map')->fileInput([
            'multiple' => false,
            'id' => "map",
        ])->label('Картинка карты экскурсии');?>
        <?php

        if (is_file(Excursions::DIR().'original/'.$model['map'])){?>

            <div class="form-group image_thumb">
                <div class="col-sm-12">
                    <div class="col-sm-9">
                        <div class="form-group">
                            <div class="col-sm-12">
                                <div class="col-sm-4">
                                    <?=Html::img(Excursions::DIRview().Yii::$app->params['resolution_main_excursion_photo'].'/'.$model['map'], ['class'=> "img-thumbnail img-responsive",'data-ratio'=> Yii::$app->params['resolution_main_excursion_photo']]); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3" style="float: right;padding-top: 20px;">
                        <div class="form-group">
                            <select style="display: none" class="is_main_sanatorium_photo form-control input-sm" name="Photo_ratio_<?=2?>" id="Photo_ratio_<?=2?>">
                                <option value="<?=Yii::$app->params['resolution_main_excursion_photo']?>" selected="selected">Все разрешения</option>
                                <option value="<?=Yii::$app->params['resolution_main_excursion_photo']?>">...</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <a href="#" class="btn btn-primary btn-xs show-dialog-thumb" data-id="<?=2?>" data-name="map" data-file_name="<?=$model['map']?>" >
                                Редактировать миниатюру <?='<span class="glyphicon glyphicon-pencil"></span>';?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        <?}?>

        <?=$form->field($model, 'distance')->label('Расстояние маршрута экскурсии')->textInput()?>

        <!--Изменить на звёздочки, возможно, а может и нет-->
        <?=$form->field($model, 'rating')->label('Рейтинг')->textInput()?>

        <!--Выбор города-->
        <?=$form->field($model, 'id_town')->label('Выбор города')->dropDownList($towns, ['prompt' => 'Город не выбран!']);?>

        <?=$form->field($model, 'duration')->label('Продолжительность')->textInput()?>

        <?=$form->field($model, 'time_start')->label('Начало экскурсии(отправление)')->input('time');?>
        <?=$form->field($model, 'time_end')->label('Конец экскурсии(Приезд)')->input('time');?>

        <?=Html::submitButton('<span class="glyphicon glyphicon-floppy-disk"></span> '.'Сохранить', [
            'class' => 'btn btn-lg btn-primary'
        ]);?>

        <div id="dialog-thumb"  title="Редактирование миниатюры" data-big="<?=Excursions::DIRview().'original/'?>"
             data-url="<?=URL::to(['excursions/ajaxcreatethumb', 'idExc' => $model->id, 'name' => ''])?>" class="modal fade multi-exc">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <!-- Заголовок модального окна -->
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title">Детали</h4>
                    </div>
                    <!-- Основное содержимое модального окна -->
                    <div class="modal-body">
                        Пока пусто
                    </div>
                    <!-- Футер модального окна -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Закрыть</button>
                        <a href="#" id="thumb-ready"  class="btn btn-primary btn-sm">Готово</a>
                    </div>
                </div>
            </div>
        </div>

        <?
        $form::end();
        ?>
    </div>
</div>