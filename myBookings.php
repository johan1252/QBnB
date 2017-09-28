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
input[type=submit]{
    background-color: #000000; 
    color: #ffffff;
}
input[type=text]{
  color: #000000;
}
</style>
-->		

 <?php
  //Create a user session or resume an existing one
 session_start();
 ?>
 
 <?php
if(isset($_SESSION['id']) ) {
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
       <li class="active"><a href="myBookings.php">My Bookings</a></li>
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
						
						if(isset($_GET['review_btn']) && $_SESSION['id']){
						   // include database connection
						    include_once 'config/connection.php'; 
							// SELECT query
						        $query = "INSERT INTO comment VALUES(null, ?, ?, ?, CURRENT_TIME)
										  ;"; 
									//AND DATE_ADD(start_date, INTERVAL 7 day) >= CURRENT_DATE;
 
						        // prepare query for execution
						        $stmt = $con->prepare($query);
		
						        // bind the parameters. This is the best way to prevent SQL injection hacks.
						        $stmt->bind_Param('iis', $_GET['booking_id'],$_GET['rating'], $_GET['text_input']);

						        // Execute the query
								$stmt->execute();
 
								// results 
								//$result = $stmt->get_result();
								header("Location: myBookings.php");
						} 
							
						if(isset($_GET['delete_btn']) && $_SESSION['id']){
						   // include database connection
						    include_once 'config/connection.php'; 
				
							// SELECT query
					        $query = "SELECT supplies.member_id
									FROM booking, supplies
									WHERE booking_id = ? AND booking.property_id = supplies.property_id
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
			
							$owner = $row["member_id"];	
				
							// SELECT query
					        $query2 = "	
									  INSERT into notification VALUES (NULL,'$owner','A booking on one of your properties was removed', FALSE)	
									  ;"; 
								//AND DATE_ADD(start_date, INTERVAL 7 day) >= CURRENT_DATE;

					        // prepare query for execution
					        $stmt2 = $con->prepare($query2);
	
					        // bind the parameters. This is the best way to prevent SQL injection hacks.
					        //$stmt2->bind_Param('i', $_GET['booking_id']);

					        // Execute the query
							$stmt2->execute();

							// SELECT query
					        $query3 = "DELETE from booking where booking_id = ?
									  ;"; 
								//AND DATE_ADD(start_date, INTERVAL 7 day) >= CURRENT_DATE;

					        // prepare query for execution
					        $stmt3 = $con->prepare($query3);
	
					        // bind the parameters. This is the best way to prevent SQL injection hacks.
					        $stmt3->bind_Param('i', $_GET['booking_id']);

					        // Execute the query
							$stmt3->execute();
			

							// results 
							//$result = $stmt->get_result();
							header("Location: myBookings.php");	
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
  <h1>Your Future Bookings</h1>
</div>

		<?php
		if($_SESSION['id']){

		 		    include_once 'config/connection.php'; 
		 			// SELECT query
		 		        $query = "SELECT
							booking_id, property_id, start_date, status, street_number, street_name, apt_number, city, province,postal_code, type, price FROM
						booking book NATURAL JOIN property WHERE
						book.member_id = ? AND DATE_ADD(start_date, INTERVAL 7 day) >= CURRENT_DATE;
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
							 <tr><th> Booking Id </th><th> Property Id</th><th> Start Date </th><th> Status </th><th> Street # </th><th> Street Name </th><th> Apt # </th><th> City </th><th> Province </th><th> Postal Code </th><th> Type </th><th> Price <th>	</th></tr>";
		 				     // output data of each row
		 				     while($row = $result->fetch_assoc()) {
		 				         echo "<tr><td>" . $row["booking_id"]. "</td><td>" . "<a href=\"property.php?property_id=" . $row["property_id"] . "\">" . $row["property_id"]. "<td>";
 								
 								if(isset($_GET['edit_btn'])){
									?> 
									<form action='myBookings.php' method=get>
									<input type='hidden' name='booking_id' id='booking_id' value="<?php echo $row['booking_id'] ?>">
 									<input type='text' name='new_date' id='new_date'  value="<?php echo $row['start_date']; ?>"  />
									<input type='submit'  name = 'confirm_btn' id = 'confirm_btn' value="Submit">
								</form>
									<?php
									echo "<td>";
 								}
 								else{
 									echo $row["start_date"]. "<td>";
 								} 
								 echo $row["status"] . "<td>" . $row["street_number"]. "<td>" . $row["street_name"]. "<td>" . $row["apt_number"]. "<td>" . $row["city"]. "<td>" . $row["province"]. "<td>" . $row["postal_code"] . "<td>" . $row["type"] . "<td>" . $row["price"] . "<td>"; 
								 ?> 
					 			<form action='myBookings.php' method=get>
								<input type='hidden' name='booking_id' id='booking_id' value="<?php echo $row['booking_id'] ?>">	
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
				}
				
 ?>

		<?php
		
 ?>
 
   		<?php
   		if(isset($_GET['confirm_btn']) && $_SESSION['id']){
   		   // include database connection
   		    include_once 'config/connection.php'; 
			
			$new = $_GET['new_date'];	
   			// SELECT query
   	        $query = "UPDATE booking
   					SET start_date = '$new', status = 'Requested'
   					WHERE booking_id = ?
   					  ;"; 
   				//AND DATE_ADD(start_date, INTERVAL 7 day) >= CURRENT_DATE;

   	        // prepare query for execution
   	        $stmt = $con->prepare($query);
			
   	        // bind the parameters. This is the best way to prevent SQL injection hacks.
   	        $stmt->bind_Param('i', $_GET['booking_id']);

   	        // Execute the query
   			$stmt->execute();
			
			// SELECT query
	        $query3 = "SELECT supplies.member_id
					FROM booking, supplies
					WHERE booking_id = ? AND booking.property_id = supplies.property_id
					  ;"; 
				//AND DATE_ADD(start_date, INTERVAL 7 day) >= CURRENT_DATE;

	        // prepare query for execution
	        $stmt3 = $con->prepare($query3);
	
	        // bind the parameters. This is the best way to prevent SQL injection hacks.
	        $stmt3->bind_Param('i', $_GET['booking_id']);

	        // Execute the query
			$stmt3->execute();
			
			// results 
			$result3 = $stmt3->get_result();
			
			$row = $result3->fetch_assoc();
			
			$owner = $row["member_id"];	
			
			// SELECT query
	        $query2 = "	
					  INSERT into notification VALUES (NULL,'$owner','A booking on one of your properties was altered', FALSE)	
					  ;"; 
				//AND DATE_ADD(start_date, INTERVAL 7 day) >= CURRENT_DATE;

	        // prepare query for execution
	        $stmt2 = $con->prepare($query2);
	
	        // bind the parameters. This is the best way to prevent SQL injection hacks.
	        //$stmt2->bind_Param('i', $_GET['booking_id']);

	        // Execute the query
			$stmt2->execute();
			
			
   			header("Location: myBookings.php");	
   		} 
    ?>	
 
<div class="page-header">
  <h1>Pending Review</h1>
</div>

		<?php
		if($_SESSION['id']){

		 		    include_once 'config/connection.php'; 
		 			// SELECT query
		 		        $query = "SELECT
								booking_id, property_id,
							start_date, street_number, street_name, apt_number, city, province, postal_code
								FROM
									booking book NATURAL JOIN property
										WHERE
										book.member_id = ? AND DATE_ADD(start_date, INTERVAL 7 day) <
									CURRENT_DATE AND NOT EXISTS(     SELECT *
									  				FROM
													comment
													WHERE
													book.booking_id = comment.booking_id)
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
								 <table class=\"table\"> <thead><tr><th> Booking Id </th><th> Property Id</th><th> Start Date </th><th> Street # </th><th> Street Name </th><th> Apt # </th><th> City </th><th> Province </th><th> Postal Code </th><th> Rating & Review </tr>";
		 				     // output data of each row
		 				     while($row = $result->fetch_assoc()) {
		 				         echo "<tr><td>" . $row["booking_id"]. "</td><td>" . "<a href=\"property.php?property_id=" . $row["property_id"] . "\">" . $row["property_id"]. "<td>" . $row["start_date"]. "<td>"  . $row["street_number"]. "<td>" . $row["street_name"]. "<td>" . $row["apt_number"]. "<td>" . $row["city"]. "<td>" . $row["province"]. "<td>" . $row["postal_code"] . "<td>"; 		
								 ?> 
					 			<form action='myBookings.php' method=get>
								<input type='hidden' name='booking_id' id='booking_id' value="<?php echo $row['booking_id'] ?>">	
							    <select name="rating" id="rating">
								  <option>1</option>
							      <option>2</option>
							      <option>3</option>
							      <option>4</option>
							      <option>5</option>
							    </select>
								<input type='text' name='text_input' id='text_input' >	
								<input type='submit'  name = 'review_btn' id = 'review_btn' value="Review">
					 			</form>
								<?php
		 							echo"</td></tr>";
		 				     }
		 				     echo "</thead></table></div>";
		 				} else {
		 				     echo "Nothing Matched Your Search";
		 				}
				}

 ?>
 

	
<div class="page-header">
  <h1>Past Bookings</h1>
</div>

		<?php
		if($_SESSION['id']){

		 		    include_once 'config/connection.php'; 
		 			// SELECT query
		 		        $query = "SELECT
								booking_id, property_id,
							start_date, street_number, street_name, apt_number, city, province, postal_code, rating, text
								FROM
									booking book NATURAL JOIN property NATURAL JOIN comment
										WHERE
										book.member_id = ? AND DATE_ADD(start_date, INTERVAL 7 day) <
									CURRENT_DATE AND EXISTS(     SELECT *
									  				FROM
													comment
													WHERE
													book.booking_id = comment.booking_id)
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
								 <table class=\"table\"> <thead><tr><th> Booking Id </th><th> Property Id</th><th> Start Date </th><th> Street # </th><th> Street Name </th><th> Apt # </th><th> City </th><th> Province </th><th> Postal Code </th><th> Your Review </tr>";
		 				     // output data of each row
		 				     while($row = $result->fetch_assoc()) {
		 				         echo "<tr><td>" . $row["booking_id"]. "</td><td>" . "<a href=\"property.php?property_id=" . $row["property_id"] . "\">" . $row["property_id"] . "<td>" . $row["start_date"]. "<td>"  . $row["street_number"]. "<td>" . $row["street_name"]. "<td>" . $row["apt_number"]. "<td>" . $row["city"]. "<td>" . $row["province"]. "<td>" . $row["postal_code"] . "<td>"."Rating: ". $row['rating']. " , " . $row["text"];
		 							echo"</td></tr>";
		 				     }
		 				     echo "</thead></table></div>";
		 				} else {
		 				     echo "Nothing Matched Your Search";
		 				}
				}

 ?>							

</body>
</html>