<?php
require_once("conn.php");
require(FPDF_PATH . 'fpdf.php');
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=iso-8859-1");

$data = json_decode(file_get_contents("php://input"));
$action = $data->action;

if($action == "GenerateCertificate"){
	$subs = $data->subs;

	$q = "SELECT tpl_path FROM certs_tpl WHERE event_id = '".$data->event_ID."' ";
	$result = $conn->query($q);
    $result = $result->fetch();
    $template = $result["tpl_path"];

	for ($i=0; $i < count($subs); $i++) { 
		$cert_inputs = array(
		'user_id' => $subs[$i]->userID,
		'event_id' => $data->event_ID,
		'cert_tpl' => $template,
		'nome' => $data->nome,
		'curso' => $data->curso,
		);
		try {
			$file = generateCertificate($cert_inputs["user_id"] , $cert_inputs["event_id"], $cert_inputs, $conn);
			$downloadPath = "download/certificados/".$file.".pdf";
			registerCertificate($cert_inputs["user_id"] , $cert_inputs["event_id"], $downloadPath, $conn);
		} catch (Exception $e) {
			echo $e;
		}

	}
}

if($action == "ToogleAvailability"){

	$user_id = $data->userID;
	$event_id = $data->eventID;
	$value = $data->valor;

	try {
		toogleAvailability($user_id, $event_id, $value, $conn);
	} catch (Exception $e) {
		echo $e;
		$q = "INSERT INTO `errors` (`id`, `type`, `message`, `agent`, `DateCreated`)
		VALUES (NULL, 'Certificado | toogle', '".$e."', '".$_SERVER['HTTP_USER_AGENT']."', '".$dateTime."')";
		$query = $conn->prepare($q);
		$query->execute();
	}
	
}

if($action == "PublishAll"){

	$event_id = $data->eventID;

	

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
	WHERE subs.ID_event = '".$event_id."' ";
	$query = $conn->prepare($q);
	$query->execute();
	$result = $query->fetchAll(PDO::FETCH_OBJ);
	$stuff;
	foreach ($result as $user ) {
		if($user->SubscriptionStatus == 1){

			$tempID = $user->userID;
			$stuff = $user;

			try {
				toogleAvailability($tempID, $event_id, 1, $conn);
			} catch (Exception $e) {
				echo $e;
				$q = "INSERT INTO `errors` (`id`, `type`, `message`, `agent`, `DateCreated`)
				VALUES (NULL, 'Certificado | toogle', '".$e."', '".$_SERVER['HTTP_USER_AGENT']."', '".$dateTime."')";
				$query = $conn->prepare($q);
				$query->execute();
			}
		}
	}
	echo json_encode("SUCCESS");
}


function generateCertificate($user_id, $event_id, $cert_data, $conn){
	$q = "SELECT * from
	(SELECT `event_user_rel`.`id`, `users`.`ID` as userID, `users`.`Nome`, `users`.`Email`, `users`.`Tipo`, `event_user_rel`.`Observation`, `event_user_rel`.`ID_event` as eventID, `event_user_rel`.`SubscriptionCode`, `event_user_rel`.`PagamentoValor`, `event_user_rel`.`Status`, `event_user_rel`.`SubscriptionStatus`
	FROM `users`
	INNER JOIN `event_user_rel`
	ON `users`.`ID` = `event_user_rel`.`ID_user`) as userSide
	INNER JOIN
	(SELECT ID as eventID, Name from events) as eventSide
	ON userSide.eventID = eventSide.eventID
	WHERE userID = '" . $user_id . "' AND eventSide.eventID = '". $event_id ."'";

	$query = $conn->prepare($q);
	$query->execute();
	$result = $query->fetch(PDO::FETCH_OBJ);

	$cert_data["nome"] =  mb_strtoupper($result->Nome, 'UTF-8');;
	$cert_data["curso"] = $result->Name;
	$hash = $event_id.$user_id.md5($user_id . $cert_data["nome"] . $event_id . $cert_data["nome"]);
	$cert_data["filename"] = $event_id."/".$hash;

	if (!file_exists("../../download/certificados/".$event_id)) {
		mkdir("../../download/certificados/".$event_id);
	}

	include("certificados/".$cert_data["cert_tpl"]);

	return $cert_data["filename"];
}

function generateCustomCertificate($cert_data){

	$cert_data["nome"] =  strtoupper($result->Nome);
	$cert_data["curso"] = $result->Name;
	$cert_data["output"] = "I";
	$hash = $event_id.$user_id.md5($user_id . $cert_data["nome"] . $event_id . $cert_data["nome"]);
	$cert_data["filename"] = $event_id."/".$hash;

	if (!file_exists("../../download/certificados/".$event_id)) {
		mkdir("../../download/certificados/".$event_id);
	}

	include("certificados/".$cert_data["cert_tpl"]);
}

function registerCertificate($user_id, $event_id, $file, $conn){

    date_default_timezone_set("America/Sao_Paulo");
    $dateTime = date("d/m/Y") . " " .date("H:i");

    $q = "SELECT id FROM cert_user_rel WHERE event_id = '".$event_id."' AND user_id = '".$user_id."' ";
	$result = $conn->query($q);
    $result = $result->fetchAll();
	if(count($result) > 0)
	{
		$q = "UPDATE `cert_user_rel` SET `path` = '".$file."', `DateModified` = '" . $dateTime . "' WHERE `event_id` = '".$event_id."' AND `user_id` = '".$user_id."' ";
		$query = $conn->prepare($q);
		$result = $query->execute();
	} else{
		$q = "INSERT INTO `cert_user_rel` (`id`, `event_id`, `user_id`, `path`, `public`, `DateCreated`)
		VALUES (NULL, '" . $event_id . "', '" . $user_id . "', '" . $file. "', 0, '" . $dateTime. "')";
		$query = $conn->prepare($q);
		$query->execute();
	}
	
}

function toogleAvailability($user_id, $event_id, $value, $conn){

    date_default_timezone_set("America/Sao_Paulo");
    $dateTime = date("d/m/Y") . " " .date("H:i");
	try {
		$q = "UPDATE `cert_user_rel` SET `public` = '".$value."', `DateModified` = '" . $dateTime. "' WHERE `event_id` = '".$event_id."' AND `user_id` = '".$user_id."' ";
		$query = $conn->prepare($q);
		$query->execute();
	} catch (Exception $e) {
		echo $e;
		$q = "INSERT INTO `errors` (`id`, `type`, `message`, `agent`, `DateCreated`)
		VALUES (NULL, 'Certificado | toogle', '".$e."', '".$_SERVER['HTTP_USER_AGENT']."', '".$dateTime."')";
		$query = $conn->prepare($q);
		$query->execute();
	}
	
}


