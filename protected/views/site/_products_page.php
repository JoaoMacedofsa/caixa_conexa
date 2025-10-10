<?php
$this->widget('zii.widgets.CListView', array(
    'dataProvider'=>$dataProvider,
    'id' => 'productList',
    'itemView'=> '_table_h',
    'emptyText' => 'Nenhum produto encontrado',
    'template' => "{items}\n{pager}",
    'itemsCssClass'=>'product-grid row',
    'cssFile'=>false,
    'pager' => array(
        'header' => '',
        'firstPageLabel' => '<<',
        'prevPageLabel' => '<',
        'nextPageLabel' => '>',
        'lastPageLabel' => '>>',
    ),
));
?>
