<div class="table-responsive">
<table class='table table-hover align-middle table-sm'>
	<thead>
		<tr>
			<th>ProductId</th>
			<th>Name</th>
			<th>Price</th>
			<th>Stock</th>
			<th>Excluir</th>
		</tr>
	</thead>
	<tbody>
		<?php $products=Products::model();
		foreach ($products->findAll() as $product):?>
		<tr>
			<td><?php echo $product->productId;?></td>
			<td><?php echo $product->name;?></td>
			<td><?php echo "R$".$product->price;?></td>
			<td><?php echo $product->stock;?></td>
			<td><?php $btnDelete = 'delete_' . $product->productId;
				echo CHtml::ajaxSubmitButton(
					'Delete',
					array('site/delete'),
					array(
						'type'=>'POST',
						'dataType'=>'json',
						'data' => array('productId' => $product->productId),
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
					array('class' => 'btnDelete', 'id'=>$btnDelete)
				);
	?></td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>
</div>