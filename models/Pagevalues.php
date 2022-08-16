<?php

namespace app\models;

use yii\base\NotSupportedException;
use yii\db\ActiveRecord;

class Pagevalues extends ActiveRecord
{	
	public function rules()
	{
		return [
		    [['id', 'obj_id'], 'integer'],
		    [['name', 'route', 'title', 'description', 'keywords', 'menu_pic'], 'string'],
		];
	}

	public static function tableName()
	{
		return 'mc_page_values';
	}

    public function attributeLabels()
    {
        return [
            'description' => 'Мета тэг description',
			'name' => 'Название страницы',
        ];
    }
}
