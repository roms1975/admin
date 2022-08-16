<?php

namespace app\models;

use yii\base\NotSupportedException;
use yii\db\ActiveRecord;

class Content extends ActiveRecord
{	
	public function rules()
    {
        return [
            [['id'], 'integer'],
            [['content'], 'safe'],
        ];
    }
	
	public static function tableName()
    {
        return 'mc_content';
    }

    public function attributeLabels()
    {
        return [
            'content' => 'Текст страницы',
        ];
    }
}
