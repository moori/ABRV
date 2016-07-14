<?php
header('Access-Control-Allow-Origin: *');
$data = json_decode(file_get_contents("php://input"));
echo($data);


?>

<html>
<script>
    </script>

</html>