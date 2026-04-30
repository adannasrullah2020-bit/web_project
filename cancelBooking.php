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

//update booking status for hotels
	$cancelHotelBookingsSQL = "UPDATE `hotelbookings` SET cancelled='yes' WHERE bookingID='$id'";
	$cancelHotelBookingsQuery = $conn->query($cancelHotelBookingsSQL);


?>