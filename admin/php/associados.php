<?php
    header("Access-Control-Allow-Origin: *");
//    header("Content-Type: application/json; charset=UTF-8");
    header("Content-Type: application/json; charset=iso-8859-1");
    include("conn.php");
    $data = json_decode(file_get_contents("php://input"));
    $action = $data->action;

    if($action == "LoadList"){
        // $q = "SELECT users.ID, users.Nome, users.Email, users.CRMV, transactions.Status FROM users LEFT JOIN transactions ON users.ID=transactions.UserID";
        $q = "SELECT users.ID, users.Nome, users.Email, users.CRMV, users.Status, users.Tipo, users.CPF FROM users";
        $query = $conn->prepare($q);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_OBJ);
        $conn = null;

        echo json_encode($result);
    }

    if($action == "DeleteUser"){

        $userID = $data->userID;

        try{
            $q = "DELETE FROM users WHERE ID = '$userID'";
            $query = $conn->prepare($q);
            $query->execute();
            $conn = null;
            echo json_encode("SUCCESS");
        }catch(Exception $e){
            echo json_encode($e->errorMessage());
        }

    }
?>