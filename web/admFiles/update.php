<?php
    use yii\helpers\Url;
    use yii\widgets\ActiveForm;
    use yii\helpers\Html;
    use yii\widgets\Pjax;

?>

<div class="container">
    <h1 class="text-center"><?$model['name']?></h1>
    <?
		$form = ActiveForm::begin([
                'options' => [
                    'class' => 'sanatorium-page',
                ],
            ]);

            echo $form->field($model, 'id_in_main_table')->label('ID в главной таблице*')->textInput(['id' => 'id-in-main-table']);

            echo $form->field($model, 'name')->label('Имя Санатория')->textInput(['id' => 'name-sanatorium']);

            echo $form->field($model, 'alias')->label('Url-адрес')->textInput(['id' => 'alias-sanatorium']);

            echo $form->field($model, 'adress')->label('Адрес Санатория')->textInput(['id' => 'adress-sanatorium']);

            echo Html::submitButton('Отправить');
        $form::end();
        ?>
            <br>
            <br>

        <?php

            Pjax::begin(['timeout' => 3000, 'enablePushState' => false]);
            ?>

            <div class="form-add-new-block elem-adm-block">
            <?php
                echo Html::beginForm('', 'post', ['data-pjax' => '', 'class' => 'form-inline']);
                echo Html::label('Приоритет(1 - 100)');
                echo Html::input('text', 'new_priority', '', ['class' => 'form-control']);
                echo "<br>";
                echo Html::checkbox('value_is_active', 0, ['label' => 'Активен']);
                echo "<br>";
                echo Html::label('Тип нового');
                //vd($type);
                echo Html::dropDownList('new-type', '', $type);
                echo "<br>";
                echo Html::submitButton('Создать блок', ['class' => 'btn btn-lg btn-primary', 'name' => 'hash-button']);
                echo Html::endForm();
                ?>

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
                                        'class' => 'adm-form-to-edit-main-page',
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
                                        echo $secondForm->field($value, 'content')->label('Название галлереи картинок')->dropDownList($galleryes);
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
                                <div class="block-priority-and-active">
                                <?php
                                //priority
                                echo $secondForm->field($value, 'priority')->label('Приоритет вывода')->textInput(['id' => 'priority-block'.$i]);
                                echo $secondForm->field($value, 'is_active')->checkbox(['id' => 'is_active-block'.$i], false)->label('Включить');
                                ?>
                                </div>
                                <div class="block-btn-sanatoriums-blocks">

                                <?php
                                echo Html::submitButton('Удалить Блок', [
                                    'class' => 'btn btn-delete-block',
                                    'formaction' => Url::to(['sanatorium/delete', "id" => $sanId]),
                                    'data-pjax' => '1'
                                ]);

                                echo Html::submitButton('Обновить блок', [
                                    'class' => 'btn btn-success',
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