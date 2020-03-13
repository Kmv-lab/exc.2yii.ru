<?php
namespace app\modules\adm\models;

use Yii;

/**
 * This is the model class for table "blocks".
 *
 * @property int $id
 * @property string $name
 * @property string $location
 * @property string $text
 * @property string $phone
 * @property int $data
 * @property int $is_active
 */

class Testi extends \yii\db\ActiveRecord
{
    function init(){
        if($this->isNewRecord){
            $this->data = time();
            $this->is_active = 1;
        }
        return parent::init();
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'testi';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'location', 'text'],'filter','filter'=>'trim'],
            [['name', 'text'], 'required'],
            [['text'], 'string', 'max'=>2000, 'min'=>5],
            [['data'], 'date', 'format'=>'php:d.m.Y', 'timestampAttribute'=>'data'],
            [['location', 'name'], 'string', 'min'=>2, 'max'=>100],
            [['is_active', 'for_main'], 'boolean'],
            [['phone'], 'string', 'max'=>50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Имя',
            'location' => 'Город',
            'text' => 'Отзыв',
            'data' => 'Дата',
            'is_active' => 'Активен',
            'phone'=> 'Телефон',
            'for_main'=> 'Для главной'
        ];
    }
}