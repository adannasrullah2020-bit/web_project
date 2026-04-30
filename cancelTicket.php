<?php session_start();
if(!isset($_SESSION["username"]))
{
    	header("Location:blocked.php");
   		$_SESSION['url'] = $_SERVER['REQUEST_URI']; 
}

// Database connection
require_once 'config.php';

$user = $_SESSION["username"];
	$id = $_POST["bookingID"];


	$cancelFlightBookingsSQL = "UPDATE `flightbookings` SET cancelled='yes' WHERE bookingID='$id'";
	$cancelFlightBookingsQuery = $conn->query($cancelFlightBookingsSQL);

	/*-------------------------------------------------------------------------------
	
	
				updated flight tickets cancellation status
	
	
	-------------------------------------------------------------------------------*/


?>