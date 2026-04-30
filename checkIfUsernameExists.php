
<?php

// Database connection
require_once 'config.php';

$username = $_POST["username"];
	
	try {
		
		$stmt = $conn->prepare("SELECT Username FROM users WHERE Username=?");
    	$stmt->execute([$username]);
		$count=$stmt->rowCount();
		
		if($count>0)
   		{
   		 echo "true";
   		}
   		else
   		{
   		 echo "false";
   }
		
	}
	catch(PDOException $e)
	{
		echo $e->getMessage();
	}

?>