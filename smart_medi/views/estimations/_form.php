<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use wbraganca\dynamicform\DynamicFormWidget;
use kartik\date\DatePicker;
use yii\helpers\Url;

$js = '

function calculation(counter)
{
	//quantity = $("#estimationitems-"+counter+"-quantity").val();
	
	/* stock checking */
	//stock = $("#estimationitems-"+counter+"-stock").val();
	//if(parseInt(quantity) > parseInt(stock))
	//{
		//alert("Insufficient Stock!");
		//$("#estimationitems-"+counter+"-quantity").val(1);
	//}
		
	quantity = $("#estimationitems-"+counter+"-quantity").val();
	if(quantity == "")
		quantity = 1;
	quantity = parseFloat(quantity);	
		
	price = $("#estimationitems-"+counter+"-price").val();
	price = parseFloat(price);
	total = quantity * price;
	
	
	$("#estimationitems-"+counter+"-total").val(total.toFixed(2));	
		
	
	/* CALCULATION FOR ALL TOTAL COLUMNS STARTS */
	subTotal = 0;
	$(".totalLinePrice").each(function(){
		if($(this).val() != "")
			subTotal += parseFloat($(this).val());
	});	
		
		
	netTotal = subTotal;
	
	var discount = $("#estimations-discount").val();
	if(discount!="")
	{
		discount = parseFloat($("#estimations-discount").val());
		netTotal = netTotal - discount;
	}
	var roundOff = $("#estimations-roundoff").val()
	if(roundOff!="")
	{
		roundOff = parseFloat($("#estimations-roundoff").val());
		netTotal = netTotal + roundOff;
	}
		
	$("#estimations-subtotal").val(subTotal.toFixed(2));	
	$("#estimations-nettotal").val(netTotal.toFixed(2));	
	
	/* CALCULATION FOR ALL TOTAL COLUMNS ENDS */
}

jQuery(document).on("focus",".autocomplete_product",function(){
		
		id_string = $(this).attr("id");
	  	id_array = id_string.split("-");
	  	counter = id_array[1];
	  	
		$(this).autocomplete({
		source: function( request, response ) {
			$.ajax({
				url : "'.Url::to(['products/search']).'",
				dataType: "json",
				method: "GET",
				data: {
				   term: request.term,
				   action: "sales"
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
		    $("#estimationitems-"+counter+"-productid").val(ui.item.data.id);
		    $("#estimationitems-"+counter+"-productname").val(ui.item.data.productName);
		    $("#estimationitems-"+counter+"-sno").val(ui.item.data.sno);
		    $("#estimationitems-"+counter+"-hsncode").val(ui.item.data.hsnCode);
		    $("#estimationitems-"+counter+"-price").val(ui.item.data.price);
		    $("#estimationitems-"+counter+"-per").val(ui.item.data.per);
		    $("#estimationitems-"+counter+"-quantity").val(1);
		    $("#estimationitems-"+counter+"-stock").val(ui.item.data.stock);
		    
		    calculation(counter);
		}		      	
	});
});

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
		    $("#estimations-customeraddress").val(ui.item.data.customerAddress); 
			$("#estimations-customergstin").val(ui.item.data.customerGstin); 
		}		      	
	});
});

$(document).on("change keyup blur",".changesMade",function(){
	id_string = $(this).attr("id");
	id_array = id_string.split("-");
	counter = id_array[1];
	calculation(counter);
});

$(document).on("click",".toggleTopBox",function(){
	$(".top_boxes").slideToggle("slow");
});


';

$this->registerJs($js);
?>

	
		
