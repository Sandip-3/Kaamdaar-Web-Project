<?php 
session_start();

$count = $_GET['count'];
$action = $_GET['action'];

require_once $_SERVER['DOCUMENT_ROOT'] . "/kaamdaar-php/constants.php";
require_once ROOT_DIR . "models/request.php";
require_once ROOT_DIR . "controllers/db/kaamdaar_orm.php";

if($action === "count")
{
    echo json_encode(['notif_count' => 6]);
    return;
}

$user_id = $_SESSION['user_id'];

$orm = new KaamdaarORM();
$notifications = [];

/* fetch_user_response_notifs() function */
(function () {
    global $notifications;
    global $orm;
    global $user_id;

    $SQL = "SELECT 
                bp.B_PROFILE_IMAGE AS `ICON`,  
                concat_ws(' ', bp.B_PROFILE_NAME, 'is offering') AS `PROFILE`,
                cat.BR_CAT_NAME AS `TITLE`,
                'Accept or reject' AS `DESCRIPTION`,
                rn.RESPONSE_TIME AS `TIME`,
                rn.RESPONSE_STATUS AS `STATUS`,
                bp.B_PROFILE_ID AS `SENDER`,
                req.REQUEST_ID AS `REQUEST_ID` 
                FROM user_res_notifs urn 
                INNER JOIN business_profile bp 
                ON bp.B_PROFILE_ID = urn.SENDER_ID 
                NATURAL JOIN response_notifications rn 
                INNER JOIN request req 
                ON req.REQUEST_ID = rn.REQUEST_ID 
                INNER JOIN br_category cat 
                ON cat.BR_CAT_ID = req.REQUEST_TYPE 
                WHERE urn.U_ID = '$user_id';";
    
    $notifs = new ResultSet($orm->connection->query($SQL));
    if($notifs && count($notifs))
    {
        foreach($notifs as $notif)
        {
            $notif['NOTIF_TYPE'] = 'RESPONSE';
            $notif['RESPONSE_TYPE'] = 'user-response';
            array_push($notifications, $notif);
        }
    }
})();

if(!isset($_SESSION['business_id']))
{
    echo json_encode($notifications);
    return;
}

$business_id = $_SESSION['business_id'];

/* fetch_business_response_notifs() function */
(function () {
    global $notifications;
    global $orm;
    global $business_id;

    $SQL = "SELECT 
                u.U_IMAGE AS `ICON`,  
                concat_ws(' ', u.U_FNAME, u.U_LNAME) AS `PROFILE`,
                cat.BR_CAT_NAME AS `TITLE`,
                '' AS `DESCRIPTION`,
                rn.RESPONSE_TIME AS `TIME`,
                rn.RESPONSE_STATUS AS `STATUS`,
                req.REQUEST_ID AS `REQUEST_ID`,
                u.U_ID AS `SENDER` 
                FROM business_res_notifs brn 
                INNER JOIN users u 
                ON u.U_ID = brn.SENDER_ID 
                NATURAL JOIN response_notifications rn 
                INNER JOIN request req 
                ON req.REQUEST_ID = rn.REQUEST_ID 
                INNER JOIN br_category cat 
                ON cat.BR_CAT_ID = req.REQUEST_TYPE 
                WHERE brn.B_PROFILE_ID = '$business_id';";

    $notifs = new ResultSet($orm->connection->query($SQL));
    if($notifs && count($notifs))
    {
        foreach($notifs as $notif)
        {
            $notif['PROFILE'] .= $notif['STATUS'] == "0" ? " accepted" : " rejected";
            $notif['NOTIF_TYPE'] = 'RESPONSE';
            $notif['RESPONSE_TYPE'] = 'business-response';
            array_push($notifications, $notif);
        }
    }
})();

/* fetch_request_notifs() function */
(function ()
{
    global $notifications;
    global $orm;
    global $business_id;
    global $count;
    global $action;
    global $user_id;

    $businesses = new ResultSet($orm->connection->query("SELECT * FROM business WHERE B_PROFILE_ID = '$business_id';"));
    if($businesses && count($businesses))
    {
        foreach($businesses as $business)
        {
            $type = $business['BUSINESS_TYPE'];
            $start_date = $business['BUSINESS_START_DATE'];
            $notif_sql = 
            "SELECT 
                u.U_IMAGE AS `ICON`, 
                concat_ws(' ', u.U_FNAME, u.U_LNAME, 'wants') as `PROFILE`,
                r.REQUEST_TYPE AS `TITLE`,
                r.REQUEST_LOCATION AS `DESCRIPTION`,
                r.REQUEST_TIME AS `TIME`,
                u.U_ID AS `USER_ID`,
                r.REQUEST_ID AS `REQUEST_ID`,
                r.REQUEST_STATUS AS `STATUS` 
                FROM request r NATURAL JOIN users u   
                WHERE r.REQUEST_TYPE = '$type' 
                AND YEAR(r.REQUEST_TIME) >= YEAR('$start_date') 
                AND MONTH(r.REQUEST_TIME) >= MONTH('$start_date') 
                AND DAY(r.REQUEST_TIME) >= DAY('$start_date') 
                AND r.U_ID != '$user_id' ORDER BY r.REQUEST_TIME DESC;";

            $notifs = new ResultSet($orm->connection->query($notif_sql));

            if($notifs && count($notifs))
            {
                foreach($notifs as $notif)
                {
                    $notif['NOTIF_TYPE'] = 'REQUEST';
                    $notif['TITLE'] = Model\REQUEST_TYPE_NAMES[$notif['TITLE']];
                    array_push($notifications, $notif);
                }
            }
        }
    }
})();

/* Sort notifications*/
function sort_notifs_by_date($notifications)
{
    usort($notifications, function($notif1, $notif2) // function timestamp_compare
    {
        $timestamp1 = strtotime($notif1['TIME']);
        $timestamp2 = strtotime($notif2['TIME']);
        return $timestamp2 - $timestamp1;
    });

    return $notifications;
}

$sorted = sort_notifs_by_date($notifications);
$notif_count = count($sorted);

echo json_encode(array_slice($sorted, 0, $count > $notif_count ? $notif_count : $count));
?>