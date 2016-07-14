<?php
include("conn.php");


// $MT_nome = $mail_toName;
// $MT_templatePath;
// $MT_authURL

// $htmlInsert = file_get_contents('$MT_templatePath');
// $htmlInsert = str_replace("##nome##", $MT_nome, $htmlInsert);
// $htmlInsert = str_replace("##authURL##", $MT_authURL, $htmlInsert);


function sendMail($mailData){
	include("conn.php");

	$mail_toEmail = $mailData["email"];
	// $mail_toName = $mailData["nome"];
	$mail_subject = $mailData["subject"];
	$mail_htmlInsert = $mailData["htmlInsert"];
	$mail_category = $mailData["category"];

	// Commom email code

	// use actual sendgrid username and password in this section
	$mail_APIurl = 'https://api.sendgrid.com/';
	$js = array(
	  'category' => $mail_category,
	);


	// note the above parameters now referenced in the 'subject', 'html', and 'text' sections
	// make the to email be your own address or where ever you would like the contact form info sent
	$params = array(
	    'api_user'  => "$SGuser",
	    'api_key'   => "$SGpass",
	    'from'      => "no-reply@abrv.org.br",
	    'to'        => "$mail_toEmail", // set TO address to have the contact form's email content sent to
	    'subject'   => "$mail_subject", // Either give a subject for each submission, or set to $mail_subject
	    'html'      => "$mail_htmlInsert",
	    'x-smtpapi' => json_encode($js),
	  );

	$curl = curl_init();
	curl_setopt($curl, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1_2);
	$request =  $mail_APIurl.'api/mail.send.json';

	// Generate curl request
	$session = curl_init($request);
	// Tell curl to use HTTP POST
	curl_setopt ($session, CURLOPT_POST, true);
	// Tell curl that this is the body of the POST
	curl_setopt ($session, CURLOPT_POSTFIELDS, $params);
	// Tell curl not to return headers, but do return the response
	curl_setopt($session, CURLOPT_HEADER, false);
	curl_setopt($session, CURLOPT_RETURNTRANSFER, true);

	// obtain response
	$response = curl_exec($session);
	curl_close($session);

	// Redirect to thank you page upon successfull completion, will want to build one if you don't alreday have one available
	// header('Location: thanks.html'); // feel free to use whatever title you wish for thank you landing page, but will need to reference that file name in place of the present 'thanks.html'
	// exit();

	// print everything out
	// print_r($response);
	// echo json_encode("ERRO");

	// date_default_timezone_set("America/Sao_Paulo");
	// $mailLogFile = 'mailLog.txt';
 //    $c = file_get_contents($mailLogFile);
 //    $c .= "Mail: " . $mail_toEmail . " | " .  date("Y-m-d") . " | " . date("h:i:s"). " " .$response . "\n";
 //    file_put_contents($mailLogFile, $c);


	date_default_timezone_set("America/Sao_Paulo");
	$dateTime = date("Y-m-d") . "|" . date("h:i:s");

    // $q = "INSERT INTO emails (Recipient, DateTime, Category, SGResponse, Subject) VALUES ('$mail_toEmail', '$dateTime', '$mail_category', '$response', '$mail_subject')";
    // $query = $conn->prepare($q);
    // $query->execute();

    // $q = "INSERT INTO `abrv_db`.`emails` (`ID`,`Recipient`, `DateTime`, `Category`, `SGResponse`, `Subject`) VALUES (NULL, '$mail_toEmail', '$dateTime', '$mail_category', '$response', '$mail_subject')";
    $q = "INSERT INTO `abrv_db`.`emails` (`ID`, `Recipient`, `DateTime`, `Category`, `SGResponse`, `Subject`) VALUES (NULL, '$mail_toEmail', '$dateTime', '$mail_category', '$response', '$mail_subject')";
    $query = $conn->prepare($q);
    $execute = $query->execute();
    
}

?>
