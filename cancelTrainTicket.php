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
	$id = $_POST["bookingID"];


	$cancelTrainBookingsSQL = "UPDATE `trainbookings` SET cancelled='yes' WHERE bookingID='$id'";
	$cancelTrainBookingsQuery = $conn->query($cancelTrainBookingsSQL);

	/*-------------------------------------------------------------------------------
	
	
				updated train tickrts cancellation status
	
	
	-------------------------------------------------------------------------------*/


?>