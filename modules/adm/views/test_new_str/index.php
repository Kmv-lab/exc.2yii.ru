<?php
    use yii\widgets\ActiveForm;
    use yii\helpers\Html;
    use yii\widgets\Pjax;

    $types = [
        0 => 'Заголовок',
        1 => 'code-mirror',
        2 => 'WYSIWYG'
    ];

    Pjax::begin([
        'timeout' => 3000,
        'enablePushState' => false,
    ]);
?>

<div class="container">

    <div class="add-new-block">
        <?
        $form1 = ActiveForm::begin([
            'options' => [
                'class' => 'adm-form-to-edit-main-page',
                'data-pjax' => '1',
            ],
        ]);?>

        <h1>Добавить новый блок</h1>

        <div class="name-type-prioryty">
            <?=$form1->field($emptyModel, 'name')->label('Название нового блока')->textInput()?>
            <?=$form1->field($emptyModel, 'type')->label('Тип нового блока')->dropDownList($types, ['prompt' => 'type не выбран!']);?>
            <?=$form1->field($emptyModel, 'priority')->label('Приоритет нового блока')->textInput(['type' => 'number', 'max' => 100, 'min' => 1, 'step' => 'any']);?>

        </div>
        <?=$form1->field($emptyModel, 'content')->hiddenInput()->label(false)?>
        <?=Html::submitButton('<span class="glyphicon glyphicon-floppy-disk"></span> '.'Создать', [
            'class' => 'btn btn-lg btn-primary'
        ]);?>
        <?$form1::end();
        ?>
    </div>

    <h1 class="text-center">Главная в админке</h1>
    <div class="row container-elem-main-page">
        <div class="col-md-2"><p>ID</p></div>
        <div class="col-md-8"><p>Название</p></div>
    </div>
    <div class="elems-for-change">

    <?php
        $i=1;

    if(!empty($model)){
        foreach ($model as $value) {
            ?>
            <div class="container-elem-main-page" id="elem-main-page<?=$i?>">
                <div class="row">
                    <div class="col-md-2"><p>№ <?=$i?></p></div>
                    <div class="col-md-8"><p><?=(isset($value->name) ? $value->name : "Название поля")?></p></div>
                    <div class="col-md-2">
                        <?=Html::a('<span class="glyphicon glyphicon-trash"></span>'.'Удалить', ['delete', 'idBlock'=>$value->id], ['class' => 'btn btn-danger deleteItem button-delete']);?>
                    </div>
                </div>

                <?

                $form = ActiveForm::begin([
                    'options' => [
                        'class' => 'adm-form-to-edit-main-page',
                        'data-pjax' => '1',
                    ],
                ]);?>

                <div class="name-type-prioryty">
                    <?=$form->field($value, 'type')->label('Тип блока')->dropDownList($types);?>
                    <?=$form->field($value, 'priority')->label('Приоритет блока')->textInput(['type' => 'number', 'max' => 100, 'min' => 1, 'step' => 'any']);?>
                    <?=$form->field($value, 'name')->hiddenInput()->label(false);?>
                </div>

                <?
                switch ($value["type"]) {
                    case 0:
                        echo $form->field($value, 'content')->label(false)->textInput(['id' => "main_page-content_$i"]);
                        break;

                    case 1:
                        //code-editor
                        echo $form->field($value, 'content')->textarea([
                            'class' => 'codemirror',
                            'id' => "main_page-content_$i"
                        ])->label(false);
                        break;
                    case 2:
                        //WYSIWYG
                        echo $form->field($value, 'content',  [
                            'inputOptions' => ['class' => 'ckeditor'],
                            'labelOptions' => ['class' => 'col-sm-3 control-label']
                        ])->textArea(['id' => "main_page-content_wysiwyg"])->label(false);
                        break;
                    case 3:
                        //Изменить на поле для картинки
                        echo $form->field($value, 'content')->fileInput([
                            'multiple' => false,
                            'id' => "main_page-content_$i"
                        ])->label(false);
                        ?><img class="img-for-main-page" src="<?=$value['content']?>" alt=""><?
                        break;
                }

                echo $form->field($value, 'id', ['inputOptions'=>['id' => "main_page-id_$i"]])->hiddenInput()->label(false);
                echo Html::submitButton('<span class="glyphicon glyphicon-floppy-disk"></span> '.'Обновить', [
                        'class' => 'btn btn-lg btn-primary'
                ]);
                $form::end();
                $i++;
                ?>

            </div>
        <?}//закрытие цикла
    }
    ?>
    </div>
</div>
<?php
Pjax::end();