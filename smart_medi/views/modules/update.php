<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Modules */

$this->title = 'Update Modules: {nameAttribute}';
$this->params['breadcrumbs'][] = ['label' => 'Modules', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="modules-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
