<?php
session_start();

// Process forgot password form
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['forgot_password'])) {
    $email = trim($_POST['email']);
    $captcha = trim($_POST['captcha']);
    $captchaSet = $_POST['captchaSet'];
    
    $errors = [];
    
    // Validate email
    if (empty($email)) {
        $errors[] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please enter a valid email address";
    }
    
    // Validate CAPTCHA
    if (empty($captcha)) {
        $errors[] = "CAPTCHA is required";
    } elseif ($captcha !== $captchaSet) {
        $errors[] = "CAPTCHA code is incorrect";
    }
    
    // If no errors, process password reset
    if (empty($errors)) {
        // Here you would typically:
        // 1. Connect to database
        // 2. Check if email exists
        // 3. Generate reset token
        // 4. Store token in database with expiry
        // 5. Send reset email with token
        // 6. Show success message
        
        // For demo purposes, we'll just show success message
        $success = "If an account with this email exists, a password reset link has been sent to your email address.";
        
        // In real application, you would do:
        // $token = bin2hex(random_bytes(32));
        // $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));
        // $sql = "UPDATE users SET reset_token = ?, reset_expiry = ? WHERE email = ?";
        // $stmt = $pdo->prepare($sql);
        // $stmt->execute([$token, $expiry, $email]);
        // 
        // // Send email with reset link
        // $resetLink = "https://yoursite.com/reset-password.php?token=" . $token;
        // mail($email, "Password Reset", "Click here to reset your password: " . $resetLink);
    }
}

// Process password reset form (if token is provided)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reset_password'])) {
    $token = $_POST['token'];
    $newPassword = $_POST['newPassword'];
    $confirmPassword = $_POST['confirmPassword'];
    $captcha = trim($_POST['captcha']);
    $captchaSet = $_POST['captchaSet'];
    
    $errors = [];
    
    // Validate token
    if (empty($token)) {
        $errors[] = "Invalid reset token";
    }
    
    // Validate new password
    if (empty($newPassword)) {
        $errors[] = "New password is required";
    } elseif (strlen($newPassword) < 6) {
        $errors[] = "Password must be at least 6 characters";
    } elseif (strlen($newPassword) > 12) {
        $errors[] = "Password must not exceed 12 characters";
    }
    
    // Validate confirm password
    if (empty($confirmPassword)) {
        $errors[] = "Please confirm your new password";
    } elseif ($newPassword !== $confirmPassword) {
        $errors[] = "Passwords do not match";
    }
    
    // Validate CAPTCHA
    if (empty($captcha)) {
        $errors[] = "CAPTCHA is required";
    } elseif ($captcha !== $captchaSet) {
        $errors[] = "CAPTCHA code is incorrect";
    }
    
    // If no errors, process password reset
    if (empty($errors)) {
        // Here you would typically:
        // 1. Connect to database
        // 2. Verify token and check expiry
        // 3. Hash new password
        // 4. Update user password
        // 5. Clear reset token
        // 6. Redirect to login
        
        // For demo purposes, we'll just show success message
        $success = "Password has been reset successfully! You can now login with your new password.";
        
        // In real application, you would do:
        // $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        // $sql = "UPDATE users SET password = ?, reset_token = NULL, reset_expiry = NULL WHERE reset_token = ? AND reset_expiry > NOW()";
        // $stmt = $pdo->prepare($sql);
        // $stmt->execute([$hashedPassword, $token]);
        // header('Location: login.php?reset=1');
        // exit();
    }
}

