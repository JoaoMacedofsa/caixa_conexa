<div class="product-grid">
    <?php $products = Products::model();
    foreach ($products->findAll() as $product): ?>
        <div class="product-card">
            <div class="product-card__header">
                <?php echo CHtml::encode($product->name); ?>
            </div>
            <div class="product-card__body">
               <div class="product-card__meta"> <?php echo CHtml::encode($product->name); ?></div>
                <div class="product-card__row">
                    <div class="product-card__price">R$ <?php echo CHtml::encode($product->price); ?></div>
                    <div>Estoque: <?php echo CHtml::encode($product->stock); ?></div>
                </div>
                <div class="product-card__row">
                    <div class="product-card__controls">
                        <?php $qtd = 'quantity_' . $product->id; ?>
                        <?php echo CHtml::numberField($qtd, 1, array(
                            'id' => $qtd,
                            'min' => 1,
                            'max' => $product->stock,
                            'class' => 'product-card__qty'
                        )); ?>
                        <?php 
                        echo CHtml::ajaxButton(
                            'Compra',
                            array('site/venda'),
                            array(
                                'type' => 'POST',
                                'dataType' => 'json',
                                'data' => "js:{id: {$product->id}, quantity: $('#$qtd').val()}",
                                'beforeSend' => 'function(){ console.log("Enviando venda para o servidor..."); }',
                                'success' => 'function(response){
                                    console.log("Resposta da API:", response);
                                    if(response.success){
                                        $("#home_table").html(response.html);
                                        alert("Sucesso! item:" + response.itens.name + "\n quantity:" + response.itens.quantity + "\n Id da venda:"+ response.result.id);
                                    }else{
                                        alert("Erro: " + response.error);
                                    }
                                }',
                                'error' => 'function(){
                                    alert("Erro na comunicação com a API.");
                                }',
                            ),
                            array('class' => 'btn')
                        );
                        ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>