<?php
header("Content-Type: application/json; charset=iso-8859-1");
include("conn.php");
$data = json_decode(file_get_contents("php://input"));
$action = $data->action;

if($action == "GetAnoVigente"){
    $q = "SELECT Valor, Descricao FROM config WHERE Parametro='AnoVigente' ";
    $query = $conn->prepare($q);
    $query->execute();
    $result = $query->fetchAll(PDO::FETCH_OBJ);
    $conn = null;
    echo json_encode($result);
}

if($action == "GetAdmins"){
    $q = "SELECT Valor, Descricao FROM config WHERE Parametro='adminEmail' ";
    $query = $conn->prepare($q);
    $query->execute();
    $result = $query->fetchAll(PDO::FETCH_OBJ);
    $conn = null;
    echo json_encode($result);
}




    
?>