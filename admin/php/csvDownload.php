<?php
include("conn.php");
// include("mailAPI.php");
// $data = json_decode(file_get_contents("php://input"));
// $action = $data->action;
// $eventID = $data->eventID;

$action = $_GET["action"];
$eventID = $_GET["eventID"];

date_default_timezone_set("America/Sao_Paulo");
$dateTime = date("Y_m_d") . "|" . date("h_i_s");

// output headers so that the file is downloaded rather than displayed
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=' . $action . '_' . $dateTime . '.csv');


if($action == "ExportSubscriptions"){

	// create a file pointer connected to the output stream
	$output = fopen('php://output', 'w');

	// output the column headings
	fputcsv($output, array('ID', 'Nome', 'Email', 'Modalidade', 'Obs', 'Codigo de inscricao', 'Valor do Pagamento', 'Status Pagamento', 'Inscricao'));

	// fetch the data
	mysql_connect('localhost', DBUSER, DBPASS);
	mysql_select_db('abrv_db');
	$q = "SELECT `users`.`ID`, `users`.`Nome`, `users`.`Email`, `users`.`Tipo`, `event_user_rel`.`Observation`, `event_user_rel`.`SubscriptionCode`, `event_user_rel`.`PagamentoValor`, `event_user_rel`.`Status`, `event_user_rel`.`SubscriptionStatus`
	FROM `users`
	INNER JOIN `event_user_rel`
	ON `users`.`ID` = `event_user_rel`.`ID_user`
	WHERE `event_user_rel`.`ID_event` = '$eventID' ";
	$rows = mysql_query($q);

	// loop over the rows, outputting them
	while ($row = mysql_fetch_assoc($rows)) fputcsv($output, $row);
}

?>