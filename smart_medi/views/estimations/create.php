<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Estimations */

$this->title = 'Create Estimation';
$this->params['breadcrumbs'][] = ['label' => 'Sales', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="estimations-create">
    <?= $this->render('_form', [
        'modelEstimation' => $modelEstimation,
        'estimationNo' => $estimationNo,
        'modelEstimationItems' => $modelEstimationItems,
    ]) ?>

</div>
