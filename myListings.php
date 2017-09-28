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

		<?php
		if($_SESSION['id']){
		 include_once 'config/connection.php'; 
			if(isset($_GET['edit_btn'])){
							$prop_id = $_GET['property_id'];
							header('Location: editpage.php?property_id=' . $prop_id . "?edit=1"); 
						}
						elseif(isset($_GET['create_btn'])){
							header('Location: addNewBooking.php'); 
						} 
		
		if(isset($_GET['update_btn'])){
		   // include database connection
		   
			// SELECT query
		        $query = "UPDATE
		  		  		  booking
						  SET
						  status = 'confirmed'
						WHERE
						  booking_id = ?
						  ;"; 
					//AND DATE_ADD(start_date, INTERVAL 7 day) >= CURRENT_DATE;
 
		        // prepare query for execution
		        $stmt = $con->prepare($query);
		
		        // bind the parameters. This is the best way to prevent SQL injection hacks.
		        $stmt->bind_Param('i', $_GET['booking_id']);

		        // Execute the query
				$stmt->execute();
				
				
				// SELECT query
		        $query = "SELECT member_id
						FROM booking
						WHERE booking_id = ?
						  ;"; 
					//AND DATE_ADD(start_date, INTERVAL 7 day) >= CURRENT_DATE;

		        // prepare query for execution
		        $stmt = $con->prepare($query);
	
		        // bind the parameters. This is the best way to prevent SQL injection hacks.
		        $stmt->bind_Param('i', $_GET['booking_id']);

		        // Execute the query
				$stmt->execute();
			
				// results 
				$result = $stmt->get_result();
			
				$row = $result->fetch_assoc();
			
				$consumer = $row["member_id"];	
				$booking = $_GET['booking_id'];
				
				// SELECT query
		        $query2 = "INSERT into notification VALUES (NULL,'$consumer',?, FALSE)	
						  ;"; 
				
				$parm2 = "Your booking #" . $booking . " was confirmed, thank you!";
				$stmt2 = $con->prepare($query2);
				$stmt2->bind_Param('s', $parm2);

		        // Execute the query
				$stmt2->execute();

				// results 
				//$result = $stmt->get_result();
				header("Location: myListings.php");
		} 
	}	
 ?>
 
		<?php
		if(isset($_GET['deny_btn']) && $_SESSION['id']){
		   // include database connection
		    include_once 'config/connection.php'; 
			// SELECT query
		        $query = "UPDATE
		  		  		  booking
						  SET
						  status = 'Denied'
						WHERE
						  booking_id = ?
						  ;"; 
					//AND DATE_ADD(start_date, INTERVAL 7 day) >= CURRENT_DATE;
 
		        // prepare query for execution
		        $stmt = $con->prepare($query);
		
		        // bind the parameters. This is the best way to prevent SQL injection hacks.
		        $stmt->bind_Param('i', $_GET['booking_id']);

		        // Execute the query
				$stmt->execute();
				
				
				// SELECT query
		        $query = "SELECT member_id
						FROM booking
						WHERE booking_id = ?
						  ;"; 
					//AND DATE_ADD(start_date, INTERVAL 7 day) >= CURRENT_DATE;

		        // prepare query for execution
		        $stmt = $con->prepare($query);
	
		        // bind the parameters. This is the best way to prevent SQL injection hacks.
		        $stmt->bind_Param('i', $_GET['booking_id']);

		        // Execute the query
				$stmt->execute();
			
				// results 
				$result = $stmt->get_result();
			
				$row = $result->fetch_assoc();
			
				$consumer = $row["member_id"];	
				$booking = $row['booking_id'];
				
				// SELECT query
		        $query2 = "INSERT into notification VALUES (NULL,'$consumer',?, FALSE)";
	


				$parm2 = "Your booking #" . $_GET["booking_id"] . " was denied, sorry!";
				$stmt2 = $con->prepare($query2);
				$stmt2->bind_Param('s', $parm2);
				
		        // Execute the query
				$stmt2->execute();

				// results 
				//$result = $stmt->get_result();
				header("Location: myListings.php");
		} 
 ?>

 <nav class="navbar navbar-inverse navbar-fixed-top">
   <div class="container-fluid">
     <div class="navbar-header">
       <a class="navbar-brand" href="index.php">QBnB</a>
     </div>
     <ul class="nav navbar-nav">
       <li><a href="gen_main.php">Home</a></li>
       <li class="active"><a href="myListings.php">My Listings</a></li>
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
  <h1>Your Listings</h1>
