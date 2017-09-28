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

<form class="form-inline" role="form" method="get">
  <div class="form-group">
	<label for="sel1">District:</label>
	<select name="districtValue" class="form-control" id="sel1">
		<option value='0'>N/A</option>
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
    <label for="sel2"># Guests:</label>
    <select name="numGeusts" class="form-control" id="sel2">
      <option value='0'>N/A</option>
	  <option>1</option>
      <option>2</option>
      <option>3</option>
      <option>4</option>
      <option>5</option>
      <option>6</option>
      <option>7</option>
      <option>8</option>
      <option>9</option>
      <option>10</option>
    </select>
  </div>
  <div class="form-group">
	<label for="sel3">Start Date:</label> 
    <input type="text" class="form-control" id="datepicker" name="date" placeholder="Unspecified Start Date">
  </div>
  <form action='gen_main.php' method=get>
  <input type="submit" class="btn btn-primary" name='search_btn' id='search_btn' value="Search">

<p><b> Filters: </b></p>

<div class="table-responsive">
<table>
	<tr><td>
	<?php 
    include_once 'config/connection.php'; 
	
	// SELECT query
        $query = "SELECT feature, feature_id	
			 	FROM property_feature";
 
        // prepare query for execution
        $stmt = $con->prepare($query);

        // Execute the query
		$stmt->execute();
 
		// results 
		$result = $stmt->get_result();
		
	while ($row = $result -> fetch_assoc()){
		?>
		
		<input type="checkbox" name="feature<?php echo  
		$row['feature_id'];?>" id="feature<?php echo  
		$row['feature_id'];?>" value="1" <?php ?>
		> <?php echo  
		$row['feature']; 
		
		?>
		&emsp;
		
		<td>
		
		<?php
		if(($row['feature_id'] == '8') || ($row['feature_id'] == '16')){
			?>
		</td></tr>
		<td>
			<?php
			
		}
	}
	
	?>
	</table>
	</div>
	<br>
  <div class="form-inline">
	 <b> Price: </b>
		<select class="form-control" name="price_min" id="price_min">
		  <option>Min</option>
		  <option>50</option>
		  <option>100</option>
		  <option>150</option>
		  <option>200</option>
		  <option>250</option>
		  <option>300</option>
		  <option>350</option>
		  <option>400</option>
		  <option>450</option>
		  <option>500</option>
		  <option>550</option>
		  <option>600</option>
		  <option>650</option>
		  <option>700</option>
		  <option>750</option>
		  <option>800</option>
		  <option>850</option>
		  <option>900</option>
		  <option>950</option>
		  <option>1000</option>
		</select>

	 
		<select class="form-control" name="price_max" id="price_max">
		  <option>Max</option>
		  <option>50</option>
		  <option>100</option>
		  <option>150</option>
		  <option>200</option>
		  <option>250</option>
		  <option>300</option>
		  <option>350</option>
		  <option>400</option>
		  <option>450</option>
		  <option>500</option>
		  <option>550</option>
		  <option>600</option>
		  <option>650</option>
		  <option>700</option>
		  <option>750</option>
		  <option>800</option>
		  <option>850</option>
		  <option>900</option>
		  <option>950</option>
		  <option>1000</option>
		</select>
		

	<label for="sel1">Type:</label>
	<select name="typeValue" class="form-control" id="sel3">
		<option value='0'>N/A</option>
	<?php 
    include_once 'config/connection.php'; 
	
	// SELECT query
        $query = "SELECT distinct type FROM property";
 
        // prepare query for execution
        $stmt = $con->prepare($query);
		
        // bind the parameters. This is the best way to prevent SQL injection hacks.
        //$stmt->bind_Param("s", $_SESSION['id']);

        // Execute the query
		$stmt->execute();
 
		// results 
		$result = $stmt->get_result();
	while ($row = $result -> fetch_assoc()){
	echo "<option>" . $row['type'] . "</option>";
	}
	?>
	</select>
	</div>
</div>
</form>
<div class="page-header">
  <h1>Search Results</h1>
