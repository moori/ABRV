<?php 

// ini_set('display_startup_errors',1);
// ini_set('display_errors',1);
// error_reporting(-1);

include("conn.php");

$data = json_decode(file_get_contents("php://input"));
$authCode = $data->code;
// $authCode = $_GET["authCode"];
$explode = explode("&", $authCode);
$email = $explode[1];


$authUser = $conn->query("SELECT Ativo, Auth, Tipo, Email FROM users WHERE Auth='$authCode'");
$authUser = $authUser->fetchAll();

date_default_timezone_set("America/Sao_Paulo");
$dateTime = date("Y-m-d") . "|" . date("h:i:s");
// echo (count($authUser));

if( count($authUser)  == 1 ){
	$hash = md5(uniqid());
	$q = "UPDATE users SET Ativo = 1, Auth = '$hash', DateModified = '$dateTime' WHERE Auth='$authCode'";
    $query = $conn->prepare($q);
    $query->execute();
    $response = $email . "&" . $hash;

    // $q = "UPDATE users SET Status = 1 WHERE Tipo = 2 OR Tipo = 3";
    // $query = $conn->prepare($q);
    // $query->execute();

    echo($response);
}else if($authUser->Email == $email){
    echo("SUCESSO");
}else{
    echo("ERROR");
}
?>