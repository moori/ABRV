<?php
header("Access-Control-Allow-Origin: *");
//header("Content-Type: application/json; charset=UTF-8");
header("Content-Type: application/json; charset=iso-8859-1");
include("conn.php");


$getID = $_GET['new_ID'];

$q = "SELECT ID, Titulo, Data, Texto, Publicado, Autor FROM news WHERE ID = " . $getID;
$query = $conn->prepare($q);
$query->execute();
$result = $query->fetchAll(PDO::FETCH_ASSOC);
$conn = null;
echo json_encode($result);

?>