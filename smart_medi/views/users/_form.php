<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Users */
/* @var $form yii\widgets\ActiveForm */
?>


<div class="users-form">
<div class="box box-default">
	<div class="box-body">

    <?php $form = ActiveForm::begin(); ?>
    
    <?php if($model->activation_status!=1): ?>
	<div class="col-md-12">	
		<?= $form->field($model, 'act_key')->textInput() ?>
	</div>
	<?php endif; ?>
	<div class="col-md-12">	
		<?= $form->field($model, 'company_name')->textInput() ?>
	</div>	
	<div class="col-md-12">	
		<?= $form->field($model, 'address')->textarea(['rows' => 6]) ?>
	</div>
	<div class="col-md-4">	
		<?= $form->field($model, 'invoice_start_no')->textInput() ?>
	</div>
	<div class="col-md-4">		
		<?= $form->field($model, 'invoicePrefix')->textInput() ?>
	</div>
	<div class="col-md-4">		
		<?= $form->field($model, 'invoiceSuffix')->textInput() ?>
    </div>
	
	<div class="col-md-4">		
		<?= $form->field($model, 'expiryAlert')->textInput() ?>
    </div>
	
	<div class="col-md-12">		
		<?= $form->field($model, 'value1')->radioList(array('yes'=>'Yes','no'=>'No')); ?>
    </div>
	
	
	<div class="col-md-12">		
		<?= $form->field($model, 'value3')->textarea(['rows' => 3]) ?>
    </div>
	
		
	<div class="col-md-12">		
		<?= $form->field($model, 'value6')->textarea(['rows' => 3]) ?>
    </div>

    <div class="col-md-12">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
	</div>

    <?php ActiveForm::end(); ?>

	</div>
	</div>
</div>
