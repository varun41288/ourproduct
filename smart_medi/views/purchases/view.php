<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\components\Helper;

/* @var $this yii\web\View */
/* @var $model app\models\Purchases */

$this->title = "Purchase No : ".$model->purchaseNo;
$this->params['breadcrumbs'][] = ['label' => 'Purchases', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
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

<div class="purchases-view">
<div class="box box-default">
<div class="box-body">
     <p>
        <?//= Html::a('Print', ['export', 'id' => $model->id], ['class' => 'btn btn-success','target' => '_blank']) ?>
        <?= Html::a('Back to Purchases', ['index'], ['class' => 'btn btn-primary']) ?>
        <?//= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?/*= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) */?>
    </p>

<table width="100%" style="table-layout: fixed;">
	
	<tr>
		<td width="50%" colspan="3" class="address">
			<b>Supplier</b><br>
			<?php echo $model->supplierName; ?> <br>
			<?php echo nl2br($model->supplierAddress);	?><br>
			GSTIN : <?php echo $model->supplierGstin; ?>
		</td>
		
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
	<?php foreach($model->purchaseItems as $key => $purchaseItem): 
	
	@$tax_bottom_box[$purchaseItem->cgstPer]['total'] += ($purchaseItem->quantity * $purchaseItem->price); 
	@$tax_bottom_box[$purchaseItem->cgstPer]['cgstTot'] += ($purchaseItem->quantity * $purchaseItem->price)*($purchaseItem->cgstPer/100); 
	@$tax_bottom_box[$purchaseItem->sgstPer]['sgstTot'] += ($purchaseItem->quantity * $purchaseItem->price)*($purchaseItem->sgstPer/100);
	@$tax_bottom_box[$purchaseItem->igstPer==""?0:$purchaseItem->igstPer]['igstTot'] += ($purchaseItem->quantity * $purchaseItem->price)*($purchaseItem->igstPer/100); 
	
	?>
	<tr class="items">
		<td style="text-align:center;"><?php echo ($key+1); ?></td>
		<td class="left">
			<?php 
			  echo $purchaseItem->productName; 
			  if(!empty($purchaseItem->sno)) 
				  echo "<br>SNO:".$purchaseItem->sno;
			?>
		</td>
		<td style="text-align:center;"><?php echo $purchaseItem->hsnCode; ?></td>
		<td><?php echo $purchaseItem->quantity." ".$purchaseItem->per; ?></td>
		<td style="text-align:right;"><?php echo ($purchaseItem->cgstPer + $purchaseItem->sgstPer + $purchaseItem->igstPer); ?>%</td>
		<td style="text-align:right;"><?php echo Helper::amount_to_money($purchaseItem->price); ?></td>
		<td style="text-align:right;"><?php echo Helper::amount_to_money($purchaseItem->quantity * $purchaseItem->price); ?></td>
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
			<td style="text-align:right;">'.$tax_bottom_key.'%</td>
			<td style="text-align:right;">'.Helper::amount_to_money(@$tax_bottom_item['cgstTot']).'</td>
			<td style="text-align:right;">'.$tax_bottom_key.'%</td>
			<td style="text-align:right;">'.Helper::amount_to_money(@$tax_bottom_item['sgstTot']).'</td>
			<td style="text-align:right;">'.((@$tax_bottom_item['igstTot']!="")?$tax_bottom_key."%":"").'</td>
			<td style="text-align:right;">'.Helper::amount_to_money(@$tax_bottom_item['igstTot']).'</td>
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

</div>
</div>
</div>

