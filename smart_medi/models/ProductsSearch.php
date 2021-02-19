<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Products;
use app\models\Users;
/**
 * ProductsSearch represents the model behind the search form of `app\models\Products`.
 */
class ProductsSearch extends Products
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'cgstPer', 'sgstPer', 'igstPer'], 'integer'],
            [['productName','productCode', 'hsnCode', 'per', 'brand', 'model'], 'safe'],
            //[['price'], 'number'],
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
        $query = Products::find();

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
            'id' => $this->id,
            //'price' => $this->price,
            'cgstPer' => $this->cgstPer,
            'sgstPer' => $this->sgstPer,
            'igstPer' => $this->igstPer,
        ]);

        $query->andFilterWhere(['like', 'productName', $this->productName])
            ->andFilterWhere(['like', 'productCode', $this->productCode])
            ->andFilterWhere(['like', 'hsnCode', $this->hsnCode])
            ->andFilterWhere(['like', 'per', $this->per])
            ->andFilterWhere(['like', 'brand', $this->brand])
            ->andFilterWhere(['like', 'model', $this->model]);

        return $dataProvider;
    }
	
	public function searchExpiry($params)
    {
        $query = Products::find();
		$user = Users::findOne(2);

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
		
		$query->where(' Cast(JulianDay(SUBSTR(expiryDate, 7, 10) || "-" || SUBSTR(expiryDate, 4, 2) || "-" || SUBSTR(expiryDate, 1, 2)) - JulianDay(CURRENT_DATE) as Integer) <= '.$user->expiryAlert);	

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            //'price' => $this->price,
            'cgstPer' => $this->cgstPer,
            'sgstPer' => $this->sgstPer,
            'igstPer' => $this->igstPer,
        ]);

        $query->andFilterWhere(['like', 'productName', $this->productName])
            ->andFilterWhere(['like', 'productCode', $this->productCode])
            ->andFilterWhere(['like', 'hsnCode', $this->hsnCode])
            ->andFilterWhere(['like', 'per', $this->per])
            ->andFilterWhere(['like', 'brand', $this->brand])
            ->andFilterWhere(['like', 'model', $this->model]);
			
		

        return $dataProvider;
    }
}
