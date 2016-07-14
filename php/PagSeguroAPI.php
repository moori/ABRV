<?php
//header('Access-Control-Allow-Origin: *');
include("../PagSeguroLibrary/PagSeguroLibrary.php");
include("conn.php"); 
$data = json_decode(file_get_contents("php://input"));
$action = $data->action;

$email = $data->Email;
$senha = $data->Senha;
$nome = $data->Nome;
$endereco = $data->Endereco;
$end_num = $data->Numero;
$end_complemento = $data->Complemento;
$cep = $data->CEP;
$bairro = $data->Bairro;
$cidade = $data->Cidade;
$estado = $data->Estado;
$tel_ddd = $data->Tel_DDD;
$telefone = $data->Telefone;
$cel = $data->Cel;
$cel_ddd = $data->Cel_DDD;
$crmv = $data->CRMV;
$especialidade = $data->Especialidade;
$cpf = $data->CPF;
$token = $data->Token;
$tipo = $data->Tipo;

$eventID = $data->eventID;

if($action == "RenovarAnuidade"){
    $q = "SELECT Titulo, Preco, DataInicio, DataFim, EventID FROM sales WHERE EventID = $eventID";
    $query = $conn->prepare($q);
    $query->execute();
    $result = $query->fetchAll(PDO::FETCH_OBJ);

    // $saleFile = 'saleLog.txt';
    // $c = file_get_contents($saleFile);
    // $c = "Result: " . var_dump($result[0]);
    // $c .= "Banana";
    // file_put_contents($saleFile, $c);

    $currentDate = date("Y-m-d"); 

    foreach ($result as $sale) {
        if($sale->DataInicio <= $currentDate && $sale->DataFim >=$currentDate){
            $itemID = $sale->EventID;
            $itemTitulo = $sale->Titulo;
            $itemPreco = $sale->Preco;

            $saleFile = '_SaleLog.txt';
            $c = file_get_contents($saleFile);
            $c .= "ID: " . $itemID . " | Titulo: " . $itemTitulo . " | Preco: " . $itemPreco . "\n";
            file_put_contents($saleFile, $c);

        }
    };



    $userInfo = $conn->query("SELECT ID, Email FROM users WHERE Token='$token'"); // TOKEN SEM ASPAS NO QUERY!!!
    $userInfo = $userInfo->fetch(PDO::FETCH_OBJ);
    $userID = $userInfo->ID;
    $userEmail = $userInfo->Email;
        $paymentRequest = new PagSeguroPaymentRequest();  
        $paymentRequest->addItem($itemID, $itemTitulo, 1, $itemPreco);
        // $sedexCode = PagSeguroShippingType::getCodeByType('SEDEX');  
        // $paymentRequest->setShippingType($sedexCode);  
        // $paymentRequest->setShippingAddress(  
        //   $cep,  
        //   $endereco,  
        //   $end_num,  
        //   $end_complemento,  
        //   $bairro,  
        //   $cidade,  
        //   $estado,  
        //   'BRA'  
        // );
        // $paymentRequest->setSender(
        //   $nome,  
        //   $userEmail,  
        //   $cel_ddd,  
        //   $cel,  
        //   'CPF',  
        //   $cpf  
        // );
        $paymentRequest->setSender(
          $nome,  
          $userEmail, 
          null,
          null, 
          'CPF',  
          $cpf  
        ); 
        $paymentRequest->setCurrency("BRL");
        // Referenciando a transação do PagSeguro em seu sistema  
        $paymentRequest->setReference($userID);
        // URL para onde o comprador será redirecionado (GET) após o fluxo de pagamento  
        $paymentRequest->setRedirectUrl($pagSeguroCheckoutURL);
        // URL para onde serão enviadas notificações (POST) indicando alterações no status da transação  
        // $paymentRequest->addParameter('notificationURL', $abrvNotificationURL); 

        date_default_timezone_set("America/Sao_Paulo");
        $logDataTime = date("Y-m-d") . " " .date("H:i:s");
        $pagseguroLogFile = '../Logs/_PagSeguroLog.txt';

        try {
            //    $credentials = PagSeguroConfig::getAccountCredentials(); // getApplicationCredentials()
            $credentials = new PagSeguroAccountCredentials(
                $pagSeguroEmail,
                $pagSeguroToken
            );
            $checkoutUrl = $paymentRequest->register($credentials);
            $code = explode("=", $checkoutUrl);
            
            $c = file_get_contents($pagseguroLogFile);
            $c .= "SUCESSO | " . $nome . " | " . $userEmail . " | " . $logDataTime . " | code: " . $code[1] . "\n";
            file_put_contents($pagseguroLogFile, $c);

            echo json_encode($code[1]);
            //    echo $$checkoutUrl;
        } catch (PagSeguroServiceException $e) {

            $c = file_get_contents($pagseguroLogFile);
            $c .= "FALHA | " . $nome . " | " . $userEmail . " | " . $logDataTime . " | error: " . $e->getMessage() . "\n";
            file_put_contents($pagseguroLogFile, $c);

            die($e->getMessage());  
        }
    
    
}

