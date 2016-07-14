<?php

include("conn.php");


$data = json_decode(file_get_contents("php://input"));
$email = $data->Email;
$coirma = $data->coirmas;
$faculdade = $data->faculdade;
$cpf = $data->CPF;
$grad = $data->grad;
$tipoFavorecido = $data->TipoFavorecido;
$token = $data->Token;
$token = trim($token, '"');

date_default_timezone_set("America/Sao_Paulo");
$dateTime = date("Y-m-d") . "|" . date("h:i:s");    


$userInfo = $conn->query("SELECT * FROM users WHERE Token = '$token' ");
$userInfo = $userInfo->fetchAll();
echo count($userInfo);
if (count($userInfo) == 1){
    // $novoTipo = $data->Tipo;

    // if($novoTipo == 1 || $novoTipo == 2 || $novoTipo == 3 || $novoTipo == 4 || $novoTipo == 5 || $novoTipo == 6 || $novoTipo == 7){

    // }else if($grad === true){
    //     $novoTipo = 8;
    // }else if(strlen($coirma)>2){
    //     $novoTipo = 9;
    // }
    $coirmaUpdate = $coirma[0] . "," . $coirma[1];

    $q = "UPDATE users SET CRMV = '$crmv', Faculdade = '$faculdade', Coirma = '$coirmaUpdate', DateModified = '$dateTime', Tipo = '$tipoFavorecido' WHERE Token = '$token'";
    $query = $conn->prepare($q);
    $query->execute();
    echo json_encode("Atualizado");
}else{
    echo "ERROR";
}





?>