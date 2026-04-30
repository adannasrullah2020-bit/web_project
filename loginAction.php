<?php
		
ob_start();
session_start();

?>

<!DOCTYPE html>

<html lang="en">
	
	<!-- HEAD TAG STARTS -->

	<head>
 		
  		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
		<title>Login | tourism_management</title> 
    
    	<link href="css/main.css" rel="stylesheet">
    	<link href="css/journey-hub-theme.css" rel="stylesheet">
    	<link href="css/bootstrap.min.css" rel="stylesheet">
    	<link href="css/bootstrap-select.css" rel="stylesheet">
		<link href="css/bootstrap-datetimepicker.css" rel="stylesheet">
		<link href="css/mobile-responsive.css" rel="stylesheet">
    	<link href="https://fonts.googleapis.com/css?family=Poppins:300,400,600,700|Roboto:300,400,500,700&display=swap" rel="stylesheet">
    	<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    
    	<script src="js/jquery-3.2.1.min.js"></script>
    	<script src="js/main.js"></script>
    	<script src="js/bootstrap.min.js"></script>
    	<script src="js/bootstrap-select.js"></script>
    	<script src="js/bootstrap-dropdown.js"></script>
    	<script src="js/jquery-2.1.1.min.js"></script>
    	<script src="js/moment-with-locales.js"></script>
    	<script src="js/bootstrap-datetimepicker.js"></script>
    		
	</head>
	
	<!-- HEAD TAG ENDS -->
	
	<!-- BODY TAG STARTS -->

	<body>

		<div class="container-fluid">
		
			<div class="login">
				
				<div class="col-sm-12">
					
					<div class="heading text-center">
						INVALID DETAILS
					</div>
						
				</div>
			
				<div class="col-sm-6 col-sm-offset-3">
				
					<div class="containerBox">

	<?php
		
		require_once 'config.php';
		require("php/PasswordHash.php");
		$hasher = new PasswordHash(8, false);
		
		if ($_SERVER["REQUEST_METHOD"] !== "POST") {
			echo '<p class="col-xs-12 dots" style="color:white; font-size:1.1em; text-align:center;">Invalid request method.</p>';
			echo '<p class="col-xs-12 dots text-center" style="margin-top:1em;"><a href="login.php" style="color:white;">Go back to Login</a></p>';
			exit();
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
			echo '<p class="col-xs-12 dots" style="color:white; font-size:1.1em; text-align:center;">Validation failed:</p>';
			foreach ($errors as $e) {
				echo '<p class="col-xs-12 dots" style="color:white; font-size:1em; text-align:center;">' . htmlspecialchars($e) . '</p>';
			}
			echo '<p class="col-xs-12 dots text-center" style="margin-top:1em;"><a href="login.php" style="color:white;">Go back to Login</a></p>';
			exit();
		}
		
		/* Database check */
		$stmt = $conn->prepare("SELECT * FROM users WHERE Username = ?");
		$stmt->bind_param("s", $username);
		$stmt->execute();
		
		$result = $stmt->get_result();
		$user = $result->fetch_assoc();
		
		if (!$user) {
			echo '<p class="col-xs-12 dots" style="color:white; font-size:1.1em; text-align:center;">Invalid username or password.</p>';
			echo '<p class="col-xs-12 dots text-center" style="margin-top:1em;"><a href="login.php" style="color:white;">Go back to Login</a></p>';
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
			echo '<p class="col-xs-12 dots" style="color:white; font-size:1.1em; text-align:center;">Invalid username or password.</p>';
			echo '<p class="col-xs-12 dots text-center" style="margin-top:1em;"><a href="login.php" style="color:white;">Go back to Login</a></p>';
			exit();
		}
	?>

					</div>

				</div>

			</div>

		</div> <!-- container-fluid -->
	
	</body>
	
	<!-- BODY TAG ENDS -->

</html>