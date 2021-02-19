<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Purchases */

$this->title = 'Update Purchase : '.$modelPurchase->purchaseNo;
$this->params['breadcrumbs'][] = ['label' => 'Purchases', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $modelPurchase->purchaseNo, 'url' => ['view', 'id' => $modelPurchase->purchaseNo]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="purchases-update">

   
    <?= $this->render('_form', [
        'modelPurchase' => $modelPurchase,
        //'modelSupplier' => $modelSupplier,
        'modelPurchaseItems' => $modelPurchaseItems,
    ]) ?>

</div>
