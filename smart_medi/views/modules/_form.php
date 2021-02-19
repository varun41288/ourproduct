<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Modules */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="modules-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'moduleName')->textInput() ?>

   <?=  $form->field($model, 'status')->dropDownList(['1' => 'Active', '0' => 'In Active']) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
