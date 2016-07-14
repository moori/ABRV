<?php
include("conn.php");

$nonAuthUsers = $conn->query("SELECT * FROM users WHERE Auth != 'Autenticado' ORDER BY DateCreated DESC ");
$nonAuthUsers = $nonAuthUsers->fetchAll();

echo "<table>";

echo "<tr>";
	echo "<td> Nome </td>";
	echo "<td> Email </td>";
	echo "<td> CPF </td>";
	echo "<td> Codigo de autentição </td>";
	echo "</tr>";

foreach ($nonAuthUsers as $user) {
	echo "<tr>";
	echo "<td>" . $user["Nome"] . "</td>";
	echo "<td>" . $user["Email"] . "</td>";
	echo "<td>" . $user["CPF"] . "</td>";
	echo "<td>" . "http://www.abrv.org.br/#/activation/" . $user["Auth"] . "</td>";
	echo "</tr>";
}
echo "</table>";
?>