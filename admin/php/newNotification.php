<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=iso-8859-1");
include("conn.php");

if(isset($_POST['notificationType']) && $_POST['notificationType'] == 'transaction'){
  // LOG

  $code = $_POST['notificationCode'];
  // REQUEST transaction
  $url = "https://ws.pagseguro.uol.com.br/v3/transactions/notifications/" . $code . "?email=" . ENV_PAGSEGURO_EMAIL . "&token=" . ENV_PAGSEGURO_TOKEN_PRODUCTION . " ";

  // use key 'http' even if you send the request to https://...
  $options = array(
      'http' => array(
          'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
          'method'  => 'GET'
      )
  );
  $context  = stream_context_create($options);
  $result = file_get_contents($url, false, $context);
  if ($result === FALSE) { /* Handle error */ }

  $xml = simplexml_load_string($result);

  $currentDate = date("Y-m-d") . "|" . date("h:i:s");

  $q = "INSERT INTO notifications (notificationCode, status, DateCreated) VALUES ('$code ', '0', '$currentDate')";
  $query = $conn->prepare($q);
  $execute = $query->execute();
}



?>
