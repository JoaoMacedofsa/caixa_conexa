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

    #Public function actionCreate(){
        #$produto = Product::model()

        #if (isset($_POST['Product'])){
         #   $produto->attributes = $_POST['Product'];

           # if ($produto->save()){
                #Yii::app()->user->setFlash('success', 'Produto adicionao ao')
    #}

    public function actionRequisicao(){
        if (isset($_POST['reqID'])){
            $reqID = $_POST['reqID'];

            $url_produto= "https://staging.conexa.app/index.php/api/v2/product/$reqID?fields=productId,name,price";
            $url_varios_produtos = 'https://staging.conexa.app/index.php/api/v2/products?companyId[]=3&isActive=1&size=5';

            $headers = ['Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE3NTg3OTg1NTcsImp0aSI6IjEwTS9MZTBXa0VteURoamIvNzhUNko3UHdNcVJMcTI2R0trdlpIRFlzR2c9IiwiaXNzIjoic3RhZ2luZyIsIm5iZiI6MTc1ODc5ODU1NywiZXhwIjoxNzU4ODI3MzU3LCJkYXRhIjp7ImlkIjoxNzMsInR5cGUiOiJhZG1pbiIsInBlcnNvbkN1c3RvbWVySWQiOm51bGx9fQ.bv_f3Cm4QSI98z5J2H6-msHNHLo3BwAwGx-KfPcGsyY',];
            
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
                   #$this->render('painel', array('result'=> $result));
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
                $headers = ['Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE3NTgyODA1MDAsImp0aSI6IjRPMitQVUJFZ1BLY1BFTGtSYW10UWQ0QlRZemQzYVdha0NDMTBxS2g2Nzg9IiwiaXNzIjoic3RhZ2luZyIsIm5iZiI6MTc1ODI4MDUwMCwiZXhwIjoxNzU4MzA5MzAwLCJkYXRhIjp7ImlkIjoxNzMsInR5cGUiOiJhZG1pbiIsInBlcnNvbkN1c3RvbWVySWQiOm51bGx9fQ.Knm4dc9mQzgQUhoKvTcL1VqsPs6pcDlFKNx0kUCvqME',];
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
    
    public function actionSiteTeste(){
        $product = new Products;
        $product->id = 2;
        $product->name = 'borracha';
        $product->price = 20.50;
        $product->stock = 5;
        $product->save();

        echo 'Produto adicionado com sucesso';
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
            echo CJSON::encode(array('success'=>false,'error'=> "Ocorreu um erro ao tentar deletar"));
            Yii::app()->end();
        }
    }

}