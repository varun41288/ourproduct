<?php
use yii\helpers\Html;
use app\components\Helper;
use kartik\daterange\DateRangePicker;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

$this->title = 'Reports';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php
$js = '

jQuery(document).on("focus",".autocomplete_customer",function(){
			  	
		$(this).autocomplete({
		source: function( request, response ) {
			$.ajax({
				url : "'.Url::to(['customers/search']).'",
				dataType: "json",
				method: "GET",
				data: {
				   term: request.term
				},
				 success: function( data ) {
				    response( $.map( data, function( item ) {
				    return {
							label: item.label,
							value: item.label,
							data : item
					  }
					}));
				  }
			});
		},
		autoFocus: true,	      	
		minLength: 0,
		select: function( event, ui ) {
			$("#customer_id").val(ui.item.data.customerGstin); 
		}		      	
	});
});

jQuery(document).on("focus",".autocomplete_supplier",function(){
			  	
		$(this).autocomplete({
		source: function( request, response ) {
			$.ajax({
				url : "'.Url::to(['suppliers/search']).'",
				dataType: "json",
				method: "GET",
				data: {
				   term: request.term
				},
				 success: function( data ) {
				    response( $.map( data, function( item ) {
				    return {
							label: item.label,
							value: item.label,
							data : item
					  }
					}));
				  }
			});
		},
		autoFocus: true,	      	
		minLength: 0,
		select: function( event, ui ) {
			$("#supplier_id").val(ui.item.data.supplierGstin); 
		}		      	
	});
});

$(document).ready(function() {
    $(".wise").click(function() {
        var wise = $("input[type=radio]:checked").val();

        if(wise==1)
			$(".datewise").show();
		else
			$(".datewise").hide();
		
		$(".customer_wise").hide();
		$(".supplier_wise").hide();
			
    });

	$("#dynamicmodel-type").change(function() {
        var type= $("#dynamicmodel-type").val();
        var wise = $("input[type=radio]:checked").val();
        if(wise==1)
		{
			$(".datewise").show();
			$(".customer_wise").hide();
			$(".supplier_wise").hide();
		}
		else if(wise==2 && type=="purchase")
		{
			$(".datewise").hide();
			$(".customer_wise").hide();
			$(".supplier_wise").show();
		}
		else 
		{
			$(".datewise").hide();
			$(".customer_wise").show();
			$(".supplier_wise").hide();
		}
			
    });

});

';

$this->registerJs($js);
?>

<div class="reports-index">

<div class="col-md-12">
	
			<p>
				<?= Html::a('Customers Report', ['customers'], ['class' => 'btn btn-primary']) ?>
				<?= Html::a('Suppliers Report', ['suppliers'], ['class' => 'btn btn-primary']) ?>
				<?= Html::a('Stock Report', ['stock'], ['class' => 'btn btn-primary']) ?>
			</p>
		
</div>

<div class="col-md-12">
<div class="box box-default">
	
	<div class="box-body">
	<div class="form">
	 
		<?php $form = ActiveForm::begin(); ?>
	     	 
			<div class="col-md-3">
				<?= $form->field($model, 'wise')->radioList(array('1'=>'Date Wise',2=>'Patient Wise'),array('class'=>'wise'))->label("Report Type"); ?>
			</div>
			<div class="col-md-5 datewise">
			<label>Date Range</label>	
			<?= DateRangePicker::widget([
					'model' => $model,
					'attribute' => 'date_range',
					'convertFormat' => false,
					'presetDropdown'=>false,
					'hideInput'=>true,
					'pluginOptions' => [
						'locale' => [
							'format' => 'DD/MM/YYYY'
						],
					],
				]) ?>
			 </div>
			 
			 <div class="col-md-4">	
				<?= $form->field($model, 'type')->dropDownList(['sales' => 'Sales Ledger', 'purchase' => 'Purchase Ledger', 'estimation' => 'Estimation Ledger'],['prompt'=>'Select...']) ?>
				
			</div>	

			 <input type="hidden" id="customer_id" name="customer_id" value="" />
			 <input type="hidden" id="supplier_id" name="supplier_id" value="" />
			 
			 <div class="col-md-5 customer_wise" style="display:none;">	
				<?= $form->field($model, 'customer')->textInput(['class'=>'form-control autocomplete_customer']) ?>
			</div>	 
			<div class="col-md-5 supplier_wise" style="display:none;">	
				<?= $form->field($model, 'supplier')->textInput(['class'=>'form-control autocomplete_supplier']) ?>
			</div>	

			 
		 	 <div class="col-md-12">
			<div class="form-group" style="text-align:center;">
				<?= Html::submitButton('Generate Report', ['class' => 'btn btn-primary']) ?>
			</div>
			</div>
			
		<?php ActiveForm::end(); ?>
	 
	</div><!-- form -->
</div>
</div>
</div>
