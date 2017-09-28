<!--
TODO: - Update the navbar to match other files
		- Update session checking to match other files
		- If you want the search value to be displayed when the results are presented, copy admin_membersearch.php
-->

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

<!-- Navigation Bar -->
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
			<li class="active"><a href="admin_propertysearch.php">Admin Property Search</a></li>
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
  <h1>Admin: Search Properties</h1>
</div>
<!-- Search Bar -->


<div class='container'>
	<div class="row">
		<div class="col-sm-12">
			<div class = "panel panel-default">
   				<div class = "panel-heading">
   					<div class="row">
		<div style="display:inline-block" class="col-sm-2">
			<font size="4"> Search By: </font>
		</div>
		<div class="input-group-btn search-panel">
						<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
							<span id="search_concept">Search By:</span> <span class="caret"></span>
						</button>
						<ul class="dropdown-menu" role="menu">
						  <li><a href="admin_propertysearch.php?search_param=property_id">property_id</a></li>
						  <li><a href="admin_propertysearch.php?search_param=district_id">district_id</a></li>
						  <li><a href="admin_propertysearch.php?search_param=district_name">District Name</a></li>
						  <li><a href="admin_propertysearch.php?search_param=address">Address</a></li>
						  <li><a href="admin_propertysearch.php?search_param=type">Type</a></li>
						  <li><a href="admin_propertysearch.php?search_param=price">Price</a></li>
						</ul>
					</div>
		<!--<div class="col-sm-2">
			<select class="form-control">
			  <option>property_id</option>
			  <option>district_id</option>
			  <option>District Name</option>
			  <option>Address</option>
			  <option>Type</option>
			  <option>Price</option>
			</select>
		</div>-->
		</div>
	</div>
</div>
   			<div class = "panel-body">
      			<form class="form-horizontal" role="form" action="admin_propertysearch.php" method="get">
      			<div class="form-group">
				  <?php
				  		if(isset($_GET['search_param']))
				  		{	
				  			$s_param = $_GET['search_param'];
				  			if($s_param == "property_id")
				  			{
				  				echo "<label class=\"control-label col-sm-2\" for=\"property_id\">property_id:</label>
										<div class=\"col-sm-8\">
					 						<input type=\"text\" class=\"form-control\" name=\"property_id\" id=\"property_id\" placeholder=\"Enter property_id\">
										</div>";
				  			}
				  			elseif($s_param == "district_id")
				  			{
				  				echo "<label class=\"control-label col-sm-2\" for=\"district_id\">district_id:</label>
										<div class=\"col-sm-8\"> 
					  						<input type=\"text\" class=\"form-control\" name=\"district_id\" id=\"district_id\" placeholder=\"Enter district_id\">
										</div>";
				  			}
				  			elseif($s_param == "district_name")
				  			{
				  				echo "<label class=\"control-label col-sm-2\" for=\"district_name\">District Name:</label>
										<div class=\"col-sm-8\"> 
					  						<input type=\"text\" class=\"form-control\" name=\"district_name\" id=\"district_name\" placeholder=\"Enter district name\">
										</div>";
				  			}
				  			elseif($s_param == "address")
							{
								echo "<label class=\"control-label col-sm-2\" for=\"number\">Address:</label>
									<div class=\"col-sm-2\"> 
										<input type=\"text\" class=\"form-control\" name=\"number\" id=\"number\" placeholder=\"Enter Number\">
									</div>";
				  				//echo "<label class=\"control-label col-sm-4\" for=\"address\">Address:</label>
								echo "<div class=\"col-sm-6\"> 
					  						<input type=\"text\" class=\"form-control\" name=\"street\" id=\"street\" placeholder=\"Enter street\">
										</div>";
				  			}
				  			elseif($s_param == "type")
				  			{
				  				echo "<label class=\"control-label col-sm-2\" for=\"type\">Type:</label>
										<div class=\"col-sm-2\"> 
											<select class=\"form-control\" name=\"type\" id=\"type\">";
								include_once 'config/connection.php';
								$query = "SELECT DISTINCT(type) FROM property ORDER BY type";
								$stmt = $con->prepare($query);
								$stmt->execute();
								$result = $stmt->get_result();
								while($row = $result->fetch_assoc())
								{
								   		echo "<option>" . $row["type"] . "</option>";
								} 
							
								echo "</select>";
								echo "</div>";
				  			}
				  			elseif($s_param == "price")
				  			{
				  				echo "<label class=\"control-label col-sm-2\" for=\"price_min\">Price (Min $ to Max $):</label>
								<div class=\"col-sm-2\"> 
									<select class=\"form-control\" name=\"price_min\" id=\"price_min\">
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
								</div>
								<div class=\"col-sm-2\"> 
									<select class=\"form-control\" name=\"price_max\" id=\"price_max\">
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
								</div>";
				  			}
				  		}
				  		else
				  		{
							echo "<label class=\"control-label col-sm-2\" for=\"property_id\">property_id:</label>
								<div class=\"col-sm-8\">
									<input type=\"text\" class=\"form-control\" name=\"property_id\" id=\"property_id\" placeholder=\"Enter property_id\">
								</div>";
				  		}
				  ?>
				  	<?php
						if (isset($_GET["search_param"]))
							echo "<input type=\"hidden\" name=\"search_param\" value=\"" . $_GET["search_param"] . "\" id=\"search_param\">";
						else
							echo "<input type=\"hidden\" name=\"search_param\" value=\"property_id\" id=\"search_param\">";
					?>
					<!-- <div class="col-sm-offset-2 col-sm-10"> -->
						<button type="submit" class="btn btn-default" id="search">Search</button>
					<!--</div>-->
				  </div>
				</form>
   			<!-- </div> -->
		<!--</div>-->
	<!--</div>-->
