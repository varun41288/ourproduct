<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\components\Helper;
use kartik\daterange\DateRangePicker;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PurchasesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Purchases';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="purchases-index">

    <div class="box box-default">
<div class="box-body">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Purchase', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'purchaseNo',
            [
				'attribute'=>'purchaseDate',
				'format'=>'html',
				'filter'=>DateRangePicker::widget([
						 'model'=>$searchModel,
						 'attribute' => 'purchaseDate',
						 'convertFormat' => false,
					'presetDropdown'=>false,
					'hideInput'=>true,
					'pluginOptions' => [
						'locale' => [
							'format' => 'DD/MM/YYYY'
						],
					],
			])],
            //'cgstTotal',
            //'sgstTotal',
            //'igstTotal',
            //'subTotal',
            'supplierName',
             [
            'attribute'  => 'taxTotal',
            'format'  => 'html',
            'value'  => function ($data) {
                 return Helper::amount_to_money($data->taxTotal);
            },
           
			],
             [
            'attribute'  => 'netTotal',
            'format'  => 'html',
            'value'  => function ($data) {
                 return Helper::amount_to_money($data->netTotal);
            },
           
			],
            [
                'attribute' => 'status',
                'filter'    => [""=>'All',"0"=>"Not Paid","1"=>"Paid"],
				'value'  => function ($data) {
					return ($data->status == 0) ? "Not Paid" : "Paid";
				},
            ], 
            //'supplierID',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
</div>
</div>
