<table>
	<thead>
		<tr>
			<th>|name|</th>
			<th>|price|</th>
			<th>|stock|</th>
			<th>|Quantity|</th>
		</tr>
	</thead>
	<tbody>
		<?php $products=Products::model();
		foreach ($products->findAll() as $product):?>
		<tr>
			<td>|<?php echo $product->name;?></td>
			<td>|<?php echo $product->price;?></td>
			<td>|<?php echo $product->stock;?></td>
            <td>|<?php $qtd = 'quantity_'.$product->id;
						echo CHtml::numberField($qtd, 1, array(
                            'id'=>$qtd,
                            'min'=>1,
                            'max'=>$product->stock
                        ));?></td>
			<td><?php 
				echo CHtml::ajaxButton(
					'Compra',
					array('site/venda'),
					array(
						'type'=>'POST',
						'dataType'=>'json',
						'data' => "js:{id: {$product->id}, quantity: $('#$qtd').val()}",
						'beforeSend' => 'function(){ console.log("Enviando venda para o servidor..."); }',
						'success'=>'function(response){
                            console.log("Resposta da API:", response);
							if(response.success){
								$("#itens_table").html(response.html);
								alert("Sucesso! item:" +response.itens.name+ "\n quantity:"+response.itens.quantity+"\n idVenda:" +response.result.id); 
							}else{
								alert("Erro: "+response.error);
							}
						}',
						'error'=>'function(){
							alert("Erro na comunicação com a API.");
						}',
					),
				);
	?></td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>
