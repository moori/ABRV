<?php
header('Access-Control-Allow-Origin: *');  
require("../sendgrid-php/sendgrid-php.php");
// Dotenv::load(__DIR__);
$sendgrid = new SendGrid('-ELuhDyNSjq-AdLCkmImcA');
$email = new SendGrid\Email();
$email
    ->addTo('daniel.moori@gmail.com')
    ->setFrom('me@bar.com')
    ->setSubject('Subject goes here')
    ->setText('Hello World!')
    ->setHtml('<strong>Hello World!</strong>')
;

// $sendgrid->send($email);

// // Or catch the error

try {
    $sendgrid->send($email);
} catch(\SendGrid\Exception $e) {
    echo $e->getCode();
    foreach($e->getErrors() as $er) {
        echo $er;
    }
}
?>