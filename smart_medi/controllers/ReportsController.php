<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\base\DynamicModel;

use app\models\Invoices;
use app\models\Purchases;
use app\models\Estimations;
use app\models\Customers;
use app\models\Suppliers;
use app\models\Products;
use app\components\Helper;

/**
 * ReportsController implements the CRUD actions for Reports model.
 */
class ReportsController extends Controller
{
    
    public function actionIndex()
    {
        $model = new DynamicModel(['date_range', 'type', 'wise','customer','supplier']);
		$model->addRule(['wise','type'], 'required');
		$model->addRule(['date_range','customer','supplier'], 'safe');
	 
		if($model->load(Yii::$app->request->post())){
			
			if($model->wise==1 && !empty($model->date_range))
			{	
				
				$dates = explode("-",$model->date_range);
				$date_format_array_from = explode("/",$dates[0]);
				$date_format_array_to = explode("/",$dates[1]);
				$sqlite_format_from = trim($date_format_array_from[2]).trim($date_format_array_from[1]).trim($date_format_array_from[0]);
				$sqlite_format_to = trim($date_format_array_to[2]).trim($date_format_array_to[1]).trim($date_format_array_to[0]);
				
				if($model->type=="sales")
				{
					$sales = Invoices::find()
									->JoinWith('invoiceItems')
									->where("substr(invoiceDate,7)||substr(invoiceDate,4,2)||substr(invoiceDate,1,2) between '".$sqlite_format_from."' AND '".$sqlite_format_to."'")
									->orderBy(['substr(invoiceDate,7)||substr(invoiceDate,4,2)||substr(invoiceDate,1,2)' => SORT_ASC])
									->all();
					$this->salesExport($sales,$model->date_range);
				}
				if($model->type=="purchase")
				{
					$purchases = Purchases::find()
									->JoinWith('purchaseItems')
									->where("substr(purchaseDate,7)||substr(purchaseDate,4,2)||substr(purchaseDate,1,2) between '".$sqlite_format_from."' AND '".$sqlite_format_to."'")
									->orderBy(['substr(purchaseDate,7)||substr(purchaseDate,4,2)||substr(purchaseDate,1,2)' => SORT_ASC])
									->all();
					$this->purchasesExport($purchases,$model->date_range);
				}
				if($model->type=="estimation")
				{
					$estimations = Estimations::find()
									->JoinWith('estimationItems')
									->where("substr(estimationDate,7)||substr(estimationDate,4,2)||substr(estimationDate,1,2) between '".$sqlite_format_from."' AND '".$sqlite_format_to."'")
									->orderBy(['substr(estimationDate,7)||substr(estimationDate,4,2)||substr(estimationDate,1,2)' => SORT_ASC])
									->all();
					$this->estimationsExport($estimations,$model->date_range);
				}
			}
			elseif($model->wise==2 && !empty($_POST['customer_id']) && (!empty($model->customer) || !empty($model->supplier)))
			{
				$model->date_range = "All";
				if($model->type=="sales")
				{
					$sales = Invoices::find()
									->JoinWith('invoiceItems')
									->where(" customerGstin = '".$_POST['customer_id']."' AND customerName = '".$model->customer."' ")
									->orderBy(['substr(invoiceDate,7)||substr(invoiceDate,4,2)||substr(invoiceDate,1,2)' => SORT_DESC])
									->all();
					$this->salesExport($sales,$model->date_range);
				}
				if($model->type=="purchase")
				{
					$purchases = Purchases::find()
									->JoinWith('purchaseItems')
									->where(" supplierGstin = '".$_POST['supplier_id']."' AND supplierName = '".$model->supplier."' ")
									->orderBy(['substr(purchaseDate,7)||substr(purchaseDate,4,2)||substr(purchaseDate,1,2)' => SORT_DESC])
									->all();
					$this->purchasesExport($purchases,$model->date_range);
				}
				if($model->type=="estimation")
				{
					$estimations = Estimations::find()
									->JoinWith('estimationItems')
									->where(" customerGstin = '".$_POST['customer_id']."' AND customerName = '".$model->customer."' ")
									->orderBy(['substr(estimationDate,7)||substr(estimationDate,4,2)||substr(estimationDate,1,2)' => SORT_DESC])
									->all();
					$this->estimationsExport($estimations,$model->date_range);
				}
			}
			else
				$model->wise = 1;	
			//return $this->redirect(['index']);
		}
		else
			$model->wise = 1;
        return $this->render('index', ['model'=>$model]);
    }
    
    
    public function actionCustomers()
    {
		
		$customers = Customers::find()->orderBy(['customerName' => SORT_ASC])->all();
				
		$objPHPExcel = new \PHPExcel();
		$sheet=0;
		$objPHPExcel->setActiveSheetIndex($sheet);
		 
			$common_styles = array(
				'font'  => array(
					//'bold'  => true,
					'size'  => 8,
					'name'  => 'Verdana'
				));
							
			$special_styles = array(
				'font'  => array(
					'bold'  => true,
					'size'  => 8,
					'name'  => 'Verdana'
				));	
						
			$bottom_styles = array(
				'font'  => array(
					'bold'  => true,
					'size'  => 10,
					'name'  => 'Verdana'
				));			
			
			foreach(range('A','C') as $columnID) {
				$objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->getStyle($columnID)->applyFromArray($common_styles);	
		   }
		   
		   $objPHPExcel->getActiveSheet()->getStyle("A1:C1")->applyFromArray($bottom_styles);
		   $objPHPExcel->getActiveSheet()->getStyle("A3:C3")->applyFromArray($special_styles);
		   	   
		    $objPHPExcel->getActiveSheet()->mergeCells('A1:B1');    
		    $objPHPExcel->getActiveSheet()->setTitle("Customers")
					 ->setCellValue('A1', 'Customers Report');
					 
			
			
			$objPHPExcel->getActiveSheet()->setCellValue('A3','Customer Name');	 
			$objPHPExcel->getActiveSheet()->setCellValue('B3','Customer Address');
			$objPHPExcel->getActiveSheet()->setCellValue('C3','Customer GSTIN');
			
					
		
		$row=4;				
		foreach ($customers as $customer) { 
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$row,$customer->customerName); 
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$row,$customer->customerAddress);
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$row,$customer->customerGstin);
			$row++;
		}
			
		$objPHPExcel->getActiveSheet()->getRowDimension($row)->setRowHeight(15);
						
		header('Content-Type: application/vnd.ms-excel');
		$filename = "customers_".date("d-m-Y-His").".xls";
		header('Content-Disposition: attachment;filename='.$filename .' ');
		header('Cache-Control: max-age=0');
		$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
		exit();  
	}
    public function actionSuppliers()
    {
		
		$suppliers = Suppliers::find()->orderBy(['supplierName' => SORT_ASC])->all();
				
		$objPHPExcel = new \PHPExcel();
		$sheet=0;
		$objPHPExcel->setActiveSheetIndex($sheet);
		 
			$common_styles = array(
				'font'  => array(
					//'bold'  => true,
					'size'  => 8,
					'name'  => 'Verdana'
				));
							
			$special_styles = array(
				'font'  => array(
					'bold'  => true,
					'size'  => 8,
					'name'  => 'Verdana'
				));	
						
			$bottom_styles = array(
				'font'  => array(
					'bold'  => true,
					'size'  => 10,
					'name'  => 'Verdana'
				));			
			
			foreach(range('A','C') as $columnID) {
				$objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->getStyle($columnID)->applyFromArray($common_styles);	
		   }
		   
		   $objPHPExcel->getActiveSheet()->getStyle("A1:C1")->applyFromArray($bottom_styles);
		   $objPHPExcel->getActiveSheet()->getStyle("A3:C3")->applyFromArray($special_styles);
		   	   
		    $objPHPExcel->getActiveSheet()->mergeCells('A1:B1');    
		    $objPHPExcel->getActiveSheet()->setTitle("Suppliers")
					 ->setCellValue('A1', 'Suppliers Report');
					 
			
			
			$objPHPExcel->getActiveSheet()->setCellValue('A3','Supplier Name');	 
			$objPHPExcel->getActiveSheet()->setCellValue('B3','Supplier Address');
			$objPHPExcel->getActiveSheet()->setCellValue('C3','Supplier GSTIN');
			
					
		
		$row=4;				
		foreach ($suppliers as $supplier) { 
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$row,$supplier->supplierName); 
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$row,$supplier->supplierAddress);
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$row,$supplier->supplierGstin);
			$row++;
		}
			
		$objPHPExcel->getActiveSheet()->getRowDimension($row)->setRowHeight(15);
						
		header('Content-Type: application/vnd.ms-excel');
		$filename = "suppliers_".date("d-m-Y-His").".xls";
		header('Content-Disposition: attachment;filename='.$filename .' ');
		header('Cache-Control: max-age=0');
		$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
		exit();  
	}
	
	public function actionStock()
    {
		
		$products = Products::find()->orderBy(['productName' => SORT_ASC])->all();
				
		$objPHPExcel = new \PHPExcel();
		$sheet=0;
		$objPHPExcel->setActiveSheetIndex($sheet);
		 
			$common_styles = array(
				'font'  => array(
					//'bold'  => true,
					'size'  => 8,
					'name'  => 'Verdana'
				));
							
			$special_styles = array(
				'font'  => array(
					'bold'  => true,
					'size'  => 8,
					'name'  => 'Verdana'
				));	
						
			$bottom_styles = array(
				'font'  => array(
					'bold'  => true,
					'size'  => 10,
					'name'  => 'Verdana'
				));			
			
			foreach(range('A','J') as $columnID) {
				$objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->getStyle($columnID)->applyFromArray($common_styles);	
		   }
		   
		   $objPHPExcel->getActiveSheet()->getStyle("A1:J1")->applyFromArray($bottom_styles);
		   $objPHPExcel->getActiveSheet()->getStyle("A3:J3")->applyFromArray($special_styles);
		   	   
		    $objPHPExcel->getActiveSheet()->mergeCells('A1:B1');    
		    $objPHPExcel->getActiveSheet()->setTitle("Products")
					 ->setCellValue('A1', 'Stock Report');
					 
			
			
			$objPHPExcel->getActiveSheet()->setCellValue('A3','Product Name');	 
			$objPHPExcel->getActiveSheet()->setCellValue('B3','Product Code');
			$objPHPExcel->getActiveSheet()->setCellValue('C3','HSN/SAC');
			$objPHPExcel->getActiveSheet()->setCellValue('D3','Unit');
			$objPHPExcel->getActiveSheet()->setCellValue('E3','Price');
			$objPHPExcel->getActiveSheet()->setCellValue('F3','CGST %');
			$objPHPExcel->getActiveSheet()->setCellValue('G3','SGST %');
			$objPHPExcel->getActiveSheet()->setCellValue('H3','IGST %');
			$objPHPExcel->getActiveSheet()->setCellValue('I3','SNO');
			$objPHPExcel->getActiveSheet()->setCellValue('J3','Stock');
			
					
		
		$row=4;				
		foreach ($products as $product) { 
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$row,$product->productName); 
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$row,$product->productCode);
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$row,$product->hsnCode);
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$row,$product->per);
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$row,$product->price);
			$objPHPExcel->getActiveSheet()->setCellValue('F'.$row,$product->cgstPer);
			$objPHPExcel->getActiveSheet()->setCellValue('G'.$row,$product->sgstPer);
			$objPHPExcel->getActiveSheet()->setCellValue('H'.$row,$product->igstPer);
			$objPHPExcel->getActiveSheet()->setCellValue('I'.$row,$product->sno);
			$objPHPExcel->getActiveSheet()->setCellValue('J'.$row,$product->stock);
			$row++;
		}
			
		$objPHPExcel->getActiveSheet()->getRowDimension($row)->setRowHeight(15);
						
		header('Content-Type: application/vnd.ms-excel');
		$filename = "stock_".date("d-m-Y-His").".xls";
		header('Content-Disposition: attachment;filename='.$filename .' ');
		header('Cache-Control: max-age=0');
		$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
		exit();  
	}
	
	
	public function salesExport($sales,$date_range)
    {
		
		$objPHPExcel = new \PHPExcel();
		$sheet=0;
		 $objPHPExcel->setActiveSheetIndex($sheet);
		 
			$common_styles = array(
				'font'  => array(
					//'bold'  => true,
					'size'  => 8,
					'name'  => 'Verdana'
				));
							
			$special_styles = array(
				'font'  => array(
					'bold'  => true,
					'size'  => 8,
					'name'  => 'Verdana'
				));	
						
			$bottom_styles = array(
				'font'  => array(
					'bold'  => true,
					'size'  => 10,
					'name'  => 'Verdana'
				));			
			
			foreach(range('A','J') as $columnID) {
				$objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->getStyle($columnID)->applyFromArray($common_styles);	
		   }
		   
		   $objPHPExcel->getActiveSheet()->getStyle("A1:C1")->applyFromArray($special_styles);
		   $objPHPExcel->getActiveSheet()->getStyle("A3:J3")->applyFromArray($special_styles);
		   $objPHPExcel->getActiveSheet()->getStyle('C1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		   
		    
		    $objPHPExcel->getActiveSheet()->mergeCells('C1:J1');    
		    $objPHPExcel->getActiveSheet()->mergeCells('A1:B1');    
		    $objPHPExcel->getActiveSheet()->setTitle("Sales")
					 ->setCellValue('A1', 'Ledger : Sales Account')
					 ->setCellValue('C1', $date_range);
			
			
			$objPHPExcel->getActiveSheet()->setCellValue('A3','Date');	 
			$objPHPExcel->getActiveSheet()->setCellValue('B3','Customer');
			$objPHPExcel->getActiveSheet()->setCellValue('C3','Customer GSTIN');
			$objPHPExcel->getActiveSheet()->setCellValue('D3','Voucher Type');
			$objPHPExcel->getActiveSheet()->setCellValue('E3','Voucher No');
			$objPHPExcel->getActiveSheet()->setCellValue('F3','Gross Total');
			$objPHPExcel->getActiveSheet()->setCellValue('G3','Sales Amount');
			$objPHPExcel->getActiveSheet()->setCellValue('H3','CGST');
			$objPHPExcel->getActiveSheet()->setCellValue('I3','SGST');
			$objPHPExcel->getActiveSheet()->setCellValue('J3','IGST');
					
		
		$row=4;				
		$netTotal = 0;
		$subTotal = 0;
		$cgstTotal = 0;
		$sgstTotal = 0;
		$igstTotal = 0;
		foreach ($sales as $sale) { 
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$row,$sale->invoiceDate); 
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$row,$sale->customerName);
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$row,$sale->customerGstin);
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$row,"Sales");
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$row,$sale->invoiceNo);
			$objPHPExcel->getActiveSheet()->setCellValue('F'.$row,Helper::amount_to_money($sale->netTotal));
			$objPHPExcel->getActiveSheet()->setCellValue('G'.$row,Helper::amount_to_money($sale->subTotal));
			$objPHPExcel->getActiveSheet()->setCellValue('H'.$row,Helper::amount_to_money($sale->cgstTotal));
			$objPHPExcel->getActiveSheet()->setCellValue('i'.$row,Helper::amount_to_money($sale->sgstTotal));
			$objPHPExcel->getActiveSheet()->setCellValue('j'.$row,Helper::amount_to_money($sale->igstTotal));
			
			$objPHPExcel->getActiveSheet()->getStyle('F'.$row.":J".$row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			
			$objPHPExcel->getActiveSheet()->getStyle('B'.$row)->applyFromArray($special_styles);
			$objPHPExcel->getActiveSheet()->getStyle('F'.$row)->applyFromArray($special_styles);
			
			$netTotal = $netTotal + $sale->netTotal;
			$subTotal = $subTotal + $sale->subTotal;
			$cgstTotal = $cgstTotal + $sale->cgstTotal;
			$sgstTotal = $sgstTotal + $sale->sgstTotal;
			$igstTotal = $igstTotal + $sale->igstTotal;
			
			$row++;
		}
		
		
		$objPHPExcel->getActiveSheet()->getRowDimension($row)->setRowHeight(15);
		
		$objPHPExcel->getActiveSheet()->setCellValue('F'.$row,Helper::amount_to_money($netTotal));
		$objPHPExcel->getActiveSheet()->setCellValue('G'.$row,Helper::amount_to_money($subTotal));
		$objPHPExcel->getActiveSheet()->setCellValue('H'.$row,Helper::amount_to_money($cgstTotal));
		$objPHPExcel->getActiveSheet()->setCellValue('i'.$row,Helper::amount_to_money($sgstTotal));
		$objPHPExcel->getActiveSheet()->setCellValue('j'.$row,Helper::amount_to_money($igstTotal));
		
		$objPHPExcel->getActiveSheet()->getStyle('A'.$row.":E".$row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$objPHPExcel->getActiveSheet()->getStyle('F'.$row.":J".$row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		
		$objPHPExcel->getActiveSheet()->getStyle('F'.$row.":J".$row)->applyFromArray($bottom_styles);
		
		
		header('Content-Type: application/vnd.ms-excel');
		$filename = "sales_".date("d-m-Y-His").".xls";
		header('Content-Disposition: attachment;filename='.$filename .' ');
		header('Cache-Control: max-age=0');
		$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
		exit();  
	}
	public function purchasesExport($purchases,$date_range)
    {
		
		$objPHPExcel = new \PHPExcel();
		$sheet=0;
		 $objPHPExcel->setActiveSheetIndex($sheet);
		 
			$common_styles = array(
				'font'  => array(
					//'bold'  => true,
					'size'  => 8,
					'name'  => 'Verdana'
				));
							
			$special_styles = array(
				'font'  => array(
					'bold'  => true,
					'size'  => 8,
					'name'  => 'Verdana'
				));	
						
			$bottom_styles = array(
				'font'  => array(
					'bold'  => true,
					'size'  => 10,
					'name'  => 'Verdana'
				));			
			
			foreach(range('A','J') as $columnID) {
				$objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->getStyle($columnID)->applyFromArray($common_styles);	
		   }
		   
		   $objPHPExcel->getActiveSheet()->getStyle("A1:C1")->applyFromArray($special_styles);
		   $objPHPExcel->getActiveSheet()->getStyle("A3:J3")->applyFromArray($special_styles);
		   $objPHPExcel->getActiveSheet()->getStyle('C1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		   
		    
		    $objPHPExcel->getActiveSheet()->mergeCells('C1:J1');    
		    $objPHPExcel->getActiveSheet()->mergeCells('A1:B1');    
		    $objPHPExcel->getActiveSheet()->setTitle("Purchases")
					 ->setCellValue('A1', 'Ledger : Purchases Account')
					 ->setCellValue('C1', $date_range);
			
			
			$objPHPExcel->getActiveSheet()->setCellValue('A3','Date');	 
			$objPHPExcel->getActiveSheet()->setCellValue('B3','Supplier');
			$objPHPExcel->getActiveSheet()->setCellValue('C3','Supplier GSTIN');
			$objPHPExcel->getActiveSheet()->setCellValue('D3','Voucher Type');
			$objPHPExcel->getActiveSheet()->setCellValue('E3','Voucher No');
			$objPHPExcel->getActiveSheet()->setCellValue('F3','Gross Total');
			$objPHPExcel->getActiveSheet()->setCellValue('G3','Purchases Amount');
			$objPHPExcel->getActiveSheet()->setCellValue('H3','CGST');
			$objPHPExcel->getActiveSheet()->setCellValue('I3','SGST');
			$objPHPExcel->getActiveSheet()->setCellValue('J3','IGST');
					
		
		$row=4;				
		$netTotal = 0;
		$subTotal = 0;
		$cgstTotal = 0;
		$sgstTotal = 0;
		$igstTotal = 0;
		foreach ($purchases as $purchase) { 
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$row,$purchase->purchaseDate); 
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$row,$purchase->supplierName);
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$row,$purchase->supplierGstin);
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$row,"Purchases");
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$row,$purchase->purchaseNo);
			$objPHPExcel->getActiveSheet()->setCellValue('F'.$row,Helper::amount_to_money($purchase->netTotal));
			$objPHPExcel->getActiveSheet()->setCellValue('G'.$row,Helper::amount_to_money($purchase->subTotal));
			$objPHPExcel->getActiveSheet()->setCellValue('H'.$row,Helper::amount_to_money($purchase->cgstTotal));
			$objPHPExcel->getActiveSheet()->setCellValue('i'.$row,Helper::amount_to_money($purchase->sgstTotal));
			$objPHPExcel->getActiveSheet()->setCellValue('j'.$row,Helper::amount_to_money($purchase->igstTotal));
			
			$objPHPExcel->getActiveSheet()->getStyle('A'.$row.":E".$row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			
			$objPHPExcel->getActiveSheet()->getStyle('F'.$row.":J".$row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			
			$objPHPExcel->getActiveSheet()->getStyle('B'.$row)->applyFromArray($special_styles);
			$objPHPExcel->getActiveSheet()->getStyle('F'.$row)->applyFromArray($special_styles);
			
			$netTotal = $netTotal + $purchase->netTotal;
			$subTotal = $subTotal + $purchase->subTotal;
			$cgstTotal = $cgstTotal + $purchase->cgstTotal;
			$sgstTotal = $sgstTotal + $purchase->sgstTotal;
			$igstTotal = $igstTotal + $purchase->igstTotal;
			
			$row++;
		}
		
		
		$objPHPExcel->getActiveSheet()->getRowDimension($row)->setRowHeight(15);
		
		$objPHPExcel->getActiveSheet()->setCellValue('F'.$row,Helper::amount_to_money($netTotal));
		$objPHPExcel->getActiveSheet()->setCellValue('G'.$row,Helper::amount_to_money($subTotal));
		$objPHPExcel->getActiveSheet()->setCellValue('H'.$row,Helper::amount_to_money($cgstTotal));
		$objPHPExcel->getActiveSheet()->setCellValue('i'.$row,Helper::amount_to_money($sgstTotal));
		$objPHPExcel->getActiveSheet()->setCellValue('j'.$row,Helper::amount_to_money($igstTotal));
		
		$objPHPExcel->getActiveSheet()->getStyle('F'.$row.":J".$row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		
		$objPHPExcel->getActiveSheet()->getStyle('F'.$row.":J".$row)->applyFromArray($bottom_styles);
		
		
		header('Content-Type: application/vnd.ms-excel');
		$filename = "purchases_".date("d-m-Y-His").".xls";
		header('Content-Disposition: attachment;filename='.$filename .' ');
		header('Cache-Control: max-age=0');
		$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
		exit();  
	}
	public function estimationsExport($estimations,$date_range)
    {
		
		$objPHPExcel = new \PHPExcel();
		$sheet=0;
		 $objPHPExcel->setActiveSheetIndex($sheet);
		 
			$common_styles = array(
				'font'  => array(
					//'bold'  => true,
					'size'  => 8,
					'name'  => 'Verdana'
				));
							
			$special_styles = array(
				'font'  => array(
					'bold'  => true,
					'size'  => 8,
					'name'  => 'Verdana'
				));	
						
			$bottom_styles = array(
				'font'  => array(
					'bold'  => true,
					'size'  => 10,
					'name'  => 'Verdana'
				));			
			
			foreach(range('A','J') as $columnID) {
				$objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->getStyle($columnID)->applyFromArray($common_styles);	
		   }
		   
		   $objPHPExcel->getActiveSheet()->getStyle("A1:C1")->applyFromArray($special_styles);
		   $objPHPExcel->getActiveSheet()->getStyle("A3:J3")->applyFromArray($special_styles);
		   $objPHPExcel->getActiveSheet()->getStyle('C1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		   
		    
		    $objPHPExcel->getActiveSheet()->mergeCells('C1:G1');    
		    $objPHPExcel->getActiveSheet()->mergeCells('A1:B1');    
		    $objPHPExcel->getActiveSheet()->setTitle("Estimations")
					 ->setCellValue('A1', 'Ledger : Estimations Account')
					 ->setCellValue('C1', $date_range);
			
			
			$objPHPExcel->getActiveSheet()->setCellValue('A3','Date');	 
			$objPHPExcel->getActiveSheet()->setCellValue('B3','Customer');
			$objPHPExcel->getActiveSheet()->setCellValue('C3','Customer GSTIN');
			$objPHPExcel->getActiveSheet()->setCellValue('D3','Voucher Type');
			$objPHPExcel->getActiveSheet()->setCellValue('E3','Voucher No');
			$objPHPExcel->getActiveSheet()->setCellValue('F3','Gross Total');
			$objPHPExcel->getActiveSheet()->setCellValue('G3','Estimations Amount');
			
					
		
		$row=4;				
		$netTotal = 0;
		$subTotal = 0;
		$cgstTotal = 0;
		$sgstTotal = 0;
		$igstTotal = 0;
		foreach ($estimations as $estimation) { 
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$row,$estimation->estimationDate); 
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$row,$estimation->customerName);
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$row,$estimation->customerGstin);
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$row,"Estimations");
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$row,$estimation->estimationNo);
			$objPHPExcel->getActiveSheet()->setCellValue('F'.$row,Helper::amount_to_money($estimation->netTotal));
			$objPHPExcel->getActiveSheet()->setCellValue('G'.$row,Helper::amount_to_money($estimation->subTotal));
			
			$objPHPExcel->getActiveSheet()->getStyle('A'.$row.":E".$row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			
			$objPHPExcel->getActiveSheet()->getStyle('F'.$row.":G".$row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			
			$objPHPExcel->getActiveSheet()->getStyle('B'.$row)->applyFromArray($special_styles);
			$objPHPExcel->getActiveSheet()->getStyle('F'.$row)->applyFromArray($special_styles);
			
			$netTotal = $netTotal + $estimation->netTotal;
			$subTotal = $subTotal + $estimation->subTotal;
			
			$row++;
		}
		
		
		$objPHPExcel->getActiveSheet()->getRowDimension($row)->setRowHeight(15);
		
		$objPHPExcel->getActiveSheet()->setCellValue('F'.$row,Helper::amount_to_money($netTotal));
		$objPHPExcel->getActiveSheet()->setCellValue('G'.$row,Helper::amount_to_money($subTotal));
		
		$objPHPExcel->getActiveSheet()->getStyle('F'.$row.":G".$row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		
		$objPHPExcel->getActiveSheet()->getStyle('F'.$row.":G".$row)->applyFromArray($bottom_styles);
		
		
		header('Content-Type: application/vnd.ms-excel');
		$filename = "estimations_".date("d-m-Y-His").".xls";
		header('Content-Disposition: attachment;filename='.$filename .' ');
		header('Cache-Control: max-age=0');
		$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
		exit();  
	}

    
   
}
