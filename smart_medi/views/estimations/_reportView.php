<?php

use app\components\Helper;

echo printEstimation($model,$profile,"Original");
echo '<div class="page-break"></div>';
echo printEstimation($model,$profile,"Copy");
	
function printEstimation($model,$profile,$title)
{
	
$content = '<p class="title"> ESTIMATION ('.$title.') <br><span> </span></p>
<table width="100%" style="table-layout: fixed;">
	<tr>
		<td width="50%" rowspan="2" colspan="3" class="address">
			<b>'.$profile->company_name.'</b><br>
			'.nl2br($profile->address).'
		</td>
		<td width="25%" style="word-wrap:break-word">Estimation No <br><b>'.$model->estimationNo.'</b></td>
		<td width="25%" style="word-wrap:break-word">Dated <br><b>'.$model->estimationDate.'</b></td>
	</tr>	
	<tr>
		<td width="25%" style="word-wrap:break-word">'.$model->box1_title.'<br><b>'.$model->box1_content.'</b></td>
		<td width="25%" style="word-wrap:break-word">'.$model->box2_title.'<br><b>'.$model->box2_content.'</b></td>
	</tr>	
	<tr>
		<td width="50%" colspan="3" class="address">
			<b>Buyer</b><br>
			'.$model->customerName.' <br>
			'.nl2br($model->customerAddress).'<br>
			GSTIN : '.$model->customerGstin.'
		</td>
		<td width="25%" style="word-wrap:break-word">'.$model->box3_title.'<br><b>'.$model->box3_content.'</b></td>
		<td width="25%" style="word-wrap:break-word">'.$model->box4_title.'<br><b>'.$model->box4_content.'</b></td>
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
	</tr>';
	foreach($model->estimationItems as $key => $estimationItem) { 
		
	$sno = "";
	if(!empty($estimationItem->sno))
	{
		$sno = "<br> SNO:".$estimationItem->sno;
	}		
	
	$content .='	
	<tr class="items">
		<td style="text-align:center;">'.($key+1).'</td>
		<td class="left">'.$estimationItem->productName.' '.$sno.'</td>
		<td style="text-align:center;">'.$estimationItem->hsnCode.'</td>
		<td>'.$estimationItem->quantity." ".$estimationItem->per.'</td>
		<td style="text-align:right;">'.Helper::amount_to_money($estimationItem->price).'</td>
		<td style="text-align:right;">'.Helper::amount_to_money($estimationItem->quantity * $estimationItem->price).'</td>
		</tr>';
	}
	
	for($i=count($model->estimationItems);$i<8;$i++){
	$content .='	
	<tr class="items">
		<td style="text-align:center;">&nbsp;</td>
		<td class="left">&nbsp;</td>
		<td style="text-align:center;">&nbsp;</td>
		<td>&nbsp;</td>
		<td style="text-align:right;">&nbsp;</td>
		<td style="text-align:right;">&nbsp;</td>
		</tr>';
	}

	
	$content .='
	<tr class="total_items total_items_style">
		<td></td>
		<td class="right"></td>
		<td></td>
		<td></td>
		<td></td>
		<td style="text-align:right;">'.Helper::amount_to_money($model->subTotal).'</td>
	</tr>';
	if($model->roundOff!="") {
	$content .='<tr class="items total_items_style">
		<td></td>
		<td class="right">Round Off </td>
		<td></td>
		<td></td>
		<td></td>
		
		<td style="text-align:right;">'.Helper::amount_to_money($model->roundOff).'</td>
	</tr>';
    }
	if($model->discount!="") {
	$content .='<tr class="items total_items_style">
		<td></td>
		<td class="right">Discount (-) </td>
		<td></td>
		<td></td>
		<td></td>
		
		<td style="text-align:right;">'.Helper::amount_to_money($model->discount).'</td>
	</tr>';
    }
	   	
	$content .=' 
	<tr class="total_items total_items_style">
		<td></td>
		<td class="right">TOTAL (Rs.) </td>
		<td></td>
		<td></td>
		
		<td></td>
		<td style="text-align:right;">'.Helper::amount_to_money($model->netTotal).'</td>
	</tr>	
	<tr class="total_items total_items_style">
		<td colspan="6" class="left"><span style="font-weight:normal;">Amount Chargeable (in words)</span> '.Helper::decimal_to_words($model->netTotal).'</td>
	</tr>	
	
</table>
';
	
	
	
	
	$content .='	
<table>	
	<tr class="">
		<td width="50%" class="left"><span style="font-weight:bold;"><u>Declaration :</u></span> <br> We declare that this estimation shows the actual price of the goods described and that all particulars are true and correct.</td>
		<td width="50%" colspan="4" class="right">For '.$profile->company_name.'<br><br><br><span style="font-weight:bold;">Authorized Signatory</span> </td>
	</tr>	
		
	
</table>
<p>This is a computer generated estimation.</p>';
return $content; 
}
