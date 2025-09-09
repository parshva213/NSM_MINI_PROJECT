<?php
session_start();
$title = "Login";
include 'start.php';
?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="text-center">LOGIN</h3>
                </div>
                <div class="card-body">
                    <!-- <?php if (isset($success)): ?>
                    <div class="alert alert-success">
                        <?php echo htmlspecialchars($success); ?>
                    </div>
                    <?php endif; ?> -->

                    <form action="" method="post" id="loginForm">
                        <div class="form-group">
                            <label for="loginEmail" class="form-label">Email Address</label>
                            <input name="email" type="text" class="form-control" id="loginEmail"
                                placeholder="Enter your email"
                                value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                        </div>

                        <div class="form-group">
                            <label for="loginPassword" class="form-label">Password</label>
                            <input name="password" type="password" class="form-control" id="loginPassword"
                                placeholder="Enter your password">
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
                                <input type="text" name="captcha" class="form-control" id="loginCaptchaInput"
                                    placeholder="Enter the captcha code">
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
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $captcha = $_POST['captcha'];
    $captchaSet = $_POST['captchaSet'];
    $error = [];
    
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
    echo "<script>console.log('$dberror');</script>";
}
?>
<!-- <script>
    $(document).ready(function() {
        $('#loginForm').validate({
            rules: {
                email: {
                    required: true,
                    email: true
                },
                password: {
                    required: true,
                    minlength: 6,
                    maxlength: 12
                },
                captcha: {
                    required: true
                }
            },
            messages: {
                email: {
                    required: "Email is required",
                    email: "Please enter a valid email address"
                },
                password: {
                    required: "Password is required",
                    minlength: "Password must be at least 6 characters long",
                    maxlength: "Password must be at most 12 characters long"
                },
                captcha: {
                    required: "Captcha is required"
                }
            }
        });
    });
</script> -->