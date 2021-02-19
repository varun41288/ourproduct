<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Invoices;

/**
 * InvoicesSearch represents the model behind the search form of `app\models\Invoices`.
 */
class InvoicesSearch extends Invoices
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['invoiceNo'], 'integer'],
            [['invoiceDate', 'customerName'], 'safe'],
            [['cgstTotal', 'sgstTotal', 'igstTotal', 'subTotal'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Invoices::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
			//'sort'=> ['defaultOrder' => ['substr(invoiceDate,7)||substr(invoiceDate,4,2)||substr(invoiceDate,1,2)'=>SORT_DESC]]
        ]);
        
              
       
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'invoiceNo' => $this->invoiceNo,
            //'invoiceDate' => $this->invoiceDate,
            'cgstTotal' => $this->cgstTotal,
            'sgstTotal' => $this->sgstTotal,
            'igstTotal' => $this->igstTotal,
            'subTotal' => $this->subTotal,
            //'taxTotal' => $this->taxTotal,
            //'netTotal' => $this->netTotal,
            //'customerID' => $this->customerID,
        ]);
        
        $query->andFilterWhere(['like', 'customerName', $this->customerName]);
        
         if (!is_null($this->invoiceDate)) {
			$dates = explode("-",$this->invoiceDate);
			$date_format_array_from = explode("/",$dates[0]);
			$date_format_array_to = explode("/",$dates[1]);
			$sqlite_format_from = trim($date_format_array_from[2]).trim($date_format_array_from[1]).trim($date_format_array_from[0]);
			$sqlite_format_to = trim($date_format_array_to[2]).trim($date_format_array_to[1]).trim($date_format_array_to[0]);
			$query->andFilterWhere(['between', 'substr(invoiceDate,7)||substr(invoiceDate,4,2)||substr(invoiceDate,1,2)', $sqlite_format_from, $sqlite_format_to]);
		}
		
		if(!isset($params['sort']))
            $query->orderBy(['id' => SORT_DESC]);
        elseif($params['sort']=='-invoiceDate')
            $query->orderBy(['substr(invoiceDate,7)||substr(invoiceDate,4,2)||substr(invoiceDate,1,2)' => SORT_DESC]);
        elseif($params['sort']=='invoiceDate')
            $query->orderBy(['substr(invoiceDate,7)||substr(invoiceDate,4,2)||substr(invoiceDate,1,2)' => SORT_ASC]);

			

				
        return $dataProvider;
    }
}
