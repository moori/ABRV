<?php
header("access-control-allow-origin: https://sandbox.pagseguro.uol.com.br");
include("../PagSeguroLibrary/PagSeguroLibrary.php");
include("conn.php"); 

//$code = '01A82C55A3EBA3EB73BEE4146F9ADBD1D7E6';
$code = 'F5966AFBADB7ADB7441334D4CFAD787B4E25';
//$code = $_POST['notificationCode'];

//echo $_POST['notificationCode'];
//if(isset($_POST['notificationType']) && $_POST['notificationType'] == 'transaction'){
    $url = 'https://ws.sandbox.pagseguro.uol.com.br/v3/transactions/notifications/' . $code . '?email=' . $pagSeguroEmail . '&token=' . $pagSeguroToken;
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $transactionCurl = curl_exec($curl);
    $transaction = simplexml_load_string($transactionCurl);
    curl_close($curl);
    
    if($transaction == 'Unauthorized'){
        // enviar email para administração 
        exit;
    }
    
var_dump($url);

    $transactionCode = $transaction->code;
    $status = $transaction->status;
    $reference = $transaction->reference;
    $decription = $transaction->items->item->description;
    $itemID = $transaction->items->item->id;
    $senderEmail = $transaction->sender->email;
//var_dump($transaction);

//    echo $transactionCode;
//    echo $status;
//    echo $reference;
//    echo $decription;
//    echo $senderEmail;
        
//    $selectTransaction = $conn->query("SELECT UserID FROM transactions WHERE UserID='$reference' AND ItemID=$ItemID ");
//    $selectResult = $selectTransaction->fetchAll(PDO::FETCH_OBJ);

//    $q = "SELECT UserID FROM transactions WHERE UserID='$reference' AND ItemID=$ItemID ";
//    $query = $conn->prepare($q);
//    $execute = $query->execute(); 
//    $selectResult = $execute->fetchAll(PDO::FETCH_ASSOC);

    // $selectTransaction = $conn->query("SELECT * FROM transactions WHERE UserID='$reference' AND ItemID='$itemID' ");
    // $selectTransaction = $selectTransaction->fetchAll();
//    echo count($selectTransaction);
//    echo $reference;
//    echo $itemID;


	// if ( count($selectTransaction) > 0){
 //        $q = "UPDATE `abrv_db`.`transactions` SET `Status` = '$status' WHERE `transactions`.`UserID` = '$reference' AND `transactions`.`ItemID`=$ItemID";
 //        $query = $conn->prepare($q);
 //        $execute = $query->execute(); 
 //        echo "Status atualizado";
	// }else{
 //        $q = "INSERT INTO `abrv_db`.`transactions` (`ID`, `UserID`, `SenderEmail`, `TransactionCode`, `ItemID`, `Description`, `Status`) VALUES (NULL, '$reference',  '$senderEmail', '$transactionCode', '$itemID', '$decription', '$status')";
 //        $query = $conn->prepare($q);
 //        $execute = $query->execute();
 //        echo "Notificação enviada";
 //    }


//}



?>