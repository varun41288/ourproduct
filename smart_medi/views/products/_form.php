<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\Products */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="products-form">

    <div class="box box-default">
		<div class="box-body">
			
			<?php $form = ActiveForm::begin(['id' => 'products-form']); ?>
				
			<div class="row">
				<div class="col-md-4">
					<?= $form->field($model, 'productName')->textInput() ?>
				</div>
				<div class="col-md-3">
					<?= $form->field($model, 'productCode')->textInput() ?>
				</div>
				<div class="col-md-2">
					<?= $form->field($model, 'sno')->textInput() ?>
				</div>
				
				<div class="col-md-3">
				
				<?php 
				echo '<label>Expiry Date</label>';
				echo DatePicker::widget([
					'model' => $model, 
					'attribute' => 'expiryDate',
					'readonly' => true,
					'removeButton' => false,
					'type' => DatePicker::TYPE_COMPONENT_APPEND,
					'options' => ['placeholder' => 'Enter expiry date ...','value' => date('d/m/Y')],
					'pluginOptions' => [
						'todayHighlight' => true,
						 'format' => 'dd/mm/yyyy',
						 'startDate' => date('d/m/Y')
					]
				]);
				?>
				</div>
			    
				<div class="col-md-4">
					<?= $form->field($model, 'hsnCode')->textInput() ?>
				</div>
				<div class="col-md-4">
					<?= $form->field($model, 'per')->textInput() ?>
				</div>
				<div class="col-md-4">
					<?= $form->field($model, 'price')->textInput() ?>
				</div>
				
				<div class="col-md-3">
					<?= $form->field($model, 'cgstPer')->textInput() ?>
				</div>
				<div class="col-md-3">
					<?= $form->field($model, 'sgstPer')->textInput() ?>
				</div>
				<div class="col-md-3">
					<?= $form->field($model, 'igstPer')->textInput() ?>
				</div>
				<?php if ($model->isNewRecord): ?>
				<div class="col-md-3">
					<?= $form->field($model, 'opening_stock')->textInput() ?>
				</div>
				<?php endif; ?>
			    
			   

				<div class="col-md-12">
			
					<div class="form-group">
						<?= Html::submitButton('Save', ['name'=>'save','class' => 'btn btn-success']) ?>
						
					</div>
				</div>	
			</div>
			<?php ActiveForm::end(); ?>
			 
		</div>
	</div>
	
</div>
