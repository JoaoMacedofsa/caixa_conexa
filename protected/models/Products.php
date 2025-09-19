<?php

#namespace app\models;
#use yii\db\ActiveRecord;

class Products extends CActiveRecord{

    public $id;
    public $name;
    public $description;
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
            array('id, name, price, stock', 'required'),
            array('stock','numerical', 'min'=>1),
            array('price','numerical', 'min'=>1),
        );
    }

    public function adicionarProduto($id,$name,$description='',$price,$stock){
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->price = $price;
        $this->stock = $stock;
        return $this->save();
    }

    public function mostrarTudo(){
        $this->findAll();
    }


}

/* Selecionou quantidade
-> Clicou em comprar 
-> É verificado se tem essa quantidade no estoque
-> Tendo, API request é enviada para o conexa confirmando a comprar
-> Não tendo, Comprar é negada com aviso "Não possuimos essa quantidade no estoque"*/
