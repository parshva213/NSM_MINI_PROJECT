<?php
session_start();
require_once 'conn.php';
// Process login form
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $captcha = trim($_POST['captcha']);
    $captchaSet = $_POST['captchaSet'];
    
    $errors = [];
    
    // Validate email
    if (empty($email)) {
        $errors['email'] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Please enter a valid email address";
    }
    
    // Validate password
    if (empty($password)) {
        $errors['password'] = "Password is required";
    } elseif (strlen($password) < 6) {
        $errors['password'] = "Password must be at least 6 characters";
    } elseif (strlen($password) > 12) {
        $errors['password'] = "Password must not exceed 12 characters";
    }
    
    // Validate CAPTCHA
    if (empty($captcha)) {
        $errors['captcha'] = "CAPTCHA is required";
    } elseif ($captcha !== $captchaSet) {
        $errors['captcha'] = "CAPTCHA code is incorrect";
    }
    
    // If there are errors, log them to login_errors table
    if (!empty($errors)) {
        $error_message = implode('; ', array_values($errors));
        $stmt = $conn->prepare("INSERT INTO login_errors (email, password, error_message) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $email, $password, $error_message);
        $stmt->execute();
        if ($stmt->error) {
            error_log("MySQL error: " . $stmt->error);
        }
        $stmt->close();
    }
    // If no errors, process login
    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT id, password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($user_id, $hashedPassword);
            $stmt->fetch();
            if (password_verify($password, $hashedPassword)) {
                $success = "Login successful!";
                // $_SESSION['user_id'] = $user_id;
                // $_SESSION['user_email'] = $email;
                // header('Location: dashboard.php');
                // exit();
            } else {
                $errors['password'] = "Incorrect password.";
                // Log this error as well
                $error_message = $errors['password'];
                $stmt2 = $conn->prepare("INSERT INTO login_errors (email, password, error_message) VALUES (?, ?, ?)");
                $stmt2->bind_param("sss", $email, $password, $error_message);
                $stmt2->execute();
                if ($stmt2->error) {
                    error_log("MySQL error: " . $stmt2->error);
                }
                $stmt2->close();
            }
        } else {
            $errors['email'] = "No account found with that email.";
            // Log this error as well
            $error_message = $errors['email'];
            $stmt2 = $conn->prepare("INSERT INTO login_errors (email, password, error_message) VALUES (?, ?, ?)");
            $stmt2->bind_param("sss", $email, $password, $error_message);
            $stmt2->execute();
            if ($stmt2->error) {
                error_log("MySQL error: " . $stmt2->error);
            }
            $stmt2->close();
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'need.php' ?>
    <meta name="description" content="Login to your account">
    <title>Login - CAPTCHA System</title>
</head>
<body>
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
                                <label for="loginEmail" class="form-label">Email address</label>
                                <input name="email" type="email" class="form-control" id="loginEmail"
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
                                        <button type="button" class="btn btn-outline-info btn-sm" id="audioLoginCaptcha"
                                            title="Audio Captcha">
                                            🔊
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

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Enhanced Captcha System -->
    <script src="captcha.js" defer></script>
    
    <script>
        $(document).ready(function() {
            // Initialize login captcha
            if (typeof captchaSystem !== 'undefined') {
                captchaSystem.generateCaptcha('login');
            }
            
            // Form validation
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
                        required: true,
                        customCaptcha: function() {
                            return $('#loginCaptchaInput').val() === $('#loginCaptchaSet').val();
                        }
                    }
                },
                messages: {
                    email: {
                        required: "Please enter your email",
                        email: "Please enter a valid email"
                    },
                    password: {
                        required: "Please enter your password",
                        minlength: "Password must be at least 6 characters",
                        maxlength: "Password must not exceed 12 characters"
                    },
                    captcha: {
                        required: "Please enter the captcha",
                        customCaptcha: "Captcha code is incorrect"
                    }
                }
            });
            
            // Add custom validation method
            $.validator.addMethod('customCaptcha', function(value, element) {
                return value === $('#loginCaptchaSet').val();
            }, 'Captcha code is incorrect');
        });
    </script>
    <script src="aswl.js"></script>
    <script>
        <?php if (isset($success)): ?>
        showSuccessPopup('<?php echo addslashes($success); ?>', 10000);
        <?php endif; ?>
    </script>
</body>
</html>