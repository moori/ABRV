<?php
	$logFile = 'cronLog.txt';
    // Open the file to get existing content
    $c = file_get_contents($logFile);
    // Append a new person to the file
    $c .= "Cronned " . date("Y-m-d") . " | " .date("H:i") . "\n";
    // Write the contents back to the file
    file_put_contents($logFile, $c);

?>	