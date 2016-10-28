<?php
include 'conn.php';
// include 'PagSeguroService.php';
include 'AbrvAdminService.php';
// date_default_timezone_set("America/Sao_Paulo");
// $d=strtotime("-30 days");
// $initialDate = date("Y-m-d", $d) . "T" .date("H:i", $d);
// $finalDate = date("Y-m-d") . "T" .date("H:i");
// $url = 'https://ws.sandbox.pagseguro.uol.com.br/v2/transactions?initialDate=' . $initialDate . '&finalDate=' . $finalDate . '&email=' . $pagSeguroEmail . '&token=' . $pagSeguroToken;
//     echo $url;
// PagSeguroService::UpdateAllTransactions();
AbrvAdminService::UpdateAllSubscriptions();
?>

<!-- UPDATE event_user_rel as subs
INNER JOIN transactions as trans
ON ID_user = UserID AND ID_sale = SaleID
SET subs.Status = trans.Status  -->
