<?php

return array(
    'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
    'name'=>'Caixa',
	
	'import'=>array(
		'application.models.*',
		'application.components.*',
	),

	'components'=>array(
		'db'=>require(dirname(__FILE__).'/database.php'),
		#'db'=>array(
			#'class'=>'system.db.CDbConnection',
			#'connectionString'  => 'sqlite:'.dirname(__FILE__).'/../data/caixa.db',
		#),
	),
);