<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "customers".
 *
 * @property int $id
 * @property string $customerName
 * @property string $customerAddress
 * @property string $customerGstin
 */
class Customers extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'customers';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customerName', 'customerAddress', 'customerGstin'], 'required'],
            [['customerAddress','customerSex'], 'string'],
            [['customerName'], 'string', 'max' => 255],
            [['customerGstin'], 'string', 'max' => 50],
            [['customerName'], 'unique','targetAttribute' => ['customerName', 'customerGstin']],
			['customerGstin', 'match', 'pattern' => '/\(?([0-9]{3})\)?([ .-]?)([0-9]{3})\2([0-9]{4})/']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'customerName' => 'Patient Name',
            'customerAddress' => 'Patient Address',
            'customerGstin' => 'Patient Mobile',
            'customerSex' => 'Sex',
        ];
    }
}
