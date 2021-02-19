<?php
use yii\helpers\Html;
use yii\helpers\Url;
use app\components\Helper;
/* @var $this yii\web\View */

$this->title = 'Dashboard';
?>
<style>
.btn-app {font-weight:bold;padding: 20px 20px 60px 20px; font-size: 14px; width:110px;}
.btn-app i {padding-bottom:4px;font-size: 16px;}

</style>
<div class="site-index">
	<div class="row">
	<div class="col-lg-12">
	    
		<?php if($activation_status==2): ?>
		 <div class="callout callout-danger">
                <h4>Activate Account!</h4>

                <p>You trial period will expires soon.Please go to settings and activate your account. </p>
        </div>
		<?php endif; ?>	  
			  
		
		<div class="box">
           
            <div class="box-body">
			
			  		  
              <a href="<?= Url::to(['/invoices']);?>" class="btn btn-app bg-green">
                <span class="badge"><?= $invoices;?></span>
                <i class="fa fa-shopping-cart"></i> Sales 
              </a>
			  
			  <a href="<?= Url::to(['/products']);?>" class="btn btn-app bg-purple">
                <span class="badge"><?= $products; ?></span>
                <i class="fa fa-barcode"></i> Products
              </a>
			  
			  <a href="<?= Url::to(['/purchases']);?>" class="btn btn-app bg-aqua">
                <span class="badge"><?= $purchases;?></span>
                <i class="fa fa-tags"></i> Purchases
              </a>
			  
			  <a href="<?= Url::to(['/customers']);?>" class="btn btn-app bg-olive">
                <span class="badge"><?= $customers; ?></span>
                <i class="fa fa-users"></i> Customers
              </a>
              <a href="<?= Url::to(['/suppliers']);?>" class="btn btn-app bg-blue">
                <span class="badge"><?= $suppliers; ?></span>
                <i class="fa fa-truck"></i> Suppliers
              </a>
			  			  
			  <?php if(Helper::modules_status('jobsheets')): ?>
			  <a href="<?= Url::to(['/estimations']);?>" class="btn btn-app bg-red">
                <span class="badge"><?= $estimations;?></span>
                <i class="fa fa-tags"></i> Job Sheets
              </a>
			  <?php endif; ?>
			  
              <?php if(Helper::modules_status('estimations')): ?>
				  <a href="<?= Url::to(['/estimations']);?>" class="btn btn-app bg-maroon">
					<span class="badge"><?= $estimations;?></span>
					<i class="fa fa-tags"></i> Estimations
				  </a>
			  <?php endif; ?>
			  
              
			  <a href="<?= Url::to(['/reports']);?>" class="btn btn-app bg-yellow">
                 <i class="fa fa-file"></i> Reports
              </a>
			  
			   
			 
            </div>
            <!-- /.box-body -->
          </div>
		 
	
	    <!-- /.col -->
      </div>     
	</div>
</div>
