<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo CHtml::encode($this->pageTitle); ?></title>
    <?php
        Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl.'/css/style.css');
        Yii::app()->clientScript->registerCoreScript('jquery');
        Yii::app()->clientScript->registerCoreScript('yii');
    ?>

</head>

<body>

    <div class='container' id='mainmenu'>
        <?php $this->widget('zii.widgets.CMenu',array(
            'items'=>array(
                array('label'=>'Home', 'url'=>array('/site/index')),
                array('label'=>'Painel', 'url'=>array('/site/painel')) 
            ),
        ));?>
    </div><!-- menu -->
    
    <?php if(isset($this->breadcrumbs)):?>
		<?php $this->widget('zii.widgets.CBreadcrumbs', array(
			'links'=>$this->breadcrumbs,
		)); ?><!-- breadcrumbs -->
	<?php endif?>

    <?php echo $content; ?>

    <div class="clear"></div>

     <!--<div id="footer">Copyright &copy; <?php echo date('Y'); ?> by My Company.<br/>All Rights Reserved.<br/></div>-->
    <!-- footer -->
</body>