</div>
		<?php
		
		
		if(isset($_GET['search_btn']) &&  $_SESSION['id']){
			
   			
	 		    include_once 'config/connection.php'; 
	 			// SELECT query
	 		        $query = "SELECT
	 		  		  		  distinct property_id,
	 						  street_number,
	 						  street_name,
	 						  city,
	 						  price,
	 						  district_name,
							  type
	 						FROM
	 						  property prop NATURAL JOIN district NATURAL JOIN has_feature
	 						WHERE
	 						  1";
							  if(!($_GET['districtValue'] == '0')){
								 $query = $query . " AND district_name = ?";
							  }
							  if(!($_GET['typeValue'] == '0')){
								  $type = $_GET['typeValue'];
								  $query = $query . " AND type = '$type'";
							  }
							  if(!($_GET['date'] == '')){
					     			$dateArray = explode('/', $_GET['date']);
					     			$dateval = $dateArray[2].'-'.$dateArray[0].'-'.$dateArray[1];
								  $query= $query ." AND
								   NOT EXISTS(     SELECT *
								  					FROM
								  					booking b
								  					WHERE
								  	prop.property_id = b.property_id AND DATE_ADD(start_date, INTERVAL 7 day) >= '$dateval' AND 												DATE_ADD('$dateval', INTERVAL 7 day) >= start_date)";
							  }
							  if(!($_GET['numGeusts'] == '0')){
								 $query = $query . " AND prop.num_guests >= ?";
							  }
							  if(isset($_GET['feature1'])){$query = $query . " AND EXISTS(SELECT * from has_feature h WHERE h.property_id = prop.property_id AND feature_id ='1')";}
							  if(isset($_GET['feature2'])){$query = $query . " AND EXISTS(SELECT * from has_feature h WHERE h.property_id = prop.property_id AND feature_id ='2')";}
							  if(isset($_GET['feature3'])){$query = $query . " AND EXISTS(SELECT * from has_feature h WHERE h.property_id = prop.property_id AND feature_id ='3')";}
							  if(isset($_GET['feature4'])){$query = $query . " AND EXISTS(SELECT * from has_feature h WHERE h.property_id = prop.property_id AND feature_id ='4')";}
							  if(isset($_GET['feature5'])){$query = $query . " AND EXISTS(SELECT * from has_feature h WHERE h.property_id = prop.property_id AND feature_id ='5')";}
							  if(isset($_GET['feature6'])){$query = $query . " AND EXISTS(SELECT * from has_feature h WHERE h.property_id = prop.property_id AND feature_id ='6')";}
							  if(isset($_GET['feature7'])){$query = $query . " AND EXISTS(SELECT * from has_feature h WHERE h.property_id = prop.property_id AND feature_id ='7')";}
							  if(isset($_GET['feature8'])){$query = $query . " AND EXISTS(SELECT * from has_feature h WHERE h.property_id = prop.property_id AND feature_id ='8')";}
							  if(isset($_GET['feature9'])){$query = $query . " AND EXISTS(SELECT * from has_feature h WHERE h.property_id = prop.property_id AND feature_id ='9')";}
							  if(isset($_GET['feature10'])){$query = $query . " AND EXISTS(SELECT * from has_feature h WHERE h.property_id = prop.property_id AND feature_id ='10')";}
							  if(isset($_GET['feature11'])){$query = $query . " AND EXISTS(SELECT * from has_feature h WHERE h.property_id = prop.property_id AND feature_id ='11')";}
							  if(isset($_GET['feature12'])){$query = $query . " AND EXISTS(SELECT * from has_feature h WHERE h.property_id = prop.property_id AND feature_id ='12')";}
							  if(isset($_GET['feature13'])){$query = $query . " AND EXISTS(SELECT * from has_feature h WHERE h.property_id = prop.property_id AND feature_id ='13')";}
							  if(isset($_GET['feature14'])){$query = $query . " AND EXISTS(SELECT * from has_feature h WHERE h.property_id = prop.property_id AND feature_id ='14')";}
							  if(isset($_GET['feature15'])){$query = $query . " AND EXISTS(SELECT * from has_feature h WHERE h.property_id = prop.property_id AND feature_id ='15')";}
							  if(isset($_GET['feature16'])){$query = $query . " AND EXISTS(SELECT * from has_feature h WHERE h.property_id = prop.property_id AND feature_id ='16')";}
							  if(isset($_GET['feature17'])){$query = $query . " AND EXISTS(SELECT * from has_feature h WHERE h.property_id = prop.property_id AND feature_id ='17')";}
							  if(isset($_GET['feature18'])){$query = $query . " AND EXISTS(SELECT * from has_feature h WHERE h.property_id = prop.property_id AND feature_id ='18')";}
							  if(isset($_GET['feature19'])){$query = $query . " AND EXISTS(SELECT * from has_feature h WHERE h.property_id = prop.property_id AND feature_id ='19')";}
							  if(isset($_GET['feature20'])){$query = $query . " AND EXISTS(SELECT * from has_feature h WHERE h.property_id = prop.property_id AND feature_id ='20')";}
							  if(isset($_GET['feature21'])){$query = $query . " AND EXISTS(SELECT * from has_feature h WHERE h.property_id = prop.property_id AND feature_id ='21')";}
							  if(isset($_GET['feature22'])){$query = $query . " AND EXISTS(SELECT * from has_feature h WHERE h.property_id = prop.property_id AND feature_id ='22')";}
							  if(isset($_GET['feature23'])){$query = $query . " AND EXISTS(SELECT * from has_feature h WHERE h.property_id = prop.property_id AND feature_id ='23')";}
    			  				if(isset($_GET['price_min']) && isset($_GET['price_max']))
    			  				{
    			  					if($_GET['price_min'] == "Min" && $_GET['price_max'] == "Max")
    			  					{
    			  						$query = $query . " ORDER BY price";
    			  					}
    			  					elseif($_GET['price_min'] == "Min" && $_GET['price_max'] != "Max")
    			  					{
    			  						$max = $_GET['price_max'];
    									$query = $query . " AND price < '$max' ORDER BY price";
    			  					}
    			  					elseif($_GET['price_min'] != "Min" && $_GET['price_max'] == "Max")
    			  					{
    			  						$min = $_GET['price_min'];
    									$query = $query . " AND price > '$min' ORDER BY price";
    			  					}
    			  					else
    			  					{
										$min = $_GET['price_min'];
										$max = $_GET['price_max'];
    			  						$query = $query . " AND price > '$min' and price < '$max' ORDER BY price";
    			  					}
							  }	
								  
							$query= $query . ";"; 
	 					

	 		        // prepare query for execution
	 		        $stmt = $con->prepare($query);
	
	 		        // bind the parameters. This is the best way to prevent SQL injection hacks.
					if($_GET['numGeusts'] == '0' && !($_GET['districtValue'] == '0')){
	 		        	$stmt->bind_Param("s", $_GET['districtValue']);
					}
					elseif(!$_GET['numGeusts'] == '0' && ($_GET['districtValue'] == '0')){
						$stmt->bind_Param("s", $_GET['numGeusts'] );
					}
					elseif(!($_GET['numGeusts'] == '0') && !($_GET['districtValue'] == '0')){
						$stmt->bind_Param("ss",$_GET['districtValue'], $_GET['numGeusts'] );
					}
						
	 		        // Execute the query
	 				$stmt->execute();

	 				// results 
	 				$result = $stmt->get_result();
	
	  
	 				if ($result->num_rows > 0) {
				
	 				     echo "<div class=\"table-responsive\">
							 <table class=\"table\"> <thead><tr><th> Property Id </th><th> Street #</th><th> Street Name </th><th> City </th><th> Price </th><th> District </th><th> Type </tr>";
	 				     // output data of each row
	 				     while($row = $result->fetch_assoc()) {
							 
	 				         echo "<tr><td>" . "<a href=\"property.php?property_id=" . $row["property_id"] . "\">" . $row["property_id"]. "</td><td>" . $row["street_number"]. "<td>" . $row["street_name"]. "<td>" . $row["city"]. "<td>" . $row["price"]. "<td>" . $row["district_name"] . "<td>" . $row["type"]; 
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