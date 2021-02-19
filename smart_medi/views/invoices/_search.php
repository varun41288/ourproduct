<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\InvoicesSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="invoices-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'invoiceNo') ?>

    <?= $form->field($model, 'invoiceDate') ?>

    <?= $form->field($model, 'cgstTotal') ?>

    <?= $form->field($model, 'sgstTotal') ?>

    <?= $form->field($model, 'igstTotal') ?>

    <?php // echo $form->field($model, 'subTotal') ?>

    <?php // echo $form->field($model, 'taxTotal') ?>

    <?php // echo $form->field($model, 'netTotal') ?>

    <?php // echo $form->field($model, 'customerID') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
