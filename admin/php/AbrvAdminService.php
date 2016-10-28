<?php
class AbrvAdminService
{
  public function UpdateAllSubscriptions(){
    include 'conn.php';
    include 'PagSeguroService.php';

    $q = "SELECT
    trans.TransactionCode,
    ID_sale,
    SaleID,
    ID_user,
    UserID,
    Date,
    subs.status as subStatus,
    trans.Status as psStatus
    FROM event_user_rel as subs
    INNER JOIN transactions as trans
    ON ID_user = UserID AND ID_sale = SaleID";
    $query = $conn->prepare($q);
    $query->execute();
		$result = $query->fetchAll(PDO::FETCH_OBJ);
		foreach ($result as $row) {
      $newStatus = $row->psStatus;
      $q = "UPDATE event_user_rel
      SET Status = $newStatus ";
      $query = $conn->prepare($q);
      $execute = $query->execute();
      error_log("UPDATE " . $row->ID_user." ".$row->psStatus);
		}
  }
}

?>
