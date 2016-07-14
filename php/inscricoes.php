<?php
header("Access-Control-Allow-Origin: *");
//header("Content-Type: application/json; charset=UTF-8");
header("Content-Type: application/json; charset=iso-8859-1");
include("conn.php");

$data = json_decode(file_get_contents("php://input"));
$action = $data->action;
$userID = $data->userID;


if($action == "LoadUserSubscriptions"){
	$q = "SELECT `events`.`Name`, `event_user_rel`.`SubscriptionCode`, `event_user_rel`.`PagamentoValor`, `event_user_rel`.`Status`,`event_user_rel`.`SubscriptionStatus`
	FROM `events`
	INNER JOIN `event_user_rel`
	ON `events`.`ID` = `event_user_rel`.`ID_event` WHERE `event_user_rel`.`ID_user` = '$userID' ";
	$query = $conn->prepare($q);
	$query->execute();
	$result = $query->fetchAll(PDO::FETCH_OBJ);
	$conn = null;
	echo json_encode($result);
}

?>