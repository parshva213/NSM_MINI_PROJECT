<?php
session_start();
$title = "Register";
include 'start.php';
include 'conn.php';
$cookie = 0;
$error = [];
$fullName = "";
$email = "";
$password = "";
$confirmPassword = "";
$captcha = "";
$captchaSet = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = $_POST['fullName'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];
    $captcha = $_POST['captcha'];
    $captchaSet = $_POST['captchaSet'];
    
    if ($fullName == ""){
        $error['fullName'] = "Name is required";
    }

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
    if ($confirmPassword == ""){
        $error['confirmPassword'] = "Confirm Password is required";
    } elseif($password !== $confirmPassword){
        $error['confirmPassword'] = "Passwords do not match";
    }
    if ($captcha == ""){
        $error['captcha'] = "Captcha is required";
    }elseif($captchaSet != $captcha){
        $error['captcha'] = "Captcha is incorrect";
    }

    $dberror = implode(",", $error);
    $password = md5($password);
    $confirmPassword = md5($confirmPassword);
    $sql = "INSERT INTO register_errors(email, full_name, password, confirm_password, captcha, error_message) VALUES ('$email','$fullName','$password','$confirmPassword','$captcha','$dberror')";
    if (isset($conn) && $conn) {
        mysqli_query($conn, $sql);
    }

    if (empty($error)) {
        $sql="SELECT * FROM users WHERE email='$email'";
        $result=mysqli_query($conn,$sql);
        if (mysqli_num_rows($result) > 0) {
            $result = mysqli_fetch_assoc($result);
            $_SESSION['email'] = $result['email'];
            $_SESSION['id'] = $result['id'];
            $_SESSION['full_name'] = $result['full_name'];
            $_SESSION['success'] = "You are already registered! Please log in.";
            $_SESSION['data'] = 'already registered';
            $cookie = 1;

            
        } else {
            // Only insert if no errors
            $sql = "INSERT INTO users (email, full_name, password) VALUES ('$email', '$fullName', '$password')";
            if (isset($conn) && $conn) {
                if (mysqli_query($conn, $sql)) {
                    $_SESSION['email'] = $email;
                    $_SESSION['full_name'] = $fullName;
                    $_SESSION['success'] = "Registration successful!";
                    $_SESSION['data'] = 'successfully registered';
                    $cookie = 1;
                } else {
                    // $error['database'] = "Error: " . mysqli_error($conn);
                    echo "<script>console.log('Database Error: " . mysqli_error($conn) . "');</script>";
                }
            } else {
                // $error['database'] = "Database connection error.";
                echo "<script>console.log('Database Connection Error');</script>";
            }
        }
    }
}
if ($cookie == 1) {
    echo "<script>window.location.href='login.php';</script>";
    exit();
}
?>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-center">REGISTER</h3>
                    </div>
                    <div class="card-body">

                        
                        <form action="" method="post" id="registerForm">
                            <div class="form-group">
                                <label for="registerFullName" class="form-label">Full Name</label>
                                <input name="fullName" type="text" class="form-control<?php echo isset($error['fullName']) ? ' is-invalid' : '' ?>" id="registerFullName"
                                    placeholder="Enter your full name"
                                    value="<?php echo isset($_POST['fullName']) ? htmlspecialchars($_POST['fullName']) : ''; ?>">
                                <div class="error">
                                    <?php echo $error['fullName']??""; ?>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="registerEmail" class="form-label">Email address</label>
                                <input name="email" type="text" class="form-control<?php echo isset($error['email']) ? ' is-invalid' : '' ?>" id="registerEmail"
                                    placeholder="Enter your email"
                                    value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                                <div class="error">
                                    <?php echo $error['email']??""; ?>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="registerPassword" class="form-label">Password</label>
                                <input name="password" type="password" class="form-control<?php echo isset($error['password']) ? ' is-invalid' : '' ?>" id="registerPassword"
                                    placeholder="Enter your password">
                                <div class="error">
                                    <?php echo $error['password']??""; ?>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="registerConfirmPassword" class="form-label">Confirm Password</label>
                                <input name="confirmPassword" type="password" class="form-control<?php echo isset($error['confirmPassword']) ? ' is-invalid' : '' ?>" id="registerConfirmPassword"
                                    placeholder="Confirm your password">
                                <div class="error">
                                    <?php echo $error['confirmPassword']??""; ?>
                                </div>
                            </div>
                            
                            <!-- Captcha Section -->
                            <div class="mb-3">
                                <label class="form-label">Enter the code below:</label>
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <div class="captcha-display" id="registerCaptchaDisplay"></div>
                                    <input type="hidden" name="captchaSet" id="registerCaptchaSet" value="">
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-outline-secondary btn-sm"
                                            id="reloadRegisterCaptcha" title="Refresh Captcha">
                                            <i class="bi bi-arrow-clockwise">↻</i>
                                        </button>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="registerCaptchaInput" class="form-label">Captcha</label>
                                    <input type="text" name="captcha" class="form-control<?php echo isset($error['captcha']) ? ' is-invalid' : '' ?>" id="registerCaptchaInput"
                                        placeholder="Enter the captcha code"
                                        value="<?php echo isset($_POST['captcha']) ? htmlspecialchars($_POST['captcha']) : ''; ?>">
                                    <div class="error">
                                        <?php echo $error['captcha']??""; ?>
                                    </div>
                                </div>
                            </div>
                            
                            <button type="submit" name="register" class="btn btn-success w-100">Register</button>
                        </form>
                        
                        <div class="mt-3 text-center">
                            <span>Already have an account?</span>
                            <a href="login.php" class="text-decoration-none ms-1">Login here</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php include 'end.php' ?>
    
    <!-- <script>
        $(document).ready(function() {
            // Initialize register captcha
            if (typeof captchaSystem !== 'undefined') {
                captchaSystem.generateCaptcha('register');
            }
            
            // Form validation
            $('#registerForm').validate({
                rules: {
                    fullName: {
                        required: true,
                        minlength: 2
                    },
                    email: {
                        required: true,
                        email: true
                    },
                    password: {
                        required: true,
                        minlength: 6
                    },
                    confirmPassword: {
                        required: true,
                        equalTo: '#registerPassword'
                    },
                    captcha: {
                        required: true,
                        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                            $fullName = $_POST['fullName'] ?? NULL;
                            $email = $_POST['email'] ?? NULL;
                            $password = $_POST['password'] ?? NULL;
                            $confirmPassword = $_POST['confirmPassword'] ?? NULL;
                            $captcha = $_POST['captcha'] ?? NULL;
                            $captchaSet = $_POST['captchaSet'] ?? NULL;

                            $rules = [
                                'fullName' => [required('Name is required')],
                                'email' => [required('Email is required'), email('Please enter a valid email address')],
                                'password' => [required('Password is required'), minLength(6, 'Password must be at least 6 characters long'), maxLength(12, 'Password must be at most 12 characters long')],
                                'confirmPassword' => [required('Confirm Password is required'), matchField('password', 'Passwords do not match')],
                                'captcha' => [required('Captcha is required'), function($value, $data) use ($captchaSet) { return ($value === $captchaSet) ? true : 'Captcha is incorrect'; }],
                            ];
                            $error = validate($_POST, $rules);

                            $dberror = implode(",", $error);
                            $password = md5($password);
                            $confirmPassword = md5($confirmPassword);
                            $sql = "INSERT INTO register_errors(email, full_name, password, confirm_password, captcha, error_message) VALUES ('$email','$fullName','$password','$confirmPassword','$captcha','$dberror')";
                            if (isset($conn) && $conn) {
                                mysqli_query($conn, $sql);
                            }

                            if (empty($error)) {
                                $sql="SELECT * FROM users WHERE email='$email'";
                                $result=mysqli_query($conn,$sql);
                                if (mysqli_num_rows($result) > 0) {
                                    $result = mysqli_fetch_assoc($result);
                                    $_SESSION['email'] = $result['email'];
                                    $_SESSION['id'] = $result['id'];
                                    $_SESSION['full_name'] = $result['full_name'];
                                    echo "<script>window.location.href='login.php';</script>";
                                    exit();
                                } else {
                                    // Only insert if no errors
                                    $sql = "INSERT INTO users (email, full_name, password) VALUES ('$email', '$fullName', '$password')";
                                    if (isset($conn) && $conn) {
                                        if (mysqli_query($conn, $sql)) {
                                            $success = "Registration successful! You can now log in.";
                                            // Set session for dashboard
                                            $_SESSION['full_name'] = $fullName;
                                            header('Location: dashboard.php');
                                            exit();
                                        } else {
                                            echo "<script>console.log('Database Error: " . mysqli_error($conn) . "');</script>";
                                        }
                                    } else {
                                        echo "<script>console.log('Database Connection Error');</script>";
                                    }
                                }
                            }