<?php session_start();
if(!isset($_SESSION["username"]))
{
    	header("Location:blocked.php");
   		$_SESSION['url'] = $_SERVER['REQUEST_URI']; 
}

// Database connection
require_once 'config.php';
		die("Connection failed: " . $conn->connect_error);
	}

	$user = $_SESSION["username"];
	
	$deleteUserSQL = "DELETE FROM `users` WHERE Username='$user'";
	$deleteUserQuery = $conn->query($deleteUserSQL);

	$deleteFlightBookingsSQL = "DELETE FROM `flightbookings` WHERE Username='$user'";
	$deleteFlightBookingsQuery = $conn->query($deleteFlightBookingsSQL);

	/*-------------------------------------------------------------------------------
	
	
			deleted flight bookings and account details of users
	
	
	-------------------------------------------------------------------------------*/


?>