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
  <h1>Property Details</h1>
</div>
		<?php
		
		if($_SESSION['id']){
			 include_once 'config/connection.php'; 
			 
			$prop_id = $_GET['property_id'];
			?>
	
	  	  <form class="form-inline" action='property.php?property_id=<?php echo $prop_id ?>' method=get>
	  	  <input class="btn btn-info"  name='booking_btn' id='booking_btn' value="Book this property!" type="submit">
		  <input type='hidden' name='property_id' id='property_id' value="<?php echo $prop_id ?>">

		  
		  <?php
					
						if(isset($_GET['reply_btn'])){
							
							$prop_id = $_GET['property_id'];
				   			// SELECT query
				   	        $query = "INSERT into reply VALUES(NULL,?, ?, CURRENT_DATE)
				   					  ;"; 

				   	        // prepare query for execution
				   	        $stmt = $con->prepare($query);
			
				   	        // bind the parameters. This is the best way to prevent SQL injection hacks.
				   	        $stmt->bind_Param('is', $_GET['comment_id'], $_GET['text_input']);

				   	        // Execute the query
				   			$stmt->execute();
							
							header('Location: property.php?property_id=' . $prop_id );  
				   		}
					if(isset($_GET['booking_btn'])){
						$prop_id = $_GET['property_id'];
						
						?>
						
					  	<label for="sel4">Start Date:</label> 
					      <input type="text" required id="datepicker" name="date" placeholder="Specify Start Date">
						  <input class="btn btn-success"  name='confirm_btn' id='confirm_btn' value="Confirm" type="submit">
					    </form>
						<?php
						
						//header('Location: property.php?property_id=' . $prop_id );  
					}
					if(isset($_GET['confirm_btn'])){
						
			   			$dateArray = explode('/', $_GET['date']);
			   			$dateval = $dateArray[2].'-'.$dateArray[0].'-'.$dateArray[1];
						
						$query = "SELECT * from booking WHERE (status != 'Denied') AND ('$dateval' between start_date AND (DATE_ADD(start_date,INTERVAL 7 day))) OR ((DATE_ADD('$dateval',INTERVAL 7 day)) between start_date AND (DATE_ADD(start_date,INTERVAL 7 day)));";
							
							
							
						
			   	        // prepare query for execution
			   	        $stmt = $con->prepare($query);

			   	        // Execute the query
			   			$stmt->execute();
						
		 				// results 
		 				$result = $stmt->get_result();
		  
		 	if ($result->num_rows > 0) {
							 $message = "The property is not available at the specified time.";
							echo "<script type='text/javascript'>alert('$message');</script>"; 
									}
				
				else{
						
			   			// SELECT query
			   	        $query = "INSERT into booking VALUES(NULL,?, '$prop_id', '$dateval', 'requested')
			   					  ;"; 

			   	        // prepare query for execution
			   	        $stmt = $con->prepare($query);
		
			   	        // bind the parameters. This is the best way to prevent SQL injection hacks.
			   	        $stmt->bind_Param('i', $_SESSION['id']);

			   	        // Execute the query
			   			$stmt->execute();
						
						////////
						
						// SELECT query
				        $query = "SELECT member_id
								FROM supplies
								WHERE property_id = $prop_id
								  ;"; 
							//AND DATE_ADD(start_date, INTERVAL 7 day) >= CURRENT_DATE;

				        // prepare query for execution
				        $stmt = $con->prepare($query);
	
				        // bind the parameters. This is the best way to prevent SQL injection hacks.
				        //$stmt->bind_Param('i', $_GET['booking_id']);

				        // Execute the query
						$stmt->execute();
			
						// results 
						$result = $stmt->get_result();
			
						$row = $result->fetch_assoc();
			
						$owner = $row["member_id"];	
				
						// SELECT query
				        $query2 = "	
								  INSERT into notification VALUES (NULL,'$owner','A booking has been added to one of your properties.', FALSE)	
								  ;"; 
							//AND DATE_ADD(start_date, INTERVAL 7 day) >= CURRENT_DATE;

				        // prepare query for execution
				        $stmt2 = $con->prepare($query2);
	
				        // bind the parameters. This is the best way to prevent SQL injection hacks.
				        //$stmt2->bind_Param('i', $_GET['booking_id']);

				        // Execute the query
						$stmt2->execute();
						
						header('Location: property.php?property_id=' . $prop_id); 
			}
						 
					}
					
		 		   
					
					$prop_id = $_GET['property_id'];
		 			// SELECT query
		 		        $query = "SELECT
							property.property_id, street_number, street_name, apt_number,
							city,
								province,
								postal_code,
								price,
								type, district.district_name
								FROM
								Property NATURAL JOIN District
								WHERE
								Property_id = '$prop_id'
		 						  ;"; 
		 					//AND DATE_ADD(start_date, INTERVAL 7 day) >= CURRENT_DATE;
 
		 		        // prepare query for execution
		 		        $stmt = $con->prepare($query);
		
		 		        // bind the parameters. This is the best way to prevent SQL injection hacks.
		 		        //$stmt->bind_Param("s", $_SESSION['id']);

		 		        // Execute the query
		 				$stmt->execute();
 
		 				// results 
		 				$result = $stmt->get_result();
		  
		 				if ($result->num_rows > 0) {
		 				     // output data of each row
							 $row = $result->fetch_assoc();
							 echo "<div class=\"table-responsive\">
								 <table class=\"table \" width=\"50\" > <thead>
							 <tr><th> Property Id </th><th>" . $row["property_id"]. "<tr><th> Street Number </th><th>" . $row["street_number"] . "<tr><th> Street Name </th><th>" . $row["street_name"] . "<tr><th> Apt Number </th><th>" . $row["apt_number"] . "<tr><th> City </th><th>" . $row["city"] . "<tr><th> Province </th><th>" . $row["province"] . "<tr><th> Postal Code </th><th>" . $row["postal_code"] . "<tr><th> Price </th><th>" . $row["price"] . "<tr><th> Type </th><th>" . $row["type"] . "<tr><th> District </th><th>" . $row["district_name"];
		 				     echo "</thead></table></div>";
		 				} else {
		 				     echo "Oops! No details for this property.";
		 				}
						
				   		
				}			
 ?>

   <h3>Upcoming Bookings:</h3>

