<?php session_start();
if(!isset($_SESSION["username"]))
{
    	header("Location:blocked.php");
   		$_SESSION['url'] = $_SERVER['REQUEST_URI']; 
}
?>

<!DOCTYPE html>

<html lang="en">
	
	<!-- HEAD TAG STARTS -->

	<head>
	
  		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	
		<title>Dashboard | tourism_management</title>
    
    	<link href="css/main.css" rel="stylesheet">
    	<link href="css/bootstrap.min.css" rel="stylesheet">
    	<link href="css/bootstrap-select.css" rel="stylesheet">
		<link href="css/bootstrap-datetimepicker.css" rel="stylesheet">
		<link href="css/mobile-responsive.css" rel="stylesheet">
    	<link href="https://fonts.googleapis.com/css?family=Oswald:200,300,400|Raleway:100,300,400,500|Roboto:100,400,500,700" rel="stylesheet">
    	<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    
    	<script src="js/jquery-3.2.1.min.js"></script>
    	<script src="js/userDashboard.js"></script>
    	<script src="js/bootstrap.min.js"></script>
    	<script src="js/bootstrap-select.js"></script>
    	<script src="js/bootstrap-dropdown.js"></script>
    	<script src="js/jquery-2.1.1.min.js"></script>
    	<script src="js/moment-with-locales.js"></script>
    	<script src="js/bootstrap-datetimepicker.js"></script>
    		
	</head>
	
	<!-- HEAD TAG ENDS -->
	
	<!-- BODY TAG STARTS -->
	
	<?php
	
		// Database connection
		require_once 'config.php';
	
	?>
		
	<body>
	
		<div class="container-fluid">
		
			<div class="col-sm-12 userDashboard text-center">
			
			<?php include("common/headerDashboardTransparentLoggedIn.php"); ?>
			
			<div class="col-sm-12">
					
				<div class="heading text-center">
					My Dashboard
				</div>
						
			</div>
			
			<div class="col-sm-1"></div>
			
			<div class="col-sm-3 containerBoxLeft">
				
				<div class="col-sm-12 menuContainer bottomBorder active">
					<span class="fa fa-user-o"></span> My Profile
				</div>
				
				<a href="userDashboardBookings.php">
				<div class="col-sm-12 menuContainer bottomBorder">
					<span class="fa fa-copy"></span> My Bookings
				</div>
				</a>
				
				<a href="userDashboardETickets.php">
				<div class="col-sm-12 menuContainer bottomBorder">
					<span class="fa fa-clone"></span> My E-Tickets
				</div>
				</a>
				
				<a href="userDashboardCancelTicket.php">
				<div class="col-sm-12 menuContainer bottomBorder">
					<span class="fa fa-close"></span> Cancel Ticket
				</div>
				</a>
				
				<a href="userDashboardAccountSettings.php">
				<div class="col-sm-12 menuContainer">
					<span class="fa fa-bar-chart"></span> Account Stats
				</div>
				</a>
				
			</div>
			
			<div class="col-sm-7 containerBoxRight text-left">
				
				<?php
				
					$user = $_SESSION["username"];
					//users query
					$profileSQL = "SELECT * FROM `users` WHERE Username='$user'";
					$profileQuery = $conn->query($profileSQL);
					$row = $profileQuery ? $profileQuery->fetch_assoc() : null;

				?>
				
				<div class="col-sm-12 profile">
				
					<div class="col-sm-12 profileWrapper">
					<span class="tag">Username: </span><span class="content"><?php echo isset($row["Username"]) ? htmlspecialchars($row["Username"]) : 'N/A'; ?> </span>
					</div>					
					<div class="col-sm-12 profileWrapper">
					<span class="tag">Full Name: </span><span class="content"><?php echo isset($row["FullName"]) ? htmlspecialchars($row["FullName"]) : 'N/A'; ?> </span>
					</div>
					<div class="col-sm-6 profileWrapper">
					<span class="tag">E-Mail: </span><span class="content"><?php echo isset($row["EMail"]) ? htmlspecialchars($row["EMail"]) : 'N/A'; ?> </span>
					</div>
					<div class="col-sm-6 profileWrapper">
					<span class="tag">Phone: </span><span class="content"><?php echo isset($row["Phone"]) ? htmlspecialchars($row["Phone"]) : 'N/A'; ?> </span>
					</div>
					<div class="col-sm-12 profileWrapper">
					<span class="tag">Address: </span><span class="content"><?php 
						$address = '';
						if(isset($row["AddressLine1"])) $address .= $row["AddressLine1"];
						if(isset($row["AddressLine2"])) $address .= ", " . $row["AddressLine2"];
						if(isset($row["City"])) $address .= ", " . $row["City"];
						if(isset($row["State"])) $address .= ", " . $row["State"];
						echo !empty($address) ? htmlspecialchars($address) : 'N/A';
					?> </span>
					</div>
					<div class="col-sm-12 profileWrapper">
					<span class="tag">Account Created: </span><span class="content"><?php echo isset($row["Date"]) ? htmlspecialchars($row["Date"]) : 'N/A'; ?> </span>
					</div>	
					
				</div>
				
				
			</div>
			
			<div class="col-sm-1"></div>
			
			<div class="col-sm-12 spacer">a</div>
			<div class="col-sm-12 spacer">a</div>
			
			</div>
		
		</div> <!-- container-fluid -->
		
		<?php include("common/footer.php"); ?>
		
	</body>
	

	<!-- BODY TAG ENDS -->
	
</html>
	