if($action == "InscreverCurso"){
    $saleID = $data->saleID; 

    
    $currentDate = date("Y-m-d") . "|" . date("h:i:s"); 
    
    $q = "SELECT Titulo, Preco, DataInicio, DataFim, EventID, TextoSnippet FROM sales WHERE ID = $saleID";
    $query = $conn->prepare($q);
    $query->execute();
    $result = $query->fetchAll(PDO::FETCH_OBJ);

    foreach ($result as $sale) {
        $itemID = $saleID;
        $itemTitulo = $sale->Titulo;
        $itemPreco = $sale->Preco;
        $observation = $sale->TextoSnippet;
    };


    $userInfo = $conn->query("SELECT ID, Email FROM users WHERE Token='$token'"); // TOKEN SEM ASPAS NO QUERY!!!
    $userInfo = $userInfo->fetch(PDO::FETCH_OBJ);
    $userID = $userInfo->ID;
    $userEmail = $userInfo->Email;
        $paymentRequest = new PagSeguroPaymentRequest();  
        $paymentRequest->addItem($itemID, $itemTitulo, 1, $itemPreco);
        // $paymentRequest->setSender(
        //   $nome,  
        //   $userEmail,  
        //   $cel_ddd,  
        //   $cel,  
        //   'CPF',  
        //   $cpf  
        // ); 
        $paymentRequest->setSender(
          $nome,  
          $userEmail,  
          null,  
          null,  
          'CPF',  
          $cpf  
        ); 
        $paymentRequest->setCurrency("BRL");
        // Referenciando a transação do PagSeguro em seu sistema  
        $paymentRequest->setReference($userID);
        // URL para onde o comprador será redirecionado (GET) após o fluxo de pagamento  
        $paymentRequest->setRedirectUrl($pagSeguroCheckoutURL);
        // URL para onde serão enviadas notificações (POST) indicando alterações no status da transação  
        // $paymentRequest->addParameter('notificationURL', $abrvNotificationURL); 

        date_default_timezone_set("America/Sao_Paulo");
        $logDataTime = date("Y-m-d") . " " .date("H:i:s");
        $pagseguroLogFile = '../Logs/_PagSeguroLog.txt';

        try {
            //    $credentials = PagSeguroConfig::getAccountCredentials(); // getApplicationCredentials()
            $credentials = new PagSeguroAccountCredentials(
                $pagSeguroEmail,
                $pagSeguroToken
            );
            $checkoutUrl = $paymentRequest->register($credentials);
            $code = explode("=", $checkoutUrl);
            
            $c = file_get_contents($pagseguroLogFile);
            $c .= "SUCESSO | " . $nome . " | " . $userEmail . " | R$" . $itemPreco . " | " . $itemTitulo . " ( ". $itemID ." ) | " . $logDataTime . " | code: " . $code[1] . "\n";
            file_put_contents($pagseguroLogFile, $c);
            $subcriptionHash = $eventID . $userID . substr(md5($eventID+111), 0, 8) . substr(md5($userID+222), 0, 8) ;

            $subscriptionData = $conn->query("SELECT * FROM event_user_rel WHERE ID_event = '$eventID' AND ID_user = '$userID' ");
            $subscriptionData = $subscriptionData->fetch();

            if($subscriptionData == false){
                $q = "INSERT INTO event_user_rel (`id`, `ID_event`, `ID_user`, `ID_sale`, `PagamentoValor`, `Status`, `SubscriptionCode`, `Observation`, `DateSubscription`, `DateModified`) VALUES (NULL, '$eventID',  '$userID', '$saleID', '$itemPreco', 0, '$subcriptionHash', '$observation', '$logDataTime', '$logDataTime')";
                $query = $conn->prepare($q);
                $execute = $query->execute();
            }else{

                $q = "UPDATE event_user_rel SET ID_sale = '$saleID', PagamentoValor = '$itemPreco', Observation = '$observation', DateModified = '$logDataTime' WHERE ID_event = '$eventID' AND ID_user = '$userID' ";
                $query = $conn->prepare($q);
                $query->execute();
            }

            

            echo json_encode($code[1]);
            //    echo $$checkoutUrl;
        } catch (PagSeguroServiceException $e) {

            $c = file_get_contents($pagseguroLogFile);
            $c .= "FALHA | " . $nome . " | " . $userEmail . " | R$" . $itemPreco . " | " . $itemTitulo . " ( ". $itemID ." ) | " . $logDataTime  . " | error: " . $e->getMessage() . "\n";
            file_put_contents($pagseguroLogFile, $c);

            die($e->getMessage());  
        }
    
    
}

?>