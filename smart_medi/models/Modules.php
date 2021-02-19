<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "modules".
 *
 * @property int $id
 * @property string $moduleName
 * @property int $status
 */
class Modules extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'modules';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['moduleName'], 'string'],
            [['status'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'moduleName' => 'Module Name',
            'status' => 'Status',
        ];
    }
}
