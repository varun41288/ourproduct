<?php

namespace app\controllers;

use Yii;
use app\models\Products;
use app\models\ProductsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;

use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * ProductsController implements the CRUD actions for Products model.
 */
class ProductsController extends Controller
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
	
	/*public function beforeAction($action)
	{
		if (\Yii::$app->request->getQueryParam('model'))
			$this->layout = 'model';
		else
			$this->layout = 'main';

		return parent::beforeAction($action);
	}
*/
    /**
     * Lists all Products models.
     * @return mixed
     */
    public function actionIndex()
    {
			
        $searchModel = new ProductsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
	
	public function actionExpiry()
    {
			
        $searchModel = new ProductsSearch();
        $dataProvider = $searchModel->searchExpiry(Yii::$app->request->queryParams);

        return $this->render('expiry', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single Products model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Products model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Products();

        if ($model->load(Yii::$app->request->post())) {
			
			$model->stock = $model->opening_stock;
			
			if($model->save())
				return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }
    
    public function actionCreateajax()
    {
        $model = new Products();
        
        if ($model->load(Yii::$app->request->post())) {
			
			$model->stock = $model->opening_stock;
			
			if($model->save())
			{	
				$model->refresh();
				Yii::$app->response->format = 'json';
				return ['message' => "success", 'id'=>$model->id];
			}
        }

        return $this->renderAjax('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Products model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }
		
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Products model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }
    
    	
	public function actionSearch($term,$action)
    {
        if (Yii::$app->request->isAjax) {
		
			
            $results = [];

            /*if (is_numeric($term)) {
              
                $model = Products::findOne(['id' => $term]);
                
                if ($model) {
                    $results[] = [
                        'id' => $model['id'],
                        'label' => $model['productName'],
                        'hsnCode' => $model['hsnCode'],
                        'price' => $model['price'],
                        'cgstPer' => $model['cgstPer'],
                        'sgstPer' => $model['sgstPer'],
                        'igstPer' => $model['igstPer'],
                        'brand' => $model['brand'],
                        'model' => $model['model'],
                    ];
                }
            } else {
            */
                $q = addslashes($term);
				//if($action=='sales')
					//$condition = "((`productName` like '%{$q}%' OR `productCode` like '%{$q}%') AND stock > 0)";
				//else
					$condition = "(`productName` like '%{$q}%' OR `productCode` like '%{$q}%')";
				
                foreach(Products::find()->where($condition)->all() as $model) {
                    $results[] = [
                        'id' => $model['id'],
                        'productName' => $model['productName'],
                        'label' => $model['productName'],
                        'sno' => $model['sno'],
                        'hsnCode' => $model['hsnCode'],
                        'price' => $model['price'],
                        'per' => $model['per'],
                        'cgstPer' => $model['cgstPer'],
                        'sgstPer' => $model['sgstPer'],
                        'igstPer' => $model['igstPer'],
                        'brand' => $model['brand'],
                        'model' => $model['model'],
                        'stock' => $model['stock'],
                    ];
                }
            //}

            echo Json::encode($results);
			exit;
        }
    }

    /**
     * Finds the Products model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Products the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Products::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
