<?php

namespace app\modules\adm\models;

use Yii;

/**
 * This is the model class for table "snippets".
 *
 * @property int $id_snippet
 * @property string $snippet_alias
 * @property string $snippet_name
 * @property string $snippet_content
 */
class Snippet extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'snippets';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['snippet_name','snippet_alias'],'filter','filter'=>'trim'],
            [['snippet_content','snippet_name','snippet_alias'], 'required'],
            ['snippet_alias','filter','filter'=>'strtolower'],
            [['snippet_content'], 'string'],
            [['snippet_alias', 'snippet_name'], 'string', 'min' => 1, 'max' => 50],
            ['snippet_alias', 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_snippet' => 'ID',
            'snippet_alias' => 'Алиас',
            'snippet_name' => 'Название',
            'snippet_content' => 'Сниппет',
        ];
    }
}
