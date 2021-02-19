<?php

namespace app\models;

use Yii;

class UserSettings extends \yii\db\ActiveRecord
{
	
    public static function tableName()
    {
        return 'user_settings';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['value1', 'value2', 'value3', 'value4','value5','value6','value7','value8','value9','value10'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'value1' => 'Use Logo in invoice?',
            'value2' => '',
            'value3' => 'Bank Details',
            'value4' => '',
            'value5' => '',
            'value6' => 'Declaration',
            'value7' => '',
            'value8' => '',
            'value9' => '',
            'value10' => '',
            
        ];
    }
}
