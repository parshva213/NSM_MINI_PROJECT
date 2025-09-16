<?php
session_start();
$title = "Login";
include 'start.php';
include 'conn.php';
$error = [];
$email = "";
$password = "";
$captcha = "";
$captchaSet = "";
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
    $password = md5($password); // Hash the password for comparison
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 0) {
        $error['email'] = "No account found with this email.";
    } else {
        foreach ($result as $user);
            if ($password !== $user['password']) {
                $error['password'] = "Incorrect password.";
        }
    }
    $dberror = implode(", ", $error);
    $sql = "INSERT INTO login_errors(email, password, captcha, error_message) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $email, $password, $captcha, $dberror);
    $stmt->execute();
    if ($stmt->error) {
        echo "<script>console.log('DB Error: " . $stmt->error . "');</script>";
    }
    if (empty($error)) {
        // Set session for dashboard
        $_SESSION['id'] = $user['id'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['full_name'] = $user['full_name'];
        $_SESSION['success'] = 'Login successful!';
        $_SESSION['data'] = 'successfully logged in'; // <-- add this here
        header('Location: dashboard.php');
        exit();
    }
}
if (isset($_SESSION['full_name']) && $_SESSION['full_name']!=="") {
    $_SESSION['data'] = isset($_SESSION['data']) ? $_SESSION['data'] : 'successfully logged in';
    header('Location: dashboard.php');
    exit();
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


                    <form action="" method="post" id="loginForm">
                        <div class="form-group">
                            <label for="loginEmail" class="form-label">Email Address</label>
                            <input name="email" type="text" class="form-control<?php echo isset($error['email']) ? " is-invalid" : "" ?>" id="loginEmail"
                                placeholder="Enter your email" autocomplete="on"
                                value="<?php echo $email; ?>">
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
                                value="<?php echo $password; ?>">
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
                                    value="<?php echo $captcha; ?>">
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
                        Don't have an account?
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