// Check if we're in reset mode (token provided)
$resetMode = isset($_GET['token']) && !empty($_GET['token']);
$token = $resetMode ? $_GET['token'] : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $resetMode ? 'Reset Password' : 'Forgot Password'; ?> - CAPTCHA System</title>
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
                        <h3 class="text-center">
                            <?php echo $resetMode ? 'RESET PASSWORD' : 'FORGOT PASSWORD'; ?>
                        </h3>
                    </div>
                    <div class="card-body">
                        <?php if (isset($success)): ?>
                            <div class="alert alert-success">
                                <?php echo htmlspecialchars($success); ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($resetMode): ?>
                            <!-- Password Reset Form -->
                            <form action="" method="post" id="resetPasswordForm">
                                <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                                
                                <div class="form-group">
                                    <label for="newPassword" class="form-label">New Password</label>
                                    <input name="newPassword" type="password" class="form-control" id="newPassword"
                                        placeholder="Enter your new password" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="confirmPassword" class="form-label">Confirm New Password</label>
                                    <input name="confirmPassword" type="password" class="form-control" id="confirmPassword"
                                        placeholder="Confirm your new password" required>
                                </div>
                                
                                <!-- Captcha Section -->
                                <div class="mb-3">
                                    <label class="form-label">Enter the code below:</label>
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <div class="captcha-display" id="resetCaptchaDisplay"></div>
                                        <input type="hidden" name="captchaSet" id="resetCaptchaSet" value="">
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-outline-secondary btn-sm"
                                                id="reloadResetCaptcha" title="Refresh Captcha">
                                                <i class="bi bi-arrow-clockwise">↻</i>
                                            </button>
                                            <button type="button" class="btn btn-outline-info btn-sm" id="audioResetCaptcha"
                                                title="Audio Captcha">
                                                🔊
                                            </button>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="resetCaptchaInput" class="form-label">Captcha</label>
                                        <input type="text" name="captcha" class="form-control" id="resetCaptchaInput"
                                            placeholder="Enter the captcha code" required>
                                    </div>
                                </div>
                                
                                <button type="submit" name="reset_password" class="btn btn-warning w-100">Reset Password</button>
                            </form>
                        <?php else: ?>
                            <!-- Forgot Password Form -->
                            <form action="" method="post" id="forgotPasswordForm">
                                <div class="form-group">
                                    <label for="forgotEmail" class="form-label">Email address</label>
                                    <input name="email" type="email" class="form-control" id="forgotEmail"
                                        placeholder="Enter your email" required 
                                        value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                                </div>
                                
                                <!-- Captcha Section -->
                                <div class="mb-3">
                                    <label class="form-label">Enter the code below:</label>
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <div class="captcha-display" id="forgotCaptchaDisplay"></div>
                                        <input type="hidden" name="captchaSet" id="forgotCaptchaSet" value="">
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-outline-secondary btn-sm"
                                                id="reloadForgotCaptcha" title="Refresh Captcha">
                                                <i class="bi bi-arrow-clockwise">↻</i>
                                            </button>
                                            <button type="button" class="btn btn-outline-info btn-sm" id="audioForgotCaptcha"
                                                title="Audio Captcha">
                                                🔊
                                            </button>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="forgotCaptchaInput" class="form-label">Captcha</label>
                                        <input type="text" name="captcha" class="form-control" id="forgotCaptchaInput"
                                            placeholder="Enter the captcha code" required>
                                    </div>
                                </div>
                                
                                <button type="submit" name="forgot_password" class="btn btn-warning w-100">Send Reset Link</button>
                            </form>
                        <?php endif; ?>
                        
                        <div class="mt-3 text-center">
                            <a href="login.php" class="text-decoration-none">Back to Login</a>
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
        // Success popup function
        function showSuccessPopup(message, duration = 10000) {
            // Create popup element
            const popup = $(`
                <div id="successPopup" style="
                    position: fixed;
                    top: 50%;
                    left: 50%;
                    transform: translate(-50%, -50%);
                    background: linear-gradient(135deg, #198754 0%, #20c997 100%);
                    color: white;
                    padding: 20px 30px;
                    border-radius: 15px;
                    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
                    z-index: 9999;
                    font-weight: 600;
                    text-align: center;
                    min-width: 300px;
                    animation: popupSlideIn 0.5s ease-out;
                ">
                    <div style="font-size: 18px; margin-bottom: 10px;">✅</div>
                    <div style="font-size: 16px;">${message}</div>
                    <div style="font-size: 12px; margin-top: 10px; opacity: 0.8;">This popup will close automatically</div>
                </div>
            `);
            
            // Add CSS animation
            $('<style>')
                .prop('type', 'text/css')
                .html(`
                    @keyframes popupSlideIn {
                        from {
                            opacity: 0;
                            transform: translate(-50%, -60%);
                        }
                        to {
                            opacity: 1;
                            transform: translate(-50%, -50%);
                        }
                    }
                    @keyframes popupSlideOut {
                        from {
                            opacity: 1;
                            transform: translate(-50%, -50%);
                        }
                        to {
                            opacity: 0;
                            transform: translate(-50%, -60%);
                        }
                    }
                `)
                .appendTo('head');
            
            // Add popup to body
            $('body').append(popup);
            
            // Auto remove after duration
            setTimeout(function() {
                popup.css('animation', 'popupSlideOut 0.5s ease-in');
                setTimeout(function() {
                    popup.remove();
                }, 500);
            }, duration);
        }

        $(document).ready(function() {
            // Initialize captcha based on mode
            if (typeof captchaSystem !== 'undefined') {
                if (<?php echo $resetMode ? 'true' : 'false'; ?>) {
                    captchaSystem.generateCaptcha('reset');
                } else {
                    captchaSystem.generateCaptcha('forgot');
                }
            } else {
                // Fallback if captchaSystem is not loaded
                console.log('Captcha system not loaded, initializing manually...');
                setTimeout(function() {
                    if (typeof captchaSystem !== 'undefined') {
                        if (<?php echo $resetMode ? 'true' : 'false'; ?>) {
                            captchaSystem.generateCaptcha('reset');
                        } else {
                            captchaSystem.generateCaptcha('forgot');
                        }
                    }
                }, 500);
            }
            
            // Form validation for forgot password
            $('#forgotPasswordForm').validate({
                rules: {
                    email: {
                        required: true,
                        email: true
                    },
                    captcha: {
                        required: true,
                        customCaptcha: function() {
                            const input = $('#forgotCaptchaInput').val();
                            const expected = $('#forgotCaptchaSet').val();
                            return input === expected; // Case-sensitive validation
                        }
                    }
                },
                messages: {
                    email: {
                        required: "Please enter your email",
                        email: "Please enter a valid email"
                    },
                    captcha: {
                        required: "Please enter the captcha",
                        customCaptcha: "Captcha code is incorrect"
                    }
                },
                errorClass: "error",
                highlight: function(element) {
                    $(element).addClass('error');
                },
                unhighlight: function(element) {
                    $(element).removeClass('error');
                },
                submitHandler: function (form) {
                    // Show success popup for 10 seconds
                    showSuccessPopup('Password reset link sent successfully!', 10000);
                    return true;
                }
            });
            
            // Form validation for reset password
            $('#resetPasswordForm').validate({
                rules: {
                    newPassword: {
                        required: true,
                        minlength: 6,
                        maxlength: 12
                    },
                    confirmPassword: {
                        required: true,
                        equalTo: '#newPassword'
                    },
                    captcha: {
                        required: true,
                        customCaptcha: function() {
                            const input = $('#resetCaptchaInput').val();
                            const expected = $('#resetCaptchaSet').val();
                            return input === expected; // Case-sensitive validation
                        }
                    }
                },
                messages: {
                    newPassword: {
                        required: "Please enter a new password",
                        minlength: "Password must be at least 6 characters",
                        maxlength: "Password must not exceed 12 characters"
                    },
                    confirmPassword: {
                        required: "Please confirm your new password",
                        equalTo: "Passwords do not match"
                    },
                    captcha: {
                        required: "Please enter the captcha",
                        customCaptcha: "Captcha code is incorrect"
                    }
                },
                errorClass: "error",
                highlight: function(element) {
                    $(element).addClass('error');
                },
                unhighlight: function(element) {
                    $(element).removeClass('error');
                },
                submitHandler: function (form) {
                    // Show success popup for 10 seconds
                    showSuccessPopup('Password reset successfully!', 10000);
                    return true;
                }
            });
            
            // Add custom validation method
            $.validator.addMethod('customCaptcha', function(value, element) {
                const formType = element.id.includes('reset') ? 'reset' : 'forgot';
                const expected = $(`#${formType}CaptchaSet`).val();
                return value === expected; // Case-sensitive validation
            }, 'Captcha code is incorrect');
            
            // Manual captcha generation as backup
            function generateManualCaptcha(formType) {
                const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
                let code = '';
                for (let i = 0; i < 6; i++) {
                    code += chars.charAt(Math.floor(Math.random() * chars.length));
                }
                
                $(`#${formType}CaptchaSet`).val(code);
                $(`#${formType}CaptchaDisplay`).text(code);
                
                return code;
            }
            
            // Generate captcha if not already generated
            setTimeout(function() {
                if ($('#forgotCaptchaDisplay').text().trim() === '') {
                    generateManualCaptcha('forgot');
                }
                if ($('#resetCaptchaDisplay').text().trim() === '') {
                    generateManualCaptcha('reset');
                }
            }, 1000);
        });
    </script>
</body>
</html>
