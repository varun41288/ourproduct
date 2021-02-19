<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Estimations;

/**
 * EstimationsSearch represents the model behind the search form of `app\models\Estimations`.
 */
class EstimationsSearch extends Estimations
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['estimationNo'], 'integer'],
            [['estimationDate', 'customerName'], 'safe'],
            [['subTotal', 'netTotal'], 'number'],
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
        $query = Estimations::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        
              
       
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'estimationNo' => $this->estimationNo,
            //'estimationDate' => $this->estimationDate,
            'subTotal' => $this->subTotal,
            'netTotal' => $this->netTotal,
            //'customerID' => $this->customerID,
        ]);
        
        $query->andFilterWhere(['like', 'customerName', $this->customerName]);
        
       if (!is_null($this->estimationDate)) {
			$dates = explode("-",$this->estimationDate);
			$date_format_array_from = explode("/",$dates[0]);
			$date_format_array_to = explode("/",$dates[1]);
			$sqlite_format_from = trim($date_format_array_from[2]).trim($date_format_array_from[1]).trim($date_format_array_from[0]);
			$sqlite_format_to = trim($date_format_array_to[2]).trim($date_format_array_to[1]).trim($date_format_array_to[0]);
			$query->andFilterWhere(['between', 'substr(estimationDate,7)||substr(estimationDate,4,2)||substr(estimationDate,1,2)', $sqlite_format_from, $sqlite_format_to]);
		}
		
		if(!isset($params['sort']))
            $query->orderBy(['substr(estimationDate,7)||substr(estimationDate,4,2)||substr(estimationDate,1,2)' => SORT_DESC]);
        elseif($params['sort']=='-estimationDate')
            $query->orderBy(['substr(estimationDate,7)||substr(estimationDate,4,2)||substr(estimationDate,1,2)' => SORT_DESC]);
        elseif($params['sort']=='estimationDate')
            $query->orderBy(['substr(estimationDate,7)||substr(estimationDate,4,2)||substr(estimationDate,1,2)' => SORT_ASC]);


        return $dataProvider;
    }
}
