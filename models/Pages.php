<?php

namespace app\models;

use yii\base\NotSupportedException;
use yii\db\ActiveRecord;

class Pages extends ActiveRecord
{	
	public function rules()
    {
        return [
            [['id', 'domain', 'parent', 'template', 'ord'], 'integer'],
            [['alt_name'], 'string'],
            [['active', 'deleted', 'is_default'], 'boolean'],
            [['updated'], 'safe'],
        ];
    }
	
	public static function tableName()
    {
        return 'mc_pages';
    }

    public function attributeLabels()
    {
        return [
            'parent' => 'Ид родительской страницы',
            'template' => 'Шаблон',
            'active' => 'Станица включена',
            'alt_name' => 'Url страницы',
            'ord' => 'Порядок в иерархии',
        ];
    }

    public function getPagevalues()
    {
        return $this->hasOne(Pagevalues::className(), ['id' => 'id']);
    }
}
