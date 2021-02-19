<?php

use app\components\Helper;

echo printPurchase($model,$profile,"Original");
echo '<div class="page-break"></div>';
echo printPurchase($model,$profile,"Copy");
	
function printPurchase($model,$profile,$title)
{
	
$content = '<p class="title">TAX PURCHASE ('.$title.') <br><span> </span></p>
<table width="100%" style="table-layout: fixed;">
	<tr>
		<td width="50%" rowspan="2" colspan="3" class="address">
			<b>'.$profile->company_name.'</b><br>
			'.nl2br($profile->address).'
		</td>
		<td width="25%" style="word-wrap:break-word">Purchase No <br><b>'.$model->purchaseNo.'</b></td>
		<td width="25%" style="word-wrap:break-word">Dated <br><b>'.$model->purchaseDate.'</b></td>
	</tr>	
	<tr>
		<td width="25%" style="word-wrap:break-word">'.$model->box1_title.'<br><b>'.$model->box1_content.'</b></td>
		<td width="25%" style="word-wrap:break-word">'.$model->box2_title.'<br><b>'.$model->box2_content.'</b></td>
	</tr>	
	<tr>
		<td width="50%" colspan="3" class="address">
			<b>Supplier</b><br>
			'.$model->supplierName.' <br>
			'.nl2br($model->supplierAddress).'<br>
			GSTIN : '.$model->supplierGstin.'
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
		<th class="topnone" width="5%">Tax</th>
		<th class="topnone" width="10%">Price</th>
		<th class="topnone" width="10%">Amount</th>
	</tr>';
	foreach($model->purchaseItems as $key => $purchaseItem) { 
	
	@$tax_bottom_box[$purchaseItem->cgstPer]['total'] += ($purchaseItem->quantity * $purchaseItem->price); 
	@$tax_bottom_box[$purchaseItem->cgstPer]['cgstTot'] += ($purchaseItem->quantity * $purchaseItem->price)*($purchaseItem->cgstPer/100); 
	@$tax_bottom_box[$purchaseItem->sgstPer]['sgstTot'] += ($purchaseItem->quantity * $purchaseItem->price)*($purchaseItem->sgstPer/100);
	@$tax_bottom_box[$purchaseItem->igstPer==""?0:$purchaseItem->igstPer]['igstTot'] += ($purchaseItem->quantity * $purchaseItem->price)*($purchaseItem->igstPer/100); 
	
	$sno = "";
	if(!empty($purchaseItem->sno))
	{
		$sno = "<br> SNO:".$purchaseItem->sno;
	}
	
	$content .='	
	<tr class="items">
		<td style="text-align:center;">'.($key+1).'</td>
		<td class="left">'.$purchaseItem->productName.' '.$sno.'</td>
		<td style="text-align:center;">'.$purchaseItem->hsnCode.'</td>
		<td>'.$purchaseItem->quantity." ".$purchaseItem->per.'</td>
		<td style="text-align:right;">'.($purchaseItem->cgstPer + $purchaseItem->sgstPer + $purchaseItem->igstPer).'%</td>
		<td style="text-align:right;">'.Helper::amount_to_money($purchaseItem->price).'</td>
		<td style="text-align:right;">'.Helper::amount_to_money($purchaseItem->quantity * $purchaseItem->price).'</td>
		</tr>';
	}
	
	for($i=count($model->purchaseItems);$i<8;$i++){
	$content .='	
	<tr class="items">
		<td style="text-align:center;">&nbsp;</td>
		<td class="left">&nbsp;</td>
		<td style="text-align:center;">&nbsp;</td>
		<td>&nbsp;</td>
		<td style="text-align:right;">&nbsp;</td>
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
		<td></td>
		<td style="text-align:right;">'.Helper::amount_to_money($model->discount).'</td>
	</tr>';
    }
	$content .='<tr class="items total_items_style">
		<td></td>
		<td class="right">CGST</td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td style="text-align:right;">'.Helper::amount_to_money($model->cgstTotal).'</td>
	</tr>	
	<tr class="items total_items_style">
		<td></td>
		<td class="right">SGST</td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td style="text-align:right;">'.Helper::amount_to_money($model->sgstTotal).'</td>
	</tr>
	<tr class="items total_items_style">
		<td></td>
		<td class="right">IGST</td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td style="text-align:right;">'.Helper::amount_to_money($model->igstTotal).'</td>
	</tr>';
   	
	$content .=' 
	<tr class="total_items total_items_style">
		<td></td>
		<td class="right">TOTAL (Rs.) </td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td style="text-align:right;">'.Helper::amount_to_money($model->netTotal).'</td>
	</tr>	
	<tr class="total_items total_items_style">
		<td colspan="7" class="left"><span style="font-weight:normal;">Amount Chargeable (in words)</span> '.Helper::decimal_to_words($model->netTotal).'</td>
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
	</tr>';
	
	$tax_bottom_total = 0;
	$tax_bottom_cgst = 0;
	$tax_bottom_sgst = 0;
	$tax_bottom_igst = 0;
	$tax_counter = 1;
	
	foreach($tax_bottom_box as $tax_bottom_key=>$tax_bottom_item)
	{
		if($tax_bottom_key!=0)
		{	
		$content .= '<tr class="items reduce_height">
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
		$content .= '<tr class="items reduce_height">
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>';
	}
	
	$content .='<tr class="tax_items reduce_height total_items_style">
		<td style="text-align:right;">'.Helper::amount_to_money($tax_bottom_total).'</td>
		<td></td>
		<td style="text-align:right;">'.Helper::amount_to_money($tax_bottom_cgst).'</td>
		<td></td>
		<td style="text-align:right;">'.Helper::amount_to_money($tax_bottom_sgst).'</td>
		<td></td>
		<td style="text-align:right;">'.Helper::amount_to_money($tax_bottom_igst).'</td>
	</tr>';
	
	
	$content .='	
	<tr class="total_items total_items_style">
		<td colspan="7" class="left"><span style="font-weight:normal;">Tax Amount (in words)</span> '.Helper::decimal_to_words($model->taxTotal).'</td>
	</tr>	
</table>
<table>	
	<tr class="">
		<td width="50%" class="left"><span style="font-weight:bold;"><u>Declaration :</u></span> <br> We declare that this purchase shows the actual price of the goods described and that all particulars are true and correct.</td>
		<td width="50%" colspan="4" class="right">For '.$profile->company_name.'<br><br><br><span style="font-weight:bold;">Authorized Signatory</span> </td>
	</tr>	
		
	
</table>
<p>This is a computer generated purchase.</p>';
return $content; 
}
