<!DOCTYPE html>
<html>
<head>

  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
  
  <meta charset="utf-8">
    <title>QBnB Main User Page</title>
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
	<?php
		//Create a user session or resume an existing one
		session_start();
	?>
 	<?php
		if(isset($_SESSION['id']) && $_SESSION['type'] == "administrator") {
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

<!-- Nav Bar - Same on all pages -->
 <nav class="navbar navbar-inverse navbar-fixed-top">
   <div class="container-fluid">
     <div class="navbar-header">
       <a class="navbar-brand" href="index.php">QBnB</a>
     </div>
     <ul class="nav navbar-nav">
       <li><a href="gen_main.php">Home</a></li>
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
   							<li><a href="notification.php"><?php echo "Notifications (UNREAD)";?> </a></li>
   								<?php
    						} else{
    		 				    ?>
   							<li><a href="notification.php"><?php echo "Notifications (0)";?> </a></li>
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
  <h1>Admin: Booking Details</h1>
</div>
		<?php
			// Can only display page if booking_id is passed via 'get' parameter
			if(isset($_GET['booking_id']) && !empty($_GET['booking_id'])){
				include_once 'config/connection.php'; 
			 	$booking_id = $_GET['booking_id'];
			} else {
				echo "No booking specified.";
				die();
			}
		?>
		<?php
			// Member Delete has been confirmed
			if(isset($_GET['confirm_delete_btn'])) {
				// Find member_id and property_id of the booking if its in the future
				$query = "SELECT booking_id, member_id FROM booking
							WHERE start_date > curdate() AND
							booking_id=?;"; 

				// prepare query for execution
				$stmt = $con->prepare($query);

				// bind the parameters. This is the best way to prevent SQL injection hacks.
				$stmt->bind_Param('s', $booking_id);

				// Execute the query
				$stmt->execute();
				$futureBookings = $stmt->get_result();
				if ($futureBookings->num_rows > 0) {	
					// Should only loop once, because we queried on booking_id
					while($booking = $futureBookings->fetch_assoc()) {
						// Notify each member:
						$query = "INSERT INTO notification VALUES (NULL, ?, ?, FALSE);";
						$parm1 = $booking["member_id"];
						$parm2 = "Your booking #" . $booking["booking_id"] . " was cancelled.";
						$stmt = $con->prepare($query);
						$stmt->bind_Param('ss', $parm1, $parm2);
						$stmt->execute();
					}
				}
				
				// Delete the property and allow deletes to cascade:
				$query = "DELETE FROM booking WHERE booking_id=?;";
				$parm1 = $booking_id;
				$stmt = $con->prepare($query);
				$stmt->bind_Param('s', $parm1);
				$stmt->execute();
				
				echo "Booking # " . $booking_id . "was sucessfully deleted.";
				die();
				
			} elseif(isset($_GET['delete_btn'])) { // 'Delete' has been clicked but not confirmed
		?>
				<form class="form-inline" action='admin_booking.php' method=get>
					<label for="confirm_delete_btn">Are you sure you want to delete this booking?</label>
					<a href="admin_booking.php?booking_id=<?php echo $booking_id ?>" class="btn btn-default" role="button">Cancel</a>
					<input type='hidden' name='booking_id' id='booking_id' value="<?php echo $booking_id ?>">
					<input class="btn btn-danger"  name='confirm_delete_btn' id='confirm_delete_btn' value="Delete" type="submit">
				</form>
		<?php
			} else { // No action has been specified yet
		?>
				<form class="form-inline" action='admin_booking.php' method=get>
				<input class="btn btn-danger" name='delete_btn' id='delete_btn' value="Delete Booking" type="submit">
				<input type='hidden' name='booking_id' id='booking_id' value="<?php echo $booking_id?>">
				</form>
		<?php
			}
		?>
		
		<?php
			// SELECT query
				$query = "SELECT booking_id, member_id, property_id, start_date, status
							FROM booking
							WHERE booking_id=?;";

				// prepare query for execution
				$stmt = $con->prepare($query);

				// bind the parameters. This is the best way to prevent SQL injection hacks.
				$stmt->bind_Param("s", $booking_id);

				// Execute the query
				$stmt->execute();

				// results 
				$result = $stmt->get_result();
  
				if ($result->num_rows > 0) {
					 // output data of each row
					 $row = $result->fetch_assoc();
					 echo "<div class=\"table-responsive\">
						 <table class=\"table \" width=\"50\" >
					 <tr><th> Booking ID </th><td>" . $row["booking_id"]. "</td></tr><tr><th> Member ID </th><td>" . $row["member_id"] . "</td></tr><tr><th> Property ID </th><td>" . $row["property_id"] . "</td></tr><tr><th> Start Date </th><td>" . $row["start_date"] . "</td></tr><tr><th> Status </th><td>" . $row["status"] . "</td></tr>";
					 echo "</table></div>";
				} else {
					 echo "Oops! No details for this booking.";
				}
			?>

</body>
</html>