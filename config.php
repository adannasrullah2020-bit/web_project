<?php
// Session ko start karein (Sabse upar hona chahiye)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Database Credentials
define('DB_SERVER', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'travel');

$conn = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);

if (!$conn) {
    die("Database Connection Failed: " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8");

// Helper Function: User Login Check
function checkUserLogin() {
    if (!isset($_SESSION["username"])) {
        header("Location: login.php");
        exit();
    }
}

// Helper Function: Admin Login Check
function checkAdminLogin() {
    if (!isset($_SESSION["username"])) {
        header("Location: adminLogin.php");
        exit();
    }
}
?>