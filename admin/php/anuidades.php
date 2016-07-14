<?php
    header("Access-Control-Allow-Origin: *");
//    header("Content-Type: application/json; charset=UTF-8");
    header("Content-Type: application/json; charset=iso-8859-1");
    include("conn.php");
    include("config.php");


    // $q = "SELECT users.ID, users.Nome, users.Email, users.Status as userStatus, transactions.Status, transactions.transactionCode, transactions.SaleID FROM users LEFT JOIN transactions ON users.ID=transactions.UserID AND transactions.SaleID = '$_anoVigenteSaleID'";
    // $query = $conn->prepare($q);
    // $query->execute();
    // $result = $query->fetchAll(PDO::FETCH_OBJ);
    


    // foreach ($result as $user) {
    //     if($user->Status == 3){
    //         $q = "UPDATE `abrv_db`.`users` SET `Status` = '1' WHERE Email = '$user->Email' ";
    //         $query = $conn->prepare($q);
    //         $query->execute();
    //     };
    // }

    $q = "SELECT users.ID, users.Nome, users.Email, users.Status as userStatus, transactions.Status, transactions.transactionCode, transactions.SaleID ,transactions.Description FROM users LEFT JOIN transactions ON users.ID=transactions.UserID AND transactions.SaleID = '$_anoVigenteSaleID'";
    $query = $conn->prepare($q);
    $query->execute();
    $result = $query->fetchAll(PDO::FETCH_OBJ);
    
    echo json_encode($result);

    $conn = null;
?>