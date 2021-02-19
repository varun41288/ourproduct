<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\components\Helper;

/* @var $this yii\web\View */
/* @var $model app\models\Estimations */

$this->title = "Estimation No : ".$model->estimationNo;
$this->params['breadcrumbs'][] = ['label' => 'Sales', 'url' => ['index']];
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

<div class="estimations-view">
<div class="box box-default">
<div class="box-body">
     <p>
        <?= Html::a('Print', ['export', 'id' => $model->id], ['class' => 'btn btn-success','target' => '_blank']) ?>
        <?//= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?/*= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) */?>
    </p>
<p class="title">ESTIMATION<br><span> </span></p>
<table width="100%" style="table-layout: fixed;">
	<tr>
		<td width="50%" rowspan="2" colspan="3" class="address">
			<b><?php echo $profile->company_name; ?></b><br>
			<?php echo nl2br($profile->address);	?>
		</td>
		<td width="25%" style="word-wrap:break-word">Estimation No <br><b><?php echo $model->estimationNo; ?></b></td>
		<td width="25%" style="word-wrap:break-word">Dated <br><b><?php echo $model->estimationDate; ?></b></td>
	</tr>	
	<tr>
		<td width="25%" style="word-wrap:break-word"><?php echo $model->box1_title; ?><br><b><?php echo $model->box1_content; ?></b></td>
		<td width="25%" style="word-wrap:break-word"><?php echo $model->box2_title; ?><br><b><?php echo $model->box2_content; ?></b></td>
	</tr>	
	<tr>
		<td width="50%" colspan="3" class="address">
			<b>Buyer</b><br>
			<?php echo $model->customerName; ?> <br>
			<?php echo nl2br($model->customerAddress);	?><br>
			GSTIN : <?php echo $model->customerGstin; ?>
		</td>
		<td width="25%" style="word-wrap:break-word"><?php echo $model->box3_title; ?><br><b><?php echo $model->box3_content; ?></b></td>
		<td width="25%" style="word-wrap:break-word"><?php echo $model->box4_title; ?><br><b><?php echo $model->box4_content; ?></b></td>
	</tr>	
	
</table>		
<table width="100%" class="item-table">		
	<tr class="items">
		<th class="topnone" width="5%">SNO</th>
		<th class="topnone" width="50%">Description of Goods</th>
		<th class="topnone" width="12%">HSN/SAC Code</th>
		<th class="topnone" width="8%">Unit</th>
		<th class="topnone" width="10%">Price</th>
		<th class="topnone" width="10%">Amount</th>
	</tr>
	<?php foreach($model->estimationItems as $key => $estimationItem): 
	
	?>
	<tr class="items">
		<td style="text-align:center;"><?php echo ($key+1); ?></td>
		<td class="left"><?php 
			  echo $estimationItem->productName; 
			  if(!empty($estimationItem->sno)) 
				  echo "<br>SNO:".$estimationItem->sno;
			?></td>
		<td style="text-align:center;"><?php echo $estimationItem->hsnCode; ?></td>
		<td><?php echo $estimationItem->quantity." ".$estimationItem->per; ?></td>
		<td style="text-align:right;"><?php echo Helper::amount_to_money($estimationItem->price); ?></td>
		<td style="text-align:right;"><?php echo Helper::amount_to_money($estimationItem->quantity * $estimationItem->price); ?></td>
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
		<td style="text-align:right;"><?php echo Helper::amount_to_money($model->subTotal); ?></td>
	</tr>
	<?php if($model->roundOff!=""): ?>
	<tr class="items total_items_style">
		<td></td>
		<td class="right">Round Off </td>
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
		<td style="text-align:right;"><?php echo Helper::amount_to_money($model->discount); ?></td>
	</tr>
	<?php endif; ?>	
	 
	<tr class="total_items total_items_style">
		<td></td>
		<td class="right"> TOTAL (Rs.) </td>
		<td></td>
		<td></td>
		<td></td>
		<td style="text-align:right;"><?php echo Helper::amount_to_money($model->netTotal); ?></td>
	</tr>	
	<tr class="total_items total_items_style">
		<td colspan="7" class="left"><span style="font-weight:normal;">Amount Chargeable (in words)</span> <?php echo Helper::decimal_to_words($model->netTotal); ?></td>
	</tr>	
	
</table>

<table>	
	<tr class="">
		<td width="50%" class="left"><span style="font-weight:bold;"><u>Declaration :</u></span> <br> We declare that this estimation shows the actual price of the goods described and that all particulars are true and correct.</td>
		<td width="50%" colspan="4" class="right">For <?php echo $profile->company_name; ?><br><br><br><span style="font-weight:bold;">Authorized Signatory</span> </td>
	</tr>	
		
	
</table>
<p>This is a computer generated estimation.</p>

</div>
</div>
</div>

