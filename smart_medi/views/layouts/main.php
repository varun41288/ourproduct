<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Modal;

/* @var $this \yii\web\View */
/* @var $content string */


if (Yii::$app->controller->action->id === 'login') { 
/**
 * Do not use this code in your template. Remove it. 
 * Instead, use the code  $this->layout = '//main-login'; in your controller.
 */
    echo $this->render(
        'main-login',
        ['content' => $content]
    );
} else {

    if (class_exists('backend\assets\AppAsset')) {
        backend\assets\AppAsset::register($this);
    } else {
        app\assets\AppAsset::register($this);
    }

    dmstr\web\AdminLteAsset::register($this);

    $directoryAsset = Yii::$app->assetManager->getPublishedUrl('@vendor/almasaeed2010/adminlte/dist');
    ?>
    <?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
        <link rel="stylesheet" href="<?= Yii::getAlias('@web') ?>/css/jquery_ui.css">
    </head>
    <?php if (Yii::$app->controller->id === 'invoices' || Yii::$app->controller->id === 'purchases'): ?>
		<body class="skin-blue sidebar-collapse sidebar-mini">
	<?php else: ?>	
		<body class="hold-transition skin-blue sidebar-mini">
	<?php endif; ?>
	
    <?php $this->beginBody() ?>
    <div class="wrapper">

        <?= $this->render(
            'header.php',
            ['directoryAsset' => $directoryAsset]
        ) ?>

        <?= $this->render(
            'left.php',
            ['directoryAsset' => $directoryAsset]
        )
        ?>

        <?= $this->render(
            'content.php',
            ['content' => $content, 'directoryAsset' => $directoryAsset]
        ) ?>

    </div>

    <?php $this->endBody() ?>
    
	<script src="<?= Yii::getAlias('@web') ?>/js/jquery_migrate_3.js"></script>
    <script src="<?= Yii::getAlias('@web') ?>/js/jquery_ui.js"></script>
    
    <?php /*
		<script>
		$(document).ready(function() {
				
			var isCtrl = false;
			var isAlt = false;
			
			// action on key up
			$(document).keyup(function(e) {
				
				if(e.which == 17) {
					isCtrl = false;
				}
				if(e.which == 18) {
					isAlt = false;
				}
				
			});
			// action on key down
			$(document).keydown(function(e) {
				if(e.which == 17) {
					isCtrl = true; 
				}
				if(e.which == 18) {
					isAlt = true; 
				}
				
				
				if(isAlt && e.which==80) { 
					window.location.href = "<?php echo Url::to(['/purchases']); ?>";				
				}
				
				if(isAlt && e.which==83) { 
					window.location.href = "<?php echo Url::to(['/invoices']); ?>";				
				}
				
				if(isAlt && e.which==82) { 
					window.location.href = "<?php echo Url::to(['/reports']); ?>";				
				}
				
				if(isCtrl && e.which==80) { 
					$('.alert').hide();
					$('#modelProducts').modal('show').find('#modelContentProducts').load("<?php echo Url::to(['/products/createajax']); ?>"); 
									
				}
				 
				
				if(isAlt && e.which==67) { 
					$('.alert').hide();
					$('#modelCustomers').modal('show').find('#modelContentCustomers').load("<?php echo Url::to(['/customers/createajax']); ?>"); 
				}
				if(isCtrl && e.which==83) { 
					$('.alert').hide();
					$('#modelSuppliers').modal('show').find('#modelContentSuppliers').load("<?php echo Url::to(['/suppliers/createajax']); ?>"); 
									
				} 
				if(isCtrl && e.which==67) { 
					 
					$.ajax({
						   url: '<?php echo Url::to(['/site/calculator']); ?>',
						   type: 'post',
						   
					  });		
				} 	
			});
			
			$('body').on('beforeSubmit', 'form#products-form', function () {
				 var form = $(this);
				 // return false if form still have some validation errors
				 if (form.find('.has-error').length) {
					  return false;
				 }
				 // submit form
				 $.ajax({
					  url: form.attr('action'),
					  type: 'post',
					  data: form.serialize(),
					  success: function (response) {
						  	$('#modelProducts').modal('toggle');
						  
					  }
				 });
				 return false;
			});
			
			$('body').on('beforeSubmit', 'form#customers-form', function () {
				 var form = $(this);
				 // return false if form still have some validation errors
				 if (form.find('.has-error').length) {
					  return false;
				 }
				 // submit form
				 $.ajax({
					  url: form.attr('action'),
					  type: 'post',
					  data: form.serialize(),
					  success: function (response) {
						  
						   //if(response.message=="success")
								$('#modelCustomers').modal('toggle');
						   
					  }
					 
				 });
				 return false;
			});
			
			$('body').on('beforeSubmit', 'form#suppliers-form', function () {
				 var form = $(this);
				 // return false if form still have some validation errors
				 if (form.find('.has-error').length) {
					  return false;
				 }
				 // submit form
				 $.ajax({
					  url: form.attr('action'),
					  type: 'post',
					  data: form.serialize(),
					  success: function (response) {
						  
						   //if(response.message=="success")
								$('#modelSuppliers').modal('toggle');
						   
					  }
					 
				 });
				 return false;
			});
			
		});

		</script>
		
		<?php if (!(Yii::$app->controller->id == 'products' && (Yii::$app->controller->action->id == 'create' || Yii::$app->controller->action->id == 'update'))) {
			Modal::begin([
					'header' => '<h4>Create Product</h4>',
					'id'     => 'modelProducts',
					'size'   => 'model-lg',
			]);
			echo "<div id='modelContentProducts'></div>";
			Modal::end();
	    }
		?>
		
		<?php if (!(Yii::$app->controller->id == 'customers' && (Yii::$app->controller->action->id == 'create' || Yii::$app->controller->action->id == 'update'))) {
			Modal::begin([
					'header' => '<h4>Create Patient</h4>',
					'id'     => 'modelCustomers',
					'size'   => 'model-lg',
			]);
			echo "<div id='modelContentCustomers'></div>";
			Modal::end();
	    }
		?>
		
		<?php if (!(Yii::$app->controller->id == 'suppliers' && (Yii::$app->controller->action->id == 'create' || Yii::$app->controller->action->id == 'update'))) {
			Modal::begin([
					'header' => '<h4>Create Suppliers</h4>',
					'id'     => 'modelSuppliers',
					'size'   => 'model-lg',
			]);
			echo "<div id='modelContentSuppliers'></div>";
			Modal::end();
	    }
		?>
	*/ ?>	
		 
    </body>
    </html>
    <?php $this->endPage() ?>
<?php } ?>