</div>
	  	  <form class="form-inline" action='myListings.php' method=get>
	  	  <input class="btn btn-info"  name='create_btn' id='create_btn' value="Create a listing" type="submit">
	  </form>
		  
		<?php
		if($_SESSION['id']){

		 		    include_once 'config/connection.php'; 
		 			// SELECT query
		 		        $query = "SELECT
								property.property_id, street_number, street_name, apt_number,
							city, province, postal_code, price,
								type,
									district.district_name 
									FROM
									property NATURAL JOIN District NATURAL JOIN Supplies WHERE
									Supplies.member_id = ?
		 						  ;"; 
		 					//AND DATE_ADD(start_date, INTERVAL 7 day) >= CURRENT_DATE;
 
		 		        // prepare query for execution
		 		        $stmt = $con->prepare($query);
		
		 		        // bind the parameters. This is the best way to prevent SQL injection hacks.
		 		        $stmt->bind_Param("s", $_SESSION['id']);

		 		        // Execute the query
		 				$stmt->execute();
 
		 				// results 
		 				$result = $stmt->get_result();
		  
		 				if ($result->num_rows > 0) {
					
		 				     echo "<div class=\"table-responsive\">
								 <table class=\"table\"> <thead>
								 <tr>
							 <th> Property Id </th>
							 <th> Street #</th>
							 <th> Street Name </th>
							 <th> Apt # </th>
							 <th> City </th>
							 <th> Province </th>
							 <th> Postal Code </th>
							 <th> Price </th>
							 <th> type </th>
							 <th> District </th>
							 <th>	</th> </tr>";
		 				     // output data of each row
		 				     while($row = $result->fetch_assoc()) {
		 				         echo "<tr><td>" . "<a href=\"property.php?property_id=" . $row["property_id"] . "\">" .    $row["property_id"] . "</a>" . "</td><td>" . $row["street_number"]. "<td>" . $row["street_name"]. "<td>" . $row["apt_number"] . "<td>" . $row["city"]. "<td>" . $row["province"]. "<td>" . $row["postal_code"]. "<td>" . $row["price"]. "<td>" . $row["type"] . "<td>" . $row["district_name"] . "<td>"; 
								 ?> 
					 			<form action='myListings.php' method=get>
								<input type='hidden' name='property_id' id='property_id' value="<?php echo $row['property_id'] ?>">	
								<input type='submit'  name = 'edit_btn' id = 'edit_btn' value="EDIT">
								<input type='submit'  name = 'delete_btn' id = 'delete_btn' value="Delete">
					 			</form>
								<?php
								
		 							echo"</td></tr>";
		 				     }
		 				     echo "</thead></table></div>";
		 				} else {
		 				     echo "Nothing Matched Your Search";
		 				}
						
						
						
				
				if(isset($_GET['delete_btn'])) { // 'Delete' has been clicked but not confirmed
					$prop_id = $_GET['property_id'];
					?>
					<form class="form-inline" action='myListings.php' method=get>
					<label for="confirm_delete_btn">Are you sure you want to delete this property?</label>
					<a href="myListings.php" class="btn btn-default" role="button">Cancel</a>
					<input type='hidden' name='property_id' id='property_id' value="<?php echo $prop_id ?>">
					<input class="btn btn-danger"  name='confirm_delete_btn' id='confirm_delete_btn' value="Delete" type="submit">
					</form>
					<?php
				}
				if(isset($_GET['confirm_delete_btn'])) { // 'Delete' has been clicked but not confirmed
					// Find all members with future bookings on property being deleted:
					$query = "SELECT booking_id, member_id FROM booking WHERE property_id=? AND start_date > curdate();"; 

					// prepare query for execution
					$stmt = $con->prepare($query);

					// bind the parameters. This is the best way to prevent SQL injection hacks.
					$stmt->bind_Param('s', $_GET['property_id']);

					// Execute the query
					$stmt->execute();
					$affectedMembers = $stmt->get_result();
					if ($affectedMembers->num_rows > 0) {	
						while($member = $affectedMembers->fetch_assoc()) {
							// Notify each member:
							$query = "INSERT INTO notification VALUES (NULL, ?, ?, FALSE);";
							$parm1 = $member["member_id"];
							$parm2 = "Your booking #" . $member["booking_id"] . " was cancelled.";
							$stmt = $con->prepare($query);
							$stmt->bind_Param('ss', $parm1, $parm2);
							$stmt->execute();
						}
					}
				
					// Delete the property and allow deletes to cascade:
					$query = "DELETE FROM property WHERE property_id=?;";
					$parm1 = $_GET['property_id'];
					$stmt = $con->prepare($query);
					$stmt->bind_Param('s', $parm1);
					$stmt->execute();
					header("Location: myListings.php");
					
						}
			}								
					
 ?>
 
 
 
