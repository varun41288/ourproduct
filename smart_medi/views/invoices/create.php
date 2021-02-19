<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Invoices */

$this->title = 'Create Invoice';
$this->params['breadcrumbs'][] = ['label' => 'Sales', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="invoices-create">
    <?= $this->render('_form', [
        'modelInvoice' => $modelInvoice,
        'invoiceNo' => $invoiceNo,
        'modelInvoiceItems' => $modelInvoiceItems,
    ]) ?>

</div>
