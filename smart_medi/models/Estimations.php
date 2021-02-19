<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "estimations".
 *
 * @property int $id
 * @property int $estimationNo
 * @property string $estimationDate
 * @property double $cgstTotal
 * @property double $sgstTotal
 * @property double $igstTotal
 * @property double $subTotal
 * @property double $taxTotal
 * @property double $netTotal
 * @property int $customerID
 */
class Estimations extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'estimations';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['estimationNo','estimationDate', 'subTotal', 'netTotal','customerName','customerAddress','customerGstin'], 'required'],
            [['estimationDate','discount','roundOff'], 'safe'],
            [[ 'subTotal', 'netTotal'], 'number'],
            [['customerName','customerAddress','customerGstin'], 'string'],
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
            'estimationNo' => 'Estimation No',
            'estimationDate' => 'Estimation Date',
            'subTotal' => 'Sub Total',
            'netTotal' => 'Net Total',
            'customerName' => 'Patient Name',
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
            
        ];
    }
     public function getEstimationItems()
    {
		return $this->hasMany(EstimationItems::className(), ['estimationID' => 'id']);
	}
     
}
