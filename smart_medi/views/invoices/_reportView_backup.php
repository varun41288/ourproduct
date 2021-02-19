<?php

use app\components\Helper;

echo printInvoice($model,$profile,$user_settings,"Original");
echo '<div class="page-break"></div>';
echo printInvoice($model,$profile,$user_settings,"Copy");
	
function printInvoice($model,$profile,$user_settings,$title)
{
	
$content = '<p class="title">TAX INVOICE ('.$title.') <br><span> </span></p>
<table width="100%" style="table-layout: fixed;">
	<tr>
		<td width="50%" rowspan="2" colspan="3" class="address">';

if($user_settings->value1=='yes')
{			
	$content .= '<table style="border:none;"><tr><td style="border:none;vertical-align:middle;"><img src="'.Yii::getAlias('@web').'/logo.png" width="70" alt=""/></td>
				<td style="border:none;"><b>'.$profile->company_name.'</b><br>
				<span style="font-size:12px;">'.nl2br($profile->address).'</span></td></tr></table>';
}
else
{
	$content .= '<b>'.$profile->company_name.'</b><br>
			'.nl2br($profile->address);
}

$content .='
		</td>
		<td width="25%" style="word-wrap:break-word;">Invoice No <br><b>'.$model->invoiceNo.'</b></td>
		<td width="25%" style="word-wrap:break-word">Dated <br><b>'.$model->invoiceDate.'</b></td>
	</tr>	
	<tr>
		<td width="25%" style="word-wrap:break-word;font-size:12px;">'.$model->box1_title.'<br><b>'.$model->box1_content.'</b></td>
		<td width="25%" style="word-wrap:break-word;font-size:12px;">'.$model->box2_title.'<br><b>'.$model->box2_content.'</b></td>
	</tr>	
	<tr>
		<td width="50%" colspan="3" class="address">
			<b>Buyer</b><br>
			'.$model->customerName.' <br>
			<span style="font-size:12px;">'.nl2br($model->customerAddress).'<br>
			GSTIN : '.$model->customerGstin.'</span>
		</td>
		<td width="25%" style="word-wrap:break-word;font-size:12px;">'.$model->box3_title.'<br><b>'.$model->box3_content.'</b></td>
		<td width="25%" rowspan="2" style="word-wrap:break-word;font-size:12px;">'.$model->box4_title.'<br><b>'.$model->box4_content.'</b></td>
	</tr>	
	
</table>		
<table width="100%" class="item-table">		
	<tr class="items">
		<th class="topnone" width="5%" style="text-align:center;">NO</th>
		<th class="topnone" width="48%" style="text-align:center;">Description of Goods</th>
		<th class="topnone" width="12%" style="text-align:center;">HSN/SAC</th>
		<th class="topnone" width="8%" style="text-align:center;">Unit</th>
		<th class="topnone" width="7%" style="text-align:center;">Tax</th>
		<th class="topnone" width="10%" style="text-align:center;">Price</th>
		<th class="topnone" width="10%" style="text-align:center;">Amount</th>
	</tr>';
	foreach($model->invoiceItems as $key => $invoiceItem) { 
	
	@$tax_bottom_box[$invoiceItem->cgstPer." %"]['total'] += ($invoiceItem->quantity * $invoiceItem->price); 
	@$tax_bottom_box[$invoiceItem->cgstPer." %"]['cgstTot'] += ($invoiceItem->quantity * $invoiceItem->price)*($invoiceItem->cgstPer/100); 
	@$tax_bottom_box[$invoiceItem->sgstPer." %"]['sgstTot'] += ($invoiceItem->quantity * $invoiceItem->price)*($invoiceItem->sgstPer/100);
	@$tax_bottom_box[$invoiceItem->igstPer==""?0:$invoiceItem->igstPer." %"]['igstTot'] += ($invoiceItem->quantity * $invoiceItem->price)*($invoiceItem->igstPer/100); 
	
	$sno = "";
	if(!empty($invoiceItem->sno))
	{
		$sno = "<br>".$invoiceItem->sno;
	}		
	
	$content .='	
	<tr class="items">
		<td style="text-align:center;font-size:12px;">'.($key+1).'</td>
		<td class="left" style="font-size:12px;">'.$invoiceItem->productName.' '.$sno.'</td>
		<td style="text-align:center;font-size:12px;" >'.$invoiceItem->hsnCode.'</td>
		<td style="font-size:12px;">'.$invoiceItem->quantity." ".$invoiceItem->per.'</td>
		<td style="text-align:right;font-size:12px;">'.($invoiceItem->cgstPer + $invoiceItem->sgstPer + $invoiceItem->igstPer).'%</td>
		<td style="text-align:right;font-size:12px;">'.Helper::amount_to_money($invoiceItem->price).'</td>
		<td style="text-align:right;font-size:12px;">'.Helper::amount_to_money($invoiceItem->quantity * $invoiceItem->price).'</td>
		</tr>';
	}
	
	for($i=count($model->invoiceItems);$i<8;$i++){
	$content .='	
	<tr class="items">
		<td style="text-align:center;font-size:12px;">&nbsp;</td>
		<td class="left" style="font-size:12px;">&nbsp;</td>
		<td style="text-align:center;font-size:12px;">&nbsp;</td>
		<td style="font-size:12px;">&nbsp;</td>
		<td style="text-align:right;font-size:12px;">&nbsp;</td>
		<td style="text-align:right;font-size:12px;">&nbsp;</td>
		<td style="text-align:right;font-size:12px;">&nbsp;</td>
		</tr>';
	}

	
	$content .='
	<tr class="total_items total_items_style">
		<td style="border-bottom:none;border-top:none;"></td>
		<td style="border-bottom:none;border-top:none;"></td>
		<td style="border-bottom:none;border-top:none;"></td>
		<td style="border-bottom:none;border-top:none;"></td>
		<td></td>
		<td></td>
		<td style="text-align:right;font-size:12px;">'.Helper::amount_to_money($model->subTotal).'</td>
	</tr>';
	if($model->roundOff!="") {
	$content .='<tr class="items total_items_style">
		<td></td>
		<td class="right" style="font-size:12px;">Round Off </td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td style="text-align:right;font-size:12px;">'.Helper::amount_to_money($model->roundOff).'</td>
	</tr>';
    }
	if($model->discount!="") {
	$content .='<tr class="items total_items_style">
		<td></td>
		<td class="right" style="font-size:12px;">Discount (-) </td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td style="text-align:right;font-size:12px;">'.Helper::amount_to_money($model->discount).'</td>
	</tr>';
    }
	if($model->cgstTotal!=0 && $model->sgstTotal!=0)
	{
	$content .='<tr class="items total_items_style">
		<td></td>
		<td class="right" style="font-size:12px;">CGST</td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td style="text-align:right;font-size:12px;">'.Helper::amount_to_money($model->cgstTotal).'</td>
	</tr>	
	<tr class="items total_items_style">
		<td></td>
		<td class="right" style="font-size:12px;">SGST</td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td style="text-align:right;font-size:12px;">'.Helper::amount_to_money($model->sgstTotal).'</td>
	</tr>';
	}
	if($model->igstTotal!=0)
	{
	$content .='<tr class="items total_items_style">
		<td></td>
		<td class="right" style="font-size:12px;">IGST</td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td style="text-align:right;font-size:12px;">'.Helper::amount_to_money($model->igstTotal).'</td>
	</tr>';
   	}
	$content .=' 
	<tr class="total_items total_items_style">
		<td></td>
		<td class="right" style="font-size:12px;">TOTAL (Rs.) </td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td style="text-align:right;font-size:12px;">'.Helper::amount_to_money($model->netTotal).'</td>
	</tr>	
	<tr class="total_items total_items_style">
		<td colspan="7" class="left" style="font-size:12px;"><span style="font-weight:normal;font-size:12px;">Amount Chargeable (in words)</span> '.Helper::decimal_to_words($model->netTotal).'</td>
	</tr>	
	
</table>

<table width="100%" class="item-table" cellspacing="0" cellpadding="0">		
	<tr class="tax_items total_items_style reduce_height">
		<td rowspan="2" style="font-size:12px;">Taxable Value</td>
		<td colspan="2" style="font-size:12px;">CGST</td>
		<td colspan="2" style="font-size:12px;">SGST</td>
		<td colspan="2" style="font-size:12px;">IGST</td>
	</tr>	
	<tr class="tax_items total_items_style reduce_height">
		<td style="font-size:12px;">Rate</td>
		<td style="font-size:12px;">Amount</td>
		<td style="font-size:12px;">Rate</td>
		<td style="font-size:12px;">Amount</td>
		<td style="font-size:12px;">Rate</td>
		<td style="font-size:12px;">Amount</td>
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
			<td style="text-align:right;font-size:12px;">'.Helper::amount_to_money(@$tax_bottom_item['total']).'</td>
			<td style="text-align:right;font-size:12px;">'.$tax_bottom_key.'</td>
			<td style="text-align:right;font-size:12px;">'.Helper::amount_to_money(@$tax_bottom_item['cgstTot']).'</td>
			<td style="text-align:right;font-size:12px;">'.$tax_bottom_key.'</td>
			<td style="text-align:right;font-size:12px;">'.Helper::amount_to_money(@$tax_bottom_item['sgstTot']).'</td>
			<td style="text-align:right;font-size:12px;">'.((@$tax_bottom_item['igstTot']!="")?$tax_bottom_key:"").'</td>
			<td style="text-align:right;font-size:12px;">'.((@$tax_bottom_item['igstTot']!=0)?Helper::amount_to_money($tax_bottom_item['igstTot']):"").'</td>
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
		<td style="font-size:12px;">&nbsp;</td>
		<td style="font-size:12px;">&nbsp;</td>
		<td style="font-size:12px;">&nbsp;</td>
		<td style="font-size:12px;">&nbsp;</td>
		<td style="font-size:12px;">&nbsp;</td>
		<td style="font-size:12px;">&nbsp;</td>
		<td style="font-size:12px;">&nbsp;</td>
	</tr>';
	}
	
	$content .='<tr class="tax_items reduce_height total_items_style">
		<td style="text-align:right;font-size:12px;">'.Helper::amount_to_money($tax_bottom_total).'</td>
		<td></td>
		<td style="text-align:right;font-size:12px;">'.Helper::amount_to_money($tax_bottom_cgst).'</td>
		<td></td>
		<td style="text-align:right;font-size:12px;">'.Helper::amount_to_money($tax_bottom_sgst).'</td>
		<td></td>
		<td style="text-align:right;font-size:12px;">'.Helper::amount_to_money($tax_bottom_igst).'</td>
	</tr>';
	
	
	$content .='	
	<tr class="total_items total_items_style">
		<td colspan="7" class="left" style="font-size:12px;"><span style="font-weight:normal;font-size:12px;">Tax Amount (in words)</span> '.Helper::decimal_to_words($model->taxTotal).'</td>
	</tr>	
</table>
<table>	
	<tr class="">
		<td width="50%" class="left" style="font-size:12px;"><span style="font-weight:bold;font-size:12px;"><u>Declaration :</u></span> <br> '.$user_settings->value6.'</td>
		<td width="50%" colspan="4" class="right">For '.$profile->company_name.'<br><br><br><span style="font-weight:bold;">Authorized Signatory</span> </td>
	</tr>	
		
	
</table>
<p>This is a computer generated invoice.</p>';
return $content; 
}