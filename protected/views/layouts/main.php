<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo CHtml::encode($this->pageTitle); ?></title>
    <?php
        Yii::app()->clientScript->registerCoreScript('jquery');
        Yii::app()->clientScript->registerCoreScript('yii');
        Yii::app()->clientScript->registerCssFile('https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css');
        Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl.'/css/style.css');
    ?>
    <link rel="icon" href="<?php echo Yii::app()->baseUrl; ?>/imgs/favicon.ico" type="image/x-icon">

</head>

<body>
        <div class="app-header" id='mainmenu'>
    	    <div class="app-title">
                <img src=imgs/logo.png alt='Logo do projeto' id='logo'>
                <?php echo CHtml::encode(Yii::app()->name); ?>
            </div>
            <nav class="nav">    
                <?php $this->widget('zii.widgets.CMenu',array(
                    'items'=>array(
                        array('label'=>'Home', 'url'=>array('/site/index')),
                        array('label'=>'Painel', 'url'=>array('/site/painel')) 
                    ),
                ));?>
            </nav>
        </div>
    
    <?php if(isset($this->breadcrumbs)):?>
		<?php $this->widget('zii.widgets.CBreadcrumbs', array(
			'links'=>$this->breadcrumbs,
		)); ?><!-- breadcrumbs -->
	<?php endif?>

    <main class="app-content">
    	<?php echo $content; ?>
    </main>

    <div class="clear"></div>

    <div id="footer">Copyright &copy; <?php echo date('Y'); ?> by My Company.<br/>All Rights Reserved.<br/></div>
    <!-- footer -->
<?php Yii::app()->clientScript->registerScriptFile('https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js', CClientScript::POS_END); ?>
</body>
</html>