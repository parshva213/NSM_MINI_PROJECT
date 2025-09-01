<?php
session_start();

// Process registration form
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    $fullName = trim($_POST['fullName']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];
    $captcha = trim($_POST['captcha']);
    $captchaSet = $_POST['captchaSet'];
    
    $errors = [];
    
    // Validate full name
    if (empty($fullName)) {
        $errors[] = "Full name is required";
    } elseif (strlen($fullName) < 2) {
        $errors[] = "Full name must be at least 2 characters";
    }
    
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
    
    // Validate confirm password
    if (empty($confirmPassword)) {
        $errors[] = "Please confirm your password";
    } elseif ($password !== $confirmPassword) {
        $errors[] = "Passwords do not match";
    }
    
    // Validate CAPTCHA
    if (empty($captcha)) {
        $errors[] = "CAPTCHA is required";
    } elseif ($captcha !== $captchaSet) {
        $errors[] = "CAPTCHA code is incorrect";
    }
    
    // If no errors, process registration
    if (empty($errors)) {
        // Here you would typically:
        // 1. Connect to database
        // 2. Check if email already exists
        // 3. Hash the password
        // 4. Insert new user
        // 5. Send confirmation email
        // 6. Redirect to login or dashboard
        
        // For demo purposes, we'll just show success message
        $success = "Registration successful! You can now login with your credentials.";
        
        // In real application, you would do:
        // $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        // $sql = "INSERT INTO users (full_name, email, password) VALUES (?, ?, ?)";
        // $stmt = $pdo->prepare($sql);
        // $stmt->execute([$fullName, $email, $hashedPassword]);
        // header('Location: login.php?registered=1');
        // exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'need.php' ?>
    <title>Register - CAPTCHA System</title>
    <meta name="description" content="Register a new account">
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-center">REGISTER</h3>
                    </div>
                    <div class="card-body">
                        <?php if (isset($success)): ?>
                            <div class="alert alert-success">
                                <?php echo htmlspecialchars($success); ?>
                            </div>
                        <?php endif; ?>
                        
                        <form action="" method="post" id="registerForm">
                            <div class="form-group">
                                <label for="registerFullName" class="form-label">Full Name</label>
                                <input name="fullName" type="text" class="form-control" id="registerFullName"
                                    placeholder="Enter your full name" required 
                                    value="<?php echo isset($_POST['fullName']) ? htmlspecialchars($_POST['fullName']) : ''; ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="registerEmail" class="form-label">Email address</label>
                                <input name="email" type="email" class="form-control" id="registerEmail"
                                    placeholder="Enter your email" required 
                                    value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="registerPassword" class="form-label">Password</label>
                                <input name="password" type="password" class="form-control" id="registerPassword"
                                    placeholder="Enter your password" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="registerConfirmPassword" class="form-label">Confirm Password</label>
                                <input name="confirmPassword" type="password" class="form-control" id="registerConfirmPassword"
                                    placeholder="Confirm your password" required>
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
                                        <button type="button" class="btn btn-outline-info btn-sm" id="audioRegisterCaptcha"
                                            title="Audio Captcha">
                                            🔊
                                        </button>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="registerCaptchaInput" class="form-label">Captcha</label>
                                    <input type="text" name="captcha" class="form-control" id="registerCaptchaInput"
                                        placeholder="Enter the captcha code" required>
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

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Enhanced Captcha System -->
    <script src="captcha.js"></script>
    
    <script>
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
                        customCaptcha: function() {
                            return $('#registerCaptchaInput').val().toLowerCase() === $('#registerCaptchaSet').val().toLowerCase();
                        }
                    }
                },
                messages: {
                    fullName: {
                        required: "Please enter your full name",
                        minlength: "Name must be at least 2 characters"
                    },
                    email: {
                        required: "Please enter your email",
                        email: "Please enter a valid email"
                    },
                    password: {
                        required: "Please enter a password",
                        minlength: "Password must be at least 6 characters"
                    },
                    confirmPassword: {
                        required: "Please confirm your password",
                        equalTo: "Passwords do not match"
                    },
                    captcha: {
                        required: "Please enter the captcha",
                        customCaptcha: "Captcha code is incorrect"
                    }
                }
            });
            
            // Add custom validation method
            $.validator.addMethod('customCaptcha', function(value, element) {
                return value.toLowerCase() === $('#registerCaptchaSet').val().toLowerCase();
            }, 'Captcha code is incorrect');
        });
    </script>
</body>
</html>