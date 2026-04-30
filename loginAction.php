<?php
ob_start();
session_start();

require_once 'config.php';
require("php/PasswordHash.php");

$hasher = new PasswordHash(8, false);

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Invalid request method");
}

$username = trim($_POST["username"] ?? "");
$password = $_POST["password"] ?? "";

/* Server side validation */
$errors = [];

if (empty($username)) {
    $errors[] = "Username is required";
} elseif (!preg_match("/^[A-Za-z0-9_]{3,20}$/", $username)) {
    $errors[] = "Invalid username format";
}

if (empty($password)) {
    $errors[] = "Password is required";
} elseif (strlen($password) < 6 || strlen($password) > 50) {
    $errors[] = "Password length must be between 6 and 50 characters";
}

if (!empty($errors)) {
    echo "Validation failed:<br>";
    foreach ($errors as $e) {
        echo $e . "<br>";
    }
    exit();
}

/* Database check */
$stmt = $conn->prepare("SELECT * FROM users WHERE Username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();

$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    echo "Invalid username or password";
    exit();
}

$passwordFromDB = $user["Password"];

if ($hasher->CheckPassword($password, $passwordFromDB)) {
    $_SESSION["valid"] = true;
    $_SESSION["timeout"] = time();
    $_SESSION["username"] = $username;
    header("Location: userDashboardProfile.php");
    exit();
} else {
    echo "Invalid username or password";
    exit();
}
?>
	
	</body>
	
</html>
	
	