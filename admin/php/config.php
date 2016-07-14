<?php 

    $q = "SELECT * FROM config WHERE Parametro = 'AnoVigente'";
    $query = $conn->prepare($q);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_OBJ);
    $_anoVigenteSaleID = $result->Valor;
?>