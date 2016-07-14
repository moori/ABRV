<?php
include("conn.php");

$data = json_decode(file_get_contents("php://input"));
$action = $data->action;
$eventID = $data->eventID;


if($action == "Inscritos"){

	$q = "SELECT `users`.`ID`, `users`.`Nome`, `users`.`Email`, `users`.`Tipo`, `event_user_rel`.`Observation`, `event_user_rel`.`SubscriptionCode`, `event_user_rel`.`PagamentoValor`, `event_user_rel`.`Status`
	FROM `users`
	INNER JOIN `event_user_rel`
	ON `users`.`ID` = `event_user_rel`.`ID_user`
	WHERE `event_user_rel`.`ID_event` = '$eventID' ";
	$query = $conn->prepare($q);
	$query->execute();
	$result = $query->fetchAll();

	download_send_headers("inscritos_export_" . date("Y-m-d") . ".csv");
	echo array2csv($result);
	die();

}


function array2csv(array &$array)
{
   if (count($array) == 0) {
     return null;
   }
   ob_start();
   $df = fopen("php://output", 'w');
   fputcsv($df, array_keys(reset($array)));
   foreach ($array as $row) {
      fputcsv($df, $row);
   }
   fclose($df);
   return ob_get_clean();
}

function download_send_headers($filename) {
    // disable caching
    $now = gmdate("D, d M Y H:i:s");
    header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
    header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
    header("Last-Modified: {$now} GMT");

    // force download  
    header("Content-Type: application/force-download");
    header("Content-Type: application/octet-stream");
    header("Content-Type: application/download");

    // disposition / encoding on response body
    header("Content-Disposition: attachment;filename={$filename}");
    header("Content-Transfer-Encoding: binary");
}
?>