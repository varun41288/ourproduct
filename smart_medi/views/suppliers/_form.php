<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Suppliers */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="suppliers-form">
<div class="box box-default">
<div class="box-body">
    <?php $form = ActiveForm::begin(['id' => 'suppliers-form','enableAjaxValidation' => false,'enableClientValidation' => true]); ?>

    <?= $form->field($model, 'supplierName')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'supplierAddress')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'supplierGstin')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
</div>
</div>
