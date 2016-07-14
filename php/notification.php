<?php

header("access-control-allow-origin: https://pagseguro.uol.com.br");

include("../PagSeguroLibrary/PagSeguroLibrary.php");
include("conn.php");
include("config.php");
include("mailAPI.php");

$code = $_POST['notificationCode'];

// if(isset($_POST['notificationType']) && $_POST['notificationType'] == 'transaction'){
    $url = 'https://ws.pagseguro.uol.com.br/v3/transactions/notifications/' . $code . '?email=' . $pagSeguroEmail . '&token=' . $pagSeguroToken;
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $transactionCurl = curl_exec($curl);
    $transaction = simplexml_load_string($transactionCurl);
    curl_close($curl);
    
    if($transaction == 'Unauthorized'){
        $headers .= 'From: <admin@abrv.org.br>' . "\r\n";
        date_default_timezone_set("America/Sao_Paulo");
        $msg = "A notificação de código:" . $code . " resultou em um erro de consulta em " . date("d/m/Y") . " " .date("H:i");
        mail($adminEmail,"notification",$msg, $headers);
        exit;
    }
    
    $transactionCode = $transaction->code;
    $status = $transaction->status;
    $reference = $transaction->reference;
    $decription = $transaction->items->item->description;
    $saleID = $transaction->items->item->id;
    $date = $transaction->date;
    $dateExp = explode("T", $date);
    $date = $dateExp[0];
    $time = $dateExp[1];
    $amount = $transaction->items->item->amount;
    $senderEmail = $transaction->sender->email;

    $q = "SELECT * FROM transactions WHERE TransactionCode = '$transactionCode' ";
    $query = $conn->prepare($q);
    $query->execute();
    $selectTransaction = $query->fetchAll(PDO::FETCH_OBJ);

    if($saleID == $_anoVigenteSaleID){
        $isAnuidade=true;
    }



	if ( count($selectTransaction) > 0){
        $q = "UPDATE `abrv_db`.`transactions` SET `Status` = '$status', `Date` = '$date', `Time` = '$time', `Amount` = '$amount' WHERE TransactionCode = '$transactionCode' ";
        $query = $conn->prepare($q);
        $execute = $query->execute(); 
        switch($status){
            case 1:
                $statusString = "Aguardando Pagamento";
                break;
            case 2:
                $statusString = "Em Análise";
                break;
            case 3:
                $statusString = "Paga";
                break;
            case 4:
                $statusString = "Em Disputa";
                break;
            case 5:
                $statusString = "Devolvida";
                break;
            case 6:
                $statusString = "Cancelada";
                break;
            case 7:
                $statusString = "Chargeback debitado";
                break;
            case 8:
                $statusString = "Em Contestação";
                break;
        }

        if($isAnuidade){
            if($status == 3){

                $userInfo = $conn->query("SELECT * FROM users WHERE ID = '$reference'");
                $userInfo = $userInfo->fetch();
                $MT_nome = $userInfo["Nome"];
                $MT_code = $transactionCode;
                $MT_valor = $amount;
                date_default_timezone_set("America/Sao_Paulo");
                $MT_dataHora = date("d/m/Y") . " " .date("H:i");
                $userEmail = $userInfo["Email"];
                echo $reference;

                $q = "UPDATE `abrv_db`.`users` SET `Status` = '1', `Tipo` = '1' WHERE ID = '$reference' ";
                $query = $conn->prepare($q);
                $query->execute();

                

                $htmlInsert = file_get_contents('../templates/mail/confirmacaoAnuidade.html');
                $htmlInsert = str_replace("##nome##", $MT_nome, $htmlInsert);
                $htmlInsert = str_replace("##code##", $MT_code, $htmlInsert);
                $htmlInsert = str_replace("##valor##", $MT_valor, $htmlInsert);
                $htmlInsert = str_replace("##dataHora##", $MT_dataHora, $htmlInsert);

                $data = array(
                    'email'  => "$userEmail",
                    'subject'   => "ABRV | Anuidade 2016",
                    'htmlInsert'   => "$htmlInsert",
                    'category' => "confirmacao_Anuidade"
                    );

                sendMail($data);
            }else{
                $q = "UPDATE `abrv_db`.`users` SET `Status` = '0' WHERE ID = '$reference' ";
                $query = $conn->prepare($q);
                $query->execute();
            }
        };
        
        echo "Status atualizado: '$statusString' em UserID:'$reference' para o item de ID:'$saleID'";
	}else{

        // if($isAnuidade){ //Provavelmente desnecessário fora do sandbox
        //     if($status == 3){

        //         $userInfo = $conn->query("SELECT * FROM users WHERE ID = '$reference'");
        //         $userInfo = $userInfo->fetch();
        //         $MT_nome = $userInfo["Nome"];
        //         $MT_code = $transactionCode;
        //         date_default_timezone_set("America/Sao_Paulo");
        //         $MT_dataHora = date("d/m/Y") . " " .date("H:i");
        //         $userEmail = $userInfo["Email"];
        //         echo $reference;

        //         $q = "UPDATE `abrv_db`.`users` SET `Status` = '1', `Tipo` = '1' WHERE ID = '$reference' ";
        //         $query = $conn->prepare($q);
        //         $query->execute();

                

        //         $htmlInsert = file_get_contents('../templates/mail/confirmacaoAnuidade.html');
        //         $htmlInsert = str_replace("##nome##", $MT_nome, $htmlInsert);
        //         $htmlInsert = str_replace("##code##", $MT_code, $htmlInsert);
        //         $htmlInsert = str_replace("##dataHora##", $MT_dataHora, $htmlInsert);

        //         $data = array(
        //             'email'  => "$userEmail",
        //             'subject'   => "ABRV | Anuidade 2016",
        //             'htmlInsert'   => "$htmlInsert",
        //             'category' => "confirmacao_Anuidade",
        //             );

        //         sendMail($data);
        //     }
        // };

        $q = "INSERT INTO transactions (`ID`, `UserID`, `SenderEmail`, `TransactionCode`, `SaleID`, `Description`, `Status`, `Date`, `Time`, `Amount`) VALUES (NULL, '$reference',  '$senderEmail', '$transactionCode', '$saleID', '$decription', '$status', '$date', '$time','$amount')";
        $query = $conn->prepare($q);
        $execute = $query->execute();

        echo "Notificação enviada: Status: '$status' em UserID:'$reference' para a oferta de ID:'$saleID' codigo: $transactionCode date: '$date' ";
    }


// }



?>