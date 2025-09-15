<?php
session_start();
if (!isset($_SESSION['full_name'])) {
    header('Location: login.php');
    exit();
}
$title = "Dashboard";
include 'start.php';
?>
<div class="container py-5">
    <div class="dashboard-box mb-4">
        <h2>Welcome, <?php echo ucwords(strtolower(htmlspecialchars($_SESSION['full_name']))); ?>!</h2>
        <p class="lead">Your personalized dashboard</p>
    </div>
    <div class="row g-4 justify-content-center">
        <div class="col-md-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body text-center">
                    <div style="font-size:2.5rem;color:#667eea;"><i class="bi bi-box-seam"></i></div>
                    <h5 class="card-title mt-3">My Orders</h5>
                    <p class="card-text">View your recent and past orders, track shipments, and manage returns.</p>
                    <a href="#" class="btn btn-primary">Go to Orders</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body text-center">
                    <div style="font-size:2.5rem;color:#764ba2;"><i class="bi bi-person-circle"></i></div>
                    <h5 class="card-title mt-3">Profile</h5>
                    <p class="card-text">Edit your personal information, change your password, and update your email.</p>
                    <a href="#" class="btn btn-success">Edit Profile</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body text-center">
                    <div style="font-size:2.5rem;color:#56ab2f;"><i class="bi bi-gear"></i></div>
                    <h5 class="card-title mt-3">Settings</h5>
                    <p class="card-text">Manage your account settings, notifications, and privacy preferences.</p>
                    <a href="#" class="btn btn-outline-secondary">Settings</a>
                </div>
            </div>
        </div>
    </div>
    <div class="text-center mt-5">
        <a href="logout.php" class="btn btn-outline-danger">Logout</a>
    </div>
</div>
<!-- Bootstrap Icons CDN -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<?php include 'end.php'; ?>
