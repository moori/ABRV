<?php 
include("conn.php");
$data = json_decode(file_get_contents("php://input"));
$email = $data->email;
$nome = $data->nome;
$endereco = $data->endereco;
$end_numero = $data->end_numero;
$end_complemento = $data->end_complemento;
$cep = $data->cep;
$bairro = $data->bairro;
$cidade = $data->cidade;
$estado = $data->estado;
$tel_ddd = $data->tel_ddd;
$telefone = $data->telefone;
$cel_ddd = $data->cel_ddd;
$cel = $data->celular;
$crmv = $data->crmv;
$especialidade = $data->especialidade;
$cpf = $data->cpf;
$tipo = $data->tipo;
$status = $data->stat;
$ativo = $data->ativo;

//TODO: Vulneravel a SQL Injection
$userInfo = $conn->query("SELECT * FROM users WHERE Email='$email'");
$userInfo = $userInfo->fetchAll(PDO::FETCH_OBJ);


date_default_timezone_set("America/Sao_Paulo");
$dateTime = date("Y-m-d") . "|" . date("h:i:s");   	

if (count($userInfo) == 1){
// $q = "UPDATE users SET Nome  = '$nome', Email = '$email', Endereco = '$endereco', Numero = '$end_numero', Complemento = '$end_complemento', Cidade = '$cidade', Estado = '$estado', CEP = '$cep', Bairro = '$bairro', Tel_DDD = '$tel_ddd', Telefone = '$telefone', Cel_DDD = '$cel_ddd', Cel = '$cel', CRMV = '$crmv', Especialidade = '$especialidade', CPF = '$cpf'  WHERE Email='$email' AND Password='$senha'";
$q = "UPDATE users SET Tipo = '$tipo', Status = '$status', Ativo = '$ativo', DateModified = '$dateTime' WHERE Email='$email'";
$query = $conn->prepare($q);
$execute = $query->execute(); 
echo "SUCCESS";
} else {
echo "ERROR";
}


	
?>