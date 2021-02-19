<?php

namespace app\controllers;

use Yii;
use app\models\Invoices;
use app\models\Customers;
use app\models\Products;
use app\models\Users;
use app\models\UserSettings;
use app\models\UserAttributes;
use app\models\InvoiceItems;
use app\models\InvoicesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\base\Model;
use yii\helpers\ArrayHelper;
use kartik\mpdf\Pdf;

/**
 * InvoicesController implements the CRUD actions for Invoices model.
 */
class InvoicesController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Invoices models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new InvoicesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Invoices model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
		
	$profile = Users::findOne(Yii::$app->user->id);	
	$user_settings = UserSettings::find()->where(['id'=>1])->one();		
	 $model = Invoices::find()
						->JoinWith('invoiceItems')
						->JoinWith('userAttributes')
						->where(['invoices.id' => $id])
						->one();
						//~ 
						//~ echo "<pre>";
						//~ print_r($model);
						//~ exit;
		//~ 
        return $this->render('view', [
            'model' => $model,
            'profile' => $profile,
            'user_settings' => $user_settings,
        ]);
    }

    /**
     * Creates a new Invoices model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
       
    public function actionCreate()
    {
        $modelInvoice = new Invoices;
        $modelInvoiceItems = [new InvoiceItems];
       
        if ($modelInvoice->load(Yii::$app->request->post())) {
			
						
            $modelInvoiceItems = Model::createMultiple(InvoiceItems::classname());
            Model::loadMultiple($modelInvoiceItems, Yii::$app->request->post());
            
            // validate all models
            $valid = $modelInvoice->validate();
            $valid = Model::validateMultiple($modelInvoiceItems) && $valid;
             
                         
            if ($valid) {
				
								
								
                $transaction = \Yii::$app->db->beginTransaction();
                try {
				    if ($flag = $modelInvoice->save(false)) {
						
						$modelUserAttribute = new UserAttributes;
						$modelUserAttribute->reference = $modelInvoice->id;
						$modelUserAttribute->key = "box5_title";
						$modelUserAttribute->value = $modelInvoice->box5_title;
						$modelUserAttribute->save(false);
						
						$modelUserAttribute = new UserAttributes;
						$modelUserAttribute->reference = $modelInvoice->id;
						$modelUserAttribute->key = "box5_content";
						$modelUserAttribute->value = $modelInvoice->box5_content;
						$modelUserAttribute->save(false);
						
                        foreach ($modelInvoiceItems as $modelInvoiceItem) {
													
							$modelInvoiceItem->invoiceID = $modelInvoice->id;
                            if (! ($flag = $modelInvoiceItem->save(false))) {
                                $transaction->rollBack();
                                break;
                            }
                        }
                    }
                    if ($flag) {
                        $transaction->commit();
						$this->updateStock('create',$modelInvoiceItems);
                        return $this->redirect(['view', 'id' => $modelInvoice->id]);
                    }
                } catch (Exception $e) {
                    $transaction->rollBack();
                }
            }
        }
       
        
        if(empty($modelInvoice->invoiceDate))
			$modelInvoice->invoiceDate = date("d/m/Y");
        
       			
        if(empty($modelInvoice->cgstTotal))
			$modelInvoice->cgstTotal = 0;
        
        if(empty($modelInvoice->sgstTotal))
			$modelInvoice->sgstTotal = 0;
        
        if(empty($modelInvoice->igstTotal))
			$modelInvoice->igstTotal = 0;
        
        if(empty($modelInvoice->taxTotal))
			$modelInvoice->taxTotal = 0;
			
        if(empty($modelInvoice->subTotal))
			$modelInvoice->subTotal = 0;
        
        if(empty($modelInvoice->netTotal))
			$modelInvoice->netTotal = 0;

		if(empty($modelInvoice->box1_title))
			$modelInvoice->box1_title = "PO.NO";

		if(empty($modelInvoice->box2_title))
			$modelInvoice->box2_title = "DC.NO";
		
		if(empty($modelInvoice->box3_title))
			$modelInvoice->box3_title = "Despatch Address";

		if(empty($modelInvoice->box4_title))
			$modelInvoice->box4_title = "Transport detail";

		if(empty($modelInvoice->box4_content))
			$modelInvoice->box4_content = "";
		
		if(empty($modelInvoice->box5_title))
			$modelInvoice->box5_title = "Destination";

		if(empty($modelInvoice->box5_content))
			$modelInvoice->box5_content = "";
		
			
		
		$profile = Users::findOne(Yii::$app->user->id);
		$invoiceLastRecord = Invoices::find()->orderBy(['id' => SORT_DESC])->one();	
				
		if(empty($invoiceLastRecord->id) && $profile->invoice_start_no!="" && $profile->invoice_start_no > 1)
			$invoice_no = $profile->invoicePrefix."".$profile->invoice_start_no."".$profile->invoiceSuffix;
		elseif(empty($invoiceLastRecord->id))
			$invoice_no = $profile->invoicePrefix."1".$profile->invoiceSuffix;
		elseif($profile->invoice_start_no!="" && $profile->invoice_start_no > $invoiceLastRecord->id+1)
			$invoice_no = $profile->invoicePrefix."".($profile->invoice_start_no + $invoiceLastRecord->id)."".$profile->invoiceSuffix;
		else	
			$invoice_no = $profile->invoicePrefix."".($invoiceLastRecord->id + 1)."".$profile->invoiceSuffix;
		
		
        
         return $this->render('create', [
            'modelInvoice' => $modelInvoice,
            'invoiceNo' => $invoice_no,
            'modelInvoiceItems' => (empty($modelInvoiceItems)) ? [new InvoiceItems] : $modelInvoiceItems
        ]);
    }

    /**
     * Updates an existing Customer model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $modelInvoice = $this->findModel($id);
        $modelInvoiceItems = $modelInvoice->invoiceItems;
        
		$user_attribute_box5_title = UserAttributes::find()->where(['reference'=>$id,'key'=>'box5_title'])->one();
		$user_attribute_box5_content = UserAttributes::find()->where(['reference'=>$id,'key'=>'box5_content'])->one();
		
		if(!empty($user_attribute_box5_title))
			$modelInvoice->box5_title = $user_attribute_box5_title->value;
		else
			$user_attribute_box5_title = new UserAttributes;
		
		if(!empty($user_attribute_box5_content))
			$modelInvoice->box5_content = $user_attribute_box5_content->value;
		else
			$user_attribute_box5_content = new UserAttributes;
		
        if ($modelInvoice->load(Yii::$app->request->post())) {
			
			$oldIDs = ArrayHelper::map($modelInvoiceItems, 'id', 'id');
            $modelInvoiceItems = Model::createMultiple(InvoiceItems::classname(), $modelInvoiceItems);
            Model::loadMultiple($modelInvoiceItems, Yii::$app->request->post());
            $deletedIDs = array_diff($oldIDs, array_filter(ArrayHelper::map($modelInvoiceItems, 'id', 'id')));

            // validate all models
            $valid = $modelInvoice->validate();
            $valid = Model::validateMultiple($modelInvoiceItems) && $valid;

            if ($valid) {
				
								
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    if ($flag = $modelInvoice->save(false)) {
						
						$user_attribute_box5_title->reference = $modelInvoice->id;
						$user_attribute_box5_title->key = 'box5_title';
						$user_attribute_box5_title->value = $modelInvoice->box5_title;
						$user_attribute_box5_title->save(false);
						
						$user_attribute_box5_content->reference = $modelInvoice->id;
						$user_attribute_box5_content->key = 'box5_content';
						$user_attribute_box5_content->value = $modelInvoice->box5_content;
						$user_attribute_box5_content->save(false);
						
                        if (! empty($deletedIDs)) {
                            InvoiceItems::deleteAll(['id' => $deletedIDs]);
                        }
                        foreach ($modelInvoiceItems as $modelInvoiceItem) {
													
                            $modelInvoiceItem->invoiceID = $modelInvoice->id;
                            if (! ($flag = $modelInvoiceItem->save(false))) {
                                $transaction->rollBack();
                                break;
                            }
                        }
                    }
                    if ($flag) {
						$this->updateStock('update',$_SESSION['modelInvoiceItems']);
                        $transaction->commit();
                        return $this->redirect(['view', 'id' => $modelInvoice->id]);
                    }
                } catch (Exception $e) {
                    $transaction->rollBack();
                }
            }
        }
        else
        {
			foreach($modelInvoiceItems as $modelInvoiceItem)
			{
				$modelInvoiceItem->productNameUser = $modelInvoiceItem->productName; 
				$modelInvoiceItem->total = $modelInvoiceItem->price * $modelInvoiceItem->quantity; 
				$modelInvoiceItem->cgstTot = $modelInvoiceItem->total*($modelInvoiceItem->cgstPer/100); 
				$modelInvoiceItem->sgstTot = $modelInvoiceItem->total*($modelInvoiceItem->sgstPer/100); 
				$modelInvoiceItem->igstTot = $modelInvoiceItem->total*($modelInvoiceItem->igstPer/100); 
				$modelInvoiceItem->tax = $modelInvoiceItem->cgstTot + $modelInvoiceItem->sgstTot + $modelInvoiceItem->igstTot; 
			}
			$_SESSION['modelInvoiceItems'] = $modelInvoiceItems;
		}
       
        return $this->render('update', [
            'modelInvoice' => $modelInvoice,
            'invoiceNo' =>    $modelInvoice->invoiceNo,
            'modelInvoiceItems' => (empty($modelInvoiceItems)) ? [new InvoiceItems] : $modelInvoiceItems
        ]);
    }
	
	public function updateStock($action,$modelInvoiceItems)
    {
		
		if($action=='create')
		{
			foreach($modelInvoiceItems as $modelInvoiceItem)
			{
				$product = Products::find()->where(['productName'=>$modelInvoiceItem->productName])->one();
				if(!empty($product))
				{
					$product->stock = $product->stock - $modelInvoiceItem->quantity;
					$product->save(false);
				}
			}
		}
		
		if($action=='update')
		{
			//$modelPurchaseItems = $_SESSION['modelPurchaseItems'];
			foreach($modelInvoiceItems as $modelInvoiceItem)
			{
				
				$modelInvoiceItem_new = InvoiceItems::findOne($modelInvoiceItem->id);
				
				$quantity = $modelInvoiceItem->quantity - $modelInvoiceItem_new->quantity;
				
				$product = Products::find()->where(['productName'=>$modelInvoiceItem->productName])->one();
				
				
				if(!empty($product))
				{
					$product->stock = $product->stock + $quantity;
					$product->save(false);
				}
			}	
		}
		
		
	}

    /**
     * Deletes an existing Invoices model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        
		$invoice_items = InvoiceItems::find()
						->where(['invoiceID' => $id])
						->all();
		foreach($invoice_items as $invoice_item)
		{
			$product = Products::find()->where(['productName'=>$invoice_item->productName])->one();
			if(!empty($product))
			{
				$product->stock = $product->stock + $invoice_item->quantity;
				$product->save(false);
			}
			$invoice_item->delete();
		}
			
		$this->findModel($id)->delete();

        return $this->redirect(['index']);
    }
    
    public function actionExport($id,$output=0,$file_name="") {
		// get your HTML raw content without any layouts or scripts
		$profile = Users::findOne(Yii::$app->user->id);	
		$user_settings = UserSettings::find()->where(['id'=>1])->one();	
				
		$model = Invoices::find()
						->JoinWith('invoiceItems')
						->JoinWith('userAttributes')
						->where(['invoices.id' => $id])
						->one();
	
		$content = $this->renderPartial('_reportView',[
				'model' => $model,
				'profile' => $profile,
				'user_settings' => $user_settings,
				]);
		
		// setup kartik\mpdf\Pdf component
		
		if($output==1)
			$dest = Pdf::DEST_FILE;
		else
			$dest = Pdf::DEST_BROWSER;
		
		$pdf = new Pdf([
			// set to use core fonts only
			'mode' => Pdf::MODE_UTF8, 
			// A4 paper format
			'format' => Pdf::FORMAT_A4, 
			// portrait orientation
			'orientation' => Pdf::ORIENT_PORTRAIT, 
			// stream to browser inline
			'destination' => $dest,
			'filename' => $file_name,
			// your html content input
			'content' => $content,  
			// format content from your own css file if needed or use the
			// enhanced bootstrap css built by Krajee for mPDF formatting 
			'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
			// any css to be embedded if required
			'cssInline' => 'table{border-collapse:collapse;}table,td,th{border:1px solid #000;padding:5px;vertical-align:top}.items td{border-bottom:none;border-top:none;height:10px}.total_items td{border-bottom:none;}.total_items_style td{font-weight:700;}.topnone{border-top:none!important}.items{height:20px}.right{text-align:right!important}p{text-align:center;}.title span{font-weight:400}.reduce_height td{line-height:0}
			@media all {.page-break	{ display: none; }}
			@media print { .page-break	{ display: block; page-break-before: always; }}
			', 
								
			 // set mPDF properties on the fly
			'options' => ['title' => ''],
			 // call mPDF methods on the fly
			
		]);
		
		// return the pdf output as per the destination setting
		return $pdf->render(); 
	}

    /**
     * Finds the Invoices model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Invoices the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Invoices::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
