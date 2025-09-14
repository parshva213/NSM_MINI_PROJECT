<?php
session_start();
// Simple dashboard page after successful login or registration
if (!isset($_SESSION['full_name'])) {
    // Redirect to login if not logged in
    header('Location: login.php');
    exit();
}
$title = "Dashboard";
include 'start.php';
?>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="dashboard-box">
                    <h2>Welcome, <?php echo ucwords(strtolower(htmlspecialchars($_SESSION['full_name']))); ?>!</h2>
                    <p class="lead"><?php echo isset($_SESSION['data'])?("You have ".htmlspecialchars($_SESSION['data'])):("Thankyou For come in"); ?>.</p>
                    <a href="logout.php" class="btn btn-outline-danger">Logout</a>
                </div>
            </div>
        </div>
    </div>
    <?php if (isset($_SESSION['success'])): ?>
    <script>
        showSuccessPopup('<?php echo addslashes($_SESSION['success']); ?>', 5000);
    </script>
    <?php unset($_SESSION['success']); endif; ?>
<?php include 'end.php'; ?>
<?php if (isset($_SESSION['success'])): ?>
    <script>
        showSuccessPopup('<?php echo addslashes($success); ?>', 5000);
        </script>
        <?php endif; ?>
