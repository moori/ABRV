<?php 
include("conn.php");
$data = json_decode(file_get_contents("php://input"));
$action = $data->action;
$email = $data->email;

	

if($action == "LoginUser"){
	$senha = sha1($data->senha);

	//TODO: Vulneravel a SQL Injection
	$userInfo = $conn->query("SELECT * FROM admins WHERE Email='$email' AND Password='$senha'");
	$userInfo = $userInfo->fetchAll(PDO::FETCH_OBJ);

	// $userInfo = $conn->prepare('SELECT * FROM admins WHERE Email = :email AND Password = :senha');
	// $userInfo = execute(array(':email' => $email, ':senha' => $senha));
	// $userInfo->bindParam(':email', $email);
	// $userInfo->bindParam(':senha', $senha);
	// $userInfo = execute();
	// $userInfo = $userInfo->fetchAll(PDO::FETCH_OBJ);

	if (count($userInfo) == 1){
	//This means that the user is logged in and let's givem a token :D :D :D
	$token = $email . " | " . uniqid() . uniqid() . uniqid();	
	$q = "UPDATE admins SET Token='$token' WHERE Email='$email' AND Password='$senha'";
	$query = $conn->prepare($q);
	$execute = $query->execute(); 
	echo json_encode($token);
	} else {
	echo "ERROR";
	}

}

if($action == "CheckToken"){

	$token = $data->token;
    $token = trim($token, '"');
	$check = $conn->query("SELECT * FROM admins WHERE Token='$token'");
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
	$check = $conn->query("SELECT * FROM admins WHERE Token='$token'");
	$check = $check->fetchAll();
	if (count($check) == 1){
		$q = "UPDATE admins SET Token='Logout' WHERE Token='$token'";
		$query = $conn->prepare($q);
		$execute = $query->execute();
		echo "Logout";
	}

}



if($action == "GetUserDataByID"){
    $uid = $data->id;
    $userData = $conn->query("SELECT * FROM users WHERE ID='$uid'");
    // $userData = $conn->query("SELECT ID, Nome, Email, Senha, Endereco, Numero, Complemento, Bairro, Cidade, Estado, CEP, Tel_DDD, Telefone, Cel_DDD, Cel, CRMV, Especialidade, CPF, Token, Tipo, Status FROM users WHERE token='$token'");
    $userData = $userData->fetchAll();
    if (count($userData) == 1){
        echo json_encode($userData);
    } else {
        echo "ERROR";
    }

}
	
?>