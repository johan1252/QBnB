<!DOCTYPE html>
<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
  
  <meta charset="utf-8">
    <title>Notifications</title>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    <script src="//code.jquery.com/jquery-1.10.2.js"></script>
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
    <link rel="stylesheet" href="/resources/demos/style.css">
    <script>
    $(function() {
      $( "#datepicker" ).datepicker();
    });
    </script>
  
</head>
<body>
<!--
<style>
table, th, td {
	
     border: 1px solid white;
    background-color: #808080; 
    color: #ffffff;
}
th, td {
	padding: 10px;
}

</style>
-->		
	
 <?php
  //Create a user session or resume an existing one
 session_start();
 ?>
 
 <?php
if(isset($_SESSION['id'])) {
   // include database connection
    include_once 'config/connection.php'; 
	
	// SELECT query
        $query = "SELECT member_id,username, password, email FROM member WHERE member_id=?";
 
        // prepare query for execution
        $stmt = $con->prepare($query);
		
        // bind the parameters. This is the best way to prevent SQL injection hacks.
        $stmt->bind_Param("s", $_SESSION['id']);

        // Execute the query
		$stmt->execute();
 
		// results 
		$result = $stmt->get_result();
		
		// Row data
		$myrow = $result->fetch_assoc();
		
} else {
	//User is not logged in. Redirect the browser to the login index.php page and kill this page.
	header("Location: index.php");
	echo "Not logged in";
	die();
}
?>

 <nav class="navbar navbar-inverse navbar-fixed-top">
   <div class="container-fluid">
     <div class="navbar-header">
       <a class="navbar-brand" href="index.php">QBnB</a>
     </div>
     <ul class="nav navbar-nav">
       <li ><a href="gen_main.php">Home</a></li>
       <li><a href="myListings.php">My Listings</a></li>
       <li><a href="myBookings.php">My Bookings</a></li>
	   <?php
	   if(isset($_SESSION['id'])){
	   	//Redirect the browser to the profile editing page and kill this page.
	   	if ($_SESSION['type'] == "administrator")
	   	{
	   		?> <li><a href="admin_membersearch.php">Admin Member Search</a></li> 
			<li><a href="admin_propertysearch.php">Admin Property Search</a></li>
			<?php 
	   	}
	   }
	    ?>
 	    
       
   </ul>
	   <ul class="nav navbar-nav navbar-right">
 	    <?php 
    		if($_SESSION['id']){
    			 include_once 'config/connection.php'; 
						
    			   			// SELECT query
    			   	        $query = "SELECT * from notification where member_id = ? AND seen = false;
    			   					  ;"; 

    			   	        // prepare query for execution
    			   	        $stmt = $con->prepare($query);
		
    			   	        // bind the parameters. This is the best way to prevent SQL injection hacks.
    			   	        $stmt->bind_Param('i', $_SESSION['id']);

    			   	        // Execute the query
    			   			$stmt->execute();
			
    						// results 
    						$result = $stmt->get_result();
			
 		 				if ($result->num_rows > 0) {
 		 				    ?>
							<li class="active"><a href="notification.php"><?php echo "Notifications (UNREAD)";?> </a></li>
								<?php
 						} else{
 		 				    ?>
							<li class="active"><a href="notification.php"><?php echo "Notifications (0)";?> </a></li>
								<?php
 						}	
						}	
 						?>
	   <li><a href="userProfile.php"> <?php echo $myrow['username'];?> </a></li>
	   <li><a href="index.php?logout=1">Log Out</a></li>
     </ul>
   </div>
 </nav>
<br>
<br>
<br>
<div class="page-header">
  <h1>Notificatons</h1>
</div>

		<?php
		if($_SESSION['id']){
		 		    include_once 'config/connection.php'; 
		 			// SELECT query
		 		        $query = "SELECT
		 		  		  		  notification_id,
								  message,
								  seen
		 						FROM
		 						  notification
		 						WHERE
		 						 member_id=?
		 						  ;"; 
 
		 		        // prepare query for execution
		 		        $stmt = $con->prepare($query);
		
		 		        // bind the parameters. This is the best way to prevent SQL injection hacks.
		 		        $stmt->bind_Param("i", $_SESSION['id'] );

		 		        // Execute the query
		 				$stmt->execute();
 
		 				// results 
		 				$result = $stmt->get_result();
			  
		 				if ($result->num_rows > 0) {
					
		 				     echo "<div class=\"table-responsive\">
								 <table class=\"table\"> <thead><tr><th> Message </th><th> Read </tr>";
		 				     // output data of each row
		 				     while($row = $result->fetch_assoc()) {
								 
		 				         echo "<tr><td>" . $row["message"] . "</td><td>";
								 if($row["seen"] == '0'){
									 echo "Unread" . "</td><td>";
									 ?>
									 <form action='notification.php' method=get>
									 <input type='submit' class="btn btn-primary"  name = 'read_btn' id = 'read_btn' value="Mark as Read">
									 <input type='hidden' name='notification_id' id='notification_id' value="<?php echo $row['notification_id'] ?>">	 	
								 </form>
									 
									 <?php
								 }else{
								 	echo "Read". "</td><td>";
								 }
								 
		 							echo"</td></tr>";
		 				     }
		 				     echo "</thead></table></div>";
		 				} else {
		 				     echo "No notifications to show.";
		 				}
						
				   		if(isset($_GET['read_btn'])){
							
				   			// SELECT query
				   	        $query = "UPDATE notification set seen = true where notification_id = ?
				   					  ;"; 

				   	        // prepare query for execution
				   	        $stmt = $con->prepare($query);
			
				   	        // bind the parameters. This is the best way to prevent SQL injection hacks.
				   	        $stmt->bind_Param('i', $_GET['notification_id']);

				   	        // Execute the query
				   			$stmt->execute();
							
		   				 
		   					
							header('Location: notification.php');  
				   		} 
						
						
				}
 ?>



</body>
</html>