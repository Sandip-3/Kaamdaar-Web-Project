<?php 
session_start();

require_once $_SERVER['DOCUMENT_ROOT'] . "/kaamdaar-php/constants.php";
require_once ROOT_DIR . "controllers/db/kaamdaar_orm.php";

$uid = $_SESSION['user_id'];
$location = $_GET['loc'];
$lat = $_GET['lat'];
$lng = $_GET['lng'];
$type = $_GET['type'];

$SQL = "INSERT INTO request(REQUEST_LOCATION, REQUEST_LATLONG, REQUEST_TYPE, REQUEST_STATUS, REQUEST_TIME, REQUEST_URGENCY, U_ID) VALUES('$location', '$lat, $lng', '$type', '0', DEFAULT, '1', '$uid');";
$orm = new KaamdaarORM();
if($orm->connection->query($SQL))
{
    echo "OK";
}
?>