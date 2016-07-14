<?php 
include("conn.php");
include("mailAPI.php");
$data = json_decode(file_get_contents("php://input"));
$action = $data->action;

if($action == "LoginUser"){
	$senha = sha1($data->senha);
    $email = $data->email;


    $userInfo = $conn->query("SELECT Email FROM users WHERE Email='$email' AND Senha='$senha' AND Ativo=1");
    $userInfo = $userInfo->fetchAll();

	//TODO: Vulneravel a SQL Injection
	// $userInfo = $conn->prepare('SELECT * FROM admins WHERE Email = :email AND Password = :senha');
	// $userInfo = execute(array(':email' => $email, ':senha' => $senha));
	// $userInfo->bindParam(':email', $email);
	// $userInfo->bindParam(':senha', $senha);
	// $userInfo = execute();
	// $userInfo = $userInfo->fetchAll(PDO::FETCH_OBJ);

	if (count($userInfo) == 1){
    	//This means that the user is logged in and let's givem a token :D :D :D
    	$token = md5($email) . md5(uniqid()) . uniqid();	
    	$q = "UPDATE users SET token='$token' WHERE Email='$email' AND Senha='$senha'";
    	$query = $conn->prepare($q);
    	$execute = $query->execute(); 
        // echo json_encode($token);
        echo $token;
	}else{
        echo "ERROR";
    }
    

}

if($action == "LoginFromAuthentication"){
    $email = $data->email;
    $hash = $data->hash;
    $userInfo = $conn->query("SELECT Email FROM users WHERE Email='$email' AND Auth='$hash' AND Ativo=1");
    $userInfo = $userInfo->fetchAll();

    //TODO: Vulneravel a SQL Injection
    // $userInfo = $conn->prepare('SELECT * FROM admins WHERE Email = :email AND Password = :senha');
    // $userInfo = execute(array(':email' => $email, ':senha' => $senha));
    // $userInfo->bindParam(':email', $email);
    // $userInfo->bindParam(':senha', $senha);
    // $userInfo = execute();
    // $userInfo = $userInfo->fetchAll(PDO::FETCH_OBJ);

    if (count($userInfo) == 1){
    //This means that the user is logged in and let's givem a token :D :D :D
    $token = md5($email) . md5(uniqid()) . uniqid();     
    $q = "UPDATE users SET token='$token' , Auth = 'Autenticado' WHERE Email='$email' AND Auth='$hash'";
    $query = $conn->prepare($q);
    $execute = $query->execute(); 
    echo $token;
    } else {
    echo "ERROR";
    }

}

if($action == "CheckToken"){

	$token = $data->token;
    $token = trim($token, '"');
	$check = $conn->query("SELECT * FROM users WHERE token='$token'");
	$check = $check->fetchAll();
	if (count($check) == 1){
		echo "authorized";
	} else {
		echo "unauthorized";
	}

}

if($action == "LogoutUser"){

	$token = $data->token;
    $token = trim($token, '"');
	$check = $conn->query("SELECT * FROM users WHERE Token='$token'");
	$check = $check->fetchAll();
	if (count($check) == 1){
		$q = "UPDATE users SET Token='Logout' WHERE Token='$token'";
		$query = $conn->prepare($q);
		$execute = $query->execute();
		echo "Logout";
	}

}

if($action == "SignUpUser"){

	$data = json_decode(file_get_contents("php://input"));
    $email = $data->email;
    $senha = $data->senha;
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
    $coirma = $data->coirma;
    $faculdade = $data->faculdade;
    $cpf = $data->cpf;
    $tipo = $data->tipo;
    $grad = $data->grad;

    $hashPass = sha1($senha);
    date_default_timezone_set("America/Sao_Paulo");
    $dateTime = date("Y-m-d") . "|" . date("h:i:s");

    $repeatedUser = $conn->query("SELECT * FROM users WHERE CPF='$cpf' OR Email ='$email'");
    $repeatedUser = $repeatedUser->fetchAll();
    if(count($repeatedUser) > 0 ){
        echo "ERROR";
    }else{

        if($grad == true){
            $novoTipo = 8;
        }else if(strlen($coirma)>2){
            $novoTipo = 9;
        }else{
            $novoTipo = 0;
        }

        $userInfo = $conn->query("SELECT Email FROM users WHERE Email='$email' AND Senha='$hashPass'");
        $userInfo = $userInfo->fetchAll();
        if (count($userInfo) == 0){
            $q = "INSERT INTO users (Email, Senha, Nome, Endereco, Numero, Complemento, Cidade, Estado, CEP, Bairro, Tel_DDD, Telefone, Cel_DDD, Cel, CRMV, Especialidade, Faculdade, Coirma, CPF, Tipo, DateCreated, DateModified) VALUES ('$email', '$hashPass', '$nome', '$endereco', '$end_numero', '$end_complemento' ,'$cidade', '$estado', '$cep', '$bairro', '$tel_ddd', '$telefone', '$cel_ddd', '$cel', '$crmv', '$especialidade', '$faculdade', '$coirma', '$cpf', '$novoTipo', '$dateTime', '$dateTime')";
            $query = $conn->prepare($q);
            $query->execute();
            // $loginData = {email: $email, senha: $hashPass};
            $loginData = (object) array('email' => $email, 'senha' => $senha);
            echo json_encode($loginData);
        }else{
            echo "ERROR";
        }
    }

}

