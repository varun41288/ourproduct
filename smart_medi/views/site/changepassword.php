<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Change Password';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="login-box">
<div class="login-box-body">
    <div class="login-logo">
        <a href="#"><h3><?= Html::encode($this->title) ?></h3></a>
    </div>

    <?php if (Yii::$app->session->hasFlash('changepasswordFormSubmitted')): ?>

        <div class="alert alert-danger">
            <?php echo $error_message; ?>
        </div>

    <?php endif; ?>

        <div class="row">
            <div class="col-lg-12">

                <?php $form = ActiveForm::begin(['id' => 'changepassword-form']); ?>

                   
					<div class="col-lg-12">
						<?= $form->field($model, 'reset_username')->textInput(['autofocus' => true]) ?>
					</div>
					
					<div class="col-lg-12">
						<?= $form->field($model, 'reset_password')->textInput() ?>
					</div>
															
					<div class="col-lg-12">
						<?= $form->field($model, 'reset_activation_key') ?>
					</div>
						
					
					
					<div class="col-lg-12">
						<div class="form-group text-center">
							<?= Html::submitButton('Change Password', ['class' => 'btn btn-success', 'name' => 'changepassword-button']) ?>
							
						</div>
                    </div>

                <?php ActiveForm::end(); ?>

            </div>
        </div>

    
</div>
</div>
