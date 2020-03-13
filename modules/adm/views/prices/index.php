<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;


Pjax::begin(['timeout' => 3000, 'enablePushState' => false]);
?>

    <div class="form-add-new-block elem-adm-block">
        <?=Html::beginForm(['sanatorium/update', "id" => 0], 'post', ['data-pjax' => '', 'class' => 'ajax-form form-inline']);?>
        <div class="flex-box-adm">
        <div>
            <?=Html::label('Тип нового блока');?>
            <?=Html::dropDownList('new-type', '', $typeOfBlock);?>
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
                    echo Html::a('Добавить галерею', Url::to(['sanatorium/new_gallery', "id" => 0, 'name' => $model['name']]), ['class' => 'btn btn-primary']);
                    if (!empty($galleryes)){
                        echo $secondForm->field($value, 'content')->label('Название галлереи картинок')->dropDownList($galleryes);
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
                    'formaction' => Url::to(['sanatorium/delete', "id" => 0]),
                    'data-pjax' => '1'
                ]);

                echo Html::submitButton('<span class="glyphicon glyphicon-floppy-disk"></span>'.'Обновить блок', [
                    'class' => 'btn btn-lg btn-primary',
                    'formaction' => Url::to(['sanatorium/update', "id" => 0]),
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