</th><th>
		<?php
		if($_SESSION['id']){

		 		    include_once 'config/connection.php'; 
					
					$prop_id = $_GET['property_id'];
		 			// SELECT query
		 		        $query = "SELECT
								booking_id,
								property_id,
								username,	
								start_date,
						DATE_ADD(start_date, INTERVAL 7 day) as end_date FROM
						Booking NATURAL JOIN member
						WHERE
						property_id = '$prop_id' AND DATE_ADD(start_date, INTERVAL 7 day) >= CURRENT_DATE AND status != 'Denied' ;
		 						  ;"; 
 
		 		        // prepare query for execution
		 		        $stmt = $con->prepare($query);
		
		 		        // bind the parameters. This is the best way to prevent SQL injection hacks.
		 		        //$stmt->bind_Param("s", $_SESSION['id']);

		 		        // Execute the query
		 				$stmt->execute();
 
		 				// results 
		 				$result = $stmt->get_result();
		  
		 				if ($result->num_rows > 0) {
					        echo "<div class=\"table-responsive\">
								 <table class=\"table\"> <thead>
							<tr><th> Booking Id </th><th> Property Id</th><th> Username </th><th> Start Date </th><th> End Date </tr>";
					        // output data of each row
					        while($row = $result->fetch_assoc()) {
					            echo "<tr><td>" . $row["booking_id"]. "</td><td>" . $row["property_id"] . "<td>" . $row["username"] . "<td>" . $row["start_date"]. "<td>"  . $row["end_date"];
					   			echo"</td></tr>";
					        }
					        echo "</thead></table></div>";
		 				} else {
		 				     echo "No upcoming bookings on this property.";
		 				}
				}			
				?>

  <h3>Features:</h3>
				
</th><th>
				<?php
				if($_SESSION['id']){

				 		    include_once 'config/connection.php'; 
					
							$prop_id = $_GET['property_id'];
				 			// SELECT query
				 		        $query = "SELECT
										feature
										FROM
										property NATURAL JOIN has_feature NATURAL JOIN property_feature
										WHERE
										property_id = '$prop_id';
				 						  ;"; 
 
				 		        // prepare query for execution
				 		        $stmt = $con->prepare($query);
		
				 		        // bind the parameters. This is the best way to prevent SQL injection hacks.
				 		        //$stmt->bind_Param("s", $_SESSION['id']);

				 		        // Execute the query
				 				$stmt->execute();
 
				 				// results 
				 				$result = $stmt->get_result();
		  
				 				if ($result->num_rows > 0) {
							        echo "<div class=\"table-responsive\">
								 <table class=\"table\"> <thead><tr><th> Feature </tr>";
							        // output data of each row
							        while($row = $result->fetch_assoc()) {
							            echo "<tr><td>" . $row["feature"];
							   			echo"</td></tr>";
							        }
							        echo "</thead></table></div>";
				 				} else {
				 				     echo "No features registered with this property.";
				 				}
						}			
						?>

  <h3>Landmarks Nearby</h3>
					
</th><th>
				<?php
				if($_SESSION['id']){

				 		    include_once 'config/connection.php'; 
					
							$prop_id = $_GET['property_id'];
				 			// SELECT query
				 		        $query = "SELECT
										poi_name
										FROM
										points_of_interest NATURAL JOIN property
										WHERE
										property_id = '$prop_id';
				 						  ;"; 
 
				 		        // prepare query for execution
				 		        $stmt = $con->prepare($query);
		
				 		        // bind the parameters. This is the best way to prevent SQL injection hacks.
				 		        //$stmt->bind_Param("s", $_SESSION['id']);

				 		        // Execute the query
				 				$stmt->execute();
 
				 				// results 
				 				$result = $stmt->get_result();
		  
				 				if ($result->num_rows > 0) {
							        echo "<div class=\"table-responsive\">
								 <table class=\"table\"> <thead><tr><th> Point of Interest: </tr>";
							        // output data of each row
							        while($row = $result->fetch_assoc()) {
							            echo "<tr><td>" . $row["poi_name"];
							   			echo"</td></tr>";
							        }
							        echo "</thead></table></div>";
				 				} else {
				 				     echo "No registered landmarks nearby";
				 				}
						}			
						?>											

