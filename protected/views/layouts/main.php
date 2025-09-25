<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo CHtml::encode($this->pageTitle); ?></title>
    <?php
        Yii::app()->clientScript->registerCoreScript('jquery');
        Yii::app()->clientScript->registerCoreScript('yii');
        Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl.'/css/style.css');
    ?>
    

</head>

<body>
    
    <header class="app-header">
    	<h1 class="app-title"><?php echo CHtml::encode(Yii::app()->name); ?></h1>
    </header>

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

    <main class="app-content">
    	<?php echo $content; ?>
    </main>

    <div class="clear"></div>

     <!--<div id="footer">Copyright &copy; <?php echo date('Y'); ?> by My Company.<br/>All Rights Reserved.<br/></div>-->
    <!-- footer -->
</body>
</html>