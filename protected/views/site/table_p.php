<table class='table'>
	<thead>
		<tr>
			<th>id</th>
			<th>name</th>
			<th>price</th>
			<th>stock</th>
			<th>Modificações</th>
		</tr>
	</thead>
	<tbody>
		<?php $products=Products::model();
		foreach ($products->findAll() as $product):?>
		<tr>
			<td><?php echo $product->id;?></td>
			<td><?php echo $product->name;?></td>
			<td><?php echo "R$".$product->price;?></td>
			<td><?php echo $product->stock;?></td>
			<td><?php 
				echo CHtml::ajaxSubmitButton(
					'Delete',
					array('site/delete'),
					array(
						'type'=>'POST',
						'dataType'=>'json',
						'data' => array('id' => $product->id),
						'success'=>'function(response){
							if(response.success){
								alert("Success! Product deleted")
								$("#itens_table").html(response.html);
							}else{
								alert("Erro: "+response.error);
							}
						}',
						'error'=>'function(){
							alert("Erro na comunicação com o banco de dados.");
						}',
					),
				);
	?></td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>