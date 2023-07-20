<?php 
session_start();

require_once "../../constants.php";
require_once ROOT_DIR . "controllers/db/kaamdaar_orm.php";

$bid = $_GET['bid'];
$new_status = $_GET['status'];
$SQL = "UPDATE business SET BUSINESS_STATUS = '$new_status' WHERE BUSINESS_ID = '$bid'";

$orm = new KaamdaarORM;
if($orm->connection->query($SQL))
{
    echo "OK";
}
?>