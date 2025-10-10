<?php


class Products extends CActiveRecord{

    public $productId;
    public $name;
    public $price;
    public $stock;

    public static function model($className=__CLASS__) {
        return parent::model($className);
    }

    public function tableName(){
        return 'products';
    }

    public function rules(){
        return array(
            array('productId, name, price, stock', 'required'),
            array('stock','numerical', 'min'=>1),
            array('price','numerical', 'min'=>1),
        );
    }
}
