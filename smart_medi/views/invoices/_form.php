<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use wbraganca\dynamicform\DynamicFormWidget;
use kartik\date\DatePicker;
use yii\helpers\Url;

$js = '

function calculation(counter)
{
	//quantity = $("#invoiceitems-"+counter+"-quantity").val();
	
	/* stock checking */
	//stock = $("#invoiceitems-"+counter+"-stock").val();
	//if(parseInt(quantity) > parseInt(stock))
	//{
		//alert("Insufficient Stock!");
		//$("#invoiceitems-"+counter+"-quantity").val(1);
	//}
		
	quantity = $("#invoiceitems-"+counter+"-quantity").val();
	if(quantity == "")
		quantity = 1;
	quantity = parseFloat(quantity);	
		
	price = $("#invoiceitems-"+counter+"-price").val();
	price = parseFloat(price);
	total = quantity * price;
		
	cgstPer = $("#invoiceitems-"+counter+"-cgstper").val();
	cgstTot = 0;
	if( total!="" && cgstPer !="" ) 
	{
		cgstPer = parseFloat(cgstPer);
		cgstTot = total*(cgstPer/100);	
	}
		
	sgstPer = $("#invoiceitems-"+counter+"-sgstper").val();
	
	sgstTot = 0;
	if( total!="" && sgstPer !="" ) 
	{
		sgstPer = parseFloat(sgstPer);
		sgstTot = total*(sgstPer/100);	
	}
		
	igstPer = $("#invoiceitems-"+counter+"-igstper").val();
	igstTot = 0;
	if( total!="" && igstPer !="" ) 
	{
		igstPer = parseFloat(igstPer);
		igstTot = total*(igstPer/100);	
	}
	
	$("#invoiceitems-"+counter+"-total").val(total.toFixed(2));	
	$("#invoiceitems-"+counter+"-tax").val((cgstTot+sgstTot+igstTot).toFixed(2));	
	$("#invoiceitems-"+counter+"-cgsttot").val(cgstTot.toFixed(2));	
	$("#invoiceitems-"+counter+"-sgsttot").val(sgstTot.toFixed(2));	
	$("#invoiceitems-"+counter+"-igsttot").val(igstTot.toFixed(2));
	
	
	
	/* CALCULATION FOR ALL TOTAL COLUMNS STARTS */
	subTotal = 0;
	$(".totalLinePrice").each(function(){
		if($(this).val() != "")
			subTotal += parseFloat($(this).val());
	});	
	
	cgstTotal = 0;
	$(".totalLineCgstPrice").each(function(){
		if($(this).val() != "")
			cgstTotal += parseFloat($(this).val());
	});	
	
	sgstTotal = 0;
	$(".totalLineSgstPrice").each(function(){
		if($(this).val() != "")
			sgstTotal += parseFloat($(this).val());
	});	
	
	igstTotal = 0;
	$(".totalLineIgstPrice").each(function(){
		if($(this).val() != "")
			igstTotal += parseFloat($(this).val());
	});	
	
	taxTotal = 0;
	$(".totalLineTaxPrice").each(function(){
		if($(this).val() != "")
			taxTotal += parseFloat($(this).val());
	});	
	
	netTotal = subTotal + taxTotal;
	
	var discount = $("#invoices-discount").val();
	if(discount!="")
	{
		discount = parseFloat($("#invoices-discount").val());
		netTotal = netTotal - discount;
	}
	var roundOff = $("#invoices-roundoff").val()
	if(roundOff!="")
	{
		roundOff = parseFloat($("#invoices-roundoff").val());
		netTotal = netTotal + roundOff;
	}
		
	$("#invoices-subtotal").val(subTotal.toFixed(2));	
	$("#invoices-cgsttotal").val(cgstTotal.toFixed(2));	
	$("#invoices-sgsttotal").val(sgstTotal.toFixed(2));	
	$("#invoices-igsttotal").val(igstTotal.toFixed(2));
	$("#invoices-taxtotal").val(taxTotal.toFixed(2));
	$("#invoices-nettotal").val(netTotal.toFixed(2));	
	
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
					 
					if(data.length === 0)
					{
						$("#invoiceitems-"+counter+"-productnameuser").val("");
					}
					
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
		    $("#invoiceitems-"+counter+"-productid").val(ui.item.data.id);
		    $("#invoiceitems-"+counter+"-productname").val(ui.item.data.productName);
		    $("#invoiceitems-"+counter+"-sno").val(ui.item.data.sno);
		    $("#invoiceitems-"+counter+"-hsncode").val(ui.item.data.hsnCode);
		    $("#invoiceitems-"+counter+"-price").val(ui.item.data.price);
		    $("#invoiceitems-"+counter+"-per").val(ui.item.data.per);
		    $("#invoiceitems-"+counter+"-quantity").val(1);
		    $("#invoiceitems-"+counter+"-cgstper").val(ui.item.data.cgstPer);
		    $("#invoiceitems-"+counter+"-sgstper").val(ui.item.data.sgstPer);
		    $("#invoiceitems-"+counter+"-igstper").val(ui.item.data.igstPer);
		    $("#invoiceitems-"+counter+"-brand").val(ui.item.data.brand);
		    $("#invoiceitems-"+counter+"-model").val(ui.item.data.model);
		    $("#invoiceitems-"+counter+"-stock").val(ui.item.data.stock);
		    
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
					 if(data.length === 0)
					{
						$("#invoices-customername").val("");
					} 
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
		    $("#invoices-customeraddress").val(ui.item.data.customerAddress); 
			$("#invoices-customergstin").val(ui.item.data.customerGstin); 
		}		      	
	});
});

