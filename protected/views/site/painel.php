<?php
/*
Aqui será o painel do admin, onde os intens serão adicionados e dado uma quantidade no estoque
*/
$this->pageTitle=Yii::app()->name. ' - Painel';
$this->breadcrumbs=array(
	'painel',
);

?>

<h1>Painel de Configuração</h1>

<?php echo CHtml::beginForm(array('site/requisicao'), 'post', array('id'=>'Req-form')) ?>
	<?php echo CHtml::label('RequisicaoID:', 'reqID')?>
	<?php echo CHtml::textfield('reqID','', array('id'=>'reqID'))?>
	<?php
		echo CHtml::ajaxSubmitButton(
			'Buscar na API',
			array('site/requisicao'),
			array(
				'type'=> 'POST',
				'dataType'=> 'json',
				'success' => 'function(response){
					if(response.success){
						$("#productId").val(response.data.productId);
						$("#name").val(response.data.name);
						$("#price").val(response.data.price);
						$("#Req-form")[0].reset();
					}else{
						alert("Erro: " + response.error);
					}
				}',
				'error'=>'function() {
                	alert("Erro na comunicação com a API.");
            	}',
			)
		);
	?>
<?php echo CHtml::endForm();?>
	<br><br>

<?php echo CHtml::beginForm(array('site/create'), 'post', array('id'=>'add-form')) ?>
	<?php echo CHtml::label('ID:', 'id')?>
	<?php echo CHtml::textfield('Product[id]', $model->id, array('id'=>'productId'))?>
	<?php echo CHtml::label('Name:', 'name')?>
	<?php echo CHtml::textfield('Product[name]', $model->name, array('id'=>'name'))?>
	<?php echo CHtml::label('Price:', 'price')?>
	<?php echo CHtml::textfield('Product[price]', $model->price, array('id'=>'price'))?>
	<?php echo CHtml::label('Stock:', 'stock')?>
	<?php echo CHtml::textfield('Product[stock]', $model->stock)?>
	
	<?php
		echo CHtml::ajaxSubmitButton(
			'Adicionar ao Banco de dados',
			array('site/create'),
			array(
				'type'=>'POST',
				'dataType'=>'json',
				'success'=>'function(response){
					if(response.success){
						alert("Sucesso! Produto adicionado ao banco de dados")
						$("#add-form")[0].reset();
						$("#itens_table").html(response.html);
					}else{
						alert("Erro:"+response.error);
					}
				}',
				'error'=>'function(){
					alert("Erro na comunicação com o banco de dados.");
				}',
			),
		);
	?>
	<br><br>
<?php echo CHtml::endForm();?>

<div class='container' id='itens_table'>
<?php
	include('table_p.php');
?>
</div>
