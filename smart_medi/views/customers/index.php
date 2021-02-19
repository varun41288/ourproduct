<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CustomersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Customers';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="customers-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
 <div class="box box-default">
	<div class="box-body">
    <p>
        <?= Html::a('Create Patient', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
  

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'customerName',
            'customerAddress:ntext',
            'customerGstin',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    
    		</div>
	</div>
</div>
