<?php
$this->pageTitle=Yii::app()->name. ' - Home';
?>

<h1>Welcome</h1>

<p> Itens disponiveis para compra</p>

<div class='container-fluid text-center' id='home_table'>
    <?php $this->renderPartial('_products_page', array('dataProvider' => $dataProvider)); ?>
</div>