if($action == "UpdateUserData"){

	$data = json_decode(file_get_contents("php://input"));
    $email = $data->email;
    // $senha = $data->senha;
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
    $coirma = $data->coirma;
    $faculdade = $data->faculdade;
    $cpf = $data->cpf;
    $token = $data->token;
    $grad = $data->grad;
    $token = trim($token, '"');

    // $hashPass = sha1($senha);
    date_default_timezone_set("America/Sao_Paulo");
    $dateTime = date("Y-m-d") . "|" . date("h:i:s");    

    $repeatedUser = $conn->query("SELECT * FROM users WHERE CPF='$cpf' OR Email ='$email'");
    $repeatedUser = $repeatedUser->fetchAll();
    if(count($repeatedUser) > 1 ){
        echo "ERROR";
    }else{


        $userInfo = $conn->query("SELECT Email FROM users WHERE Token = '$token'");
        $userInfo = $userInfo->fetchAll();
        if (count($userInfo) == 1){
            $novoTipo = $userInfo["Tipo"];

            if($novoTipo == 1 || $novoTipo == 2 || $novoTipo == 3 || $novoTipo == 4 || $novoTipo == 5 || $novoTipo == 6 || $novoTipo == 7){

            }else if($grad === true){
                $novoTipo = 8;
            }else if(strlen($coirma)>2){
                $novoTipo = 9;
            }


            // $q = "UPDATE users (Nome, Endereco, Numero, Complemento, Cidade, Estado, CEP, Bairro, Tel_DDD, Telefone, Cel_DDD, Cel, CRMV, Especialidade, CPF) SET ('$nome', '$endereco', '$end_numero', '$end_complemento' ,'$cidade', '$estado', '$cep', '$bairro', '$tel_ddd', '$telefone', '$cel_ddd', '$cel', '$crmv', '$especialidade', '$cpf') WHERE Token = '$token'";
            $q = "UPDATE users SET Nome  = '$nome', Email = '$email', Endereco = '$endereco', Numero = '$end_numero', Complemento = '$end_complemento', Cidade = '$cidade', Estado = '$estado', CEP = '$cep', Bairro = '$bairro', Tel_DDD = '$tel_ddd', Telefone = '$telefone', Cel_DDD = '$cel_ddd', Cel = '$cel', CRMV = '$crmv', Especialidade = '$especialidade', Faculdade = '$faculdade', Coirma = '$coirma', CPF = '$cpf', DateModified = '$dateTime', Tipo = '$novoTipo' WHERE Token = '$token'";
            $query = $conn->prepare($q);
            $query->execute();
            echo json_encode("Atualizado");
        }else{
            echo "ERROR";
        }
    }

}

if($action == "GetUserData"){
    $token = "";
    $token = $data->token;
    $token = trim($token, '"');
    $userData = $conn->query("SELECT * FROM users WHERE token='$token'");
    // $userData = $conn->query("SELECT ID, Nome, Email, Senha, Endereco, Numero, Complemento, Bairro, Cidade, Estado, CEP, Tel_DDD, Telefone, Cel_DDD, Cel, CRMV, Especialidade, CPF, Token, Tipo, Status FROM users WHERE token='$token'");
    $userData = $userData->fetchAll();
    if (count($userData) == 1){
        echo json_encode($userData);
    } else {
        echo "ERROR";
    }

}

if($action == "ChangePassword"){
    $senha = sha1($data->senha);
    $novaSenha = sha1($data->novaSenha);
    $token = $data->token;
    $userInfo = $conn->query("SELECT * FROM users WHERE Senha='$senha' AND token='$token'");
    $userInfo = $userInfo->fetchAll();

    if (count($userInfo) == 1){  
    $q = "UPDATE users SET Senha='$novaSenha'  WHERE Senha='$senha' AND token='$token'";
    $query = $conn->prepare($q);
    $execute = $query->execute(); 
    echo "SUCCESS";
    } else {
    echo "ERROR";
    }

}



if($action == "ResetPassword"){
    $email = $data->email;

    $userInfo = $conn->query("SELECT * FROM users WHERE Email='$email'");
    $userInfo = $userInfo->fetchAll();

    if (count($userInfo) == 1){ 

        $novaSenha = substr( md5(uniqid()) , -8);
        // $novaSenha = "banana";
        $hash = sha1($novaSenha);
        $q = "UPDATE users SET Senha='$hash' WHERE Email='$email'";
        $query = $conn->prepare($q);
        $execute = $query->execute();

        $htmlInsert = file_get_contents('../templates/mail/passwordResetMail.html');
        $htmlInsert = str_replace("##nome##", $userInfo[0]['Nome'], $htmlInsert);
        $htmlInsert = str_replace("##novaSenha##", $novaSenha, $htmlInsert);


        $mailData = array(
            'email'  => "$email",
            'subject'   => "ABRV | Renovação de senha",
            'htmlInsert'   => "$htmlInsert",
            'category' => "Password Reset"
            );

        sendMail($mailData);

    echo "SUCCESS";
    } else {
    echo "ERROR";
    }

}
	
?>