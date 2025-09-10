<!-- <?php
session_start();
require_once 'conn.php';
// Process change password form
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
    $email = trim($_POST['email']);
    $newPassword = trim($_POST['newPassword']);
    $confirmPassword = trim($_POST['confirmPassword']);
    $errors = [];

    // Validate email
    if (empty($email)) {
        $errors['email'] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Please enter a valid email address";
    }

    // Validate new password
    if (empty($newPassword)) {
        $errors['newPassword'] = "New password is required";
    } elseif (strlen($newPassword) < 6) {
        $errors['newPassword'] = "Password must be at least 6 characters";
    } elseif (strlen($newPassword) > 12) {
        $errors['newPassword'] = "Password must not exceed 12 characters";
    }

    // Validate confirm password
    if (empty($confirmPassword)) {
        $errors['confirmPassword'] = "Please confirm your new password";
    } elseif ($newPassword !== $confirmPassword) {
        $errors['confirmPassword'] = "Passwords do not match";
    }

    // If there are errors, log them to forgot_password_errors table
    if (!empty($errors)) {
        $error_message = implode('; ', $errors);
        $stmt = $conn->prepare("INSERT INTO forgot_password_errors (email, new_password, confirm_password, error_message) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $email, $newPassword, $confirmPassword, $error_message);
        $stmt->execute();
        $stmt->close();
    }
    // If no errors, process password change
    if (empty($errors)) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        if ($user) {
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $update = $pdo->prepare("UPDATE users SET password = ? WHERE email = ?");
            $update->execute([$hashedPassword, $email]);
            $success = "Password changed successfully!";
        } else {
            $errors['email'] = "No account found with that email.";
        }
    }
}
?> -->
<?php 
$title = "Forgot Password";
include 'start.php'; 
?>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-center">CHANGE PASSWORD</h3>
                    </div>
                    <div class="card-body">
                        <?php if (isset($success)): ?>
                            <div class="alert alert-success">
                                <?php echo htmlspecialchars($success); ?>
                            </div>
                        <?php endif; ?>
                        <form action="" method="post" id="changePasswordForm">
                            <div class="form-group">
                                <label for="email" class="form-label">Email address</label>
                                <input type="email" class="form-control<?php if(isset($errors['email'])) echo ' is-invalid'; ?>" id="email" name="email" placeholder="Enter your email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                                <?php if (isset($errors['email'])): ?>
                                    <div class="invalid-feedback"><?php echo htmlspecialchars($errors['email']); ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="form-group">
                                <label for="newPassword" class="form-label">New Password</label>
                                <input name="newPassword" type="password" class="form-control<?php if(isset($errors['newPassword'])) echo ' is-invalid'; ?>" id="newPassword" placeholder="Enter your new password">
                                <?php if (isset($errors['newPassword'])): ?>
                                    <div class="invalid-feedback"><?php echo htmlspecialchars($errors['newPassword']); ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="form-group">
                                <label for="confirmPassword" class="form-label">Confirm New Password</label>
                                <input name="confirmPassword" type="password" class="form-control<?php if(isset($errors['confirmPassword'])) echo ' is-invalid'; ?>" id="confirmPassword" placeholder="Confirm your new password">
                                <?php if (isset($errors['confirmPassword'])): ?>
                                    <div class="invalid-feedback"><?php echo htmlspecialchars($errors['confirmPassword']); ?></div>
                                <?php endif; ?>
                            </div>
                            <button type="submit" name="change_password" class="btn btn-warning w-100 mt-3">Change Password</button>
                        </form>
                        <div class="mt-3 text-center">
                            <a href="login.php" class="text-decoration-none">Back to Login</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        // Example: show success popup if password changed
        <?php if (isset($success)): ?>
        showSuccessPopup('<?php echo addslashes($success); ?>', 5000);
        <?php endif; ?>
    </script>
<?php include 'end.php'; ?>
