<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\components\Helper;
use kartik\daterange\DateRangePicker;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $searchModel app\models\EstimationsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Estimation';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="estimations-index">

    <div class="box box-default">
<div class="box-body">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Estimation', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'estimationNo',
            [
				'attribute'=>'estimationDate',
				'format'=>'html',
				'filter'=>DateRangePicker::widget([
						 'model'=>$searchModel,
						 'attribute' => 'estimationDate',
						 'convertFormat' => false,
					'presetDropdown'=>false,
					'hideInput'=>true,
					'pluginOptions' => [
						'locale' => [
							'format' => 'DD/MM/YYYY'
						],
					],
			])],
            
            'customerName',
             
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
