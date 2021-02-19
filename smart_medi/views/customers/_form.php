<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Customers */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="customers-form">
 <div class="box box-default">
		<div class="box-body">
			<?php $form = ActiveForm::begin(['id' => 'customers-form','enableAjaxValidation' => false,'enableClientValidation' => true]); ?>

			<?= $form->field($model, 'customerName')->textInput(['maxlength' => true]) ?>

			<?= $form->field($model, 'customerAddress')->textarea(['rows' => 6]) ?>

			<?= $form->field($model, 'customerGstin')->textInput(['maxlength' => true]) ?>
							
			<?= $form->field($model, 'customerSex')->radioList(array('Male'=>'Male','Female'=>'Female','Transgender'=>'Transgender')); ?>
   
			<div class="form-group">
				<?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
			</div>

			<?php ActiveForm::end(); ?>
		</div>
	</div>
</div>