</div>

<div class="container">
  <h2>Search Results</h2>                                                                                     
  <div class="table-responsive">          
  <table class="table">
    <thead>
      <tr>
        <th>PropertyID</th>
        <th>District</th>
        <th>Street Number</th>
        <th>Street Name</th>
        <th>Apt. Number</th>
        <th>City</th>
        <th>Province</th>
        <th>Postal Code</th>
        <th>type</th>
        <th>price</th>
      </tr>
    </thead>
    <tbody>

		<?php
    		include_once 'config/connection.php';
			
			if(isset($_GET['search_param']))
			{
				$s_param = $_GET['search_param'];
				if($s_param == "property_id" && isset($_GET['property_id']) )
				{
					$query = "SELECT property_id, district_name, street_number, street_name, apt_number, city, province, postal_code, type, price FROM property NATURAL JOIN district WHERE property_id=? ORDER BY property_id";
					$stmt = $con->prepare($query);
					$param1 = $_GET['property_id'];
					$stmt->bind_Param("s", $param1);
				}
				elseif($s_param == "district_id" &&  isset($_GET['district_id']))
				{
					$query = "SELECT property_id, district_name, street_number, street_name, apt_number, city, province, postal_code, type, price FROM property NATURAL JOIN district WHERE district_id=? ORDER BY property_id";
					$stmt = $con->prepare($query);
					$param1 = $_GET['district_id'];
					$stmt->bind_Param("s", $param1);
				}
				elseif($s_param == "district_name" && isset($_GET['district_name']))
				{
					$query = "SELECT property_id, district_name, district_name, street_number, street_name, apt_number, city, province, postal_code, type, price FROM property NATURAL JOIN district WHERE district_name LIKE ? ORDER BY property_id";
					$stmt = $con->prepare($query);
					$param1 = "%" . $_GET['district_name'] . "%";
					$stmt->bind_Param("s", $param1);
				}
				elseif($s_param == "address" && isset($_GET['street']) && isset($_GET['number']))
				{
					if(!empty($_GET['street']) && !empty($_GET['number']))
					{
						$query = "SELECT property_id, district_name, street_number, street_name, apt_number, city, province, postal_code, type, price FROM property NATURAL JOIN district WHERE street_name LIKE ? and street_number=? ORDER BY property_id";
						$stmt = $con->prepare($query);
						$param1 = "%" . $_GET['street'] . "%";
						$param2 = $_GET['number'];
						$stmt->bind_Param("ss", $param1, $param2);
					}
					elseif(!empty($_GET['street']) && empty($_GET['number']))
					{
						$query = "SELECT property_id, district_name, street_number, street_name, apt_number, city, province, postal_code, type, price FROM property NATURAL JOIN district WHERE street_name LIKE ? ORDER BY property_id";
						$stmt = $con->prepare($query);
						$param1 = "%" . $_GET['street'] . "%";
						$stmt->bind_Param("s", $param1);
					}
					else
					{
						$query = "SELECT property_id, district_name, street_number, street_name, apt_number, city, province, postal_code, type, price FROM property NATURAL JOIN district WHERE street_number=? ORDER BY property_id";
						$stmt = $con->prepare($query);
						$param1 = $_GET['number'];
						$stmt->bind_Param("s", $param1);
					}
				}
				elseif($s_param == "type" && isset($_GET['type']))
				{
					$query = "SELECT property_id, district_name, street_number, street_name, apt_number, city, province, postal_code, type, price FROM property NATURAL JOIN district WHERE type=? ORDER BY property_id";
					$stmt = $con->prepare($query);
					$param1 = $_GET['type'];
					$stmt->bind_Param("s", $param1);
				}
				elseif($s_param == "price" && isset($_GET['price_min']) && isset($_GET['price_max']))
				{
					if($_GET['price_min'] == "Min" && $_GET['price_max'] == "Max")
					{
						$query = "SELECT property_id, district_name, street_number, street_name, apt_number, city, province, postal_code, type, price FROM property NATURAL JOIN district ORDER BY price";
						$stmt = $con->prepare($query);
					}
					elseif($_GET['price_min'] == "Min" && $_GET['price_max'] != "Max")
					{
						$query = "SELECT property_id, district_name, street_number, street_name, apt_number, city, province, postal_code, type, price FROM property NATURAL JOIN district WHERE price<? ORDER BY price";
						$stmt = $con->prepare($query);
						$param1 = $_GET['price_max'];
						$stmt->bind_Param("s", $param1);
					}
					elseif($_GET['price_min'] != "Min" && $_GET['price_max'] == "Max")
					{
						$query = "SELECT property_id, district_name, street_number, street_name, apt_number, city, province, postal_code, type, price FROM property NATURAL JOIN district WHERE price>? ORDER BY price";
						$stmt = $con->prepare($query);
						$param1 = $_GET['price_min'];
						$stmt->bind_Param("s", $param1);
					}
					else
					{
						$query = "SELECT property_id, district_name, street_number, street_name, apt_number, city, province, postal_code, type, price FROM property NATURAL JOIN district WHERE price>? and price<? ORDER BY price";
						$stmt = $con->prepare($query);
						$param1 = $_GET['price_min'];
						$param2 = $_GET['price_max'];
						$stmt->bind_Param("ss", $param1, $param2);
					}
				}
				else
				{
					$query = "SELECT property_id, district_name, street_number, street_name, apt_number, city, province, postal_code, type, price FROM property NATURAL JOIN district ORDER BY property_id";
					$stmt = $con->prepare($query);
				}


					/*$query = "SELECT member_id, username, first_name, last_name, email, member_type FROM member WHERE first_name LIKE ? OR last_name LIKE ? ORDER BY member_id";
					$stmt = $con->prepare($query);
					$param1 = "%" . $_GET['search'] . "%";
					$stmt->bind_Param("ss", $param1, $param1);*/
			}	
			else
			{
				$query = "SELECT property_id, district_name, street_number, street_name, apt_number, city, province, postal_code, type, price FROM property NATURAL JOIN district ORDER BY property_id";
				$stmt = $con->prepare($query);
			}
				
			$stmt->execute();
			$result = $stmt->get_result();
			
			if($result->num_rows > 0)
			{
				while($row = $result->fetch_assoc())
				{
				echo "<tr>";
				echo "<td><a href=admin_property.php?property_id=" . $row["property_id"] . ">" . $row["property_id"]. "</a>" . "</td>";
				echo "<td>" . $row["district_name"]. "</td>";
				echo "<td>" . $row["street_number"] . "</td>";
				echo "<td>" . $row["street_name"] . "</td>";
				echo "<td>" . $row["apt_number"] . "</td>";
				echo "<td>" . $row["city"] . "</td>";
				echo "<td>" . $row["province"] . "</td>";
				echo "<td>" . $row["postal_code"] . "</td>";
				echo "<td>" . $row["type"] . "</td>";
				echo "<td>" . $row["price"] . "</td>";
				echo "</tr>";	
				}
			}
			mysqli_close($con);
		?>
    </tbody>
  </table>
  </div>
</div>

</body>
</html>