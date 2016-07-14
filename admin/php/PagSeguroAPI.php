<?php
//header('Access-Control-Allow-Origin: *');
include("../PagSeguroLibrary/PagSeguroLibrary.php");
include("conn.php"); 
$data = json_decode(file_get_contents("php://input"));
$action = $data->action;
$email = $data->email;
$senha = $data->senha;
$nome = $data->nome;
$endereco = $data->endereco;
$end_num = $data->end_num;
$end_complemento = $data->end_complement;
$cep = $data->cep;
$bairro = $data->bairro;
$cidade = $data->cidade;
$estado = $data->estado;
$tel_ddd = $data->tel_ddd;
$telefone = $data->telefone;
$cel = $data->cel;
$cel_ddd = $data->cel_ddd;
$crmv = $data->crmv;
$especialidade = $data->especialidade;
$cpf = $data->cpf;
$token = $data->token;

if($action == "Request"){
    $userInfo = $conn->query("SELECT ID, Email FROM users WHERE Token=$token"); // TOKEN SEM ASPAS NO QUERY!!!
    $userInfo = $userInfo->fetch(PDO::FETCH_OBJ);
    $userID = $userInfo->ID;
        $paymentRequest = new PagSeguroPaymentRequest();  
        $paymentRequest->addItem('0001', 'Palestra Raio-X', 1, 2500.00);
        $sedexCode = PagSeguroShippingType::getCodeByType('SEDEX');  
        $paymentRequest->setShippingType($sedexCode);  
        $paymentRequest->setShippingAddress(  
          $cep,  
          $endereco,  
          $end_num,  
          $end_complemento,  
          $bairro,  
          $cidade,  
          $edtado,  
          'BRA'  
        );
        $paymentRequest->setSender(
          $nome,  
          'c81082519749641330943@sandbox.pagseguro.com.br',  
          $tel_ddd,  
          $telefone,  
          'CPF',  
          $cpf  
        ); 
        $paymentRequest->setCurrency("BRL");
        // Referenciando a transação do PagSeguro em seu sistema  
        $paymentRequest->setReference($userID);
        // URL para onde o comprador será redirecionado (GET) após o fluxo de pagamento  
        $paymentRequest->setRedirectUrl("https://sandbox.pagseguro.uol.com.br/v2/checkout/payment.html");
        // URL para onde serão enviadas notificações (POST) indicando alterações no status da transação  
        $paymentRequest->addParameter('notificationURL', 'http://abrv.com.br/www/php/notification.php'); 
        try {
            //    $credentials = PagSeguroConfig::getAccountCredentials(); // getApplicationCredentials()
            $credentials = new PagSeguroAccountCredentials(
                $pagSeguroEmail,
                $pagSeguroToken
            );
            $checkoutUrl = $paymentRequest->register($credentials);
            $code = explode("=", $checkoutUrl);
            echo json_encode($code[1]);
            //    echo $$checkoutUrl;
        } catch (PagSeguroServiceException $e) {
            die($e->getMessage());  
        }
    
    
}
?>