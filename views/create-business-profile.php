<?php 
    session_start();

    require_once "../utils.php";
    require_once ROOT_DIR . "/controllers/db/kaamdaar_orm.php";
    require_once ROOT_DIR . "/models/user.php";

    $orm = new KaamdaarORM();
    $bp = $orm->getBusinessProfile($_SESSION["user_id"]);
    if($bp)
        header("location:bprofile.php");

    $route = "bprofile.php";

    if(isset($_GET['route']))
        $route = $_GET['route'];
    
    $error = [];

    // registerBusinessProfile function
    (function()
    {
        global $error;
        global $route;

        if(!isset($_POST['create-business']))
            return;

        $name = $_POST['business-name'];
        $phone = $_POST['business-phone'];
        $image = $_POST['business-phone'];

        if(empty(trim($name)))
        {
            $error['name'] = "Business name is required";
        }

        if(empty(trim($phone)))
        {
            $error['phone'] = "Business phone is required";
        }

        if(!count($error))
        {
            $orm = new KaamdaarORM();
            $business_id = random_uniqid("b.", 11);
            $SQL = "INSERT INTO business_profile(B_PROFILE_ID, B_PROFILE_NAME, B_PROFILE_IMAGE, U_ID) VALUES('$business_id', '$name', '', '" . $_SESSION['user_id'] . "');";
            $orm->connection->query($SQL);
            $orm->close();

            $_SESSION['business_id'] = $business_id;
            header("location:$route");
        }
    })();
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <link rel="stylesheet" href="../static/css/create-business-profile.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400&display=swap" rel="stylesheet">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

        <title>Create business profile</title>
    </head>
    <body>
        <div class="container">
            <div class="business-form">
                <h2>
                    Create business profile
                </h2>
                <p>
                    Create business profile to expand your businesses
                </p>
                <form class="bc-form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                    <div class="bc-form-1">
                        <div class="input-section profile-name-section">
                            <label for="bname">Business name <span class="input-op">*</span></label>
                            <input class="inputtext <?php echo isset($error['name']) ? "inputerror" : "";?>" name="business-name" type="text" placeholder="Enter your business name" value="<?php echo isset($_POST['business-name']) ? $_POST['business-name'] : ""; ?>">
                        </div>

                        <div class="input-section profile-phone-section">
                            <label for="bphone">Business phone <span class="input-op">*</span></label>
                            <input class="inputtext <?php echo isset($error['phone']) ? "inputerror" : "";?>" name="business-phone" type="text" placeholder="Enter your phone number" value="<?php echo isset($_POST['business-phone']) ? $_POST['business-phone'] : ""; ?>">
                        </div>

                        <div class="profile-image-section">
                            <label>Profile picture</label>
                            <div class="pis-1">
                                <!-- <img id="profile-img" class="profile-img" src="https://thypix.com/wp-content/uploads/2021/10/anime-avatar-profile-picture-thypix-117-700x700.jpg" alt=""> -->
                                <img id="profile-img" class="profile-img" src="<?php echo $_SESSION['user_image']; ?>" alt="">
                                <input id="input-profile" class="inputfile input-profile" name="business-profile" type="file" value="Upload new picture" accept="image/*" onchange="readURL(this);">
                                <button onclick="openFileTray();" class="upload-file-btn">Upload new picture</button>
                            </div>
                        </div>
                    </div>
                    <div class="rfloat">
                        <input class="create-business-btn" type="submit" value="Create" name="create-business">
                    </div>
                </form>
            </div>
        </div>

        <script>
            function openFileTray()
            {
                document.getElementById("input-profile").click();
            }

            function readURL(input) 
            {
                if (input.files && input.files[0]) 
                {
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        $('#profile-img').attr('src', e.target.result);
                    };

                    console.log(input.files[0]);
                    reader.readAsDataURL(input.files[0]);
                }
            }
        </script>
    </body>
</html>