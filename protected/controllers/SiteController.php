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
        $dataProvider = new CActiveDataProvider('Products', array(
            'pagination'=>array("pageSize" => 9),
            'criteria'=>array('order'=>'productId ASC'),
            )        
        );

        $this->render('index', array('dataProvider'=>$dataProvider));
    }

    public function actionPainel(){
        $this->render('painel');
    }


    
    public function actionRequisicao(){
        $url_base = "https://staging.conexa.app/index.php/api/v2/";
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        if(isset($_POST['username']) && isset($_POST['password'])){
            $username = $_POST['username'];
            $password = $_POST['password'];

            $url_auth = $url_base."auth";

            $data = array('username'=>$username, 'password'=>$password);
            $jsonData = json_encode($data);

            
            curl_setopt($curl, CURLOPT_URL, $url_auth);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonData);

            $response = curl_exec($curl);
            $result = json_decode($response, true);
            curl_close($curl);
            if(!$result['accessToken']){

                header('Content-Type: application/json');
                echo CJSON::encode(array(
                    'success'=> false, 
                    'error' => 'Requisicao Invalida'
                ));

                Yii::app()->end();
            }else{
                Yii::app()->session['accessToken'] = $result['accessToken'];

                header('Content-Type: application/json');
                echo CJSON::encode(array('success'=> true, 'token'=>$result['accessToken']));

                Yii::app()->end();
            }

        }

        $token = Yii::app()->session['accessToken'];
        $headers = ["Authorization: Bearer $token"];
                
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        if(isset($_GET['reqID'])){
            $reqID = $_GET['reqID'];
            $url_produto = $url_base . "product/$reqID?fields=productId,name,price";

            curl_setopt($curl, CURLOPT_URL, $url_produto);

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
        }

        if (isset($_POST['productId']) && isset($_POST['quantity'])){
            $id = (int)$_POST['productId'];
            $qtd = (int)$_POST['quantity'];
                
            $product = Products::model()->findByPK($id);
            $stock = $product->stock;
            if ($qtd <= $stock){
                $newStock = $stock - $qtd;
                $product->stock = $newStock;
                $product->save();


                $dataProvider = new CActiveDataProvider('Products', array('pagination' => array('pageSize' => 9)));
                $html = $this->renderPartial('_products_page', array('dataProvider' => $dataProvider), true, false);
                $itens = array('name'=>$product->name, 'quantity'=>$qtd);

                $url_venda= $url_base."sale";

                $data = array(
                    "customerId"=> 30,
                    "productId"=> $id,
                    "quantity"=> $qtd
                );
                $jsonData = json_encode($data);

                curl_setopt($curl, CURLOPT_URL, $url_venda);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonData);

                $response = curl_exec($curl);
                $result = json_decode($response, true);

                if (curl_error($curl)){
                    echo 'Error: '.curl_error($curl);
                }

                if(!$result['id']){
                    curl_close($curl);
                    #Garantindo que valor do estoque não mude
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

                    Yii::app()->end();
                };

            }else{
                echo CJSON::encode(array(
                    'success'=>false,
                    'error'=> "Quantity bigger than stock, stock is: ". $stock));
                    Yii::app()->end();
            } 
        }   
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
        if(isset($_POST['productId'])){
            $id = (int)$_POST['productId'];
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