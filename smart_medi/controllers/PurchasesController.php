<?php

namespace app\controllers;

use Yii;
use app\models\Purchases;
use app\models\Suppliers;
use app\models\Products;
use app\models\Users;
use app\models\PurchaseItems;
use app\models\PurchasesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\base\Model;
use yii\helpers\ArrayHelper;
use kartik\mpdf\Pdf;

/**
 * PurchasesController implements the CRUD actions for Purchases model.
 */
class PurchasesController extends Controller
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
     * Lists all Purchases models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PurchasesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Purchases model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
		
	$profile = Users::findOne(Yii::$app->user->id);	
		
	 $model = Purchases::find()
						->JoinWith('purchaseItems')
						->where(['purchases.id' => $id])
						->one();
						//~ 
						//~ echo "<pre>";
						//~ print_r($model);
						//~ exit;
		//~ 
        return $this->render('view', [
            'model' => $model,
            'profile' => $profile,
        ]);
    }

    /**
     * Creates a new Purchases model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
       
    public function actionCreate()
    {
        $modelPurchase = new Purchases;
        $modelPurchaseItems = [new PurchaseItems];
       
        if ($modelPurchase->load(Yii::$app->request->post())) {
			
						
            $modelPurchaseItems = Model::createMultiple(PurchaseItems::classname());
            Model::loadMultiple($modelPurchaseItems, Yii::$app->request->post());
            
            // validate all models
            $valid = $modelPurchase->validate();
            $valid = Model::validateMultiple($modelPurchaseItems) && $valid;
             
                         
            if ($valid) {
				
								
								
                $transaction = \Yii::$app->db->beginTransaction();
                try {
				    if ($flag = $modelPurchase->save(false)) {
                        foreach ($modelPurchaseItems as $modelPurchaseItem) {
													
							$modelPurchaseItem->purchaseID = $modelPurchase->id;
                            if (! ($flag = $modelPurchaseItem->save(false))) {
                                $transaction->rollBack();
                                break;
                            }
                        }
                    }
                    if ($flag) {
                        $transaction->commit();
						$this->updateStock('create',$modelPurchaseItems);
                        return $this->redirect(['view', 'id' => $modelPurchase->id]);
                    }
                } catch (Exception $e) {
                    $transaction->rollBack();
                }
            }
        }
       
        
        if(empty($modelPurchase->purchaseDate))
			$modelPurchase->purchaseDate = date("d/m/Y");
        
       			
        if(empty($modelPurchase->cgstTotal))
			$modelPurchase->cgstTotal = 0;
        
        if(empty($modelPurchase->sgstTotal))
			$modelPurchase->sgstTotal = 0;
        
        if(empty($modelPurchase->igstTotal))
			$modelPurchase->igstTotal = 0;
        
        if(empty($modelPurchase->taxTotal))
			$modelPurchase->taxTotal = 0;
			
        if(empty($modelPurchase->subTotal))
			$modelPurchase->subTotal = 0;
        
        if(empty($modelPurchase->netTotal))
			$modelPurchase->netTotal = 0;
		
		if(empty($modelPurchase->box2_title))
			$modelPurchase->box2_title = "Mode/Terms of Payment";
		
		if(empty($modelPurchase->box3_title))
			$modelPurchase->box3_title = "Supplier\'s References";
		
		if(empty($modelPurchase->box4_title))
			$modelPurchase->box4_title = "Terms of Delivery";
				
		
		$profile = Users::findOne(Yii::$app->user->id);
		
				        
         return $this->render('create', [
            'modelPurchase' => $modelPurchase,
            'modelPurchaseItems' => (empty($modelPurchaseItems)) ? [new PurchaseItems] : $modelPurchaseItems
        ]);
    }

    /**
     * Updates an existing Supplier model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $modelPurchase = $this->findModel($id);
        $modelPurchaseItems = $modelPurchase->purchaseItems;
        
        if ($modelPurchase->load(Yii::$app->request->post())) {
			
			$oldIDs = ArrayHelper::map($modelPurchaseItems, 'id', 'id');
            $modelPurchaseItems = Model::createMultiple(PurchaseItems::classname(), $modelPurchaseItems);
            Model::loadMultiple($modelPurchaseItems, Yii::$app->request->post());
            $deletedIDs = array_diff($oldIDs, array_filter(ArrayHelper::map($modelPurchaseItems, 'id', 'id')));

            // validate all models
            $valid = $modelPurchase->validate();
            $valid = Model::validateMultiple($modelPurchaseItems) && $valid;

            if ($valid) {
				
								
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    if ($flag = $modelPurchase->save(false)) {
                        if (! empty($deletedIDs)) {
                            PurchaseItems::deleteAll(['id' => $deletedIDs]);
                        }
                        foreach ($modelPurchaseItems as $modelPurchaseItem) {
													
                            $modelPurchaseItem->purchaseID = $modelPurchase->id;
                            if (! ($flag = $modelPurchaseItem->save(false))) {
                                $transaction->rollBack();
                                break;
                            }
                        }
                    }
                    if ($flag) {
                        $this->updateStock('update',$_SESSION['modelPurchaseItems']);
						$transaction->commit();
						return $this->redirect(['view', 'id' => $modelPurchase->id]);
                    }
                } catch (Exception $e) {
                    $transaction->rollBack();
                }
            }
        }
        else
        {
			      
			foreach($modelPurchaseItems as $modelPurchaseItem)
			{
				$modelPurchaseItem->productNameUser = $modelPurchaseItem->productName; 
				$modelPurchaseItem->total = $modelPurchaseItem->price * $modelPurchaseItem->quantity; 
				$modelPurchaseItem->cgstTot = $modelPurchaseItem->total*($modelPurchaseItem->cgstPer/100); 
				$modelPurchaseItem->sgstTot = $modelPurchaseItem->total*($modelPurchaseItem->sgstPer/100); 
				$modelPurchaseItem->igstTot = $modelPurchaseItem->total*($modelPurchaseItem->igstPer/100); 
				$modelPurchaseItem->tax = $modelPurchaseItem->cgstTot + $modelPurchaseItem->sgstTot + $modelPurchaseItem->igstTot; 
			}
			$_SESSION['modelPurchaseItems'] = $modelPurchaseItems;
		}
       
        return $this->render('update', [
            'modelPurchase' => $modelPurchase,
            'purchaseNo' =>    $modelPurchase->purchaseNo,
            'modelPurchaseItems' => (empty($modelPurchaseItems)) ? [new PurchaseItems] : $modelPurchaseItems
        ]);
    }
	
	public function updateStock($action,$modelPurchaseItems)
    {
		
		if($action=='create')
		{
			foreach($modelPurchaseItems as $modelPurchaseItem)
			{
				$product = Products::find()->where(['productName'=>$modelPurchaseItem->productName])->one();
				if(!empty($product))
				{
					$product->stock = $product->stock + $modelPurchaseItem->quantity;
					$product->save(false);
				}
			}
		}
		
		if($action=='update')
		{
			//$modelPurchaseItems = $_SESSION['modelPurchaseItems'];
			foreach($modelPurchaseItems as $modelPurchaseItem)
			{
				
				$newPurchaseItem = PurchaseItems::findOne($modelPurchaseItem->id);
				
				$quantity = $newPurchaseItem->quantity - $modelPurchaseItem->quantity;
				
				$product = Products::find()->where(['productName'=>$modelPurchaseItem->productName])->one();
				if(!empty($product))
				{
					$product->stock = $product->stock + $quantity;
					$product->save(false);
				}
			}	
		}
		
		
	}

    /**
     * Deletes an existing Purchases model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        
		$purchase_items = PurchaseItems::find()
						->where(['purchaseID' => $id])
						->all();
		foreach($purchase_items as $purchase_item)
		{
			$product = Products::find()->where(['productName'=>$purchase_item->productName])->one();
			if(!empty($product))
			{
				$product->stock = $product->stock - $purchase_item->quantity;
				$product->save(false);
			}
			$purchase_item->delete();
		}
			
		$this->findModel($id)->delete();

        return $this->redirect(['index']);
    }
    
    public function actionExport($id,$output=0,$file_name="") {
		// get your HTML raw content without any layouts or scripts
		$profile = Users::findOne(Yii::$app->user->id);	
		$model = Purchases::find()
						->JoinWith('purchaseItems')
						->where(['purchases.id' => $id])
						->one();
	
								
		$content = $this->renderPartial('_reportView',[
				'model' => $model,
				'profile' => $profile,
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
			'cssInline' => 'table{border-collapse:collapse}table,td,th{border:1px solid #000;padding:5px;vertical-align:top}.items td{border-bottom:none;border-top:none;height:10px}.total_items td{border-bottom:none;}.total_items_style td{font-weight:700;}.topnone{border-top:none!important}.items{height:20px}.right{text-align:right!important}p{text-align:center;}.title span{font-weight:400}.reduce_height td{line-height:0}
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
     * Finds the Purchases model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Purchases the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Purchases::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
