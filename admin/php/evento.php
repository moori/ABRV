<?php
header("Access-Control-Allow-Origin: *");
//header("Content-Type: application/json; charset=UTF-8");
header("Content-Type: application/json; charset=iso-8859-1");
include("conn.php");

$data = json_decode(file_get_contents("php://input"));
$action = $data->action;
$eventID = $data->eventID;


if($action == "LoadEventsList"){
	// Inner joint subs
	$q = "SELECT * FROM events WHERE Type = 'Evento'";
	$query = $conn->prepare($q);
	$query->execute();
	$result = $query->fetchAll(PDO::FETCH_OBJ);
	$conn = null;
	echo json_encode($result);
}

if($action == "LoadEvento"){
	$id = $data->id;

	$q = "SELECT * FROM events WHERE Type = 'Evento' AND ID = '$id' ";
	$query = $conn->prepare($q);
	$query->execute();
	$result = $query->fetchAll(PDO::FETCH_OBJ);
	$conn = null;
	echo json_encode($result[0]);
}

if($action == "LoadSales"){
	$id = $data->id;


    date_default_timezone_set("America/Sao_Paulo");
    $currentaDate = date("Y-m-d");

	$q = "SELECT * FROM sales WHERE EventID = '$id' AND DataInicio <= '$currentaDate' AND DataFim >=  '$currentaDate' ";
	$query = $conn->prepare($q);
	$query->execute();
	$result = $query->fetchAll(PDO::FETCH_OBJ);
	$conn = null;
	echo json_encode($result);
}

if($action == "LoadUserSubscriptions"){
	$eventID = $data->eventID;
	// $q = "SELECT `event_user_rel`.`id`, `users`.`ID`, `users`.`Nome`, `users`.`Email`, `users`.`Tipo`, `event_user_rel`.`Observation`, `event_user_rel`.`SubscriptionCode`, `event_user_rel`.`PagamentoValor`, `event_user_rel`.`Status`, `event_user_rel`.`SubscriptionStatus`
	// FROM `users`
	// INNER JOIN `event_user_rel`
	// ON `users`.`ID` = `event_user_rel`.`ID_user`
	// WHERE `event_user_rel`.`ID_event` = '$eventID'";
	$q = "SELECT * FROM
	(SELECT 
	`event_user_rel`.`id`, 
	`users`.`ID` userID, 
	`users`.`Nome`, 
	`users`.`Email`, 
	`users`.`Tipo`, 
	`event_user_rel`.`Observation`, 
	`event_user_rel`.`SubscriptionCode`, 
	`event_user_rel`.`PagamentoValor`, 
	`event_user_rel`.`Status`, 
	`event_user_rel`.`ID_event`, 
	`event_user_rel`.`SubscriptionStatus`
	FROM `users`
	INNER JOIN `event_user_rel`
	ON `users`.`ID` = `event_user_rel`.`ID_user`) as subs
	LEFT JOIN
	(SELECT 
	user_id, 
	event_id, path, 
	`public` 
	FROM cert_user_rel ) as certs
	ON subs.userID = certs.user_ID AND subs.ID_event = certs.event_id
	WHERE subs.ID_event = '".$eventID."' ";



	$query = $conn->prepare($q);
	$query->execute();
	$result = $query->fetchAll(PDO::FETCH_OBJ);
	$conn = null;
	echo json_encode($result);
}

if($action == "ForceApproval"){
	$userID = $data->userID;
	$eventID = $data->eventID;
	$q = "UPDATE event_user_rel SET `SubscriptionStatus` = 1
	WHERE ID_event = '$eventID' AND ID_user = $userID";
	$query = $conn->prepare($q);
	$query->execute();
	$conn = null;
	// echo "Aprovado";
	// echo json_encode($result);
}

if($action == "ForceCancel"){
	// $userID = $data->userID;
	// $eventID = $data->eventID;
	$subID = $data->subID;
	$q = "DELETE FROM event_user_rel WHERE id = $subID";
	$query = $conn->prepare($q);
	$query->execute();
	$conn = null;
	// echo "Cancelado";
	// echo json_encode($result);
}

if($action == "ForceSubscription"){
	$userID = $data->userID;
	$eventID = $data->eventID;
	$valor = $data->valor;
	$subcriptionHash = $eventID . $userID . substr(md5($eventID+111), 0, 8) . substr(md5($userID+222), 0, 8);
    $currentDate = date("Y-m-d") . "|" . date("h:i:s"); 
	$q = "INSERT INTO `abrv_db`.`event_user_rel` (`id`, `ID_event`, `ID_user`, `ID_sale`, `PagamentoValor`, `Status`, `SubscriptionCode`, `SubscriptionStatus`,  `Observation`, `DateSubscription`, `DateModified`) VALUES (NULL, '$eventID',  '$userID', null, '$valor', 0, '$subcriptionHash', 1,'Cadastrado por Admin', '$currentDate', '$currentDate')";
    $query = $conn->prepare($q);
    $execute = $query->execute();
	$conn = null;
	// echo "Inscrito";
	// echo json_encode($result);
}



?>