<?php
header("Access-Control-Allow-Origin: *");
//header("Content-Type: application/json; charset=UTF-8");
header("Content-Type: application/json; charset=iso-8859-1");
include("conn.php");

$q = "SELECT * FROM news WHERE Publicado = 1";
$query = $conn->prepare($q);
$query->execute();
$result = $query->fetchAll(PDO::FETCH_OBJ);
$conn = null;
echo json_encode($result);

?>