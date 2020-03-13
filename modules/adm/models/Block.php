<?php

namespace app\modules\adm\models;

use Yii;

/**
 * This is the model class for table "blocks".
 *
 * @property int $id_block
 * @property string $block_name
 * @property string $block_content
 * @property int $ckeditor
 */
class Block extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'blocks';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['block_name'],'filter','filter'=>'trim'],
            [['block_name'], 'required'],
            [['block_content'], 'string'],
            [['ckeditor'], 'boolean'],
            [['block_name'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_block' => 'ID',
            'block_name' => 'Название',
            'block_content' => 'Содержимое блока',
            'ckeditor' => 'Ckeditor',
        ];
    }
}
