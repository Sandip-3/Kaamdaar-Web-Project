<?php 
    session_start();

    $route = '';
    $session_id = '';
    $phone = '';
    if(isset($_GET['redirect']) && (isset($_SESSION['vp-session-id']) && isset($_SESSION['phone'])))
    {
        $route = $_GET['redirect'];
        $session_id = $_SESSION['vp-session-id'];
        $phone = $_SESSION['phone'];
    }
    else 
    {
        require_once("./error/access-error.html");
        die();
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="../static/css/verify-phone.css">
    </head>
    <body>
        <div class="header">
            <h1>Kaamdaar</h1>
        </div>

        <div class="container">
            <h2>Enter security code</h2>
            <hr width="100%">
            <p>Please solve the following captcha and check your phone for a message with your code. Your code is 6 numbers long.</p>
            <div id="captcha-container"></div>
            <input id="code-entry" type="text" placeholder="Enter code">
            <?php  
            $phone_len = strlen($phone);
            ?>
            <span>We will send your code to: <?php echo $phone[0].$phone[1] . "******" . $phone[$phone_len - 2].$phone[$phone_len - 1]; ?></span>

            <div class="code-resend">
                <span id="resend-time-left"></span>
            </div>

            <div class="error-field">
                <p id="error-head"></p>
                <p id="error-body"></p>
            </div>

            <hr width="100%">

            <div class="rfloat">
                <button class="form-btn cancel" onclick="location.href = '/';">Cancel</button>
                <button class="form-btn continue" onclick="verifyOTP();">Continue</button>
            </div>
        </div>

        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/firebase/7.14.1-0/firebase.js">
        </script>

        <script>
            const firebaseConfig = {
                apiKey: "AIzaSyBassjVLCwvNoalGeiqUiijVfIHKjxIDqI",
                authDomain: "kaamdaar-a076d.firebaseapp.com",
                projectId: "kaamdaar-a076d",
                storageBucket: "kaamdaar-a076d.appspot.com",
                messagingSenderId: "439619740989",
                appId: "1:439619740989:web:4343756f43ce9442525154",
            };

            firebase.initializeApp(firebaseConfig);

            (function renderreCaptcha()
            {
                window.recaptchaVerifier = new firebase.auth.RecaptchaVerifier('captcha-container', {
                    size: "normal",
                    callback: sendOTP
                });
                window.recaptchaVerifier.render();
            })();

            function sendOTP()
            {
                const phone = "<?php echo $phone; ?>";
                console.log(`Sending OTP to ${phone}`);
                firebase.auth().signInWithPhoneNumber(phone, window.recaptchaVerifier)
                    .then(confirmationResult => {
                        window.confirmationResult = confirmationResult;
                        document.getElementsByClassName("error-field")[0].style.display = "none";
                        let resendTime = document.getElementById('resend-time-left');
                        var countdown = 60;
                        setInterval(() => {
                            resendTime.innerHTML = `Resend in <b>${countdown}</b>`;
                            countdown--;
                        }, 1000);
                    })
                    .catch(error => {
                        let errorField = document.getElementsByClassName("error-field")[0];
                        errorField.style.display = "block";
                        let errorHead = document.getElementById('error-head');
                        let errorBody = document.getElementById('error-body');

                        errorHead.innerHTML = error.code;
                        errorBody.innerHTML = error.message;
                    });
            };

            function verifyOTP()
            {
                location.href = '<?php echo $route . "?verified=1&vpsi=$session_id"; ?>';
                // const otp = document.getElementById("code-entry").value;
                // window.confirmationResult.confirm(otp)
                //     .then(() => {
                //         location.href = '<?php echo $route . "?verified=1&vpsi=$session_id"; ?>';
                //     })
                //     .catch(() => {
                //         document.getElementById('error-msg').innerHTML = "<p style='color: red;'>Couldn't verify phone. <b>Resend code</b>";
                //     });
            }
        </script>
    </body>
</html>