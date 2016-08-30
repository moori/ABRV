<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=iso-8859-1");
include("conn.php");

$data = json_decode(file_get_contents("php://input"));
$code = $data->code;


header("Location: https://ws.pagseguro.uol.com.br/v2/transactions/".$code."?email=".ENV_PAGSEGURO_EMAIL."&token=".ENV_PAGSEGURO_TOKEN_PRODUCTION);
exit();

?>