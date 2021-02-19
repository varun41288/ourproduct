<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property int $status
 * @property int $role
 * @property string $created_at
 * @property string $updated_at
 */
class Users extends \yii\db\ActiveRecord
{
	public $password;
	public $reset_username;
	public $reset_password;
	public $reset_activation_key;
	
	public $value1;
	public $value2;
	public $value3;
	public $value4;
	public $value5;
	public $value6;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'auth_key', 'password_hash', 'password_reset_token', 'email', 'status', 'role', 'created_at', 'updated_at', 'address', 'invoice_start_no','company_name','phone','expiryAlert'], 'required'],
            [['username', 'auth_key', 'password_hash', 'password_reset_token', 'email','invoicePrefix','invoiceSuffix'], 'string'],
            [['status', 'role', 'invoice_start_no','expiryAlert'], 'integer'],
            [['created_at', 'updated_at', 'address', 'invoice_start_no','company_name','company_gstin','phone','act_key','act_date','secure_key','activation_status','invoicePrefix','invoiceSuffix'], 'safe'],
			
			[['value1', 'value2', 'value3', 'value4','value5','value6'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'auth_key' => 'Auth Key',
            'password_hash' => 'Password Hash',
            'password_reset_token' => 'Password Reset Token',
            'email' => 'Email',
            'phone' => 'Phone',
            'status' => 'Status',
            'role' => 'Role',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'company_name' => 'Company Name',
            'act_key' => 'Activation Key',
            'act_date' => 'Activation Date',
            'reset_username' => 'New Username',
            'reset_password' => 'New Password',
            'reset_activation_key' => 'Activation Key',
			 'invoicePrefix' => 'Invoice No Prefix',
            'invoiceSuffix' => 'Invoice No Suffix',
			'value1' => 'Use Logo in invoice?',
            'value2' => '',
            'value3' => 'Bank Details',
            'value4' => '',
            'value5' => '',
            'value6' => 'Declaration',
			'expiryAlert' => 'Product expiry remainder in days',
        ];
    }
}
