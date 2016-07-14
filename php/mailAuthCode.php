<?php
include("conn.php");
include("mailAPI.php");
$data = json_decode(file_get_contents("php://input"));
$email = $data->email;
$nome = $data->nome;
$tipo = $data->tipo;


$MT_nome = $nome;
$authcode = uniqid() . "&" . $email . "&" . md5(uniqid());

$q = " UPDATE `abrv_db`.`users` SET `Auth` = '$authcode' WHERE Email = '$email' ";
$query = $conn->prepare($q);
$execute = $query->execute(); 

$MT_authURL = "http://www.abrv.org.br/#/activation/" . $authcode;

$htmlInsert = file_get_contents('../templates/mail/activationEmail.html');
$htmlInsert = str_replace("##nome##", $MT_nome, $htmlInsert);
$htmlInsert = str_replace("##authURL##", $MT_authURL, $htmlInsert);


$mailData = array(
	'email'  => "$email",
    'subject'   => "ABRV | Bem-vindo!",
    'htmlInsert'   => "$htmlInsert",
    'category' => "signUp Authentication"
	);

sendMail($mailData);


?>
