<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Invoices */

$this->title = 'Update Invoice : '.$modelInvoice->invoiceNo;
$this->params['breadcrumbs'][] = ['label' => 'Sales', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $modelInvoice->invoiceNo, 'url' => ['view', 'id' => $modelInvoice->invoiceNo]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="invoices-update">

   
    <?= $this->render('_form', [
        'modelInvoice' => $modelInvoice,
        //'modelCustomer' => $modelCustomer,
        'invoiceNo' => $invoiceNo,
        'modelInvoiceItems' => $modelInvoiceItems,
    ]) ?>

</div>
