<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Estimations */

$this->title = 'Update Estimation : '.$modelEstimation->estimationNo;
$this->params['breadcrumbs'][] = ['label' => 'Sales', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $modelEstimation->estimationNo, 'url' => ['view', 'id' => $modelEstimation->estimationNo]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="estimations-update">

   
    <?= $this->render('_form', [
        'modelEstimation' => $modelEstimation,
        'modelCustomer' => $modelCustomer,
        'estimationNo' => $estimationNo,
        'modelEstimationItems' => $modelEstimationItems,
    ]) ?>

</div>
