<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "purchases".
 *
 * @property int $id
 * @property int $purchaseNo
 * @property string $purchaseDate
 * @property double $cgstTotal
 * @property double $sgstTotal
 * @property double $igstTotal
 * @property double $subTotal
 * @property double $taxTotal
 * @property double $netTotal
 * @property int $supplierID
 */
class Purchases extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'purchases';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['purchaseNo','purchaseDate', 'cgstTotal', 'sgstTotal', 'igstTotal', 'subTotal', 'taxTotal', 'netTotal','supplierName','supplierAddress','supplierGstin','status'], 'required'],
            [['purchaseNo'], 'unique'],
            [['purchaseDate','discount','roundOff'], 'safe'],
            [['cgstTotal', 'sgstTotal', 'igstTotal', 'subTotal', 'taxTotal', 'netTotal'], 'number'],
            [['supplierName','supplierAddress','supplierGstin'], 'string'],
            [['box1_title','box2_title','box3_title','box4_title'], 'string'],
            [['box1_content','box2_content','box3_content','box4_content'], 'string'],
			[['discount'], 'number', 'numberPattern' => '/^\s*[0-9]+(\.[0-9][0-9]?)?\s*$/','message' => 'Not a valid discount price.'],
			[['roundOff'], 'number', 'numberPattern' => '/^-?[0-9]\d*(\.\d+)?$/','message' => 'Not a valid roundOff price.'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'purchaseNo' => 'Purchase No',
            'purchaseDate' => 'Purchase Date',
            'cgstTotal' => 'CGST Total',
            'sgstTotal' => 'SGST Total',
            'igstTotal' => 'IGST Total',
            'subTotal' => 'Sub Total',
            'taxTotal' => 'Tax Total',
            'netTotal' => 'Net Total',
            'supplierName' => 'Supplier Name',
            'discount' => 'Discount',
            'roundOff' => 'Round Off',
            'box1_title' => 'Box 1 Title',
            'box2_title' => 'Box 2 Title',
            'box3_title' => 'Box 3 Title',
            'box4_title' => 'Box 4 Title',
			'box1_content' => 'Box 1 Content',
			'box2_content' => 'Box 2 Content',
			'box3_content' => 'Box 3 Content',
			'box4_content' => 'Box 4 Content',
            'status' => 'Status',
        ];
    }
     public function getPurchaseItems()
    {
		return $this->hasMany(PurchaseItems::className(), ['purchaseID' => 'id']);
	}
     
}
