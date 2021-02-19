<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Software Activation';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="login-box" style="width:60%;">
<div class="login-box-body">
    <div class="login-logo">
        <a href="#"><h3><?= Html::encode($this->title) ?></h3></a>
    </div>

    <?php if (Yii::$app->session->hasFlash('activationFormSubmitted')): ?>

        <div class="alert alert-danger">
            <?php echo $error_message; ?>
        </div>

    <?php endif; ?>

        <div class="row">
            <div class="col-lg-12">

                <?php $form = ActiveForm::begin(['id' => 'activation-form']); ?>

                    <div class="col-lg-5">
						<?= $form->field($model, 'company_name')->hiddenInput()->label(false); ?>
					</div>
					
					<div class="col-lg-3">
						<?= $form->field($model, 'phone')->hiddenInput()->label(false); ?>
					</div>
					
					<div class="col-lg-4">
						<?= $form->field($model, 'email')->hiddenInput()->label(false); ?>
					</div>
					
					<div class="col-lg-12">
						<?= $form->field($model, 'address')->textarea(['rows' => 3])->hiddenInput()->label(false); ?>
					</div>
                    
					<div class="col-lg-4">
						<?= $form->field($model, 'username')->textInput(['autofocus' => true])->hiddenInput()->label(false); ?>
					</div>
					
					<div class="col-lg-4">
						<?= $form->field($model, 'password')->textInput()->hiddenInput()->label(false); ?>
					</div>
					
					<div class="col-lg-4">
						<?= $form->field($model, 'invoice_start_no')->hiddenInput()->label(false); ?>
					</div>
					
					<div class="col-lg-12">
						<?= $form->field($model, 'act_key') ?>
					</div>
						
					
					
					<div class="col-lg-12">
						<div class="form-group text-center">
							<?= Html::submitButton('Activate', ['class' => 'btn btn-success', 'name' => 'activate-button']) ?>
							<?/*= Html::submitButton('Request for Activation and use Trial Account of 3 days', ['class' => 'btn btn-danger', 'name' => 'request-activate-button']) */?>
						</div>
                    </div>

                <?php ActiveForm::end(); ?>

            </div>
        </div>

    
</div>
</div>
