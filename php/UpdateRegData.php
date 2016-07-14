<?php 
include("conn.php");


$userInfo = $conn->query("SELECT emails.DateTime, users.DateCreated, users.DateModified, users.Email FROM emails INNER JOIN users on emails.Recipient = users.Email WHERE emails.Category='signUp Authentication'");
$userInfo = $userInfo->fetchAll(PDO::FETCH_OBJ);

for ($i=0; $i < count($userInfo); $i++) { 
    $DateTime = $userInfo[$i]->DateTime;
    $email = $userInfo[$i]->Email;
    $q = "UPDATE users SET DateCreated='$DateTime', DateModified='$DateTime' WHERE Email='$email' ";
    $query = $conn->prepare($q);
    $execute = $query->execute(); 
}


// if (count($userInfo) == 1){
// 	//This means that the user is logged in and let's givem a token :D :D :D
// 	$token = md5($email) . md5(uniqid()) . uniqid();	
// 	$q = "UPDATE users SET token='$token' WHERE Email='$email' AND Senha='$senha'";
// 	$query = $conn->prepare($q);
// 	$execute = $query->execute(); 
//     // echo json_encode($token);
//     echo $token;
// }else{
//     echo "ERROR";
// }
    