$(document).on("change keyup blur",".changesMade",function(){
	id_string = $(this).attr("id");
	id_array = id_string.split("-");
	counter = id_array[1];
	calculation(counter);
});

//$(document).on("click",".toggleTopBox",function(){
	//$(".top_boxes").slideToggle("slow");
//});


';

$this->registerJs($js);
?>

	
		
<div class="invoice-form">
<div class="box box-default">
<div class="box-body">
    <?php $form = ActiveForm::begin(['id' => 'invoice-form']); ?>
    <div class="row">
		<div class='col-lg-4'>
			<div class="form-group">
			
				<?php 
				echo '<label>Invoice Date</label>';
				echo DatePicker::widget([
					'model' => $modelInvoice, 
					'attribute' => 'invoiceDate',
					'readonly' => true,
					'removeButton' => false,
					'type' => DatePicker::TYPE_COMPONENT_APPEND,
					'options' => ['placeholder' => 'Enter invoice date ...'],
					'pluginOptions' => [
						'todayHighlight' => true,
						 'format' => 'dd/mm/yyyy'
					]
				]);
						
				?>
			</div>
			<div class="form-group">
				<?= $form->field($modelInvoice, 'invoiceNo')->textInput(['maxlength' => true,'value' => $invoiceNo,'readOnly'=> true]) ?>
				
			</div>
		</div>
	     <div class='col-lg-4'>
			<div class="form-group">
				<?= $form->field($modelInvoice, 'customerName')->textInput(['maxlength' => true,'class'=>'form-control autocomplete_customer','placeholder'=>'Find by patient name']) ?>
				
			</div>
			<div class="form-group">
				<?= $form->field($modelInvoice, 'customerGstin')->textInput(['maxlength' => true]) ?>
			</div>
		</div>
	    <div class='col-lg-4'>
			<div class="form-group">
				<?= $form->field($modelInvoice, 'customerAddress')->textArea(['rows' => 4]) ?>
			</div>
		</div>
		
				
				
    </div>
     
             <?php DynamicFormWidget::begin([
                'widgetContainer' => 'dynamicform_wrapper', // required: only alphanumeric characters plus "_" [A-Za-z0-9_]
                'widgetBody' => '.container-items', // required: css class selector
                'widgetItem' => '.item', // required: css class
                'limit' => 100, // the maximum times, an element can be cloned (default 999)
                'min' => 1, // 0 or 1 (default 1)
                'insertButton' => '.add-item', // css class
                'deleteButton' => '.remove-item', // css class
                'model' => $modelInvoiceItems[0],
                'formId' => 'invoice-form',
                'formFields' => [
                    'productName',
                    'sno',
                    'hsnCode',
                    'per',
                    'price',
                    'cgstper',
                    'sgstper',
                    'igstper',
                    'brand',
                    'model',
                ],
            ]); ?>
			<div class="box-body table-responsive no-padding">
              <table class="table text-center">
              	<tr>
				  	
                  <th width="12%">Product</th>
                  <th width="10%">SNO</th>
                  <th width="10%">HSN/SAC</th>
                  <th width="10%">Price</th>
                  <th width="6%">Qty</th>
                  <th width="6%">Unit</th>
                  <th width="9%">Total</th>
                  <th width="6%">Cgst %</th>
                  <th width="6%">Sgst %</th>
                  <th width="6%">Igst %</th>
                  <th width="9%">Tax</th>
                  <th width="10%"></th>
				  
                </tr>
             <tbody class="container-items"> 	
             <?php foreach ($modelInvoiceItems as $i => $modelInvoiceItem): ?>
			
				<tr class="item">
				  
                  <td>
						
						<?= $form->field($modelInvoiceItem, "[{$i}]productNameUser")->textInput(['maxlength' => true,'class'=>'form-control autocomplete_product','placeholder'=>'Name or Code'])->label(false); ?>
                  </td>
				   <td>
						<?= $form->field($modelInvoiceItem, "[{$i}]sno")->textInput(['maxlength' => true,'class'=>'form-control'])->label(false); ?>
				  </td>
                  <td>
						<?= $form->field($modelInvoiceItem, "[{$i}]hsnCode")->textInput(['maxlength' => true,'class'=>'form-control'])->label(false); ?>
				  </td>
                  <td>
						<?= $form->field($modelInvoiceItem, "[{$i}]price")->textInput(['maxlength' => true,'class'=>'form-control changesMade'])->label(false); ?>
				  </td>
				  <td>
						<?= $form->field($modelInvoiceItem, "[{$i}]quantity")->textInput(['maxlength' => true,'class'=>'form-control changesMade'])->label(false); ?>
					</td>
					
					<td>
						<?= $form->field($modelInvoiceItem, "[{$i}]per")->textInput(['maxlength' => true])->label(false); ?>
					</td>
					
					<td>
						<?= $form->field($modelInvoiceItem, "[{$i}]total")->textInput(['readonly' => true,'class' => 'form-control totalLinePrice'])->label(false); ?>
					</td>
																	  
					<td>
						<?= $form->field($modelInvoiceItem, "[{$i}]cgstPer")->textInput(['maxlength' => true,'class'=>'form-control changesMade'])->label(false); ?>
					 </td>
					<td>
						<?= $form->field($modelInvoiceItem, "[{$i}]sgstPer")->textInput(['maxlength' => true,'class'=>'form-control changesMade'])->label(false); ?>
						
					</td>
					<td>
						<?= $form->field($modelInvoiceItem, "[{$i}]igstPer")->textInput(['maxlength' => true,'class'=>'form-control changesMade'])->label(false); ?>
					   
					</td>
							 
				 
					<td>
						<?= $form->field($modelInvoiceItem, "[{$i}]tax")->textInput(['readonly' => true,'class' => 'form-control totalLineTaxPrice'])->label(false); ?>
						
					</td>              
					<td>
						<button type="button" class="add-item btn btn-success"><i class="glyphicon glyphicon-plus"></i></button>
                        <button type="button" class="remove-item btn btn-danger"><i class="glyphicon glyphicon-minus"></i></button>
                        <?php
                            // necessary for update action.
                            if (! $modelInvoiceItem->isNewRecord) {
                                echo Html::activeHiddenInput($modelInvoiceItem, "[{$i}]id");
                            }
                        ?>
                        
                            <?= $form->field($modelInvoiceItem, "[{$i}]cgstTot")->hiddenInput(['class'=>'totalLineCgstPrice'])->label(false); ?>
                            <?= $form->field($modelInvoiceItem, "[{$i}]sgstTot")->hiddenInput(['class'=>'totalLineSgstPrice'])->label(false); ?>
                            <?= $form->field($modelInvoiceItem, "[{$i}]igstTot")->hiddenInput(['class'=>'totalLineIgstPrice'])->label(false); ?>
                            <?= $form->field($modelInvoiceItem, "[{$i}]stock")->hiddenInput(['class'=>''])->label(false); ?>
                            <?= $form->field($modelInvoiceItem, "[{$i}]productName")->hiddenInput(['class'=>''])->label(false); ?>    	
				  </td>
                </tr>
	                    
            <?php endforeach; ?>
			</tbody></table>
            
            </div>
            <?php DynamicFormWidget::end(); ?>
	
     <div class="row">
		<div class='col-md-3'>
				<?= $form->field($modelInvoice, 'cgstTotal')->textInput(['maxlength' => true,'readOnly'=> true]) ?>
				
			</div>
			<div class='col-md-3'>
				<?= $form->field($modelInvoice, 'sgstTotal')->textInput(['maxlength' => true,'readOnly'=> true]) ?>
				
			</div>
			<div class='col-md-3'>
				<?= $form->field($modelInvoice, 'igstTotal')->textInput(['maxlength' => true,'readOnly'=> true]) ?>
				
			</div>
			<div class='col-md-3'>
				<?= $form->field($modelInvoice, 'taxTotal')->textInput(['maxlength' => true,'readOnly'=> true]) ?>
			</div>	
	  </div>

	 <div class="row">
			
			<div class='col-md-3'>
				<?= $form->field($modelInvoice, 'discount')->textInput(['maxlength' => true,'class'=>'form-control changesMade']) ?>
				
			</div>
			<div class='col-md-3'>
				<?= $form->field($modelInvoice, 'roundOff')->textInput(['maxlength' => true,'class'=>'form-control changesMade']) ?>
				
			</div>
				
			
			<div class='col-md-3'>
				<?= $form->field($modelInvoice, 'subTotal')->textInput(['maxlength' => true,'readOnly'=> true]) ?>
				
			</div>
			<div class='col-md-3'>
				<?= $form->field($modelInvoice, 'netTotal')->textInput(['maxlength' => true,'readOnly'=> true]) ?>
				
			</div>
	</div>
     
	<hr> 
	
		<div class='top_boxes'>		
			
            
             <div class="row">
			
					<div class='col-md-2'>
						<?= $form->field($modelInvoice, 'box1_title')->textInput(['maxlength' => true,'class'=>'form-control'])->label(false) ?>
						<?= $form->field($modelInvoice, 'box1_content')->textInput(['maxlength' => true,'class'=>'form-control'])->label(false) ?>
						
					</div>
					<div class='col-md-2'>
						<?= $form->field($modelInvoice, 'box2_title')->textInput(['maxlength' => true,'class'=>'form-control'])->label(false) ?>
						<?= $form->field($modelInvoice, 'box2_content')->textInput(['maxlength' => true,'class'=>'form-control'])->label(false) ?>
						
					</div>
					<div class='col-md-2'>
						<?= $form->field($modelInvoice, 'box3_title')->textInput(['maxlength' => true,'class'=>'form-control'])->label(false) ?>
						<?= $form->field($modelInvoice, 'box3_content')->textArea(['rows' => 3,'class'=>'form-control'])->label(false) ?>
						
					</div>
					<div class='col-md-3'>
						<?= $form->field($modelInvoice, 'box4_title')->textInput(['maxlength' => true,'class'=>'form-control'])->label(false) ?>
						<?= $form->field($modelInvoice, 'box4_content')->textInput(['maxlength' => true,'class'=>'form-control'])->label(false) ?>
					</div>
					
					<div class='col-md-3'>
						<?= $form->field($modelInvoice, 'box5_title')->textInput(['maxlength' => true,'class'=>'form-control'])->label(false) ?>
						<?= $form->field($modelInvoice, 'box5_content')->textInput(['maxlength' => true,'class'=>'form-control'])->label(false) ?>
					</div>
						
					
				</div>
           
        </div>
   
	
   
	
    <div class="form-group">
        <?= Html::submitButton($modelInvoiceItem->isNewRecord ? 'Create' : 'Update', ['class' => 'btn btn-primary']) ?>
        
    </div>

    <?php ActiveForm::end(); ?>

</div>
</div>
</div>
