<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "estimation_items".
 *
 * @property int $id
 * @property int $estimationID
 * @property int $productID
 * @property int $quantity
 */
class EstimationItems extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    
    
    public $total; 
    public $stock; 
	public $productNameUser;
     
    public static function tableName()
    {
        return 'estimation_items';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['hsnCode', 'price'], 'required'],
            [['estimationID'], 'integer'],
            [['productName','sno', 'hsnCode', 'per', 'total', 'stock'], 'string'],
            [['price'], 'number', 'numberPattern' => '/^\s*[0-9]+(\.[0-9][0-9]?)?\s*$/','message' => 'Not a valid Price.'],
            [['quantity'], 'number', 'numberPattern' => '/^\s*[0-9]+(\.[0-9][0-9][0-9]?)?\s*$/','message' => 'Not a valid quantity.'],
            [['quantity'], 'number', 'min' => 0.1,'message' => 'Not a valid Quantity.'],
			
			[['productNameUser'], 'required', 'message' => 'Product name is required.'],
            
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'estimationID' => 'Estimation ID',
            'productID' => 'No',
            'quantity' => 'Quantity',
            'per' => 'Per',
            'total' => 'Total',
            'sno' => 'SNO',
        ];
    }
    
    
}
