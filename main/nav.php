<?php
session_start();

// Set full_name if not already set
$_SESSION['full_name'] = $_SESSION['full_name'] ?? "";

// Determine login/logout state
$x = (!empty($_SESSION['full_name'])) ? 'logout' : 'login';
?>
<!-- nav.php -->
<header class="bg-dark text-white d-flex justify-content-between align-items-center p-4">

    <div class="nav-left">
        <h1>Network Security</h1>
    </div>
    <nav>
        <ul class="nav gap-5">
            <li><a class="nav-link text-white fw-bold" href="#">Home</a></li>
            <li><a class="nav-link text-white fw-bold" href="#">About</a></li>
            <li><a class="nav-link text-white fw-bold" href="#">Contact</a></li>
        </ul>
    </nav>
    <div class="d-flex align-items-center gap-3">
        <span id="user-name">Welcome <?php echo htmlspecialchars($_SESSION['full_name']); ?></span>
        <button type="button" class="btn btn-primary fw-bol" id="auth-button" onClick="handleAuthClick()"><?php echo $x; ?></button>
    </div>

    <script>
        function handleAuthClick() {
            let page = "<?php echo $x; ?>";
            window.location.href = page + ".php";
        }
    </script>
</header>
