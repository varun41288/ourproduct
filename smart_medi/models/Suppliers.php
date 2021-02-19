<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "suppliers".
 *
 * @property int $id
 * @property string $supplierName
 * @property string $supplierAddress
 * @property string $supplierGstin
 */
class Suppliers extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'suppliers';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['supplierName', 'supplierAddress', 'supplierGstin'], 'required'],
            [['supplierAddress'], 'string'],
            [['supplierName'], 'string', 'max' => 255],
            [['supplierGstin'], 'string', 'max' => 50],
			[['supplierName'], 'unique','targetAttribute' => ['supplierName', 'supplierGstin']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'supplierName' => 'Supplier Name',
            'supplierAddress' => 'Supplier Address',
            'supplierGstin' => 'Supplier Gstin',
        ];
    }
}
