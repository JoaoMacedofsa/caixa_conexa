<?php
$this->pageTitle=Yii::app()->name. ' - Home';
?>

<h1> Welcome to <i><?php echo CHtml::encode(Yii::app()->name);?></i></h1>

    <p> Itens disponiveis para compra</p>
    <?php # Aqui usarei um for do php para exibir os produtos do db ?>

<div class='container' id='itens_table'>
    <?php
        include("table_h.php");
    ?>
</div>
