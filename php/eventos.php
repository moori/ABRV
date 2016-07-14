<?php
header("Access-Control-Allow-Origin: *");
//header("Content-Type: application/json; charset=UTF-8");
header("Content-Type: application/json; charset=iso-8859-1");
include("conn.php");

$data = json_decode(file_get_contents("php://input"));
$action = $data->action;


if($action == "LoadEventsList"){
	$q = "SELECT * FROM events WHERE Type = 'Evento' AND Active = 1";
	$query = $conn->prepare($q);
	$query->execute();
	$result = $query->fetchAll(PDO::FETCH_OBJ);
	$conn = null;
	echo json_encode($result);
}

if($action == "LoadEvento"){
	$id = $data->id;

	$q = "SELECT * FROM events WHERE Type = 'Evento' AND Active = 1 AND ID = '$id' ";
	$query = $conn->prepare($q);
	$query->execute();
	$result = $query->fetchAll(PDO::FETCH_OBJ);
	$conn = null;
	echo json_encode($result);
}

if($action == "LoadSales"){
	$id = $data->id;

    date_default_timezone_set("America/Sao_Paulo");
    // $currentaDate =  date("Y-m-d") . "|" . date("h:i:s"); 
    $currentaDate =  date("Y-m-d"); 

	// $q = "SELECT * FROM sales WHERE EventID = '$id' AND DataInicio <= '$currentaDate' AND DataFim >=  '$currentaDate' ";
	$q = "SELECT * FROM sales WHERE EventID = '$id' AND Active = 1 AND '$currentaDate' BETWEEN DataInicio AND DataFim ";
	$query = $conn->prepare($q);
	$query->execute();
	$result = $query->fetchAll(PDO::FETCH_OBJ);
	$conn = null;
	echo json_encode($result);
}

if($action == "LoadAllSales"){
	$id = $data->id;

	$q = "SELECT * FROM sales WHERE EventID = '$id' AND Active = 1 ORDER BY DataInicio ";
	$query = $conn->prepare($q);
	$query->execute();
	$result = $query->fetchAll(PDO::FETCH_OBJ);
	$conn = null;
	echo json_encode($result);
}

?>