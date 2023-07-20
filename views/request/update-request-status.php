<?php 
session_start();

require_once "../../constants.php";
require_once ROOT_DIR . "controllers/db/kaamdaar_orm.php";

$rid = $_GET['rid'];
$SQL = "UPDATE request SET REQUEST_STATUS = 1 WHERE REQUEST_ID = '$rid';";

$orm = new KaamdaarORM;
if($orm->connection->query($SQL))
{
    echo "OK";
}
?>