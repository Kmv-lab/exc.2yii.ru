<?php

use app\modules\adm\models\ExcursionPhotos;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;


$this->registerJsFile('/js/jcrop/jquery.Jcrop.min.js', ['depends'=>['yii\web\JqueryAsset']]);
$this->registerCssFile('/js/jcrop/jquery.Jcrop.min.css');

$iconDel    = '<span class="glyphicon glyphicon-remove"></span>';
?>
<div class="gallery-update">
    <div class="gallery-form">
        <h1><?= $uploadSuccess ? "Фото загружены успешно!!!" : ''?></h1>

        <?
        $form = ActiveForm::begin([
            'options' => [
                'class' => 'sanatorium-page form-horizontal',
            ],
        ]);

        echo $form->field($model, 'name[]')->fileInput(['multiple' => true, 'accept' => 'image/*']);

        echo Html::submitButton('Сохранить', ['class' => 'btn btn-success pull-right']);

        $form::end();
        ?>
    </div>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <div class="form-horizontal">

        <?php
        if(!empty($photos)){
            foreach ($photos as $photo){
                $delUrl = URL::to(['delete_photo', 'idExc'=>$idExc, 'idPhoto'=>$photo->id]);
                ?>

                <div class="form-group image_thumb">
                    <div class="col-sm-12">
                        <div class="col-sm-9">
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <div class="col-sm-4">
                                        <?=Html::img(ExcursionPhotos::DIRview().Yii::$app->params['resolution_excursion_photo'].'/'.$photo['name'], ['class'=> "img-thumbnail img-responsive",'data-ratio'=> Yii::$app->params['resolution_excursion_photo']]); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3" style="float: right;padding-top: 20px;">
                            <div class="form-group">
                                <select style="display: none" class="is_main_sanatorium_photo form-control input-sm" name="Photo_ratio_<?=$photo->id?>" id="Photo_ratio_<?=$photo->id?>">
                                    <option value="<?=Yii::$app->params['resolution_excursion_photo']?>" selected="selected">Все разрешения</option>
                                    <option value="<?=Yii::$app->params['resolution_excursion_photo']?>">...</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <a href="#" class="btn btn-primary btn-xs show-dialog-thumb" data-id="<?=$photo->id?>" data-name="main_photo" data-file_name="<?=$photo->name?>" >
                                    Редактировать миниатюру <?='<span class="glyphicon glyphicon-pencil"></span>';?>
                                </a>
                            </div>
                            <div class="form-group">
                                <a href="<?=$delUrl?>" class="col-sm-5 deleteItem btn btn-inverse btn-default btn-xs">Удалить <?=$iconDel?></a>
                            </div>
                        </div>
                    </div>
                </div>
            <?}
        ?>
    </div>

        <div id="dialog-thumb"  title="Редактирование миниатюры" data-big="<?=ExcursionPhotos::DIRview().'original/'?>"
             data-url="<?=URL::to(['excursions/ajaxcreatethumb', 'idExc' => $idExc, 'name' => 'name', 'model' => 'ExcursionPhotos'])?>" class="modal fade">
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
        }?>


</div>