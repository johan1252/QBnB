 <?php
  //Create a user session or resume an existing one
 session_start();
 ?>
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

<style>
h1 {
      font-size: 30px;
      color: #303030;
      font-weight: 600;
      margin-bottom: 30px;
  }

</style>  
  

 
 <?php
if(isset($_SESSION['id'])) {
   // include database connection
    include_once 'config/connection.php'; 
  
  // SELECT query
        $query = "SELECT m.first_name, m.last_name, m.email, m.password, m.username, mp.phone_number, d.year, d.faculty, d.degree_type FROM member m NATURAL JOIN member_phone mp NATURAL JOIN has_degree NATURAL JOIN degree d WHERE member_id=?";
 
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
						
						
 			 	if(isset($_POST['confirm_delete_btn'])) { // 'Delete' has been clicked but not confirmed
					
 					// Find member_id and property_id of any future bookings
 					$query = "SELECT booking_id, member_id FROM booking WHERE start_date > curdate() AND
 								property_id in ( SELECT property_id
 												FROM supplies
 												WHERE member_id=?);"; 

 					// prepare query for execution
 					$stmt = $con->prepare($query);

 					// bind the parameters. This is the best way to prevent SQL injection hacks.
 					$stmt->bind_Param('s', $_SESSION['id']);

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
				
 					// Delete the property and allow deletes to cascade:
 					$query = "DELETE FROM member WHERE member_id=?;";
					
 					$stmt = $con->prepare($query);
 					$stmt->bind_Param('s', $_SESSION['id']);
 					$stmt->execute();
				
 					header("Location: index.php?logout=1");
 					die();
					
 				}
						
						
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
	   <li class="active"><a href="userProfile.php"> <?php echo $myrow['username'];?> </a></li>
	   <li><a href="index.php?logout=1">Log Out</a></li>
     </ul>
   </div>
 </nav>
<br>

<br>
<br>
  <h1 style = "margin-left: 10px;">Welcome <?php echo $myrow['username'];?>,</h1>

<?php
 
if(isset($_POST['updateBtn']) && isset($_SESSION['id'])){
 // include database connection
   include_once 'config/connection.php'; 

 $query = "UPDATE member, member_phone SET member.password=?,member.email=?, member_phone.phone_number=? WHERE member.member_id=? AND member_phone.member_id=?";

 $stmt = $con->prepare($query); 
  $stmt->bind_param('sssss', $_POST['password'], $_POST['email'], $_POST['phonenumber'],$_SESSION['id'],$_SESSION['id']);
 // Execute the query
       $stmt->execute();
            
         
     Header("Location: userProfile.php");
  }

?>
<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
<form name='login' id='login' action='userProfile.php' method='post'>
    <div class="row">
        <div style = "margin-left: 10px;" class="col-md-3">
            <div class="form-login">
                        <label for="username">Username:</label>

            <input disabled type="text" name='username' id='username' class="form-control input-sm chat-input" value="<?php echo $myrow['username']; ?>"/>
                        <label for="password">Password:</label>

            <input type="password" name='password' id='password' class="form-control input-sm chat-input" value="<?php echo $myrow['password']; ?>" />
                        <label for="firstname">First Name:</label>

            <input disabled type="text" name='firstname' id='firstname' class="form-control input-sm chat-input" value="<?php echo $myrow['first_name']; ?>" />
                        <label for="lastname">Last Name:</label>

            <input disabled type="text" name='lastname' id='lastname' class="form-control input-sm chat-input" value="<?php echo $myrow['last_name']; ?>" />
            <label for="email">Email Address:</label>

            <input type="text" name='email' id='email' class="form-control input-sm chat-input" value="<?php echo $myrow['email']; ?>" />
                         <label for="phonenumber">Phone Number:</label>

            <input type="text" name='phonenumber' id='phonenumber' class="form-control input-sm chat-input" value="<?php echo $myrow['phone_number']; ?>" />
                        <label for="degreeyear">Graduation Year:</label>

            <input disabled type="text" name='degreeyear' id='degreeyear' class="form-control input-sm chat-input" value="<?php echo $myrow['year']; ?>" />
                        <label for="faculty">Faculty:</label>

            <input disabled type="text" name='faculty' id='faculty' class="form-control input-sm chat-input" value="<?php echo $myrow['faculty']; ?>" />
                                    <label for="degreetype">Degree Type:</label>

            <input disabled type="text" name='degreetype' id='degreetype' class="form-control input-sm chat-input" value="<?php echo $myrow['degree_type']; ?>" />
            </br>
            <div class="wrapper">
            <span class="group-btn">     
                <input type='submit' id='updateBtn' name='updateBtn' value='Update' class="btn btn-primary btn-md" />
				<br>
				<br>
				<br>
				<br>
				<input type='submit' id='delete_Btn' name='delete_Btn' value='Delete Account' class="btn btn-danger btn-md" />
			    
            </span>
            </div>
            </div>
        
    </div>
</div>
</form>

				<?php
 
				 if(isset($_SESSION['id'])){
				  // include database connection
				    include_once 'config/connection.php';
			 	if(isset($_POST['delete_Btn'])) { // 'Delete' has been clicked but not confirmed
			 		?>
			 		<form class="form-inline" action='userProfile.php' method=POST>
			 		<label for="confirm_delete_btn">Are you sure you want to delete your account forever?</label>
			 		<a href="userProfile.php" class="btn btn-default" role="button">Cancel</a>
			 		<input class="btn btn-danger"  name='confirm_delete_btn' id='confirm_delete_btn' value="Delete" type="submit">
			 		</form>
			 		<?php
				}
				
			}
					?>

</body>
</html>