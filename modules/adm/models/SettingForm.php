<?php
namespace app\modules\adm\models;

use yii\base\Model;

class SettingForm extends Model
{
    public $set_sys_value;

    public function rules()
    {
        return [
            ['set_sys_value', 'string', 'max' => 500],
        ];
    }

    public function attributeLabels()
    {
        return [
            'set_sys_value' => 'Значение',
        ];
    }
}