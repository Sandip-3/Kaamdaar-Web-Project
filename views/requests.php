<?php 
	require_once '../constants.php';
    require_once "k_auth.php";
    require_once ROOT_DIR . "controllers/db/kaamdaar_orm.php";
    require_once "./request-constants.php";

	session_start();
	if(!isset($_SESSION['user_phone'])) header('location:login.php');

    $orm = new KaamdaarORM();
?>

<!DOCTYPE html>
<html>
    <head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400&display=swap" rel="stylesheet">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script src="../static/js/modal.js"></script>
		
        <link rel="stylesheet" href="../static/css/base/layout.css">
		<link rel="stylesheet" href="../static/css/requests.css">
        <link rel="stylesheet" href="../static/css/modal/notif-modal.css">

		<title>Your requests</title>
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
                        <div class="head-icon-section head-notif-section" onclick="showModal('notif-modal');">
                            <span class="notif-count">
                                3
                            </span>
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
                                <li class="nav-link">
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
                                <li class="nav-link nav-link-active">
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
                    <div class="delete-request-modal" style="display: none">
                        <div class="delete-request-modal--content">
                            <p id="delete-request-modal--msg" class="delete-request-modal--msg">
                            </p>
                            <div class="delete-request-modal--acts rfloat">
                                <button class="delete-request-modal--button delete-request-modal--cancel" onclick="document.getElementsByClassName('delete-request-modal')[0].style.display = 'none';">CANCEL</button>
                                <button class="delete-request-modal--button delete-request-modal--ok">CLOSE</button>
                            </div>
                        </div>
                    </div>

                    <div id="request-options-container" class="request-options-container" style="display: none;">
                        <div id="request-options" class="request-options">
                            <ul class="ro-list" style="list-style-type: none;">
                                <li id="ro-delete" class="ro-item ro-delete">
                                    <i class="fa fa-trash" style="font-size:24px"></i> Delete request
                                </li>
                            </ul>
                        </div>
                    </div>

                    <?php 
                        $all_requests = $orm->getAllRequests($_SESSION['user_id']);
                    ?>
                    <div class="req-list-container">
                        <div class="sort">
                            Sort by
                            <select name="" id="">
                                <option value="" disabled selected>
                                    Sort by
                                </option>
                                <option value="date">
                                    Date
                                </option>
                            </select>
                        </div>
                        <ul class="req-list">
                            <?php foreach($all_requests as $request) { ?>
                            <li class="req-list-item">
                                <div class="rli-root">
                                    <div class="rli-head">
                                        <img id="request-icon" class="request-icon" src="<?php echo $request['BR_CAT_ICON']; ?>" alt="request icon">
                                        <span>
                                            <strong><?php echo $request['BR_CAT_NAME']; ?></strong>
                                            <span class="u-lvl"><?php echo $request['REQUEST_URGENCY'] == "1" ? "Urgent" : ""; ?></span>
                                        </span>
                                        <div class="act-btns">
                                            <button class="view-on-map-btn">View on map <i class="fa fa-map" style="font-size:20px;"></i></button>
                                            <i class="fa fa-ellipsis-v td-icon" style="font-size:24px" onclick="showRequestOptions(<?php echo $request['REQUEST_ID']; ?>);"></i>
                                        </div>
                                    </div>
                                    <div class="rli-body">
                                        <div class="req-info">
                                            <span><?php echo date('M j Y g:i A', strtotime($request['REQUEST_TIME'])); ?></span>
                                            &#8226;
                                            <span><?php echo $request['REQUEST_LOCATION'];; ?></span>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <script>
            function showRequestOptions(rid)
            {
                let reqOptionsContainer = document.getElementById("request-options-container");
                reqOptionsContainer.style.display = "block";

                const X = `${(window.event.pageX - 200)}px`;
                const Y = `${(window.event.pageY)}px`;
                
                let reqOptions = document.getElementById("request-options");
                reqOptions.style.position = "absolute";
                reqOptions.style.left = X;
                reqOptions.style.top = Y;

                let closeOption = document.getElementById("ro-delete");
                closeOption.addEventListener("click", () => openRequestDeleteDialog(rid));

                window.onclick = (event) => {
                    if(event.target == reqOptionsContainer)
                        reqOptionsContainer.style.display = "none";
                };
            }

            function openRequestDeleteDialog(rid)
            {
                let closeModal = document.getElementsByClassName("delete-request-modal")[0];
                let message = document.getElementById("delete-request-modal--msg");
                closeModal.style.display = "block";

                let okButton = document.getElementsByClassName("delete-request-modal--ok")[0];
                let newStatus;
                message.innerText = "Are you sure you want to delete this request?";
                okButton.innerText = "DELETE";
                newStatus = 2;

                okButton.addEventListener("click", function deleteRequest() {
                    let xhr = new XMLHttpRequest();
                    const url = `./request/update-request-status.php?rid=${rid}`;
                    xhr.open("GET", url, true);
                    xhr.send();

                    okButton.removeEventListener("click", deleteRequest);
                    window.location.reload();
                })

                window.onclick = (event) => {
                    if(event.target == closeModal)
                        closeModal.style.display = "none";
                }
            }
        </script>
    </body>
</html>