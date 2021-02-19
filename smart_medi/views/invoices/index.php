<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\components\Helper;
use kartik\daterange\DateRangePicker;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $searchModel app\models\InvoicesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Sales';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="invoices-index">

    <div class="box box-default">
<div class="box-body">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Invoice', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'invoiceNo',
            [
				'attribute'=>'invoiceDate',
				'format'=>'html',
				'filter'=>DateRangePicker::widget([
						 'model'=>$searchModel,
						 'attribute' => 'invoiceDate',
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
            'customerName',
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
           
            //'customerID',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
</div>
</div>
