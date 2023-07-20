<?php 
	require_once '../constants.php';
    require_once "k_auth.php";

	session_start();
	if(!isset($_SESSION['user_phone'])) header('location:login.php?route=profile.php');

	require_once '../utils.php';
	require_once ROOT_DIR . "controllers/db/db_kaamdaar.php";
	require_once ROOT_DIR . "controllers/db/kaamdaar_orm.php";

    $korm = new KaamdaarORM();
    $phone = $_SESSION['user_phone'];
    $user = $korm->getUserByPhone($phone);
    $uid = $user->id;
    $business_id = null;

    if(isset($_SESSION['business_id']))
    {
        $business_id = $_SESSION['business_id'];
    }
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="../static/css/base/layout.css">
		<link rel="stylesheet" href="../static/css/profile.css">
        <link rel="stylesheet" href="../static/css/modal/notif-modal.css">

		<link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400&display=swap" rel="stylesheet">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
		<!-- <link href="https://api.mapbox.com/mapbox-gl-js/v2.7.0/mapbox-gl.css" rel="stylesheet">
    	<script src="https://api.mapbox.com/mapbox-gl-js/v2.7.0/mapbox-gl.js"></script>
		<script src="../static/js/map/profile-map.js" defer></script> -->
        <script src="../static/js/modal.js"></script>
        <script src="../static/js/notif/notif.js"></script>
        
        <title>Profile</title>
	</head>
	<body>
		<div class="container">

            <div id="notif-modal" class="modal notif-modal">
                <?php 
                    require_once("./modal/notif-modal.php");
                ?>
            </div>

            <div class="container-head">
                <div class="container-head-pt-1">
                    <h1>Kaamdaar</h1>
                    <input type="text" class="search" placeholder="&setminus; Search kaamdaar">
                </div>
                <div class="container-head-pt-2">
                    <div class="head-icons">
                        <div class="head-icon-section head-notif-section" onclick="showNotificationModal(); //showNotificationModal function is inside notif-modal.js">
                            <span id="notif-count" class="notif-count"></span>
                            <img class="head-icon notif-icon" src="https://img.icons8.com/fluency-systems-filled/452/appointment-reminders.png" alt="Notif">
                        </div>
                        <div class="head-icon-section head-profile-section">
                            <img class="head-icon profile-icon" src="<?php echo $_SESSION['user_image']; ?>" alt="Profile">
                        </div>
                    </div>
                </div>
            </div>
            <div class="container-body">
                <div class="nav-bar">
                    <div class="nav-links-cat">
                        <div class="nav-links-cat-head">
                            <p class="nav-links-cat-title">
                                Profile
                            </p>
                        </div>
                        <div class="nav-links-cat-body">
                            <ul class="nav-links-list">
                                <li class="nav-link nav-link-active">
                                    <div class="nav-link-root">
                                        <div class="nav-link-icon">
                                            <img class="nav-link-icon-round" src="<?php echo $_SESSION['user_image']; ?>" alt="">
                                        </div>
                                        <div class="nav-link-text">
                                            <a href="profile.php">Profile</a>
                                        </div>
                                    </div>
                                </li>
                                <li class="nav-link">
                                    <div class="nav-link-root">
                                        <div class="nav-link-icon">
                                            <img class="nav-link-icon-norm" src="https://img.icons8.com/ios-glyphs/344/briefcase.png" alt="business icon">
                                            <!-- src="https://img.icons8.com/color/344/briefcase.png"  -->
                                        </div>
                                        <div class="nav-link-text">
                                            <a href="bprofile.php">Business profile</a>
                                        </div>
                                    </div>
                                </li>
                                <li class="nav-link">
                                    <div class="nav-link-root">
                                        <div class="nav-link-icon">
                                            <!-- <img class="nav-link-icon-norm" src="https://img.icons8.com/fluency/344/paper-plane.png" alt=""> -->
                                            <img class="nav-link-icon-norm" src="https://img.icons8.com/ios-glyphs/344/paper-plane.png" alt="">
                                        </div>
                                        <div class="nav-link-text">
                                            <a href="requests.php">Your requests</a>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="nav-links-cat">
                        <div class="nav-links-cat-head">
                            <p class="nav-links-cat-title">
                                New
                            </p>
                        </div>
                        <div class="nav-links-cat-body">
                            <ul class="nav-links-list">
                                <li class="nav-link">
                                    <div class="nav-link-root">
                                        <div class="nav-link-icon">
                                            <!-- <img class="nav-link-icon-norm" src="https://img.icons8.com/office/344/plus-math.png" alt=""> -->
                                            <img class="nav-link-icon-norm" src="https://img.icons8.com/external-simple-solid-edt.graphics/344/external-Plus-add-and-remove-simple-solid-edt.graphics.png" alt="">
                                        </div>
                                        <div class="nav-link-text">
                                            <a href="add-request.php">Add new request</a>
                                        </div>
                                    </div>
                                </li>
                                <li class="nav-link">
                                    <div class="nav-link-root">
                                        <div class="nav-link-icon">
                                            <img class="nav-link-icon-norm" src="https://img.icons8.com/external-simple-solid-edt.graphics/344/external-Plus-add-and-remove-simple-solid-edt.graphics.png" alt="">
                                        </div>
                                        <div class="nav-link-text">
                                            <a href="add-business.php">Add new business</a>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="nav-links-cat">
                        <div class="nav-links-cat-head">
                            <p class="nav-links-cat-title">
                                Others
                            </p>
                        </div>
                        <div class="nav-links-cat-body">
                            <ul class="nav-links-list">
                                <li class="nav-link">
                                    <div class="nav-link-root">
                                        <div class="nav-link-icon">
                                            <!-- <img class="nav-link-icon-norm" src="https://img.icons8.com/fluency/344/exit.png" alt=""> -->
                                            <img class="nav-link-icon-norm" src="https://img.icons8.com/material-rounded/344/exit.png" alt="">
                                        </div>
                                        <div class="nav-link-text">
                                            <a href="logout.php">Logout</a>
                                        </div>
                                    </div>
                                </li>
                                <li class="nav-link">
                                    <div class="nav-link-root">
                                        <div class="nav-link-icon">
                                            <!-- <img class="nav-link-icon-norm" src="https://img.icons8.com/fluency/344/settings.png" alt=""> -->
                                            <img class="nav-link-icon-norm" src="https://img.icons8.com/material-sharp/344/settings.png" alt="">
                                        </div>
                                        <div class="nav-link-text">
                                            <a href="settings.php">Settings</a>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="nav-link-root">
                                        <div class="nav-link-icon">
                                            <!-- <img class="nav-link-icon-norm" src="https://img.icons8.com/fluency/344/privacy-policy.png" alt=""> -->
                                            <img class="nav-link-icon-norm" src="https://img.icons8.com/ios-filled/344/privacy-policy.png" alt="">
                                        </div>
                                        <div class="nav-link-text">
                                            <a href="#">Privacy and policies</a>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="page-content">
					<div class="profile-content">
						<div class="profile-top pdiv">
							<div class="pt-1"></div>
							<div class="pt-2">
								<img class="profile-pic" src="<?php echo $_SESSION['user_image'] ?>" alt="Profile pic">
								<div class="pt-2-1">
									<h2 class="profile-name"><?php echo $user->fname . " " . $user->lname;?></h2>
									<p class="profile-loc"><?php echo $user->location; ?></p>
									<button class="profile-edit-btn">Edit your profile</button>
								</div>
							</div>
						</div>
						<div class="profile-middle">
							<div class="profile-about pdiv">
								<h4>About</h4>
								<p><?php echo $user->phone;?></p>
								<p><?php echo $user->location;?></p>
							</div>
							<div class="profile-stat pdiv">
								<h4>Insights</h4>
								<div>
                                    <div class="profile-stat-item profile-stat-1">
                                        <?php 
                                        $sql = "select count(*) as bcount from business where b_profile_id = '$business_id' and business_status != 2;";
                                        $business_count = $korm->rawQuery($sql)->current(); ?>
                                        <h3><?php echo $business_id ? $business_count['bcount'] : "0"; ?></h3>
                                        <p>business(es)</p>
                                    </div>
                                    <div class="profile-stat-item profile-stat-2">
                                        <?php $request_count = $korm->rawQuery("select count(*) as rcount from request where u_id = '$uid';")->current(); ?>
										<h3><?php echo $request_count['rcount']; ?></h3>
										<p>Request(s)</p>
                                    </div>
									<div class="profile-stat-item profile-stat-3">
										<h3>1</h3>
										<p>Rating(s)</p>
                                    </div>
								</div>
							</div>
						</div>
						<div class="profile-bottom">
							<div class="profile-recent-activity pdiv">
								<h4>Your recent activities</h4>
								<ul class="pra-list">
									<li class="pra-list-item">
										<div class="pra-li-root">
											<div class="pra-li-top">
												<span><i class="fa fa-laptop" aria-hidden="true"></i></span>
												<div>
													<h3 class="pra-bname">Computer Repair</h3>
													<p class="pra-boname">B. Tech Solutions</p>
												</div>
											</div>
											<div class="pra-li-bottom">
												<span class="fa fa-star checked"></span>
												<span class="fa fa-star checked"></span>
												<span class="fa fa-star checked"></span>
												<span class="fa fa-star"></span>
												<span class="fa fa-star"></span>
												<span class="pra-review">I like it</span>
												<p class="pra-date">Date</p>
											</div>
										</div>
									</li>
								</ul>
							</div>

							<div class="profile-map pdiv">
								<h4>You on map</h4>
								<p>Drag and drop to change your location</p>
								<div class="map" id="map"></div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

        <script src="../static/js/notif/notif-modal.js"></script>
        <script defer>
            updateNotifCount();
        </script>
	</body>
</html>