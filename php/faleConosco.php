<?php 
include("conn.php");
include("mailAPI.php");

$data = json_decode(file_get_contents("php://input"));
$nome = $data->nome;
$email = $data->email;
$telefone = $data->telefone;
$assunto = $data->assunto;
$mensagem = $data->mensagem;

$q = "INSERT INTO faleConosco (id, Nome, Email, Telefone, Assunto, Mensagem) VALUES (NULL, '$nome', '$email', '$telefone', '$assunto' ,'$mensagem')";
$query = $conn->prepare($q);
$query->execute();
echo date("d/m/Y") . " " .date("H:i");

$htmlInsert = file_get_contents('../templates/mail/faleConoscoMail.html');
$htmlInsert = str_replace("##nome##", $nome, $htmlInsert);
$htmlInsert = str_replace("##email##", $email, $htmlInsert);
$htmlInsert = str_replace("##telefone##", $telefone, $htmlInsert);
$htmlInsert = str_replace("##assunto##", $assunto, $htmlInsert);
$htmlInsert = str_replace("##mensagem##", $mensagem, $htmlInsert);


$mailData = array(
	'email'  => "abrv@abrv.org.br",
    'subject'   => "Fale Conosco | $assunto",
    'htmlInsert'   => "$htmlInsert",
    'category' => "Fale Conosco"
	);


sendMail($mailData);


?>