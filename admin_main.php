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

 <nav class="navbar navbar-inverse navbar-fixed-top">
   <div class="container-fluid">
     <div class="navbar-header">
       <a class="navbar-brand" href="index.php">QBnB</a>
     </div>
     <ul class="nav navbar-nav">
       <li class="active"><a href="#">Home</a></li>
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
  <h1>Find your next vacation now!</h1>
</div>

<form class="form-inline" role="form">
  <div class="form-group">
	<select>
	<?php 
    include_once 'config/connection.php'; 
	
	// SELECT query
        $query = "SELECT district_name FROM district";
 
        // prepare query for execution
        $stmt = $con->prepare($query);
		
        // bind the parameters. This is the best way to prevent SQL injection hacks.
        $stmt->bind_Param("s", $_SESSION['id']);

        // Execute the query
		$stmt->execute();
 
		// results 
		$result = $stmt->get_result();
	while ($row = $result -> fetch_assoc()){
	echo "<option>" . $row['district_name'] . "</option>";
	}
	?>
	</select>
  </div>
  <div class="form-group">
    <label for="sel1"># Guests:</label>
    <select class="form-control" id="sel1">
      <option>1</option>
      <option>2</option>
      <option>3</option>
      <option>4</option>
    </select>
  </div>
  <div class="form-group">
    <p>Date: <input type="text" id="datepicker"></p>
  </div>
  <button type="button" class="btn btn-success">Success</button>
</form>

		<?php
		if(isset($_GET['submit_btn']) && $_SESSION['member_id']){
		   // include database connection
		    include_once 'config/connection.php'; 
	
			// SELECT query
		        $query = "SELECT
		  		  		  property_id,
						  street_number,
						  street_name,
						  city,
						  price,
						  district_name
						FROM
						  property NATURAL JOIN district
						WHERE
						  district_id = ?
						  ;"; 
					//AND DATE_ADD(start_date, INTERVAL 7 day) >= CURRENT_DATE;
 
		        // prepare query for execution
		        $stmt = $con->prepare($query);
		
		        // bind the parameters. This is the best way to prevent SQL injection hacks.
		        $stmt->bind_Param("i", $_GET['district_id']);

		        // Execute the query
				$stmt->execute();
 
				// results 
				$result = $stmt->get_result();
		
				if ($result->num_rows > 0) {
				     echo "<table><tr><th> Property Id </th><th> Street #</th><th> Street Name </th><th> City </th><th> Price </th><th> District </tr>";
				     // output data of each row
				     while($row = $result->fetch_assoc()) {
				         echo "<tr><td>" . $row["property_id"]. "</td><td>" . $row["street_number"]. "<td>" . $row["street_name"]. "<td>" . $row["city"]. "<td>" . $row["price"]. "<td>" . $row["district_name"]. "</td><td>"; 
							echo"</td></tr>";
				     }
				     echo "</table>";
				} else {
				     echo "Nothing Matched Your Search";
				}
		} 
 ?>

</body>
</html>