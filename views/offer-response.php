<?php 
session_start();

require_once $_SERVER['DOCUMENT_ROOT'] . "/kaamdaar-php/constants.php";
require_once ROOT_DIR . "controllers/db/kaamdaar_orm.php";

$request_id = $_GET['req-id'];
$business_id = $_GET['rec-id'];
$response_status = $_GET['response-status'];
$user_id = $_SESSION['user_id'];

$orm = new KaamdaarORM;

$SQL = "INSERT INTO response_notifications(REQUEST_ID, RESPONSE_STATUS, RESPONSE_TIME) VALUES('$request_id', '$response_status', DEFAULT);";
if($orm->connection->query($SQL))
{
    $response_id = $orm->lastInsertId();
    $SQL = "INSERT INTO business_res_notifs(B_PROFILE_ID, RESPONSE_ID, SENDER_ID) VALUES('$business_id', '$response_id', '$user_id');";
    if($orm->connection->query($SQL))
    {
        echo "OK";
    }
}
$orm->close();
?>