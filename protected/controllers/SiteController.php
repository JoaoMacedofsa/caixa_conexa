<?php
class SiteController extends Controller{
    public function actions(){
        return array(
            'page'=>array(
                'class'=>"CViewAction",
            ),
        );
    }

    public function actionIndex(){
        $this->render('index');
    }

    public function actionPainel(){
        $this->render('painel');
    }

    public function actionRequisicao(){
        if (isset($_GET['reqID'])){
            $reqID = $_GET['reqID'];

            $url_produto= "https://staging.conexa.app/index.php/api/v2/product/$reqID?fields=productId,name,price";
            $url_varios_produtos = 'https://staging.conexa.app/index.php/api/v2/products?companyId[]=3&isActive=1&size=5';

            $headers = ['Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE3NTk0OTE1MjUsImp0aSI6IldqREh0MDNRWjJ1bHRCaDh6L1hlaVBGcVFRTUFVdVEzTUNZOWFLSVhNQVU9IiwiaXNzIjoic3RhZ2luZyIsIm5iZiI6MTc1OTQ5MTUyNSwiZXhwIjoxNzU5NTIwMzI1LCJkYXRhIjp7ImlkIjoxNzMsInR5cGUiOiJhZG1pbiIsInBlcnNvbkN1c3RvbWVySWQiOm51bGx9fQ.6PbiVwt55yrc0h35UstiGlYbJU1jyDvnPDjqDbJ1pjk',];
            
            $curl = curl_init();

            curl_setopt($curl, CURLOPT_URL, $url_produto);
            #curl_setopt($curl, CURLOPT_URL, $url_varios_produtos);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
            
            # True = a resposta da API é retornada como string e não sai direto com o curl_exec()
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

            $response = curl_exec($curl);
            $result = json_decode($response, true);

            if (curl_error($curl)){
                echo 'Error: '.curl_error($curl);
            }

            if(!$result['productId']){
                header('Content-Type: application/json');
                echo CJSON::encode(array(
                    'success'=> false,
                    'error' => 'Requisicao Invalida',
                ));
                Yii::app()->end();

            }else{
                curl_close($curl);

                if ($result === null){
                    echo 'Failed to decode JSON response';
                }else{
                    $data = array(
                    'productId' => $result['productId'] ? $result['productId']:'',
                    'name' => $result['name'] ? $result['name']:'',
                    'price' => $result['price'] ? $result['price']: '',
                    );
                    header('Content-Type: application/json');
                    echo CJSON::encode(array(
                        'success'=> true,
                        'data' => $data,
                    ));
                    
                    Yii::app()->end();
                }
            };
            
        }else{
            header('Content-Type: application/json');
                echo CJSON::encode(array(
                    'success'=> false,
                    'error' => 'reqID não enviado',
                ));
            Yii::app()->end();  
        }
    }

    public function actionVenda(){
        if (isset($_POST['id']) && isset($_POST['quantity'])){
            $id = (int)$_POST['id'];
            $qtd = (int)$_POST['quantity'];

            $product = Products::model()->findByPK($id);
            $stock = $product->stock;
            if ($qtd <= $stock){
                $newStock = $stock - $qtd;
                $product->stock = $newStock;
                $product->save();
                
                
                $produtos = Products::model()->findAll();
                $html = $this->renderPartial("table_h", array('produtos'=>$produtos), true, false);
                $itens = array('name'=>$product->name, 'quantity'=>$qtd);

                $url_produto= "https://staging.conexa.app/index.php/api/v2/sale";
                $headers = ['Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE3NTk0MDY5MDUsImp0aSI6IkxhMk1VdFhtY2lHN0FGQ1h2c0JOZGhVZXNZTWhLbGJrbGdLVUpsd2VrRkU9IiwiaXNzIjoic3RhZ2luZyIsIm5iZiI6MTc1OTQwNjkwNSwiZXhwIjoxNzU5NDM1NzA1LCJkYXRhIjp7ImlkIjoxNzMsInR5cGUiOiJhZG1pbiIsInBlcnNvbkN1c3RvbWVySWQiOm51bGx9fQ.5H_37plxWCSbL9yEpy9S4u6fDPH6Ip75qZloHiGDjV8',];
                $data = array(
                    "customerId"=> 30,
                    "productId"=> $id,
                    "quantity"=> $qtd
                );
                $jsonData = json_encode($data);
                $curl = curl_init();

                curl_setopt($curl, CURLOPT_URL, $url_produto);
                curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonData);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

                $response = curl_exec($curl);
                $result = json_decode($response, true);

                if (curl_error($curl)){
                echo 'Error: '.curl_error($curl);
                }
                if(!$result['id']){
                    $product->stock = $stock;
                    $product->save();
                    header('Content-Type: application/json');
                    echo CJSON::encode(array(
                        'success'=> false,
                        'error' => 'Requisicao Invalida',
                    ));
                    Yii::app()->end();  
                }else{

                    curl_close($curl);
                    header('Content-Type: application/json');
                    echo CJSON::encode(array(
                        'success'=>true,
                        'html' =>$html,
                        'result'=>$result,
                        'itens'=>$itens
                    ));
                }
                Yii::app()->end();
                
                
            }else{
                echo CJSON::encode(array(
                    'success'=>false,
                    'error'=> "Quantity bigger than stock, stock is: ". $stock));
            Yii::app()->end();} 

            
        }
    }

    public function actionTeste(){
        $product = Products::model()->findByPK(2146);
        echo $product->name;
        if($product->delete()){echo ' Deletado com sucesso';};
        /*$newStock = 100;
        $stock = $product->stock + $newStock;
        $product->stock = $stock;
        if($product->save()){echo ' Stock com sucesso';};*/
    }

    public function actionCreate(){
        $product = new Products;
        if(isset($_POST['Product'])){
            $product->attributes = $_POST['Product'];
            if($product->save()){

                $produtos = Products::model()->findAll();
                $html = $this->renderPartial("table_p", array('produtos'=>$produtos), true);
                
                header('Content-Type: application/json');
                echo CJSON::encode(array(
                    'success'=> true,
                    'html' => $html
                ));
                Yii::app()->end();
            };
        }
    }

    public function actionDelete(){
        if(isset($_POST['id'])){
            $id = (int)$_POST['id'];
            $product = Products::model()->findByPK($id);

            if($product !== null){
                if($product->delete()){
                    $produtos = Products::model()->findAll();
                    $html = $this->renderPartial("table_p", array('produtos'=>$produtos), true);

                    header('Content-Type: application/json');
                    echo CJSON::encode(array(
                        'success'=> true,
                        'html' =>$html
                    ));
                    Yii::app()->end();
                }
            }
            header('Content-Type: application/json');
            echo CJSON::encode(array('success'=>false,'error'=> "Produto não existe"));
            Yii::app()->end();
        }
    }
}