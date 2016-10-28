<?php
// require("conn.php");

class PagSeguroService
{
	public function GetTransactionURLByCode($subCode){

		include 'conn.php';

		$q = "Select TransactionCode FROM transactions
			INNER JOIN
			event_user_rel ON ID_user = UserID AND ID_sale = SaleID
			WHERE SubscriptionCode = '$subCode'";

		$query = $conn->prepare($q);
		$query->execute();
		$result = $query->fetch(PDO::FETCH_OBJ);
		$conn = null;

		$transactionCode = $result->TransactionCode;
		$url = "https://ws.pagseguro.uol.com.br/v3/transactions/" . $transactionCode . "?email=" . ENV_PAGSEGURO_EMAIL . "&token=" . ENV_PAGSEGURO_TOKEN_PRODUCTION . " ";
		return $url;
	}

	public function GetTransactionURLByTransactionCode($transCode){

		include 'conn.php';
		$transactionCode = $transCode;
		$url = "https://ws.pagseguro.uol.com.br/v3/transactions/" . $transactionCode . "?email=" . ENV_PAGSEGURO_EMAIL . "&token=" . ENV_PAGSEGURO_TOKEN_PRODUCTION . " ";
		return $url;
	}

	public function CheckSubscription($subCode){

		include 'conn.php';

		$q = "Select TransactionCode FROM transactions
			INNER JOIN
			event_user_rel ON ID_user = UserID AND ID_sale = SaleID
			WHERE SubscriptionCode = '$subCode'";

		// $q = "SELECT TransactionCode FROM transactions WHERE UserID = '$userID' AND SaleID = '$saleID' ";
	    $query = $conn->prepare($q);
	    $query->execute();
		$result = $query->fetch(PDO::FETCH_OBJ);
		$conn = null;


		$url = "https://ws.pagseguro.uol.com.br/v3/transactions/" . $result->TransactionCode . "?email=" . ENV_PAGSEGURO_EMAIL . "&token=" . ENV_PAGSEGURO_TOKEN_PRODUCTION . " ";
		$data = array('key1' => 'value1');

		// use key 'http' even if you send the request to https://...
		$options = array(
		    'http' => array(
		        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
		        'method'  => 'GET',
		        'content' => http_build_query($data)
		    )
		);
		$context  = stream_context_create($options);
		$result = file_get_contents($url, false, $context);
		if ($result === FALSE) { /* Handle error */ }

		$xml = simplexml_load_string($result);
		return json_encode($xml);
	}

	public function GetStatusBySubscriptionCode($subCode){

		include 'conn.php';

		$q = "Select TransactionCode FROM transactions
			INNER JOIN
			event_user_rel ON ID_user = UserID AND ID_sale = SaleID
			WHERE SubscriptionCode = '$subCode'";

		// $q = "SELECT TransactionCode FROM transactions WHERE UserID = '$userID' AND SaleID = '$saleID' ";
	    $query = $conn->prepare($q);
	    $query->execute();
		$result = $query->fetch(PDO::FETCH_OBJ);
		$conn = null;


		$url = "https://ws.pagseguro.uol.com.br/v3/transactions/" . $result->TransactionCode . "?email=" . ENV_PAGSEGURO_EMAIL . "&token=" . ENV_PAGSEGURO_TOKEN_PRODUCTION . " ";
		$data = array('key1' => 'value1');

		// use key 'http' even if you send the request to https://...
		$options = array(
		    'http' => array(
		        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
		        'method'  => 'GET',
		        'content' => http_build_query($data)
		    )
		);
		$context  = stream_context_create($options);
		$result = file_get_contents($url, false, $context);
		if ($result === FALSE) { /* Handle error */ }

		$xml = simplexml_load_string($result);
		return $xml->status;
		// return $xml ;
	}

	public function UpdateStatusBySubscriptionCode($subCode){

		include 'conn.php';

		$q = "Select TransactionCode FROM transactions
			INNER JOIN
			event_user_rel ON ID_user = UserID AND ID_sale = SaleID
			WHERE SubscriptionCode = '$subCode'";

		// $q = "SELECT TransactionCode FROM transactions WHERE UserID = '$userID' AND SaleID = '$saleID' ";
    $query = $conn->prepare($q);
    $query->execute();
		$result = $query->fetch(PDO::FETCH_OBJ);

		$transactionCode = $result->TransactionCode;
		$url = "https://ws.pagseguro.uol.com.br/v3/transactions/" . $transactionCode . "?email=" . ENV_PAGSEGURO_EMAIL . "&token=" . ENV_PAGSEGURO_TOKEN_PRODUCTION . " ";

		$options = array(
		    'http' => array(
		        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
		        'method'  => 'GET'
		    )
		);
		$context  = stream_context_create($options);
		$result = file_get_contents($url, false, $context);

		$xml = simplexml_load_string($result);
		$newStatus = $xml->status;
		// $newStatus = json_decode($xml->status);
		$q = "UPDATE transactions SET Status = $newStatus WHERE TransactionCode = '$transactionCode' ";
	  $query = $conn->prepare($q);
	  $execute = $query->execute();
	}

	public function UpdateAllTransactions(){

		include 'conn.php';

		$q = "Select TransactionCode FROM transactions
			INNER JOIN
			event_user_rel ON ID_user = UserID AND ID_sale = SaleID";

		// $q = "SELECT TransactionCode FROM transactions WHERE UserID = '$userID' AND SaleID = '$saleID' ";
    $query = $conn->prepare($q);
    $query->execute();
		$result = $query->fetchAll(PDO::FETCH_OBJ);
		foreach ($result as $row) {
			error_log("LOOOOOOOOG " . $row->TransactionCode);
			$transactionCode = $row->TransactionCode;
			$url = "https://ws.pagseguro.uol.com.br/v3/transactions/" . $transactionCode . "?email=" . ENV_PAGSEGURO_EMAIL . "&token=" . ENV_PAGSEGURO_TOKEN_PRODUCTION . " ";

			error_log($url);
			$options = array(
			    'http' => array(
			        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
			        'method'  => 'GET'
			    )
			);
			$context  = stream_context_create($options);

			$result = file_get_contents($url, false, $context);
			if ($result === FALSE) {
				error_log($transactionCode . " Não existe mais");
			}else{
				$xml = simplexml_load_string($result);
				$newStatus = $xml->status;
				// $newStatus = json_decode($xml->status);
				$q = "UPDATE transactions SET Status = $newStatus WHERE TransactionCode = '$transactionCode' ";
			  $query = $conn->prepare($q);
			  $execute = $query->execute();
			}
		}
	}

	public function UpdateTransaction($transCode){

		$url = "https://ws.pagseguro.uol.com.br/v3/transactions/" . $transCode . "?email=" . ENV_PAGSEGURO_EMAIL . "&token=" . ENV_PAGSEGURO_TOKEN_PRODUCTION . " ";

		$options = array(
		    'http' => array(
		        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
		        'method'  => 'GET'
		    )
		);
		$context  = stream_context_create($options);
		$result = file_get_contents($url, false, $context);

		$xml = simplexml_load_string($result);
		$status = $xml->status;

		$q = "UPDATE transactions SET Status = '$status' WHERE TransactionCode = '$transCode' ";
    $query = $conn->prepare($q);
    $query->execute();
	}

}

?>
