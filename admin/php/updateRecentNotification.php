<?php
header("access-control-allow-origin: https://pagseguro.uol.com.br");
include("../PagSeguroLibrary/PagSeguroLibrary.php");
include("conn.php");
include("../../php/mailAPI.php");

// $code = 'F5966AFBADB7ADB7441334D4CFAD787B4E25';
//$code = $_POST['notificationCode'];
date_default_timezone_set("America/Sao_Paulo");
$d=strtotime("-1 days");   
$initialDate = date("Y-m-d", $d) . "T" .date("H:i", $d);
$primordial=date_create("2016-03-14");
// if($initialDate < $primordial){
//     $initialDate = "2016-03-14T01:07:30.000-03:00";
// };
$finalDate = date("Y-m-d") . "T" .date("H:i");


// echo "The time is " . date("Y-m-d") . "T" .date("h:m");
// $d=strtotime("-30 days");
// echo "The time is " . date("Y-m-d", $d) . "T" .date("h:m", $d);

    $url = 'https://ws.pagseguro.uol.com.br/v2/transactions?initialDate=' . $initialDate . '&finalDate=' . $finalDate . '&email=' . $pagSeguroEmail . '&token=' . $pagSeguroToken;
    // echo $url;
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $transactionCurl = curl_exec($curl);
    $transactionList = simplexml_load_string($transactionCurl);
    curl_close($curl);
    
    if($transactionList == 'Unauthorized'){
        // enviar email para administração 
        exit;
    }

    $transactions = $transactionList->transactions->transaction;
    if (is_array($transactions) || is_object($transactions))
    {
        foreach ($transactions as $transaction) {

            $transactionCode = $transaction->code;

            $url = 'https://ws.pagseguro.uol.com.br/v3/transactions/' . $transactionCode . '?email=' . $pagSeguroEmail . '&token=' . $pagSeguroToken;
            
            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $transactionCurl = curl_exec($curl);
            $transaction = simplexml_load_string($transactionCurl);
            curl_close($curl);

            
            $status = $transaction->status;
            $reference = $transaction->reference;
            $date = $transaction->date;
            $decription = $transaction->items->item->description;
            $SaleID = $transaction->items->item->id;
            $amount = $transaction->items->item->amount;
            $senderEmail = $transaction->sender->email;
            $dateExp = explode("T", $date);
            $date = $dateExp[0];
            $time = $dateExp[1];

            $selectTransaction = $conn->query("SELECT * FROM transactions WHERE TransactionCode = '$transactionCode' ");
            $selectTransaction = $selectTransaction->fetchAll();
            echo "Codigo: " . $transactionCode . "<br>";
            echo "Data: " . $date . "<br>";
            echo "Novo Status: " .$status . "<br>";
            echo "Item ID: " .$SaleID . "<br>";
            echo "User ID: " .$reference . "<br>";
            echo "User Pagseguro Email: " .$senderEmail . "<br>";




            if ( count($selectTransaction) > 0){
                $q = "UPDATE `abrv_db`.`transactions` SET `Status` = '$status', `Amount` = '$amount', `Date` = '$date', `Time` = '$time'  WHERE  TransactionCode = '$transactionCode'";
                $query = $conn->prepare($q);
                $execute = $query->execute();
                $updateEcho = "Status atualizado";
                echo $updateEcho;

                // Update Curso
                $saleData = $conn->query("SELECT * FROM sales WHERE ID = '$SaleID'");
                $saleData = $saleData->fetch();

                $eventID = $saleData["EventID"];
                if($status == 3 || $status == 4){
                    $subState = 1;
                }else{
                    $subState = 0;
                }

                $q = "UPDATE `abrv_db`.`event_user_rel` SET `SubscriptionStatus` = '$subState', `Status` = '$status' WHERE ID_event = '$eventID' AND ID_user = '$reference' ";
                $query = $conn->prepare($q);
                $query->execute();
                // end update curso

                $mailConfirmation = $conn->query("SELECT * FROM transactions WHERE TransactionCode = '$transactionCode' AND ( `Status` = 3 OR `Status` = 4) AND Notificated = 0");
                $mailConfirmation = $mailConfirmation->fetchAll();

                if ( count($mailConfirmation) > 0){

                    $saleData = $conn->query("SELECT * FROM sales WHERE ID = '$SaleID'");
                    $saleData = $saleData->fetch();

                    $eventID = $saleData["EventID"];

                    $eventData = $conn->query("SELECT * FROM events WHERE ID = '$eventID'");
                    $eventData = $eventData->fetch();

                    $userInfo = $conn->query("SELECT * FROM users WHERE ID = '$reference'");
                    $userInfo = $userInfo->fetch();


                    $MT_nome = $userInfo["Nome"];
                    $MT_code = $transactionCode;
                    $MT_valor = $amount;
                    date_default_timezone_set("America/Sao_Paulo");
                    $MT_dataHora = date("d/m/Y") . " " .date("H:i");
                    $userEmail = $userInfo["Email"];


                        if($eventData["Type"] == "Anuidade"){
                            $q = "UPDATE `abrv_db`.`users` SET `Status` = '1', `Tipo` = '1' WHERE ID = '$reference' ";
                            $query = $conn->prepare($q);
                            $query->execute();

                            $htmlInsert = file_get_contents('../../templates/mail/confirmacaoAnuidade.html');
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

                        }

                        if($eventData["Type"] == "Evento"){
                            $q = "UPDATE `abrv_db`.`event_user_rel` SET `SubscriptionStatus` = '1', `Status` = '$status' WHERE ID_event = '$eventID' AND ID_user = '$reference' ";
                            $query = $conn->prepare($q);
                            $query->execute();

                            $subscription = $conn->query("SELECT * FROM event_user_rel WHERE ID_event = '$eventID' AND ID_user = '$reference' ");
                            $subscription = $subscription->fetch();

                            $MT_inscricao = $subscription["SubscriptionCode"];
                            $MT_evento = $eventData["Name"];

                            //html mail template de curso
                            $htmlInsert = file_get_contents('../../templates/mail/confirmacaoCurso.html');
                            $htmlInsert = str_replace("##nome##", $MT_nome, $htmlInsert);
                            $htmlInsert = str_replace("##code##", $MT_code, $htmlInsert);
                            $htmlInsert = str_replace("##valor##", $MT_valor, $htmlInsert);
                            $htmlInsert = str_replace("##inscricao##", $MT_inscricao, $htmlInsert);
                            $htmlInsert = str_replace("##evento##", $MT_evento, $htmlInsert);
                            $htmlInsert = str_replace("##dataHora##", $MT_dataHora, $htmlInsert);

                            $subjectText = "ABRV | " . $MT_evento;

                            $data = array(
                                'email'  => "$userEmail",
                                'subject'   => "$subjectText",
                                'htmlInsert'   => "$htmlInsert",
                                'category' => "inscricao_evento"
                                );

                            sendMail($data);
                        }

                    

                    
                        $q = "UPDATE `abrv_db`.`transactions` SET Notificated = 3 WHERE TransactionCode = '$transactionCode'";
                        $query = $conn->prepare($q);
                        $query->execute();

                        
                        echo "Notificação por email Enviada para $userEmail Codigo: $MT_code";
                    }




            } else{
                $q = "INSERT INTO `abrv_db`.`transactions` (`ID`, `UserID`, `SenderEmail`, `TransactionCode`, `SaleID`, `Description`, `Status`, `Date`, `Time`, `Amount`) VALUES (NULL, '$reference',  '$senderEmail', '$transactionCode', '$SaleID', '$decription', '$status', '$date', '$time', '$amount')";
                $query = $conn->prepare($q);
                $execute = $query->execute();
                $updateEcho = "Notificação enviada";
                echo $updateEcho;
                
            }
            echo "<br><br>";

            $log = date("Y-m-d") . " " . date("h:i:s") . " | " . "Code: " . $transactionCode . " | " . $updateEcho . ": " . $status . "\n";

            // $file = 'updateNotificationLog.txt';
            // // Open the file to get existing content
            // $current = file_get_contents($file);
            // // Append a new person to the file
            // $current .= $log;
            // // Write the contents back to the file
            // file_put_contents($file, $current);

            // $dateFile = 'dateLog.txt';
            // // Open the file to get existing content
            // $c = file_get_contents($dateFile);
            // // Append a new person to the file
            // $c .= "Date: " . $date . " | Amount: " . $amount . "\n";
            // // Write the contents back to the file
            // file_put_contents($dateFile, $c);
        }
    }


?>