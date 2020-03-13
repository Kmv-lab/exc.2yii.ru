<?php
    use app\models\SansPrev;use yii\helpers\Url;
    use yii\widgets\ActiveForm;
    use yii\helpers\Html;
    use yii\widgets\Pjax;

    $this->registerJsFile('/js/jcrop/jquery.Jcrop.min.js', ['depends'=>['yii\web\JqueryAsset']]);
    $this->registerCssFile('/js/jcrop/jquery.Jcrop.min.css');

?>

<div class="container">
    <h1 class="text-center"><?$model['name']?></h1>
    <?
		$form = ActiveForm::begin([
                'options' => [
                    'class' => 'sanatorium-page form-horizontal',
                ],
            ]);
		?>


            <?=$form->field($model, 'id_in_main_table')->label('ID в главной таблице*')->textInput(['id' => 'id-in-main-table']);?>

            <?=$form->field($model, 'name')->label('Имя Санатория')->textInput(['id' => 'name-sanatorium']);?>

            <?=$form->field($model, 'alias')->label('Url-адрес')->textInput(['id' => 'alias-sanatorium']);?>

            <?=$form->field($model, 'adress')->label('Адрес Санатория')->textInput(['id' => 'adress-sanatorium']);?>

            <?=$form->field($model, 'file_name')->fileInput([
                        'multiple' => false,
                        'id' => "file_name",
                    ])->label('Главная картинка санатория');?>

            <?php

            if (is_file(SansPrev::DIR().'original/'.$model['file_name'])){?>

                <div class="form-group image_thumb">
                    <div class="col-sm-12">
                        <div class="col-sm-9">
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <div class="col-sm-4">
                                        <?=Html::img(SansPrev::DIRview().Yii::$app->params['resolution_main_sanatorium_photo'].'/'.$model['file_name'], ['class'=> "img-thumbnail img-responsive",'data-ratio'=> Yii::$app->params['resolution_main_sanatorium_photo']]); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3" style="float: right;padding-top: 20px;">
                            <div class="form-group">
                                <select style="display: none" class="is_main_sanatorium_photo form-control input-sm" name="Photo_ratio_<?=$sanId?>" id="Photo_ratio_<?=$sanId?>">
                                    <option value="<?=Yii::$app->params['resolution_main_sanatorium_photo']?>" selected="selected">Все разрешения</option>
                                    <option value="<?=Yii::$app->params['resolution_main_sanatorium_photo']?>">...</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <a href="#" class="btn btn-primary btn-xs show-dialog-thumb" data-id="<?=$sanId?>" data-file_name="<?=$model['file_name']?>" >
                                    Редактировать миниатюру <?='<span class="glyphicon glyphicon-pencil"></span>';?>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="dialog-thumb"  title="Редактирование миниатюры" data-big="<?=SansPrev::DIRview().'original/'?>"
                     data-url="<?=URL::to(['sanatorium/ajaxcreatethumb'])?>" class="modal fade">
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

            <?}?>



            <?=Html::submitButton('Отправить', ['class' => 'btn btn-lg btn-primary', 'name' => 'hash-button']);?>


            <?php
        $form::end();
        ?>
            <br>
            <div class="block-adm-to-edit">
                <?=Html::a('Изменить комнаты', Url::to(['sanatorium/rooms', "id" => $sanId, 'sanatoriumName' => $model['name']]), ['class' => 'btn btn-primary']) ?>
                <?=Html::a('Изменить Галерей для '.$model->name, Url::to(['sanatorium/galleries_all', "idSan" => $sanId, 'name' => $model->name]), ['class' => 'btn btn-primary']) ?>
            </div>
            <br>

        <?php

            Pjax::begin(['timeout' => 3000, 'enablePushState' => false]);
            ?>

            <div class="form-add-new-block elem-adm-block">
            <?php
                echo Html::beginForm(['sanatorium/update', "id" => $sanId], 'post', ['data-pjax' => '', 'class' => 'ajax-form form-inline']);
                ?>
                <div class="flex-box-adm">
                    <div>
                        <?=Html::label('Тип нового блока');?>
                        <?=Html::dropDownList('new-type', '', $type);?>
                    </div>
                    <div>
                        <?=Html::label('Приоритет(1 - 100)');?>
                        <?=Html::input('text', 'new_priority', '', ['class' => 'form-control']);?>
                    </div>
                    <?=Html::checkbox('value_is_active', 0, ['label' => 'Включить']);?>
                </div>
                <?=Html::submitButton('Создать блок', [
                        'class' => 'btn btn-lg btn-primary',
                        'name' => 'hash-button',
                        'data-pjax' => '1'
                ]);?>

                <?=Html::endForm();?>


            </div>
            <?php
                $i = 0;
                    foreach ($modelForBlock as $value){
                        ?>

                        <div class="elem-adm-block">
                            <div>
                                <?php
                                $secondForm = ActiveForm::begin([
                                    'options' => [
                                        'class' => 'ajax-form adm-form-to-edit-main-page',
                                        'data-pjax' => '1',
                                    ],
                                ]);
                                switch ($value->type){
                                    case 0:
                                        //Просто профили лечения Никто не может этого изменить :)
                                        echo '<div class="block-edit-admins-func"><h1>Профили лечения</h1></div>';
                                        break;
                                    case 1:
                                        //выбор галлереи

                                        //загрузка новой галлереи
                                        echo Html::a('Добавить галерею', Url::to(['sanatorium/new_gallery', "id" => $sanId, 'name' => $model['name']]), ['class' => 'btn btn-primary']);
                                        if (!empty($galleryes)){
                                            //vd($value);
                                            echo $secondForm->field($value, 'content')->label('Название галлереи картинок')->dropDownList($galleryes, ['prompt' => 'Галерея не выбрана!']);
                                        }

                                        break;
                                    case 2:
                                        //WYSIWYG
                                        echo "<label>Текст</label>";
                                        echo $secondForm->field($value, 'content',  [
                                            'inputOptions' => ['class' => 'ckeditor'],
                                            'labelOptions' => ['class' => 'col-sm-3 control-label']
                                        ])->textArea(['id' => 'wysiwyg'.$i])->label(false);
                                        break;
                                    case 3:
                                        //code mirror
                                        echo $secondForm->field($value, 'content')->textarea([
                                            'class' => 'codemirror',
                                            'id' => 'code-mirror'.$i
                                        ])->label('Код элемента');
                                        break;
                                    case 4:
                                        //Сюда закидывается ссылка на youtube видео
                                        echo $secondForm->field($value, 'content')->label('Ссылка на youTube-видео')->textInput(['id' => 'youtube-url'.$i]);
                                        break;
                                    case 5:
                                        //Просто номера санатория Никто не может этого изменить :)
                                        echo '<div class="block-edit-admins-func"><h1>Номера</h1></div>';
                                        break;
                                    case 6:
                                        //Просто табличка цен Никто не может этого изменить :)
                                        echo '<div class="block-edit-admins-func"><h1>Таблица цен</h1></div>';
                                        break;
                                }

                                echo $secondForm->field($value, 'id')->hiddenInput(['id' => 'id-block'.$i])->label(false);
                                echo $secondForm->field($value, 'type')->hiddenInput(['id' => 'type-block'.$i])->label(false);
                                ?>
                                <div class="flex-box-adm">
                                <?php
                                //priority
                                echo $secondForm->field($value, 'priority')->label('Приоритет вывода')->textInput(['id' => 'priority-block'.$i]);
                                echo $secondForm->field($value, 'is_active')->checkbox(['id' => 'is_active-block'.$i], false)->label('Включить');
                                ?>
                                </div>
                                <div class="flex-box-adm">

                                <?php
                                echo Html::submitButton('<span class="glyphicon glyphicon-trash"></span> '.'Удалить Блок', [
                                    'class' => 'text-adm-btn btn-lg btn btn-delete-block',
                                    'formaction' => Url::to(['sanatorium/delete', "id" => $sanId]),
                                    'data-pjax' => '1'
                                ]);

                                echo Html::submitButton('<span class="glyphicon glyphicon-floppy-disk"></span>'.'Обновить блок', [
                                    'class' => 'btn btn-lg btn-primary',
                                    'formaction' => Url::to(['sanatorium/update', "id" => $sanId]),
                                    'data-pjax' => '1'
                                ]);
                                ?>

                                </div>

                                <?php
                                //echo Html::submitButton('Обновить блок', ['data-pjax' => '1']);

                                $secondForm::end();
                                ?>
                            </div>
                            <div>

                            </div>
                        </div>
                        <?php
                        $i++;
                    }
            Pjax::end();