<h3>Reviews:</h3>					
<tr><th>
		<?php
		if($_SESSION['id']){

		 		    include_once 'config/connection.php'; 
			
					$prop_id = $_GET['property_id'];
		 			// SELECT query
					
	 		        $query = "SELECT
							member_id
							FROM
							supplies
							WHERE
							property_id = '$prop_id';
	 						  ;"; 

	 		        // prepare query for execution
	 		        $stmt = $con->prepare($query);

	 		        // bind the parameters. This is the best way to prevent SQL injection hacks.
	 		        //$stmt->bind_Param("s", $_SESSION['id']);

	 		        // Execute the query
	 				$stmt->execute();

	 				// results 
	 				$result = $stmt->get_result();

	 				if ($result->num_rows > 0) {  
				        while($row = $result->fetch_assoc()) {
				           $prop_owner = $row['member_id']; 
				        }
	 				} 
					
					
		 		        $query = "SELECT
								booking_id,rating, text, comment_id, member_id, time
								FROM
								comment NATURAL JOIN booking b
								WHERE
								b.property_id = '$prop_id';
		 						  ;"; 

		 		        // prepare query for execution
		 		        $stmt = $con->prepare($query);

		 		        // bind the parameters. This is the best way to prevent SQL injection hacks.
		 		        //$stmt->bind_Param("s", $_SESSION['id']);

		 		        // Execute the query
		 				$stmt->execute();

		 				// results 
		 				$result = $stmt->get_result();
  
		 				if ($result->num_rows > 0) {
					        echo "<div class=\"table-responsive\">
								 <table class=\"table\"> <thead>
							<tr><th> Comment Id </th><th> Member Id </th><th> Rating </th><th> Comment </th><th> Time";
							
							if($prop_owner == $_SESSION['id']){
								echo "</th><th> Reply";
							}	
							echo "</tr>";
					        // output data of each row
					        while($row = $result->fetch_assoc()) {
					            echo "<tr>";
					            echo "<td>" . $row["comment_id"] . "</td>";
					            echo "<td>" . $row["member_id"] . "</td>";
					            echo "<td>" . $row["rating"] . "</td>";
					            echo "<td>" . $row["text"] . "</td>";
					            echo "<td>" . $row["time"] . "</td>";
								
								// Find owner of property
								$query = "SELECT reply.text FROM comment, reply WHERE reply.comment_id=" . $row["comment_id"] . ";";
								$stmt = $con->prepare($query);
								$stmt->execute();
								$replyRslt = $stmt->get_result();
								
								if($reply = $replyRslt->fetch_assoc()) { // If there is already a reply
									echo "<td>" . $reply["text"] . "</td>";
								} else { // There is no reply yet
									if($prop_owner == $_SESSION['id']) {
										echo "<th>";
										?> 
										<form action='admin_property.php' method=get>
											<input type='hidden' name='comment_id' id='comment_id' value="<?php echo $row['comment_id'] ?>">	
											<input type='hidden' name='property_id' id='property_id' value="<?php echo $prop_id ?>">
											<input type='text' name='text_input' id='text_input' placeholder="Reply to comment." >	
											<input type='submit'  name = 'reply_btn' id = 'reply_btn' value="Reply">	
			
										</form>
										<?php
									} else {
										echo "<td></td>"; // leave blank
									}
								}
					   			echo"</tr>";
					        }
					        echo "</thead></table></div>";
		 				} else {
		 				     echo "No reviews have been placed on this property.";
		 				} 
				}			
				?>
  <h3>Contact Info:</h3>								
</th><th>
				<?php
				if($_SESSION['id']){

				 		    include_once 'config/connection.php'; 
					
							
				 			// SELECT query
				 		        $query = "SELECT
										first_name,last_name, email
										FROM
										member m NATURAL JOIN supplies s
										WHERE
										s.property_id = '$prop_id';
				 						  ;"; 
 
				 		        // prepare query for execution
				 		        $stmt = $con->prepare($query);
		
				 		        // bind the parameters. This is the best way to prevent SQL injection hacks.
				 		        //$stmt->bind_Param("s", $_SESSION['id']);

				 		        // Execute the query
				 				$stmt->execute();
 
				 				// results 
				 				$result = $stmt->get_result();
		  
				 				if ($result->num_rows > 0) {
							        echo "<div class=\"table-responsive\">
								 <table class=\"table\">
									<tr><th> First Name </th><th> Last Name </th><th> Email </tr>";
							        // output data of each row
							        while($row = $result->fetch_assoc()) {
							            echo "<tr><td>" . $row["first_name"] . "</th><th>" . $row["last_name"] . "</th><th>" . $row["email"];
							   			echo"</td></tr>";
							        }
							        echo "</table></div>";
				 				} else {
				 				     echo "No contact info available.";
				 				}
						}			
						?>								
   		
	
     



		
</body>
</html>