<div class="page-header">
  <h1>Upcoming Bookings</h1>
</div>
 		<?php
 		if(isset($_SESSION['id'])){
 		   // include database connection
 		    include_once 'config/connection.php'; 
	
 			// SELECT query
 		        $query = "SELECT
 		  		  		  booking_id,
 				 	 	  B.property_id,
 						  username,
 						  start_date,
 						  status
 						FROM
 						  Supplies S,
 						  Booking B Natural Join Member
 						WHERE
 						  S.member_id = ? AND S.property_id = B.property_id AND status != 'Requested' AND status != 'Denied'
 						  ;"; 
 					//AND DATE_ADD(start_date, INTERVAL 7 day) >= CURRENT_DATE;
 
 		        // prepare query for execution
 		        $stmt = $con->prepare($query);
		
 		        // bind the parameters. This is the best way to prevent SQL injection hacks.
 		        $stmt->bind_Param("s", $_SESSION['id']);

 		        // Execute the query
 				$stmt->execute();
 
 				// results 
 				$result = $stmt->get_result();
		
 				if ($result->num_rows > 0) {
 				     echo "<div class=\"table-responsive\">
								 <table class=\"table\"> <thead>
					 <tr><th> Booking Id </th><th> Property ID </th><th> Renter </th><th> Start Date </th><th> Status</tr>";
 				     // output data of each row
 				     while($row = $result->fetch_assoc()) {
 				         echo "<tr><td>" . $row["booking_id"]. "</td><td>" . "<a href=\"property.php?property_id=" . $row["property_id"] . "\">" . $row["property_id"]. "<td>" . $row["username"]. "<td>" . $row["start_date"]. "<td>" . $row["status"]. "</td></tr>";
 				     }
 				     echo "</thead></table></div>";
 				} else {
 				     echo "You have no upcoming bookings.";
 				}
 		} 
  ?>
<div class="page-header">
  <h1>Requested Bookings</h1>
</div>
		<?php
		if(isset($_SESSION['id'])){
		   // include database connection
		    include_once 'config/connection.php'; 
	
			// SELECT query
		        $query = "SELECT
		  		  		  booking_id,
				 	 	  B.property_id,
						  username,
						  start_date,
						  status
						FROM
						  Supplies S,
						  Booking B Natural Join Member
						WHERE
						  S.member_id = ? AND S.property_id = B.property_id AND status = 							'Requested'
						  ;"; 
					//AND DATE_ADD(start_date, INTERVAL 7 day) >= CURRENT_DATE;
 
		        // prepare query for execution
		        $stmt = $con->prepare($query);
		
		        // bind the parameters. This is the best way to prevent SQL injection hacks.
		        $stmt->bind_Param("s", $_SESSION['id']);

		        // Execute the query
				$stmt->execute();
 
				// results 
				$result = $stmt->get_result();
		
				if ($result->num_rows > 0) {
				     echo "<div class=\"table-responsive\">
								 <table class=\"table\"> <thead>
					 <tr><th> Booking Id </th><th> Property ID </th><th> Renter </th><th> Start Date </th><th> Status</tr>";
				     // output data of each row
				     while($row = $result->fetch_assoc()) {
				         echo "<tr><td>" . $row["booking_id"]. "</td><td>" . "<a href=\"property.php?property_id=" . $row["property_id"] . "\">" . $row["property_id"]. "<td>" . $row["username"]. "<td>" . $row["start_date"]. "<td>" . $row["status"]. "</td><td>"; 
						 ?> 
			 			<form action='myListings.php' method=get>
						<input type='hidden' name='booking_id' id='booking_id' value="<?php echo $row['booking_id'] ?>">	
			 		    <input type='submit'  name = 'update_btn' id = 'update_btn' value="Approve">
						<input type='submit'  name = 'deny_btn' id = 'deny_btn' value="Deny">
			 			</form>
						<?php
							
							
							echo"</td></tr>";
				     }
				     echo "</thead></table></div>";
				} else {
				     echo "You have no bookings to approve.";
				}
		} else {
			//User is not logged in. Redirect the browser to the login index.php page and kill this page.
			header("Location: index.php");
			die();
		}
	
 ?>
 
 <br/>
		


</body>
</html>