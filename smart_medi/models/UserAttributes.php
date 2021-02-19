<?php

namespace app\models;

use Yii;

class UserAttributes extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_attributes';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['reference', 'key', 'value'], 'required'],
            [['reference','key','value'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            
        ];
    }
}
