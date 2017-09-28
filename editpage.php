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
							
							$prop_id = $_GET['property_id'];
						if(isset($_GET['addpoi_btn'])){
							
									 $query = "SELECT district_id 
										FROM
										property 
										WHERE
										property_id = '$prop_id';
				 						  ;";
 				 		         $stmt = $con->prepare($query); 
							  // Execute the query
							        $stmt->execute();
							          $result = $stmt->get_result();
							      // Get the number of rows returned
							          if($row = $result->fetch_assoc()){          
							            $districtID = $row["district_id"];
							          }
						

				 			// SELECT query
				 		        $query = "INSERT into points_of_interest  values(null,'$districtID',?)";

				 		        // prepare query for execution
				 		        $stmt = $con->prepare($query);
		
				 		        // bind the parameters. This is the best way to prevent SQL injection hacks.
				 		        $stmt->bind_Param("s", $_GET['poi']);

				 		        // Execute the query
				 				$stmt->execute();
				 				header('Location: editpage.php?property_id=' . $prop_id); 
 
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
							 <tr><th> Property Id </th><th>" . $row["property_id"]. "<tr><th> Street Number </th><th>" . $row["street_number"] . "<tr><th> Street Name </th><th>" . $row["street_name"] . "<tr><th> Apt Number </th><th>" . $row["apt_number"] . "<tr><th> City </th><th>" . $row["city"] . "<tr><th> Province </th><th>" . $row["province"] . "<tr><th> Postal Code </th><th>" . $row["postal_code"] . "<tr><th> Price </th><th>";
							 			?>
							 			
							 		<form action='editpage.php?property_id=<?php echo $row["prop_id"]?>?edit=1' method=get>
							 	<input type='hidden' name='property_id' id='property_id' value="<?php echo $row['property_id'] ?>">	

 									<input type='text' name='new_price' id='new_price'  value="<?php echo $row['price']; ?>"  />
 									<input type='submit'  name = 'confirm_btn' id = 'confirm_btn' value="Update">

 									</form>
 									<?php	

							  echo "<tr><th> Type </th><th>" . $row["type"] . "<tr><th> District </th><th>" . $row["district_name"];
		 				     echo "</thead></table></div>";


							 if(isset($_GET['confirm_btn']) && isset($_SESSION['id'])){
							  // include database connection
							    include_once 'config/connection.php'; 

							  $query = "UPDATE property SET property.price=? WHERE property.property_id='$prop_id'";

							  $stmt = $con->prepare($query); 
							   $stmt->bind_param('s', $_GET['new_price']);
							  // Execute the query
							        $stmt->execute();
							       header('Location: editpage.php?property_id=' . $prop_id);       						          
							   }
 

			 
		 				} else {
		 				     echo "Oops! No details for this property.";
		 				}
						
				   		
				}			
 ?>

   

  <h3>Features:</h3>
   <form action='editpage.php?property_id=<?php echo $row["prop_id"]?>?edit=1' method=get>

<div class="form-group" >
    <label for="selF">Features:</label>
    <select name="selF" class="form-control" id="selF" style="width: 300px !important; min-width: 300px; max-width: 300px;">
      <option value='0'>N/A</option>
	  <option name = 'Kitchen'>Kitchen</option>
      <option name = 'Internet'>Internet</option>
      <option name = 'Shampoo'>Shampoo</option>
	  <option name = 'Washer'>Washer</option>
	  <option name = 'Dryer'>Dryer</option>
	  <option name = 'Heating'>Heating</option>
	  <option name = 'Air Conditioning'>Air Conditioning</option>
	  <option name = 'Parking'>Parking</option>
	  <option name = 'TV'>TV</option>
	  <option name = 'Pets Allowed'>Pets Allowed</option>
	  <option name = 'Smoking Allowed'>Smoking Allowed</option>
	  <option name = 'Wheelchair Accessible'>Wheelchair Accessible</option>
	  <option name = 'Pool'>Pool</option>
	  <option name = 'Gym'>Gym</option>
	  <option name = 'Breakfast'>Breakfast</option>
	  <option name = 'Iron'>Iron</option>
	  <option name = 'Towels'>Towels</option>
	  <option name = 'Smoke Free'>Smoke Free</option>
	  <option name = 'Room Service'>Room Service</option>
	  <option name = 'Shuttle Service'>Shuttle Service</option>
	  <option name = 'OC Transpo Accesible'>OC Transpo Accesible</option>
	  <option name = 'Netflix Service'>Netflix Service</option>
	  <option name = 'Hot Tub'>Hot tub</option>
    </select> 
  </div>	
 <input type='hidden' name='property_id' id='property_id' value="<?php echo $row['property_id'] ?>">	
 <input type='submit'  name = 'addfeature_btn' id = 'addfeature_btn' value="Add Feature">
</form>		


</th><th>
				<?php
	 		    include_once 'config/connection.php'; 

				if($_SESSION['id']){
							$prop_id = $_GET['property_id'];
							if(isset($_GET['addfeature_btn'])){
								if(!($_GET['selF'] == '0')){
									 $query = "SELECT feature_id 
										FROM
										property_feature 
										WHERE
										feature = ?"; 
 				 		         $stmt = $con->prepare($query); 
								   $stmt->bind_param("s", $_GET['selF']);
							  // Execute the query
							        $stmt->execute();
							          $result = $stmt->get_result();
							      // Get the number of rows returned
							          if($row = $result->fetch_assoc()){          
							            $featureID = $row["feature_id"];
							          }
							        $query = "SELECT * FROM has_feature WHERE feature_id = '$featureID' AND property_id = '$prop_id'";
						        // prepare query for execution
						    	$stmt = $con->prepare($query);
						      // Execute the query
						      $stmt->execute();
						      $result = $stmt->get_result();
						      $num = $result->num_rows;
						      if($num>0){
						       $message = "This feature is already listed for this property.";
						        echo "<script type='text/javascript'>alert('$message');</script>";  
						       }
						       else 
						       {
								  $query = "INSERT into has_feature values('$prop_id','$featureID')";
								  $stmt = $con->prepare($query);
								  $stmt->execute();
						       } 		

 							}
						}
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

   <form action='editpage.php?property_id=<?php echo $row["prop_id"]?>?edit=1' method=get>

   <input type='hidden' name='property_id' id='property_id' value="<?php echo $prop_id ?>">	
  <input type='text' name='poi' id='poi'  placeholder="Points of interest..."  />
 <input type='submit'  name = 'addpoi_btn' id = 'addpoi_btn' value="Add POI">
</form>
  </th><th>
				<?php
				if($_SESSION['id']){

				 		    include_once 'config/connection.php'; 
							
							


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


  
     



		
</body>
</html>