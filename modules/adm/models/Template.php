<?php

namespace app\modules\adm\models;

use Yii;

/**
 * This is the model class for table "templates".
 *
 * @property int $id_tpl
 * @property string $tpl_name
 * @property string $tpl_content
 */
class Template extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'templates';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tpl_name'],'filter','filter'=>'trim'],
            [['tpl_name', 'tpl_content'], 'required'],
            [['tpl_name'], 'string', 'max' => 100],
            [['tpl_content'], 'string', 'max' => 2000],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_tpl' => 'ID',
            'tpl_name' => 'Название',
            'tpl_content' => 'Шаблончик',
        ];
    }
}
