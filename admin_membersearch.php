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
	   		?> <li class="active"s><a href="admin_membersearch.php">Admin Member Search</a></li> 
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
  <h1>Admin: Search Members</h1>
</div>

<!-- Search Bar -->
<form name='member_search' id='member_search' action='admin_membersearch.php' method='get'>
	<div class="container">
		<div class="row">    
			<div class="col-xs-8 col-xs-offset-2">
				<div class="input-group">
					<div class="input-group-btn search-panel">
						<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
							<span id="search_concept">Search By:</span> <span class="caret"></span>
						</button>
						<ul class="dropdown-menu" role="menu">
						  <li><a href="admin_membersearch.php?search_param=member_id">member_id</a></li>
						  <li><a href="admin_membersearch.php?search_param=name">name</a></li>
						  <li><a href="admin_membersearch.php?search_param=email">e-mail</a></li>
						  <li><a href="admin_membersearch.php?search_param=username">username</a></li>
						  <li class="divider"></li>
						  <li><a href="admin_membersearch.php?search_param=all">All</a></li>
						</ul>
					</div>
					<span class="input-group-addon" id="sizing-addon2">
					
					<!-- Display currently selected search parameter: -->
					<?php 
						if (isset($_GET["search_param"]))
							echo $_GET["search_param"];
						else
							echo "All";
					?>
					
					</span>
					<!-- Remember what search parameters are being used: -->
					<?php
						if (isset($_GET["search_param"]))
							echo "<input type=\"hidden\" name=\"search_param\" value=\"" . $_GET["search_param"] . "\" id=\"search_param\">";
						else
							echo "<input type=\"hidden\" name=\"search_param\" value=\"all\" id=\"search_param\">";
					?>
					  
					<?php
						if(isset($_GET["search"]))
						{
							echo "<input type=\"text\" class=\"form-control\" name=\"search\" placeholder=\"" . $_GET["search"] . "\">";
						}
						else
						{
							echo "<input type=\"text\" class=\"form-control\" name=\"search\" placeholder=\"Search term...\">";
						}
						
					?>     
					<span class="input-group-btn">
						<input type='submit' value="Search" class="btn btn-primary btn-md" />
						<!-- <button class="btn btn-default" type="button">
						<span class="glyphicon glyphicon-search"></span></button> -->
					</span>
				</div>
			</div>
		</div>
	</div>
</form>

<div class="container">
  <h2>Search Results</h2>                                                                                     
  <div class="table-responsive">          
  <table class="table">
    <thead>
      <tr>
        <th>UserID</th>
        <th>Username</th>
        <th>Firstname</th>
        <th>Lastname</th>
        <th>E-mail</th>
        <th> member_type</th>
      </tr>
    </thead>
    <tbody>

		<?php
    		include_once 'config/connection.php';
			
			if(isset($_GET["search_param"]) && isset($_GET["search"]))
			{
				$param = $_GET["search_param"];
				if ($param == "member_id")
				{
					$query = "SELECT member_id, username, first_name, last_name, email, member_type FROM member WHERE member_id=? ORDER BY member_id";
					$stmt = $con->prepare($query);
					$stmt->bind_Param("s", $_GET['search']);
				}
				elseif ($param == "name")
				{
					$query = "SELECT member_id, username, first_name, last_name, email, member_type FROM member WHERE first_name LIKE ? OR last_name LIKE ? ORDER BY member_id";
					$stmt = $con->prepare($query);
					$param1 = "%" . $_GET['search'] . "%";
					$stmt->bind_Param("ss", $param1, $param1);
				}
				elseif ($param == "email")
				{
					$query = "SELECT member_id, username, first_name, last_name, email, member_type FROM member WHERE email LIKE ? ORDER BY member_id";
					$stmt = $con->prepare($query);
					$param1 = "%" . $_GET['search'] . "%";
					$stmt->bind_Param("s", $param1);
				}
				elseif ($param == "username")
				{
					$query = "SELECT member_id, username, first_name, last_name, email, member_type FROM member WHERE username LIKE ? ORDER BY member_id";
					$stmt = $con->prepare($query);
					$param1 = "%" . $_GET['search'] . "%";
					$stmt->bind_Param("s", $param1);
				}
				elseif ($param == "all")
				{
					$query = "SELECT member_id, username, first_name, last_name, email, member_type FROM member WHERE member_id LIKE ? OR first_name LIKE ? OR last_name LIKE ? OR email LIKE ? OR username LIKE ? ORDER BY member_id";
					$stmt = $con->prepare($query);
					$param1 = "%" . $_GET['search'] . "%";
					$stmt->bind_Param("sssss", $param1, $param1, $param1, $param1, $param1);
				}
				else
				{
					$query = "SELECT member_id, username, first_name, last_name, email, member_type FROM member ORDER BY member_id";
				}
			}
			else
			{
				$query = "SELECT member_id, username, first_name, last_name, email, member_type FROM member ORDER BY member_id";
				$stmt = $con->prepare($query);
			}
				
			$stmt->execute();
			//$result = mysqli_query("SELECT member_id, first_name, last_name FROM member")
			//			or die("Couldn't execute query.");
			$result = $stmt->get_result();
			
			if($result->num_rows > 0)
			{
				while($row = $result->fetch_assoc())
				{
				echo "<tr>";
				echo "<td><a href=admin_member.php?member_id=" . $row["member_id"] . ">" . $row["member_id"]. "</a>" . "</td>";
				echo "<td>" . $row["username"] . "</td>";
				echo "<td>" . $row["first_name"] . "</td>";
				echo "<td>" . $row["last_name"] . "</td>";
				echo "<td>" . $row["email"] . "</td>";
				echo "<td>" . $row["member_type"] . "</td>";
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