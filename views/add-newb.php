<?php 
session_start();

require_once "../constants.php";
require_once ROOT_DIR . "controllers/db/kaamdaar_orm.php";

$type = $_GET['type'];
$bid = $_SESSION['business_id'];

$SQL = "INSERT INTO business(BUSINESS_TYPE, BUSINESS_START_DATE, B_PROFILE_ID) VALUES('$type', DEFAULT, '" . $bid . "');";

$orm = new KaamdaarORM();

if(!$orm->connection->query($SQL))
    header('HTTP/1.1 500 Internal Server Error');

$orm->close();
?>