<?php 
    session_start();

    require_once "../constants.php";
    require_once "../utils.php";
    require_once "k_auth.php";

    $route = "profile.php";
    $redirected = False;
    if(isset($_GET['route']))
    {
        $route = $_GET['route'];
        $redirected = True;
    }

    if(isset($_SESSION['user_phone'])) header("location:$route");
?>

<!DOCTYPE html>
<html>
    <head>    
        <title>Login - Kaamdaar</title>
        <link rel="stylesheet" href="../static/css/login.css">
    </head>
    <body>
        <?php 
        require_once ROOT_DIR . "utils.php";
        require_once ROOT_DIR . "controllers/db/kaamdaar_orm.php";

        $kdb = new KaamdaarORM();
        $error = [];

        function validate_login()
        {
            global $error;
            global $kdb;
            global $route;

            if(!isset($_POST['login']))
                return;

            if(!isset($_POST['phone']) || empty(trim($_POST['phone'])))
                $error['phone'] = "Please enter your phone to proceed.";

            if(!isset($_POST['password']) || empty(trim($_POST['password'])))
                $error['password'] = "Please enter password to proceed.";

            if(!count($error))
            {
                $user_phone = $_POST['phone'];
                $user_pass = $_POST['password'];

                $user = $kdb->getUserByPhone($user_phone);
                if($user)
                {
                    if(md5($user_pass) != md5($user->password))
                    {
                        $error['password'] = "The password you've entered is incorrect.";
                        return;
                    }

                    $business = $kdb->getBusinessProfile($user->id);

                    $user_image = $user->image;
                    if(empty(trim($user_image)))
                        $user_image = "https://www.pngitem.com/pimgs/m/146-1468843_profile-icon-orange-png-transparent-png.png";

                    if(isset($_POST['remember-me']))
                    {
                        $cookie_time = time() + (86400 * 30 * 12);

                        $HOME_URL = "/";
                        setcookie('user_phone', $user_phone, $cookie_time, $HOME_URL);
                        setcookie('user_id', $user->id, $cookie_time, $HOME_URL);
                        setcookie('user_image', $user_image, $cookie_time, $HOME_URL);

                        if($business)
                        {
                            setcookie('business_id', $business->b_profile_id, $cookie_time);
                            setcookie('business_name', $business->b_profile_name, $cookie_time);
                        }
                    }

                    $_SESSION['user_phone'] = $user_phone;
                    $_SESSION['user_id'] = $user->id;
                    $_SESSION['user_image'] = $user_image;

                    if($business)
                    {
                        $_SESSION['business_id'] = $business->b_profile_id;
                        $_SESSION['business_name'] = $business->b_profile_name;
                    }

                    header("location:" . $route);
                }
                else
                {
                    $error['phone'] = "Phone number is not registered. <a style='color: red' href='signup.php'><b>Register now.</b></a>";
                    return;
                }
            }
        }

        validate_login();
        ?>
        <div class="container">
            <p style="<?php echo "display:" . ($redirected ? "block" : "none"); ?>" class="redirected-msg">&#9432; You must login to continue</p>

            <form class="login-form" action="<?php echo $_SERVER['PHP_SELF'] . "?route=$route"; ?>" method="POST">
                <h1>Login</h1>
                <?php $phone_value = isset($_POST['phone']) ? $_POST['phone'] : ''; ?>
                <input class="user-phone" type="text" placeholder="Enter your phone" name="phone" value="<?php echo $phone_value;?>">
                <?php 
                    if(isset($error['phone'])) { 
                ?>
                    <span class="error"> 
                        <?php echo $error['phone']; ?>
                    </span>
                <?php 
                    }
                    
                ?>

                <?php $pass_value = isset($_POST['password']) ? $_POST['password'] : ''; ?>
                <input type="password" class="user-pass" placeholder="Enter your password" name="password" value="<?php echo $pass_value; ?>">
                <?php 
                    if(isset($error['password'])) { 
                ?>
                    <span class="error"> 
                        <?php echo $error['password']; ?>
                    </span>
                <?php 
                    }
                ?>

                <div>
                    <input class="remember-me" type="checkbox" name="remember-me">
                    <span id="rm">Remember Me</span>
                </div>

                <input type="submit" name="login" value="Login" id="login-btn">
                <br>

                <a class="acnt-recover" href="recover.php">Forgot password?</a>

                <hr style="width:85%">

                <div class="create-acnt">
                    <a href="signup.php" class="create-acnt-btn">Create account</a>
                </div>
            </form>
        </div>
    </body>
</html>