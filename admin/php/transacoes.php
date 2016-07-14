<?php
    header("Access-Control-Allow-Origin: *");
//    header("Content-Type: application/json; charset=UTF-8");
    header("Content-Type: application/json; charset=iso-8859-1");
    include("conn.php");
    // $q = "SELECT `transactions`.`ID`,`users`.`Nome`, `transactions`.`TransactionCode`, `transactions`.`Date`, `transactions`.`SaleID`, `transactions`.`Description`, `transactions`.`Amount` FROM transactions INNER JOIN users WHERE `transaction`.`UserID` = `users`.`ID` ";
    $q = "SELECT transactions.ID, users.Nome, transactions.UserID, transactions.SenderEmail, transactions.TransactionCode, transactions.Date, transactions.SaleID, transactions.Description, transactions.Status, transactions.Amount FROM transactions INNER JOIN users WHERE transactions.UserID = users.ID ";
    $query = $conn->prepare($q);
    $query->execute();
    $result = $query->fetchAll(PDO::FETCH_OBJ);
    $conn = null;

    echo json_encode($result);
?>