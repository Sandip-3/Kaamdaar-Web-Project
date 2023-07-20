<?php 
    require_once "k_auth.php";

    session_start();
	if(!isset($_SESSION['user_phone'])) header('location:login.php?route=bprofile.php');

    require_once "../controllers/db/kaamdaar_orm.php";
    require_once "../models/business-profile.php";

    const business_icons = array(
        'plumber' => '../static/icons/business/plumber.png',
        'Auto Mobile Repair' => 'https://www.svgrepo.com/show/52022/car-repair.svg'
    );

    $kdb = new KaamdaarORM();
    $bp = $kdb->getBusinessProfile($_SESSION["user_id"]);
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
		<link rel="stylesheet" href="../static/css/business.css">
        <link rel="stylesheet" href="../static/css/modal/notif-modal.css">

		<title>Business Profile</title>
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
                                <li class="nav-link nav-link-active">
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
                    <?php if(!$bp) { ?>
                            <div class='no-bprofile-msg'>
                                <p>You haven't set up your business profile.</p>
                                <button class="setup-business-btn" onclick="location.href = 'create-business-profile.php';">Setup</button>
                            </div>
                    <?php 
                        die();
                    } ?>

                    <div class="business-profile">
                        <div class="profile-top pdiv">
                            <div class="pt-1"></div>
                            <div class="pt-2">
                                <img class="profile-pic" src="../static/images/profile.jpg" alt="Profile pic">
                                <div class="pt-2-1">
                                    <h2 class="profile-name"><?php echo $bp->b_profile_name; ?></h2>
                                    <button class="profile-edit-btn">Edit your profile</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="close-business-modal">
                        <div class="close-business-modal--content">
                            <p id="close-business-modal--msg" class="close-business-modal--msg">
                            </p>
                            <div class="close-business-modal--acts rfloat">
                                <button class="close-business-modal--button close-business-modal--cancel" onclick="document.getElementsByClassName('close-business-modal')[0].style.display = 'none';">CANCEL</button>
                                <button class="close-business-modal--button close-business-modal--ok">CLOSE</button>
                            </div>
                        </div>
                    </div>

                    <div id="business-options-container" class="business-options-container" style="display: none;">
                        <div id="business-options" class="business-options">
                            <ul class="bo-list" style="list-style-type: none;">
                                <li id="bo-suspend" class="bo-item bo-suspend">
                                    <i class="fa fa-eye-slash" style="font-size:24px"></i> Suspend business
                                </li>
                                <li id="bo-close" class="bo-item bo-close">
                                    <i class="fa fa-trash" style="font-size:24px"></i> Close business
                                </li>
                            </ul>
                        </div>
                    </div>

                    <script>
                        var businessTypes = [];
                        var totalServed = [];
                        var rating = [];
                        var revenue = [];
                    </script>

                    <ul class="bl">
                    <?php 
                    $businesses = $kdb->getAllBusinessInfo($bp->b_profile_id);
                    $totalBusinesses = 0;

                    if(count($businesses) < 1)
                    {
                        echo "You don't own any business. <a href='add-business.php'>Start</a> by creating one.";
                        die('');
                    }

                    foreach($businesses as $bus)
                    {
                        $totalBusinesses++;
                    ?>
                        <li class="bli">
                            <div class="bli-root">
                                <div class="bli-head">
                                    <?php $type = $bus->business_type;?>
                                    <img src="<?php echo $business_icons[$type];?>" alt="Icon">
                                    <script>businessTypes.push("<?php echo $type; ?>");</script>
                                    <span>
                                        <strong><?php echo ucwords($type);?></strong> <span class="bli-status"><?php echo ($bus->business_status == "1" ? "<span>(Suspended)</span>" : ""); ?></span>
                                    </span>
                                    <i class="fa fa-ellipsis-v td-icon" style="font-size:24px" id="td-icon" onclick="showBusinessOptions('<?php echo $bus->business_id; ?>');"></i>
                                </div>
                                <div class="bli-stat">
                                    <div class="bli-st-i bli-total">
                                        <p>Total served</p>
                                        <p><strong><?php echo $bus->business_total; ?></strong></p>
                                        <script>totalServed.push([Number("<?php echo $bus->business_total;?>")]);</script>
                                        <p>On last 30 days</p>
                                    </div>
                                    <div class="bli-st-i bli-rev">
                                        <p>Gross revenue</p>
                                        <p><strong><?php echo $bus->business_revenue; ?></strong></p>
                                        <script>revenue.push([Number("<?php echo $bus->business_revenue;?>")]);</script>
                                    </div>
                                    <div class="bli-st-i bli-rating">
                                        <p>Rating</p>
                                        <p><strong><?php echo $bus->business_rating; ?></strong></p>
                                        <script>rating.push([parseFloat("<?php echo $bus->business_total;?>")]);</script>
                                        <span class="fa fa-star checked"></span>
                                        <span class="fa fa-star checked"></span>
                                        <span class="fa fa-star checked"></span>
                                        <span class="fa fa-star"></span>
                                        <span class="fa fa-star"></span>
                                    </div>
                                </div>
                            </div>
                        </li>
                    <?php
                        }
                    ?>
                    </ul>

                    <div>
                        <h2><?php echo $totalBusinesses . " business" . ($totalBusinesses > 1 ? "es" : "");?></h2>
                    </div>

                    <div class="business-analytics">
                        <div>
                            <select name="chart-options" id="chart-options">
                                <option value="rev">
                                    Revenue
                                </option>
                                <option value="rat">
                                    Rating
                                </option>
                                <option value="tot" selected>
                                    Total Served
                                </option>
                            </select>
                        </div>
                        <canvas id="business-analytics-chart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.js"></script>
        <script src="business-chart.js"></script>
        <script>
            function showBusinessOptions(bid)
            {
                let boOptionsContainer = document.getElementById("business-options-container");
                boOptionsContainer.style.display = "block";

                const X = `${(window.event.pageX - 200)}px`;
                const Y = `${(window.event.pageY)}px`;
                
                let boOptions = document.getElementById("business-options");
                boOptions.style.position = "absolute";
                boOptions.style.left = X;
                boOptions.style.top = Y;

                let closeOption = document.getElementById("bo-close");
                closeOption.addEventListener("click", () => openBusinessActionDialog("close", bid));

                let suspendOption = document.getElementById("bo-suspend");
                suspendOption.addEventListener("click", () => openBusinessActionDialog("suspend", bid));

                window.onclick = (event) => {
                    if(event.target == boOptionsContainer)
                        boOptionsContainer.style.display = "none";
                };
            }

            function openBusinessActionDialog(action, bid)
            {
                let closeModal = document.getElementsByClassName("close-business-modal")[0];
                let message = document.getElementById("close-business-modal--msg");
                closeModal.style.display = "block";

                let okButton = document.getElementsByClassName("close-business-modal--ok")[0];
                let newStatus;
                if(action === "suspend")
                {
                    message.innerText = "Are you sure you want to suspend this business? You can continue this business later.";
                    okButton.innerText = "SUSPEND";
                    newStatus = 1;
                }
                else if(action === "close")
                {
                    message.innerText = "Are you sure you want to close this business? You will lose every details associated with this business and no longer receive notifications for this business.";
                    okButton.innerText = "CLOSE";
                    newStatus = 2;
                }

                okButton.addEventListener("click", function closeBusiness() {
                    let xhr = new XMLHttpRequest();
                    const url = `./business/update-business-status.php?bid=${bid}&status=${newStatus}`;
                    console.log(url);
                    xhr.open("GET", url, true);
                    xhr.send();

                    okButton.removeEventListener("click", closeBusiness);
                    window.location.reload();
                })

                window.onclick = (event) => {
                    if(event.target == closeModal)
                        closeModal.style.display = "none";
                }
            }

            let label = "Total Served";
            let data = totalServed;
            const context = document.getElementById("business-analytics-chart").getContext("2d");
            let chart = generateChart(context, label, businessTypes, data);

            var businessAnalyticsOptions = document.getElementById("chart-options");
            businessAnalyticsOptions.addEventListener("change", function() {
                let chosen = this.value;
                if(chosen === "rat")
                {
                    label = "Rating";
                    data = rating;
                }
                else if(chosen === "tot")
                {
                    label = "Total served";
                    data = totalServed;
                }
                else if(chosen === "rev")
                {
                    label = "Revenue";
                    data = revenue;
                }
                updateChart(chart, label, businessTypes, data);
            });
        </script>
    </body>
</html>