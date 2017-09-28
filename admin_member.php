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
	   <li><a href="userProfile.php"> <?php echo $myrow['username'];?> </a></li>
	   <li><a href="index.php?logout=1">Log Out</a></li>
     </ul>
   </div>
 </nav>
<br>
<br>
<br>



<div class="page-header">
  <h1>Admin: Member Details</h1>
</div>
		<?php
			// Can only display page if property_id is passed via 'get' parameter
			if(isset($_GET['member_id']) && !empty($_GET['member_id'])){
				include_once 'config/connection.php'; 
			 	$member_id = $_GET['member_id'];
			} else {
				echo "No member specified.";
				die();
			}
		?>
		<?php
			// Member Delete has been confirmed
			if(isset($_GET['confirm_delete_btn'])) {
				// Find member_id and property_id of any future bookings
				$query = "SELECT booking_id, member_id FROM booking WHERE start_date > curdate() AND
							property_id in ( SELECT property_id
											FROM supplies
											WHERE member_id=?);"; 

				// prepare query for execution
				$stmt = $con->prepare($query);

				// bind the parameters. This is the best way to prevent SQL injection hacks.
				$stmt->bind_Param('s', $member_id);

				// Execute the query
				$stmt->execute();
				$futureBookings = $stmt->get_result();
				if ($futureBookings->num_rows > 0) {	
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
				
				// Find all properties owned by member and delete them:
				$query = "SELECT property_id FROM supplies WHERE member_id=?;";
				$stmt = $con->prepare($query);
				$stmt->bind_Param('s', $member_id);
				$stmt->execute();
				$listings = $stmt->get_result();
				if($listings->num_rows > 0) {
					while($property = $listings->fetch_assoc()) {
						$query2 = "DELETE FROM property WHERE property_id=?;";
						$stmt2 = $con->prepare($query2);
						$stmt2->bind_Param('s', $property['property_id']);
						$stmt2->execute();
					}
				}
				
				
				// Delete the property and allow deletes to cascade:
				$query = "DELETE FROM member WHERE member_id=?;";
				$parm1 = $member_id;
				$stmt = $con->prepare($query);
				$stmt->bind_Param('s', $parm1);
				$stmt->execute();
				
				echo "Member # " . $member_id . "sucessfully deleted.";
				die();
				
			} elseif(isset($_GET['delete_btn'])) { // 'Delete' has been clicked but not confirmed
		?>
				<form class="form-inline" action='admin_member.php' method=get>
					<label for="confirm_delete_btn">Are you sure you want to delete this member?</label>
					<a href="admin_member.php?member_id=<?php echo $member_id ?>" class="btn btn-default" role="button">Cancel</a>
					<input type='hidden' name='member_id' id='member_id' value="<?php echo $member_id ?>">
					<input class="btn btn-danger"  name='confirm_delete_btn' id='confirm_delete_btn' value="Delete" type="submit">
				</form>
		<?php
			} else { // No action has been specified yet
		?>
				<form class="form-inline" action='admin_member.php' method=get>
				<input class="btn btn-danger" name='delete_btn' id='delete_btn' value="Delete Member" type="submit">
				<input type='hidden' name='member_id' id='member_id' value="<?php echo $member_id?>">
				</form>
		<?php
			}
		?>
		
		<?php
			// SELECT query
				$query = "SELECT member_id, member_type, first_name, last_name, email, username FROM member WHERE member_id=?;";

				// prepare query for execution
				$stmt = $con->prepare($query);

				// bind the parameters. This is the best way to prevent SQL injection hacks.
				$stmt->bind_Param("s", $member_id);

				// Execute the query
				$stmt->execute();

				// results 
				$result = $stmt->get_result();
  
				if ($result->num_rows > 0) {
					 // output data of each row
					 $row = $result->fetch_assoc();
					 echo "<div class=\"table-responsive\">
						 <table class=\"table \" width=\"50\" >
					 <tr><th> Member ID </th><td>" . $row["member_id"]. "</td></tr><tr><th> Member Type </th><td>" . $row["member_type"] . "</td></tr><tr><th> First Name </th><td>" . $row["first_name"] . "</td></tr><tr><th> Last Name </th><td>" . $row["last_name"] . "</td></tr><tr><th> Email </th><td>" . $row["email"] . "</td></tr><tr><th> Username </th><td>" . $row["username"] . "</td></tr>";
					 echo "</table></div>";
				} else {
					 echo "Oops! No details for this member.";
				}
			?>

   <h3>Property Listings:</h3>
		<?php
			include_once 'config/connection.php'; 
			// SELECT query
			$query = "SELECT property_id, district_name, street_number, street_name, apt_number, city, province, postal_code, price, type 
						FROM property NATURAL JOIN district NATURAL JOIN supplies WHERE
						supplies.member_id = ?;"; 
				//AND DATE_ADD(start_date, INTERVAL 7 day) >= CURRENT_DATE;

			// prepare query for execution
			$stmt = $con->prepare($query);

			// bind the parameters. This is the best way to prevent SQL injection hacks.
			$stmt->bind_Param("s", $member_id);

			// Execute the query
			$stmt->execute();

			// results 
			$result = $stmt->get_result();

			if ($result->num_rows > 0) {
				echo 
					 "<div class=\"table-responsive\">
						 <table class=\"table\"> 
							<thead>
								<th>Property ID</th>
								<th>District</th>
								<th>Street Number</th>
								<th>Street</th>
								<th>Apt. Number</th>
								<th>City</th>
								<th>Province</th>
								<th>Postal Code</th>
								<th>Price ($)</th>
								<th>Type</th>
							</thead>
						 <tr>";
				while($row = $result->fetch_assoc()) {	 
					echo "<tr>";
					echo "<td><a href=admin_property.php?property_id=" . $row["property_id"] . ">" . $row["property_id"]. "</a>" . "</td>";
					echo "<td>" . $row["district_name"] . "</td>";
					echo "<td>" . $row["street_number"] . "</td>";
					echo "<td>" . $row["street_name"] . "</td>";
					echo "<td>" . $row["apt_number"] . "</td>";
					echo "<td>" . $row["city"] . "</td>";
					echo "<td>" . $row["province"] . "</td>";
					echo "<td>" . $row["postal_code"] . "</td>";
					echo "<td>" . $row["price"] . "</td>";
					echo "<td>" . $row["type"] . "</td>";
					echo "</tr>";	
				 }
				 // output data of each row
	
				 echo "</table></div>";
			} else {
				 echo "Member has no property listings.";
			}
		?>
				
   <h3>Property Bookings:</h3>
   <!-- By property -->
	<?php
			// Redo the query from above......This is a huge hack
			// SELECT query
			$query = "SELECT property_id, district_name, street_number, street_name, apt_number, city, province, postal_code, price, type 
						FROM property NATURAL JOIN district NATURAL JOIN supplies WHERE
						supplies.member_id = ?;"; 
				//AND DATE_ADD(start_date, INTERVAL 7 day) >= CURRENT_DATE;

			// prepare query for execution
			$stmt = $con->prepare($query);

			// bind the parameters. This is the best way to prevent SQL injection hacks.
			$stmt->bind_Param("s", $member_id);

			// Execute the query
			$stmt->execute();

			// results 
			$result = $stmt->get_result();
			
			if ($result->num_rows > 0) {
				while($row = $result->fetch_assoc()) {
					echo "<h4>" . $row['street_number'] . " " . $row['street_name'] . "</h4>";
					// SELECT query
					$query = "SELECT booking_id, member_id, start_date, status 
								FROM booking WHERE
								property_id=?;"; 
						//AND DATE_ADD(start_date, INTERVAL 7 day) >= CURRENT_DATE;

					// prepare query for execution
					$stmt = $con->prepare($query);

					// bind the parameters. This is the best way to prevent SQL injection hacks.
					$stmt->bind_Param("s", $row['property_id']);

					// Execute the query
					$stmt->execute();

					// results 
					$result2 = $stmt->get_result();

					if ($result2->num_rows > 0) {
						echo 
							 "<div class=\"table-responsive\">
								 <table class=\"table\"> 
									<thead>
										<th>Booking ID</th>
										<th>Member ID</th>
										<th>Start Date</th>
										<th>Status</th>
									</thead>
								 <tr>";
						while($row2 = $result2->fetch_assoc()) {	 
							echo "<tr>";
							//echo "<td><a href=admin_property.php?property_id=" . $row["property_id"] . ">" . $row["property_id"]. "</a>" . "</td>";
							echo "<td><a href=admin_booking.php?booking_id=" . $row2["booking_id"] . ">" . $row2["booking_id"]. "</a>" . "</td>";
							echo "<td>" . $row2["member_id"] . "</td>";
							echo "<td>" . $row2["start_date"] . "</td>";
							echo "<td>" . $row2["status"] . "</td>";
							echo "</tr>";	
						 }
						 // output data of each row
	
						 echo "</table></div>";
					} else {
						 echo "Property has no bookings.";
					}
				 }
				 // output data of each row
	
			} else {
				 echo "Member has no property listings.";
			}
	?>

	<h3>Property Reviews:</h3>
		<?php
			// Redo the query from above......This is a huge hack
			// SELECT query
			$query = "SELECT property_id, district_name, street_number, street_name, apt_number, city, province, postal_code, price, type 
						FROM property NATURAL JOIN district NATURAL JOIN supplies WHERE
						supplies.member_id = ?;"; 
				//AND DATE_ADD(start_date, INTERVAL 7 day) >= CURRENT_DATE;

			// prepare query for execution
			$stmt = $con->prepare($query);

			// bind the parameters. This is the best way to prevent SQL injection hacks.
			$stmt->bind_Param("s", $member_id);

			// Execute the query
			$stmt->execute();

			// results 
			$result = $stmt->get_result();
			
			if ($result->num_rows > 0) {
				while($row = $result->fetch_assoc()) {
					echo "<h4>" . $row['street_number'] . " " . $row['street_name'] . "</h4>";
					// SELECT query
					$query = "SELECT comment_id, booking_id, rating, text, time
								FROM comment NATURAL JOIN booking
								WHERE property_id=?;"; 
						//AND DATE_ADD(start_date, INTERVAL 7 day) >= CURRENT_DATE;

					// prepare query for execution
					$stmt = $con->prepare($query);

					// bind the parameters. This is the best way to prevent SQL injection hacks.
					$stmt->bind_Param("s", $row["property_id"]);

					// Execute the query
					$stmt->execute();

					// results 
					$result2 = $stmt->get_result();

					if ($result2->num_rows > 0) {
						echo "<div class=\"table-responsive\">
								 <table class=\"table\"> 
									<thead>
										<th>Comment ID</th>
										<th>Booking ID</th>
										<th>Rating</th>
										<th>Review</th>
										<th>Time</th>
									</thead>
								 <tr>";
						while($row2 = $result2->fetch_assoc()) {	 
							//echo "<td><a href=admin_property.php?property_id=" . $row["property_id"] . ">" . $row["property_id"]. "</a>" . "</td>";
							echo "<td>" . $row2["comment_id"] . "</td>";
							echo "<td>" . $row2["booking_id"] . "</td>";
							echo "<td>" . $row2["rating"] . "</td>";
							echo "<td>" . $row2["text"] . "</td>";
							echo "<td>" . $row2["time"] . "</td>";
							echo "</tr>";	
						 }
						 // output data of each row
	
						 echo "</table></div>";
					} else {
						 echo "Property has no reviews.";
					}
				 }
			} else {
				 echo "Member has no property listings.";
			}
		?>
  <h3>Member Bookings:</h3>		
		<?php
			// SELECT query
			$query = "SELECT booking_id, property_id, member_id, start_date, status
						FROM booking
						WHERE member_id=?;"; 
				//AND DATE_ADD(start_date, INTERVAL 7 day) >= CURRENT_DATE;

			// prepare query for execution
			$stmt = $con->prepare($query);

			// bind the parameters. This is the best way to prevent SQL injection hacks.
			$stmt->bind_Param("s", $member_id);

			// Execute the query
			$stmt->execute();

			// results 
			$result = $stmt->get_result();

			if ($result->num_rows > 0) {
				echo 
					 "<div class=\"table-responsive\">
						 <table class=\"table\"> 
							<thead>
								<th>Booking ID</th>
								<th>Property ID</th>
								<th>Member ID</th>
								<th>Start Date</th>
								<th>Status</th>
							</thead>
						 <tr>";
				while($row = $result->fetch_assoc()) {	 
					echo "<tr>";
					//echo "<td><a href=admin_property.php?property_id=" . $row["property_id"] . ">" . $row["property_id"]. "</a>" . "</td>";
					echo "<td><a href=admin_booking.php?booking_id=" . $row["booking_id"] . ">" . $row["booking_id"]. "</a>" . "</td>";
					echo "<td>" . $row["property_id"] . "</td>";
					echo "<td>" . $row["member_id"] . "</td>";
					echo "<td>" . $row["start_date"] . "</td>";
					echo "<td>" . $row["status"] . "</td>";
					echo "</tr>";	
				 }
				 // output data of each row

				 echo "</table></div>";
			} else {
				 echo "Member has no bookings.";
			}
		?>

</body>
</html>