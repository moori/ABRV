<?php
include("conn.php");
include("mailAPI.php");
$data = json_decode(file_get_contents("php://input"));
$action = $data->action;
$email = $data->email;


if($action == "sendEmail"){

	$authcode = uniqid() . "&" . $email . "&" . md5(uniqid());

	date_default_timezone_set("America/Sao_Paulo");
	$dateTime = date("Y-m-d") . "|" . date("h:i:s");

	$q = "INSERT INTO `abrv_db`.`newsletter` (`ID`, `Email`, `Auth`, `Ativo`, `DateCreate`) VALUES (NULL, '$email', '$authcode', 0, '$dateTime')";
    $query = $conn->prepare($q);
    $execute = $query->execute();

	$MT_authURL = "http://www.abrv.org.br/#/newsletterActivation/" . $authcode;

	$htmlInsert = file_get_contents('../templates/mail/newsletterActivationEmail.html');
	$htmlInsert = str_replace("##authURL##", $MT_authURL, $htmlInsert);


	$mailData = array(
		'email'  => "$email",
	    'subject'   => "Confirme sua inscrição na ABRV",
	    'htmlInsert'   => "$htmlInsert",
	    'category' => "Newsletter Authentication"
		);

	sendMail($mailData);
	echo "Sent";
}

if($action == "AuthenticateNewsletter"){
	$data = json_decode(file_get_contents("php://input"));
	$authCode = $data->code;


	$authUser = $conn->query("SELECT * FROM newsletter WHERE Auth='$authCode'");
	$authUser = $authUser->fetchAll();

	if( count($authUser)  == 1 ){
		$q = "UPDATE newsletter SET Ativo = 1 WHERE Auth='$authCode'";
	    $query = $conn->prepare($q);
	    $query->execute();

    echo($response);

	}else{
	    echo("ERROR");
	}
}	


?>
