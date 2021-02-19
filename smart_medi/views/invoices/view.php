<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\components\Helper;

/* @var $this yii\web\View */
/* @var $model app\models\Invoices */

$this->title = "Invoice No : ".$model->invoiceNo;
$this->params['breadcrumbs'][] = ['label' => 'Sales', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;


$box5_title = "";
if(!empty($model->userAttributes[0]->value) && !empty($model->userAttributes[0]->key))
	if($model->userAttributes[0]->key=='box5_title')
		$box5_title = $model->userAttributes[0]->value;

$box5_content = "";
if(!empty($model->userAttributes[1]->value) && !empty($model->userAttributes[1]->key))
	if($model->userAttributes[1]->key=='box5_content')
		$box5_content = $model->userAttributes[1]->value;	
	


?>

<style>
table {
    border-collapse: collapse;
}
table, td, th {
    border: 1px solid black;
    padding:5px;
    vertical-align:top;
}
.items td {border-bottom:none;border-top:none;height:10px;}
.total_items td {border-bottom:none;font-weight:bold;}
.total_items_style td {font-weight:bold;}
.topnone {border-top:none !important;}
.right {text-align:right !important;}
p {text-align:center;}
.title {font-weight:bold;}
.title span {font-weight:normal;}

</style>

<div class="invoices-view">
<div class="box box-default">
<div class="box-body">
     <p>
        <?= Html::a('Print', ['export', 'id' => $model->id], ['class' => 'btn btn-success','target' => '_blank']) ?>
       
    </p>
<p class="title">TAX INVOICE<br><span> </span></p>
<table width="100%" style="table-layout: fixed;">
	<tr>
		<td width="50%" rowspan="2" colspan="3" class="address">
			
		<?php if($user_settings->value1=='yes'): ?>
			<div style="float:left;height:120px;padding-top:10px;padding-right:5px;"><img src="<?= Yii::getAlias('@web') ?>/logo.png" width="100" alt=""/></div>
    		<div><b><?php echo $profile->company_name; ?></b><br>
			<?php echo nl2br($profile->address);	?></div>
		<?php else: ?>
		
			<b><?php echo $profile->company_name; ?></b><br>
			<?php echo nl2br($profile->address);	?>

		<?php endif; ?>	



		</td>
		<td width="25%" style="word-wrap:break-word">Invoice No <br><b><?php echo $model->invoiceNo; ?></b></td>
		<td width="25%" style="word-wrap:break-word">Dated <br><b><?php echo $model->invoiceDate; ?></b></td>
	</tr>	
	<tr>
		<td width="25%" style="word-wrap:break-word"><?php echo $model->box1_title; ?><br><b><?php echo $model->box1_content; ?></b></td>
		<td width="25%" style="word-wrap:break-word"><?php echo $model->box2_title; ?><br><b><?php echo $model->box2_content; ?></b></td>
	</tr>	
	<tr>
		<td width="50%" colspan="3" rowspan="2" class="address">
			<b>Patient</b><br>
			<?php echo $model->customerName; ?> <br>
			<?php echo nl2br($model->customerAddress);	?><br>
			GSTIN : <?php echo $model->customerGstin; ?>
		</td>
		<td width="25%" rowspan="2" style="word-wrap:break-word"><?php echo $model->box3_title; ?><br><b><?php echo nl2br($model->box3_content); ?></b></td>
		<td width="25%" style="word-wrap:break-word;"><?php echo $model->box4_title; ?><br><b><?php echo $model->box4_content; ?></b></td>
		
	</tr>
	<tr>
		<td width="25%" style="word-wrap:break-word;"><?php echo $box5_title; ?><br><b><?php echo $box5_content; ?></b></td>
			
	</tr>		
	
</table>		
<table width="100%" class="item-table">		
	<tr class="items">
		<th class="topnone" width="5%">SNO</th>
		<th class="topnone" width="50%">Description of Goods</th>
		<th class="topnone" width="12%">HSN/SAC Code</th>
		<th class="topnone" width="8%">Unit</th>
		<th class="topnone" width="5%">Tax</th>
		<th class="topnone" width="10%">Price</th>
		<th class="topnone" width="10%">Amount</th>
	</tr>
	<?php foreach($model->invoiceItems as $key => $invoiceItem): 
	
	@$tax_bottom_box[$invoiceItem->cgstPer." %"]['total'] += ($invoiceItem->quantity * $invoiceItem->price); 
	@$tax_bottom_box[$invoiceItem->cgstPer." %"]['cgstTot'] += ($invoiceItem->quantity * $invoiceItem->price)*($invoiceItem->cgstPer/100); 
	@$tax_bottom_box[$invoiceItem->sgstPer." %"]['sgstTot'] += ($invoiceItem->quantity * $invoiceItem->price)*($invoiceItem->sgstPer/100);
	@$tax_bottom_box[$invoiceItem->igstPer==""?0:$invoiceItem->igstPer." %"]['igstTot'] += ($invoiceItem->quantity * $invoiceItem->price)*($invoiceItem->igstPer/100); 
	
	?>
	<tr class="items">
		<td style="text-align:center;"><?php echo ($key+1); ?></td>
		<td class="left"><?php 
			  echo $invoiceItem->productName; 
			  if(!empty($invoiceItem->sno)) 
				  echo "<br>SNO:".$invoiceItem->sno;
			?></td>
		<td style="text-align:center;"><?php echo $invoiceItem->hsnCode; ?></td>
		<td><?php echo $invoiceItem->quantity." ".$invoiceItem->per; ?></td>
		<td style="text-align:right;"><?php echo ($invoiceItem->cgstPer + $invoiceItem->sgstPer + $invoiceItem->igstPer); ?>%</td>
		<td style="text-align:right;"><?php echo Helper::amount_to_money($invoiceItem->price); ?></td>
		<td style="text-align:right;"><?php echo Helper::amount_to_money($invoiceItem->quantity * $invoiceItem->price); ?></td>
		</tr>
	<?php endforeach; 
	
	//~ echo "<pre>";
	//~ print_r($tax_bottom_box);
	//~ exit;
	
	?>
	<tr class="total_items total_items_style">
		<td></td>
		<td class="right"></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td style="text-align:right;"><?php echo Helper::amount_to_money($model->subTotal); ?></td>
	</tr>
	<?php if($model->roundOff!=""): ?>
	<tr class="items total_items_style">
		<td></td>
		<td class="right">Round Off </td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td style="text-align:right;"><?php echo Helper::amount_to_money($model->roundOff); ?></td>
	</tr>
	<?php endif; ?>	
	<?php if($model->discount!=""): ?>
	<tr class="items total_items_style">
		<td></td>
		<td class="right">Discount (-) </td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td style="text-align:right;"><?php echo Helper::amount_to_money($model->discount); ?></td>
	</tr>
	<?php endif; ?>	
	<tr class="items total_items_style">
		<td></td>
		<td class="right">CGST</td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td style="text-align:right;"><?php echo Helper::amount_to_money($model->cgstTotal); ?></td>
	</tr>	
	<tr class="items total_items_style">
		<td></td>
		<td class="right">SGST</td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td style="text-align:right;"><?php echo Helper::amount_to_money($model->sgstTotal); ?></td>
	</tr>
	<tr class="items total_items_style">
		<td></td>
		<td class="right">IGST</td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td style="text-align:right;"><?php echo Helper::amount_to_money($model->igstTotal); ?></td>
	</tr>
	 
	<tr class="total_items total_items_style">
		<td></td>
		<td class="right"> TOTAL (Rs.) </td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td style="text-align:right;"><?php echo Helper::amount_to_money($model->netTotal); ?></td>
	</tr>	
	<tr class="total_items total_items_style">
		<td colspan="7" class="left"><span style="font-weight:normal;">Amount Chargeable (in words)</span> <?php echo Helper::decimal_to_words($model->netTotal); ?></td>
	</tr>	
	
</table>

<table width="100%" class="item-table" cellspacing="0" cellpadding="0">		
	<tr class="tax_items total_items_style reduce_height">
		<td rowspan="2">Taxable Value</td>
		<td colspan="2">CGST</td>
		<td colspan="2">SGST</td>
		<td colspan="2">IGST</td>
	</tr>	
	<tr class="tax_items total_items_style reduce_height">
		<td>Rate</td>
		<td>Amount</td>
		<td>Rate</td>
		<td>Amount</td>
		<td>Rate</td>
		<td>Amount</td>
	</tr>
	<?php
	$tax_bottom_total = 0;
	$tax_bottom_cgst = 0;
	$tax_bottom_sgst = 0;
	$tax_bottom_igst = 0;
	$tax_counter = 1;
	$html = "";
	
		
	foreach($tax_bottom_box as $tax_bottom_key=>$tax_bottom_item)
	{
		if($tax_bottom_key!=0)
		{	
		$html .= '<tr class="items reduce_height">
			<td style="text-align:right;">'.Helper::amount_to_money(@$tax_bottom_item['total']).'</td>
			<td style="text-align:right;">'.$tax_bottom_key.'</td>
			<td style="text-align:right;">'.Helper::amount_to_money(@$tax_bottom_item['cgstTot']).'</td>
			<td style="text-align:right;">'.$tax_bottom_key.'</td>
			<td style="text-align:right;">'.Helper::amount_to_money(@$tax_bottom_item['sgstTot']).'</td>
			<td style="text-align:right;">'.((@$tax_bottom_item['igstTot']!="")?$tax_bottom_key:"").'</td>
			<td style="text-align:right;">'.((@$tax_bottom_item['igstTot']!=0)?Helper::amount_to_money($tax_bottom_item['igstTot']):"").'</td>
		</tr>';
		@$tax_bottom_total += $tax_bottom_item['total'];
		@$tax_bottom_cgst += $tax_bottom_item['cgstTot'];
		@$tax_bottom_sgst += $tax_bottom_item['sgstTot'];
		@$tax_bottom_igst += $tax_bottom_item['igstTot'];
		$tax_counter++;
		}
	}
	for($i=$tax_counter;$i<=2;$i++)
	{
		$html .= '<tr class="items reduce_height">
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>';
	}
	
	$html .='<tr class="tax_items reduce_height total_items_style">
		<td style="text-align:right;">'.Helper::amount_to_money($tax_bottom_total).'</td>
		<td></td>
		<td style="text-align:right;">'.Helper::amount_to_money($tax_bottom_cgst).'</td>
		<td></td>
		<td style="text-align:right;">'.Helper::amount_to_money($tax_bottom_sgst).'</td>
		<td></td>
		<td style="text-align:right;">'.Helper::amount_to_money($tax_bottom_igst).'</td>
	</tr>'; ?>
	<?php echo $html; ?>	
	<tr class="total_items total_items_style">
		<td colspan="7" class="left"><span style="font-weight:normal;">Tax Amount (in words)</span> <?php echo Helper::decimal_to_words($model->taxTotal); ?></td>
	</tr>	
</table>
<table width="100%">	
	<tr class="">
		
		<td width="50%" colspan="4" style="font-weight:bold;">Receiver Sign with Seal<br><br>&nbsp;</td>
		<td width="50%" class="left"><span><u>Bank details :</u></span> <br> <span><?php echo $user_settings->value3; ?></span></td>
	</tr>	
		
	
</table>
<table>	
	<tr class="">
		<td width="50%" class="left"><span style="font-weight:bold;"><u>Declaration :</u></span> <br> <?php echo $user_settings->value6; ?></td>
		<td width="50%" colspan="4" class="right">For <?php echo $profile->company_name; ?><br><br><br><span style="font-weight:bold;">Authorized Signatory</span> </td>
	</tr>	
		
	
</table>
<p>This is a computer generated invoice.</p>

</div>
</div>
</div>

