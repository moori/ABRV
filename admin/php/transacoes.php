<?php
    header("Access-Control-Allow-Origin: *");
//    header("Content-Type: application/json; charset=UTF-8");
    header("Content-Type: application/json; charset=iso-8859-1");
    include("conn.php");
    include("PagSeguroService.php");
    $data = json_decode(file_get_contents("php://input"));
    $action = $data->action;

    if($action == "LoadTransactions"){
      $q = "SELECT transactions.ID, users.Nome, transactions.UserID, transactions.SenderEmail, transactions.TransactionCode, transactions.Date, transactions.SaleID, transactions.Description, transactions.Status, transactions.Amount FROM transactions INNER JOIN users WHERE transactions.UserID = users.ID ";
      $query = $conn->prepare($q);
      $query->execute();
      $result = $query->fetchAll(PDO::FETCH_OBJ);
      $conn = null;

      echo json_encode($result);
    }

    if($action == "GetTransactionURL"){
      $transCode = $data->transCode;
    	echo json_encode(PagSeguroService::GetTransactionURLByTransactionCode($transCode));
    }

?>
