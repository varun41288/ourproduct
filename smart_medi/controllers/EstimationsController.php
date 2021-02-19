<?php

namespace app\controllers;

use Yii;
use app\models\Estimations;
use app\models\Customers;
use app\models\Products;
use app\models\Users;
use app\models\EstimationItems;
use app\models\EstimationsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\base\Model;
use yii\helpers\ArrayHelper;
use kartik\mpdf\Pdf;

/**
 * EstimationsController implements the CRUD actions for Estimations model.
 */
class EstimationsController extends Controller
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
     * Lists all Estimations models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new EstimationsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Estimations model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
		
	$profile = Users::findOne(Yii::$app->user->id);	
		
	 $model = Estimations::find()
						->JoinWith('estimationItems')
						->where(['estimations.id' => $id])
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
     * Creates a new Estimations model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
       
    public function actionCreate()
    {
        $modelEstimation = new Estimations;
        $modelEstimationItems = [new EstimationItems];
       
        if ($modelEstimation->load(Yii::$app->request->post())) {
			
						
            $modelEstimationItems = Model::createMultiple(EstimationItems::classname());
            Model::loadMultiple($modelEstimationItems, Yii::$app->request->post());
            
            // validate all models
            $valid = $modelEstimation->validate();
            $valid = Model::validateMultiple($modelEstimationItems) && $valid;
             
                         
            if ($valid) {
				
								
								
                $transaction = \Yii::$app->db->beginTransaction();
                try {
				    if ($flag = $modelEstimation->save(false)) {
                        foreach ($modelEstimationItems as $modelEstimationItem) {
													
							$modelEstimationItem->estimationID = $modelEstimation->id;
                            if (! ($flag = $modelEstimationItem->save(false))) {
                                $transaction->rollBack();
                                break;
                            }
                        }
                    }
                    if ($flag) {
                        $transaction->commit();
						$this->updateStock('create',$modelEstimationItems);
                        return $this->redirect(['view', 'id' => $modelEstimation->id]);
                    }
                } catch (Exception $e) {
                    $transaction->rollBack();
                }
            }
        }
       
        
        if(empty($modelEstimation->estimationDate))
			$modelEstimation->estimationDate = date("d/m/Y");
        
              
			
        if(empty($modelEstimation->subTotal))
			$modelEstimation->subTotal = 0;
        
        if(empty($modelEstimation->netTotal))
			$modelEstimation->netTotal = 0;
		
		if(empty($modelEstimation->box2_title))
			$modelEstimation->box2_title = "Mode/Terms of Payment";
		
		if(empty($modelEstimation->box3_title))
			$modelEstimation->box3_title = "Supplier\'s References";
		
		if(empty($modelEstimation->box4_title))
			$modelEstimation->box4_title = "Terms of Delivery";
				
		
		$profile = Users::findOne(Yii::$app->user->id);
		$estimationLastRecord = Estimations::find()->orderBy(['estimationNo' => SORT_DESC])->one();	
		
		if(empty($estimationLastRecord->estimationNo))
			$estimation_no = 1;
		else	
			$estimation_no = $estimationLastRecord->estimationNo + 1;
        
         return $this->render('create', [
            'modelEstimation' => $modelEstimation,
            'estimationNo' => $estimation_no,
            'modelEstimationItems' => (empty($modelEstimationItems)) ? [new EstimationItems] : $modelEstimationItems
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
        $modelEstimation = $this->findModel($id);
        $modelEstimationItems = $modelEstimation->estimationItems;
              
        if ($modelEstimation->load(Yii::$app->request->post())) {
			
			$oldIDs = ArrayHelper::map($modelEstimationItems, 'id', 'id');
            $modelEstimationItems = Model::createMultiple(EstimationItems::classname(), $modelEstimationItems);
            Model::loadMultiple($modelEstimationItems, Yii::$app->request->post());
            $deletedIDs = array_diff($oldIDs, array_filter(ArrayHelper::map($modelEstimationItems, 'id', 'id')));

            // validate all models
            $valid = $modelEstimation->validate();
            $valid = Model::validateMultiple($modelEstimationItems) && $valid;

            if ($valid) {
				
								
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    if ($flag = $modelEstimation->save(false)) {
                        if (! empty($deletedIDs)) {
                            EstimationItems::deleteAll(['id' => $deletedIDs]);
                        }
                        foreach ($modelEstimationItems as $modelEstimationItem) {
													
                            $modelEstimationItem->estimationID = $modelEstimation->id;
                            if (! ($flag = $modelEstimationItem->save(false))) {
                                $transaction->rollBack();
                                break;
                            }
                        }
                    }
                    if ($flag) {
						$this->updateStock('update',$_SESSION['modelEstimationItems']);
                        $transaction->commit();
                        return $this->redirect(['view', 'id' => $modelEstimation->id]);
                    }
                } catch (Exception $e) {
                    $transaction->rollBack();
                }
            }
        }
        else
        {
			foreach($modelEstimationItems as $modelEstimationItem)
			{
				$modelEstimationItem->productNameUser = $modelEstimationItem->productName; 
				$modelEstimationItem->total = $modelEstimationItem->price * $modelEstimationItem->quantity; 
				
			}
			$_SESSION['modelEstimationItems'] = $modelEstimationItems;
		}
       
        return $this->render('update', [
            'modelEstimation' => $modelEstimation,
            'estimationNo' =>    $modelEstimation->estimationNo,
            'modelEstimationItems' => (empty($modelEstimationItems)) ? [new EstimationItems] : $modelEstimationItems
        ]);
    }
	
	public function updateStock($action,$modelEstimationItems)
    {
		
		if($action=='create')
		{
			foreach($modelEstimationItems as $modelEstimationItem)
			{
				$product = Products::find()->where(['productName'=>$modelEstimationItem->productName])->one();
				if(!empty($product))
				{
					$product->stock = $product->stock - $modelEstimationItem->quantity;
					$product->save(false);
				}
			}
		}
		
		if($action=='update')
		{
			//$modelPurchaseItems = $_SESSION['modelPurchaseItems'];
			foreach($modelEstimationItems as $modelEstimationItem)
			{
				
				$modelEstimationItem_new = EstimationItems::findOne($modelEstimationItem->id);
				
				$quantity = $modelEstimationItem->quantity - $modelEstimationItem_new->quantity;
				
				$product = Products::find()->where(['productName'=>$modelEstimationItem->productName])->one();
				if(!empty($product))
				{
					$product->stock = $product->stock + $quantity;
					$product->save(false);
				}
			}	
		}
		
		
	}

    /**
     * Deletes an existing Estimations model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        
		$estimation_items = EstimationItems::find()
						->where(['estimationID' => $id])
						->all();
		foreach($estimation_items as $estimation_item)
		{
			$product = Products::find()->where(['productName'=>$estimation_item->productName])->one();
			if(!empty($product))
			{
				$product->stock = $product->stock + $estimation_item->quantity;
				$product->save(false);
			}
			$estimation_item->delete();
		}
			
		$this->findModel($id)->delete();

        return $this->redirect(['index']);
    }
    
    public function actionExport($id,$output=0,$file_name="") {
		// get your HTML raw content without any layouts or scripts
		$profile = Users::findOne(Yii::$app->user->id);	
		$model = Estimations::find()
						->JoinWith('estimationItems')
						->where(['estimations.id' => $id])
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
     * Finds the Estimations model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Estimations the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Estimations::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
