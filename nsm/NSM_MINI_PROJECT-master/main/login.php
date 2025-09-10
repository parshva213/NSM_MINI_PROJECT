<?php
session_start();
$title = "Login";
include 'start.php';
include 'conn.php';
$error = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $captcha = $_POST['captcha'];
    $captchaSet = $_POST['captchaSet'];
    
    if ($email == ""){
        $error['email'] = "Email is required";
    }elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $error['email'] = "Please enter a valid email address";
    }
    if ($password == ""){
        $error['password'] = "Password is required";
    } elseif(strlen($password) < 6){
        $error['password'] = "Password must be at least 6 characters long";
    } elseif(strlen($password) > 12){
        $error['password'] = "Password must be at most 12 characters long";
    }
    if ($captcha == ""){
        $error['captcha'] = "Captcha is required";
    }elseif($captchaSet != $captcha){
        $error['captcha'] = "Captcha is incorrect";
    }

    $dberror = implode(",", $error);
    $sql = "INSERT INTO login_errors(email,password,captcha,error_message) VALUES ('$email','$password','$captcha','$dberror')";
    if (isset($conn) && $conn) {
        mysqli_query($conn, $sql);
    }

    if (!empty($error)) {
        echo "false";
    } else {
        // $success = "Login successful!";
        ?><script>
        <?php if (isset($success)): ?>
        showSuccessPopup('<?php echo addslashes($success); ?>', 10000);
        <?php endif; ?>
        </script><?php
    }
    
    ?>
    <script>
        console.log('<?php foreach ($error as $key => $value) {
            echo $key . ": " . $value . "\\n";
        } ?>');
        console.log('Captcha Set: <?php echo $captchaSet; ?>, User Input: <?php echo $captcha; ?>');

    </script>
    <?php
}
?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="text-center">LOGIN</h3>
                </div>
                <div class="card-body">
                    <?php if (isset($success)): ?>
                    <div class="alert alert-success">
                        <?php echo htmlspecialchars($success); ?>
                    </div>
                    <?php endif; ?>

                    <form action="" method="post" id="loginForm">
                        <div class="form-group">
                            <label for="loginEmail" class="form-label">Email Address</label>
                            <input name="email" type="text" class="form-control<?php echo isset($error['email']) ? " is-invalid" : "" ?>" id="loginEmail"
                                placeholder="Enter your email" autocomplete="on"
                                value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                            <div class="error">
                                <?php  
                                    echo $error['email']??"";
                                ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="loginPassword" class="form-label">Password</label>
                            <input name="password" type="password" class="form-control<?php echo isset($error['password']) ? " is-invalid" : "" ?>" id="loginPassword"
                                placeholder="Enter your password"
                                value="<?php echo isset($_POST['password']) ? htmlspecialchars($_POST['password']) : ''; ?>">
                            <div class="error">
                                <?php  
                                    echo $error['password']??"";
                                ?>
                            </div>
                        </div>

                        <!-- Captcha Section -->
                        <div class="mb-3">
                            <label class="form-label">Enter the code below:</label>
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <div class="captcha-display" id="loginCaptchaDisplay" title="Captcha"></div>
                                <input type="hidden" name="captchaSet" id="loginCaptchaSet" value="">
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-outline-secondary btn-sm"
                                        id="reloadLoginCaptcha" title="Refresh Captcha">
                                        <i class="bi bi-arrow-clockwise">↻</i>
                                    </button>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="loginCaptchaInput" class="form-label">Captcha</label>
                                <input type="text" name="captcha" class="form-control<?php echo isset($error['captcha']) ? " is-invalid" : "" ?>" id="loginCaptchaInput"
                                    placeholder="Enter the captcha code"
                                    value="<?php echo isset($_POST['captcha']) ? htmlspecialchars($_POST['captcha']) : ''; ?>">
                                <div class="error">
                                <?php  
                                    echo $error['captcha']??"";
                                ?>
                            </div>
                            </div>
                        </div>

                        <button type="submit" name="login" class="btn btn-primary w-100">Login</button>
                    </form>
                    <div class="mt-3 text-center">
                        <a href="forgot-password.php" class="text-decoration-none">Forgot Password?</a>
                        <span class="mx-2">|</span>
                        <a href="register.php" class="text-decoration-none">Create Account</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
include 'end.php';
?>
<script>
    // $('#loginForm').validate({
        // rules: {
        //     captcha: {
        //         required: true,
        //         customCaptcha: function () {
        //             const input = $('#loginCaptchaInput').val();
        //             const expected = $('#loginCaptchaSet').val();
        //             return input === expected; // Case-sensitive validation
        //         }
        //     }
        // },
        // messages: {
        //     captcha: {
        //         required: "Please enter the captcha",
        //         customCaptcha: "Captcha code is incorrect"
        //     }
        // },
    //     errorClass: "error",
    //     highlight: function (element) {
    //         $(element).addClass('error');
    //     },
    //     unhighlight: function (element) {
    //         $(element).removeClass('error');
    //     },
    //     submitHandler: function (form) {
    //         // Show success popup for 10 seconds
    //         showSuccessPopup('Login form submitted successfully!', 10000);
    //         return true;
    //     }
    // });
</script>