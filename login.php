 <?php
  //Create a user session or resume an existing one
 session_start();
 ?>
 
 <?php
 //check if the user clicked the logout link and set the logout GET parameter
if(isset($_GET['logout'])){
	//Destroy the user's session.
	$_SESSION['id']=null;
	$_SESSION['type']=null;
	session_destroy();
}
 ?>
 
<!DOCTYPE HTML>
<html>
    <head>
        <title>QBnB</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
  		<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
  		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
  		<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
  		  		<style>
  body {
      font: 400 15px Lato, sans-serif;
      line-height: 1.8;
      color: #818181;
  }
  h2 {
      font-size: 24px;
      text-transform: uppercase;
      color: #303030;
      font-weight: 600;
      margin-bottom: 30px;
  }
  h4 {
      font-size: 15px;
      line-height: 1.375em;
      color: #303030;
      font-weight: 400;
      margin-bottom: 30px;
      padding-top: 70px; 
      padding-bottom: 0px; 

  }  

  .navbar {
      margin-bottom: 0;
      background-color: #000000;
      z-index: 9999;
      border: 0;
      font-size: 12px !important;
      line-height: 1.42857143 !important;
      letter-spacing: 4px;
      border-radius: 0;
      font-family: Montserrat, sans-serif;
  }
  .navbar li a, .navbar .navbar-brand {
      color: #fff !important;
  }
  .navbar-nav li a:hover, .navbar-nav li.active a {
      color: #f4511e !important;
      background-color: #fff !important;
  }
  .navbar-default .navbar-toggle {
      border-color: transparent;
      color: #fff !important;
  }

  </style>
    </head>
<body id="myPage" data-spy="scroll" data-target=".navbar" data-offset="60">
<nav class="navbar navbar-default navbar-fixed-top">
  <div class="container">
    <div class="navbar-header">
	<a href="index.php">
	<img src="logo2.png" style="width:70px;height:60px;">
	</a>                 
    </div>
    <div class="collapse navbar-collapse" id="myNavbar">
      <ul class="nav navbar-nav navbar-right">
        
      </ul>
    </div>
  </div>
</nav>

 
 <?php
 //check if the user is already logged in and has an active session
if(isset($_SESSION['id'])){
	//Redirect the browser to the profile editing page and kill this page.
	if ($_SESSION['type'] == "general")
	{
		header("Location: gen_main.php");
	}
	elseif ($_SESSION['type'] == "administrator")
	{
		header("Location: gen_main.php");
	}
	die();
}
 ?>
 <nav class="navbar navbar-default navbar-fixed-top">
  <div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>                        
      </button>
	   		<a href="index.php">
	       <img src="logo2.png" style="width:70px;height:60px;">
	   </a>                   
    </div>
    <div class="collapse navbar-collapse" id="myNavbar">
      <ul class="nav navbar-nav navbar-right">
        
      </ul>
    </div>
  </div>
</nav>



 <?php

 
//check if the login form has been submitted
if(isset($_POST['loginBtn'])){
 
    // include database connection
    include_once 'config/connection.php'; 
	
	// SELECT query
        $query = "SELECT member_id, member_type, username, password, email FROM member WHERE username=? AND password=?";
 
        // prepare query for execution
        if($stmt = $con->prepare($query)){
		
			// bind the parameters. This is the best way to prevent SQL injection hacks.
			$stmt->bind_Param("ss", $_POST['username'], $_POST['password']);
		 
			// Execute the query
			$stmt->execute();
 
			/* resultset */
			$result = $stmt->get_result();

			// Get the number of rows returned
			$num = $result->num_rows;
		
			if($num>0){
				//If the username/password matches a user in our database
				//Read the user details
				$myrow = $result->fetch_assoc();
				//Create a session variable that holds the user's id
				$_SESSION['id'] = $myrow['member_id'];
				//Create a session variable that holds the user's type
				$_SESSION['type'] = $myrow['member_type'];
			
				//Redirect the browser to the profile editing page and kill this page.
				if ($myrow['member_type'] == "general")
				{
					header("Location: gen_main.php");
					
				}
				elseif ($myrow['member_type'] == "administrator")
				{
					header("Location: gen_main.php");
				}
				die();
			} else {

				 $message = "Failed Login.";
				echo "<script type='text/javascript'>alert('$message');</script>";   


			}
		} else {
			echo "failed to prepare the SQL";
		}
 }
 
?>


<!--Pulling Awesome Font -->
<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
<form name='login' id='login' action='login.php' method='post'>
<div class="container">
    <div class="row">
        <div class="col-md-offset-0.6 col-md-3">
            <div class="form-login">
            <h4>Please enter your login information.</h4>
            <input type="text" name='username' id='username' class="form-control input-sm chat-input" required placeholder="username" />
            </br>
            <input type="password" name='password' id='password' class="form-control input-sm chat-input" required placeholder="password" />
            </br>
            <div class="wrapper">
            <span class="group-btn">     
                <input type='submit' id='loginBtn' name='loginBtn' value='Log In' class="btn btn-primary btn-md" />
            </span>
            </div>
            </div>
        
        </div>
    </div>
</div>
</form>
</body>
</html>