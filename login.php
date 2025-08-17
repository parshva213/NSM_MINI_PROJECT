<?php
session_start();

// Process login form
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $captcha = trim($_POST['captcha']);
    $captchaSet = $_POST['captchaSet'];
    
    $errors = [];
    
    // Validate email
    if (empty($email)) {
        $errors[] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please enter a valid email address";
    }
    
    // Validate password
    if (empty($password)) {
        $errors[] = "Password is required";
    } elseif (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters";
    } elseif (strlen($password) > 12) {
        $errors[] = "Password must not exceed 12 characters";
    }
    
    // Validate CAPTCHA
    if (empty($captcha)) {
        $errors[] = "CAPTCHA is required";
    } elseif ($captcha !== $captchaSet) {
        $errors[] = "CAPTCHA code is incorrect";
    }
    
    // If no errors, process login
    if (empty($errors)) {
        // Here you would typically:
        // 1. Connect to database
        // 2. Check if user exists
        // 3. Verify password hash
        // 4. Set session variables
        // 5. Redirect to dashboard
        
        // For demo purposes, we'll just show success message
        $success = "Login successful! (Demo mode - no actual authentication)";
        
        // In real application, you would do:
        // $_SESSION['user_id'] = $user['id'];
        // $_SESSION['user_email'] = $user['email'];
        // header('Location: dashboard.php');
        // exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - CAPTCHA System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="login.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
</head>
<body>
    <div class="container mt-5">
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
                                    placeholder="Enter your email" required 
                                    value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="loginPassword" class="form-label">Password</label>
                                <input name="password" type="password" class="form-control" id="loginPassword"
                                    placeholder="Enter your password" required>
                            </div>
                            
                            <!-- Captcha Section -->
                            <div class="mb-3">
                                <label class="form-label">Enter the code below:</label>
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <div class="captcha-display" id="loginCaptchaDisplay"></div>
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
                                        placeholder="Enter the captcha code" required>
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
    <script src="captcha.js"></script>
    
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
                        minlength: 6
                    },
                    captcha: {
                        required: true,
                        customCaptcha: function() {
                            return $('#loginCaptchaInput').val().toLowerCase() === $('#loginCaptchaSet').val().toLowerCase();
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
                        minlength: "Password must be at least 6 characters"
                    },
                    captcha: {
                        required: "Please enter the captcha",
                        customCaptcha: "Captcha code is incorrect"
                    }
                }
            });
            
            // Add custom validation method
            $.validator.addMethod('customCaptcha', function(value, element) {
                return value.toLowerCase() === $('#loginCaptchaSet').val().toLowerCase();
            }, 'Captcha code is incorrect');
        });
    </script>
</body>
</html>