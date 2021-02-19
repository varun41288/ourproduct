<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\components\Helper;

/* @var $this yii\web\View */
/* @var $model app\models\Products */

$this->title = "Product : ".$model->productName;
$this->params['breadcrumbs'][] = ['label' => 'Products', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="products-view">
<div class="box box-default">
<div class="box-body">
    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'productName:ntext',
            'productCode:ntext',
            'hsnCode:ntext',
            'per:ntext',
            [
            'attribute'  => 'price',
            'format'  => 'html',
            'value'  => function ($data) {
                 return Helper::amount_to_money($data->price);
            },
           
			],
            [
            'attribute'  => 'cgstPer',
            'format'  => 'html',
            'value'  => function ($data) {
                 return ($data->cgstPer."%");
            },
           
			],
            [
            'attribute'  => 'sgstPer',
            'format'  => 'html',
            'value'  => function ($data) {
                 return ($data->sgstPer."%");
            },
           
			],
           [
            'attribute'  => 'igstPer',
            'format'  => 'html',
            'value'  => function ($data) {
                 return ($data->igstPer."%");
            },
           
			],
            //'brand:ntext',
            //'model:ntext',
			'expiryDate:ntext',
        ],
    ]) ?>

</div>
</div>
</div>
