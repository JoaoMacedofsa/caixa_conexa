<div class='col' id='product-card'>
    <div class="product-card__header">
        <?php echo CHtml::encode($data->name); ?>
    </div>
    
    <div class="product-card__body">
        <div class="product-card__meta"><?php echo CHtml::encode($data->name); ?></div>

        <div class="product-card__row">
            <div class="product-card__price">R$ <?php echo CHtml::encode($data->price); ?></div>
            <div>Estoque: <?php echo CHtml::encode($data->stock); ?></div>
        </div>

        <div class="product-card__row">
            <div class="product-card__controls">
                <?php 
                $btnBuy = 'buy_'.$data->productId;
                $qtd = 'quantity_'.$data->productId;

                echo CHtml::numberField($qtd, 1, array(
                    'id' => $qtd,
                    'min' => 1,
                    'max' => $data->stock,
                    'class' => 'product-card__qty'
                ));

                echo CHtml::ajaxSubmitButton(
                    'Compra',
                    array('site/requisicao'),
                    array(
                        'type' => 'POST',
                        'dataType' => 'json',
                        'data' => "js:{productId: {$data->productId}, quantity: $('#$qtd').val()}",
                        'success' => 'function(response){
                            if(response.success){
                                $.fn.yiiListView.update("productList");
                                alert("Sucesso!\n item: " + response.itens.name + "\n quantity: " + response.itens.quantity + "\n Id da venda: "+ response.result.id);
                            }else{
                                alert("Erro: " + response.error);
                            }
                        }',
                        'error' => 'function(){
                            alert("Erro na comunicação com a API.");
                        }',
                    ),
                    array('class' => 'btnBuy', 'id'=>$btnBuy)
                );
                ?>
            </div>
        </div>
    </div>
</div>
