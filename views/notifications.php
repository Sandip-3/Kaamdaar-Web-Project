<?php 
	require_once '../constants.php';
    require_once "k_auth.php";
    require_once ROOT_DIR . "controllers/db/kaamdaar_orm.php";
    require_once ROOT_DIR . "models/request-notification.php";

    use Model\RequestNotification;

	session_start();
	if(!isset($_SESSION['user_phone'])) header('location:login.php?route=notifications.php');

    require_once("request-constants.php");

    $orm = new KaamdaarORM();
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="../static/css/nav.css">
        <link rel="stylesheet" href="../static/css/notifications.css">

		<link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400&display=swap" rel="stylesheet">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <title>Notifications</title>
    </head>
    <body>
        <div class="root">
            <div class="page-head">
                <div>
                    <h2>Kaamdaar</h2>
                </div>
                <div>
                    <input type="text" class="search-bar" placeholder="Search">
                </div>
            </div>
            <div class="page-body">
                <div id="nav-bar" class="nav-bar">
                    <a href="profile.php" class="nav-link"><i class="fa fa-user" style="font-size:24px;"></i> Profile</a>
                    <a href="bprofile.php" class="nav-link"><i class="fa fa-briefcase" style="font-size:24px"></i> Business Profile</a>
                    <a href="requests.php" class="nav-link"><i class="fa fa-send" style="font-size:24px"></i> Your requests</a>
                    <a href="#" class="nav-link"><span class="nav-link-active"><i class="fa fa-bell" style="font-size:24px;"></i> Notifications</span></a>
                    <hr>
                    <a href="add-request.php" class="nav-link">Add new request</a>
                    <a href="add-business.php" class="nav-link">Add new business</a>
                    <hr>
                    <a href="logout.php" class="nav-link"><i class="fa fa-sign-out" style="font-size:24px"></i>Logout</a>
                    <a href="settings.php" class="nav-link"><i class="fa fa-cog" style="font-size:24px"></i> Settings</a>
                    <a href="#" class="nav-link"><i class="fa fa-print" style="font-size:24px"></i> Privacy policy</a>
                </div>
                <div class="page-content">
                    <div class="pc-title">
                        <div class="pct-1">
                            <div class="pc-main-title">
                                <h2>Notifications</h2>
                            </div>
                            <div class="pc-sub-title">
                                <h4>View all the notifications</h4>
                            </div>
                        </div>
                    </div>
                    <?php 
                        $all_notifs = $orm->fetchRequestNotifications(1);
                        if(count($all_notifs) <= 0)
                        {
                            echo "<b>No new notifications</b>";
                            die();
                        }
                    ?>
                    <div class="notif-list-container">
                        <ul class="notif-list">
                            <?php 
                            foreach($all_notifs as $notif) 
                            { 
                                $request = $notif->request;
                                $user = $notif->user;
                            ?>
                            <li class="nli">
                                <div class="nli-root">
                                    <div class="nli-1">
                                        <img id="nli-img" src="../static/images/profile.jpg" alt="Profile">
                                    </div>
                                    <div class="nli-2">
                                        <p id="nli-name"><?php echo $user->fname . ' ' . $user->lname; ?></p>
                                        <p id="nli-type-name"><?php echo REQUEST_TYPE_NAMES[$request->type]; ?></p>
                                        <div class="nli-2-2">
                                            <span id="nli-time"><?php echo $request->time; ?></span>
                                            <span style="color: #A19D9D">&#8226;</span>
                                            <span class="nli-status nli-status-<?php echo $request->status == 0 ? "pending" : "fulfilled"; ?>"><?php echo REQUEST_STATUS[$request->status]; ?></span>
                                        </div>
                                        <button id="nli-view-btn">View</button>
                                    </div>
                                </div>
                            </li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>