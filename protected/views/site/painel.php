<?php
$this->pageTitle=Yii::app()->name. ' - Painel';
$this->breadcrumbs=array(
	'Painel',
);
?>

<h1>Painel de Configuração</h1>

<br>

<div class='container-form'>
<?php echo CHtml::beginForm(array('site/requisicao'), 'post', array('id'=>'token-form')) ?>
		<h2>Pegar o token</h2>
	<hr>

	<div class= "form-row">
		<?php echo CHtml::label('Username:', 'username')?>
		<?php echo CHtml::textfield('username', '', array('id'=>'username'))?>
	</div>

	<div class= "form-row">
		<?php echo CHtml::label('Password:', 'password')?>
		<?php echo CHtml::passwordfield('password', '', array('id'=>'password'))?>
	</div>

	<hr>	
	<?php
		echo CHtml::ajaxSubmitButton(
			'Pegar token',
			array('site/requisicao'),
			array(
				'type'=>'POST',
				'dataType'=>'json',
				'success'=>'function(response){
					if(response.success){
						alert("Sucesso! Token atualizado" + " token: " + response.token);
						$("#token-form")[0].reset();
					}else{
						alert("Erro:"+response.error);
					}
				}',
				'error'=>'function(){
					alert("Erro ao enviar os dados.");
				}',
			),
			array('id'=>'btnToken'),
		);
	?>

<?php echo CHtml::endForm();?>
</div>

<br><br>

<div class='container-form'>
	<h2>Buscar Produto</h2>
	<hr>
	<div class="form-row">
		<?php echo CHtml::beginForm(array('site/requisicao'), 'get', array('id'=>'Req-form')) ?>
			<?php echo CHtml::label('ID:', 'reqID')?>
			<?php echo CHtml::textfield('reqID','', array('id'=>'reqID'))?>
			<?php
				echo CHtml::ajaxButton(
					'Buscar na API',
					array('site/requisicao'),
					array(
						'type'=> 'GET',
						'dataType'=> 'json',
						'success' => 'function(response){
							if(response.success){
								$("#productId").val(response.data.productId);
								$("#name").val(response.data.name);
								$("#price").val(response.data.price);
								$("#Req-form").find("#reqID").val("");
							}else{
								alert("Erro: " + response.error);
							}
						}',
						'error'=>'function() {
							alert("Erro na comunicação com a API.");
						}',
					),
					array('id'=> 'btnReq'),
				);
			?>
		<?php echo CHtml::endForm();?>
	</div>
</div>

<br><br>

<div class='container-form'>
<?php echo CHtml::beginForm(array('site/create'), 'post', array('id'=>'add-form')) ?>
		<h2>Registrar um novo Produto</h2>
	<hr>

	<div class= "form-row">
		<?php echo CHtml::label('productId:', 'productId')?>
		<?php echo CHtml::textfield('Product[productId]', '', array('id'=>'productId'))?>
	</div>

	<div class= "form-row">
		<?php echo CHtml::label('Name:', 'name')?>
		<?php echo CHtml::textfield('Product[name]', '', array('id'=>'name'))?>
	</div>

	<div class= "form-row">
		<?php echo CHtml::label('Price:', 'price')?>
		<?php echo CHtml::textfield('Product[price]', '', array('id'=>'price'))?>
	</div>

	<div class= "form-row">	
		<?php echo CHtml::label('Stock:', 'stock')?>
		<?php echo CHtml::textfield('Product[stock]', '', array('id'=>'stock'))?>
	</div>

	<hr>	
	<?php
		echo CHtml::ajaxSubmitButton(
			'Adicionar ao Banco de Dados',
			array('site/create'),
			array(
				'type'=>'POST',
				'dataType'=>'json',
				'success'=>'function(response){
					if(response.success){
						alert("Sucesso! Produto adicionado ao banco de dados")
						$("#add-form").find("#productId,#name, #price, #stock").val("");
						$("#itens_table").html(response.html);
					}else{
						alert("Erro:"+response.error);
					}
				}',
				'error'=>'function(){
					alert("Erro na comunicação com o banco de dados.");
				}',
			),
			array('id'=>'btnAdd', 'onclick'=>"setTimeout(function(){ window.location.reload(); }, 100)"),
		);
	?>

<?php echo CHtml::endForm();?>
</div>

<br><br>

<div class='container' id='itens_table'>
	<?php
		$html = $this->renderPartial("table_p");
	?>
</div>
