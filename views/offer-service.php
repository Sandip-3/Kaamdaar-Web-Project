<?php 
session_start();

require_once $_SERVER['DOCUMENT_ROOT'] . "/kaamdaar-php/constants.php";
require_once ROOT_DIR . "controllers/db/kaamdaar_orm.php";

$request_id = $_GET['req-id'];
$user_id = $_GET['rec-id'];
$business_id = $_SESSION['business_id'];

$orm = new KaamdaarORM;

$SQL = "INSERT INTO response_notifications(REQUEST_ID, RESPONSE_STATUS, RESPONSE_TIME) VALUES('$request_id', '0', DEFAULT);";
if($orm->connection->query($SQL))
{
    $response_id = $orm->lastInsertId();
    $SQL = "INSERT INTO user_res_notifs(U_ID, RESPONSE_ID, SENDER_ID) VALUES('$user_id', '$response_id', '$business_id');";
    if($orm->connection->query($SQL))
    {
        echo "OK";
    }
}
$orm->close();
?>