<div class="estimation-form">
<div class="box box-default">
<div class="box-body">
    <?php $form = ActiveForm::begin(['id' => 'estimation-form']); ?>
    <div class="row">
		<div class='col-lg-4'>
			<div class="form-group">
				<?//= $form->field($modelEstimation, 'estimationDate')->textInput(['maxlength' => true,'value' => date("d/m/Y")]) ?>
				
				<?php 
				echo '<label>Estimation Date</label>';
				echo DatePicker::widget([
					'model' => $modelEstimation, 
					'attribute' => 'estimationDate',
					'readonly' => true,
					'removeButton' => false,
					'type' => DatePicker::TYPE_COMPONENT_APPEND,
					'options' => ['placeholder' => 'Enter estimation date ...'],
					'pluginOptions' => [
						'todayHighlight' => true,
						 'format' => 'dd/mm/yyyy'
					]
				]);
						
				?>
			</div>
			<div class="form-group">
				<?= $form->field($modelEstimation, 'estimationNo')->textInput(['maxlength' => true,'value' => $estimationNo,'readOnly'=> true]) ?>
				
			</div>
		</div>
	     <div class='col-lg-4'>
			<div class="form-group">
				<?= $form->field($modelEstimation, 'customerName')->textInput(['maxlength' => true,'class'=>'form-control autocomplete_customer','placeholder'=>'Find by customer name']) ?>
				
			</div>
			<div class="form-group">
				<?= $form->field($modelEstimation, 'customerGstin')->textInput(['maxlength' => true]) ?>
			</div>
		</div>
	    <div class='col-lg-4'>
			<div class="form-group">
				<?= $form->field($modelEstimation, 'customerAddress')->textArea(['rows' => 4]) ?>
			</div>
		</div>
		
				
				
    </div>
     
             <?php DynamicFormWidget::begin([
                'widgetContainer' => 'dynamicform_wrapper', // required: only alphanumeric characters plus "_" [A-Za-z0-9_]
                'widgetBody' => '.container-items', // required: css class selector
                'widgetItem' => '.item', // required: css class
                'limit' => 20, // the maximum times, an element can be cloned (default 999)
                'min' => 1, // 0 or 1 (default 1)
                'insertButton' => '.add-item', // css class
                'deleteButton' => '.remove-item', // css class
                'model' => $modelEstimationItems[0],
                'formId' => 'estimation-form',
                'formFields' => [
                    'productName',
                    'sno',
                    'hsnCode',
                    'per',
                    'price',
                    
                ],
            ]); ?>
			<div class="box-body table-responsive no-padding">
              <table class="table text-center">
              	<tr>
				  	
                  <th width="22%">Product</th>
                  <th width="15%">SNO</th>
                  <th width="15%">HSN/SAC</th>
                  <th width="10%">Price</th>
                  <th width="6%">Qty</th>
                  <th width="6%">Per</th>
                  <th width="16%">Total</th>
                  <th width="10%"></th>
				  
                </tr>
             <tbody class="container-items"> 	
             <?php foreach ($modelEstimationItems as $i => $modelEstimationItem): ?>
			
				<tr class="item">
				  
                  <td>
						
						<?= $form->field($modelEstimationItem, "[{$i}]productNameUser")->textInput(['maxlength' => true,'class'=>'form-control autocomplete_product','placeholder'=>'Name or Code'])->label(false); ?>
                  </td>
				   <td>
						<?= $form->field($modelEstimationItem, "[{$i}]sno")->textInput(['maxlength' => true,'class'=>'form-control'])->label(false); ?>
				  </td>
                  <td>
						<?= $form->field($modelEstimationItem, "[{$i}]hsnCode")->textInput(['maxlength' => true,'class'=>'form-control'])->label(false); ?>
				  </td>
                  <td>
						<?= $form->field($modelEstimationItem, "[{$i}]price")->textInput(['maxlength' => true,'class'=>'form-control changesMade'])->label(false); ?>
				  </td>
				  <td>
						<?= $form->field($modelEstimationItem, "[{$i}]quantity")->textInput(['maxlength' => true,'class'=>'form-control changesMade'])->label(false); ?>
					</td>
					
					<td>
						<?= $form->field($modelEstimationItem, "[{$i}]per")->textInput(['maxlength' => true])->label(false); ?>
					</td>
					<td>
						<?= $form->field($modelEstimationItem, "[{$i}]total")->textInput(['readonly' => true,'class' => 'form-control totalLinePrice'])->label(false); ?>
					</td>
					             
					<td>
						<button type="button" class="add-item btn btn-success"><i class="glyphicon glyphicon-plus"></i></button>
                        <button type="button" class="remove-item btn btn-danger"><i class="glyphicon glyphicon-minus"></i></button>
						<?php
                            // necessary for update action.
                            if (! $modelEstimationItem->isNewRecord) {
                                echo Html::activeHiddenInput($modelEstimationItem, "[{$i}]id");
                            }
                        ?>
                        <?= $form->field($modelEstimationItem, "[{$i}]stock")->hiddenInput(['class'=>''])->label(false); ?>
						<?= $form->field($modelEstimationItem, "[{$i}]productName")->hiddenInput(['class'=>''])->label(false); ?>
                            	
				  </td>
                </tr>
	                    
            <?php endforeach; ?>
			</tbody></table>
            
            </div>
            <?php DynamicFormWidget::end(); ?>
	   

	 <div class="row">
			
			<div class='col-md-3'>
				<?= $form->field($modelEstimation, 'discount')->textInput(['maxlength' => true,'class'=>'form-control changesMade']) ?>
				
			</div>
			<div class='col-md-3'>
				<?= $form->field($modelEstimation, 'roundOff')->textInput(['maxlength' => true,'class'=>'form-control changesMade']) ?>
				
			</div>
				
			
			<div class='col-md-3'>
				<?= $form->field($modelEstimation, 'subTotal')->textInput(['maxlength' => true,'readOnly'=> true]) ?>
				
			</div>
			<div class='col-md-3'>
				<?= $form->field($modelEstimation, 'netTotal')->textInput(['maxlength' => true,'readOnly'=> true]) ?>
				
			</div>
	</div>
     
	<hr> 
	
		<div class='top_boxes' style="display:none;">		
			
            
             <div class="row">
			
					<div class='col-md-3'>
						<?= $form->field($modelEstimation, 'box1_title')->textInput(['maxlength' => true,'class'=>'form-control']) ?>
						
					</div>
					<div class='col-md-3'>
						<?= $form->field($modelEstimation, 'box1_content')->textInput(['maxlength' => true,'class'=>'form-control']) ?>
						
					</div>
					<div class='col-md-3'>
						<?= $form->field($modelEstimation, 'box2_title')->textInput(['maxlength' => true,'class'=>'form-control']) ?>
						
					</div>
					<div class='col-md-3'>
						<?= $form->field($modelEstimation, 'box2_content')->textInput(['maxlength' => true,'class'=>'form-control']) ?>
						
					</div>
						
					
				</div>
				
				<div class="row">
			
					<div class='col-md-3'>
						<?= $form->field($modelEstimation, 'box3_title')->textInput(['maxlength' => true,'class'=>'form-control']) ?>
						
					</div>
					<div class='col-md-3'>
						<?= $form->field($modelEstimation, 'box3_content')->textInput(['maxlength' => true,'class'=>'form-control']) ?>
						
					</div>
					<div class='col-md-3'>
						<?= $form->field($modelEstimation, 'box4_title')->textInput(['maxlength' => true,'class'=>'form-control']) ?>
						
					</div>
					<div class='col-md-3'>
						<?= $form->field($modelEstimation, 'box4_content')->textInput(['maxlength' => true,'class'=>'form-control']) ?>
						
					</div>
						
					
				</div>
           
        </div>
   
	
   
	
    <div class="form-group">
        <?= Html::submitButton($modelEstimationItem->isNewRecord ? 'Create' : 'Update', ['class' => 'btn btn-primary']) ?>
        <?= Html::Button('Settings for top boxes in Estimation', ['class' => 'btn btn-primary toggleTopBox pull-right']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
</div>
</div>
