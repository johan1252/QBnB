 <?php
  //Create a user session or resume an existing one
 session_start();
 ?>

<?php

 
//check if the login form has been submitted
if(isset($_POST['signUpBtn'])){
include_once 'config/connection.php'; 

    $query = "SELECT * FROM member WHERE username=?";
        // prepare query for execution
      if($stmt = $con->prepare($query)){
      $stmt->bind_Param("s", $_POST['username']);
      // bind the parameters. This is the best way to prevent SQL injection hacks.
   
      // Execute the query
      $stmt->execute();
 
      /* resultset */
      $result = $stmt->get_result();

      // Get the number of rows returned
      $num = $result->num_rows;
      if($num>0){
     
        $message = "This username has already been taken";
        echo "<script type='text/javascript'>alert('$message');</script>";     
      } 
      else 
      {
        
        $query = "INSERT into member values(null,'general',?,?,?,?,?)";
          $stmt = $con->prepare($query);
          $stmt->bind_Param("sssss", $_POST['firstname'], $_POST['lastname'],$_POST['email'],$_POST['username'],$_POST['password']);
          $stmt->execute();

        

        // header("Location: login.php");
        
      }
    }  
    
    else {
      echo "failed to prepare the SQL";
    }
 
}           


 
//check if the login form has been submitted
if(isset($_POST['signUpBtn'])){
include_once 'config/connection.php'; 

    $query = "SELECT * FROM degree WHERE year=? AND faculty=? AND degree_type=?";
        // prepare query for execution
      if($stmt = $con->prepare($query)){
      $stmt->bind_Param("sss", $_POST['gradY'],$_POST['selF'],$_POST['selD']);
      // bind the parameters. This is the best way to prevent SQL injection hacks.
   
      // Execute the query
      $stmt->execute();
 
      /* resultset */
      $result = $stmt->get_result();

      // Get the number of rows returned
      $num = $result->num_rows;
    
      if($num>0){
     
        //$message = "This degree is already in the table.";
        //echo "<script type='text/javascript'>alert('$message');</script>";  

        $query = "SELECT degree_id FROM degree WHERE year=? AND faculty = ? AND degree_type = ?";
         $stmt = $con->prepare($query);
        $stmt->bind_Param("sss", $_POST['gradY'],$_POST['selF'],$_POST['selD']);
        $stmt->execute();

        $result = $stmt->get_result();  
        if($row = $result->fetch_assoc()){
           $degreeID = $row["degree_id"];
                               //echo "<script type='text/javascript'>alert('$degreeID');</script>";     
        }
      $query = "SELECT member_id FROM member WHERE username=?";
      $stmt = $con->prepare($query);
      $stmt->bind_Param("s", $_POST['username']);
      $stmt->execute();
      $result = $stmt->get_result();
      // Get the number of rows returned
          if($row = $result->fetch_assoc()){          
            $memberID = $row["member_id"];
                    //echo "<script type='text/javascript'>alert('$memberID');</script>";     
          }
       
      $query = "INSERT into has_degree values('$memberID','$degreeID')";
      $stmt = $con->prepare($query);
      $stmt->execute();
      $query = "INSERT into member_phone values('$memberID',?)";
      $stmt = $con->prepare($query);
      $stmt->bind_Param("s", $_POST['phonenumber']);
      $stmt->execute();
      $query = "INSERT into member_phone values('$memberID',?)";
      $stmt = $con->prepare($query);
      $stmt->bind_Param("s", $_POST['phonenumber']);
      $stmt->execute();
       header("Location: login.php?logout=1");
     
      } 
      else 
      {
        
        
        $query = "INSERT into degree values(null,?,?,?)";
          $stmt = $con->prepare($query);
          $stmt->bind_Param("sss", $_POST['gradY'],$_POST['selF'],$_POST['selD']);
          $stmt->execute();

         $query = "SELECT * FROM degree WHERE year=? AND faculty = ? AND degree_type = ?";
         $stmt = $con->prepare($query);
        $stmt->bind_Param("sss", $_POST['gradY'],$_POST['selF'],$_POST['selD']);
        $stmt->execute();

        $result = $stmt->get_result();  
        if($row = $result->fetch_assoc()){
           $degreeID = $row["degree_id"];
             // echo "<script type='text/javascript'>alert('$degreeID');</script>";     
       }
      $query = "SELECT member_id FROM member WHERE username=?";
      $stmt = $con->prepare($query);
      $stmt->bind_Param("s", $_POST['username']);
      $stmt->execute();
      $result = $stmt->get_result();
      // Get the number of rows returned
          if($row = $result->fetch_assoc()){          
            $memberID = $row["member_id"];
                   // echo "<script type='text/javascript'>alert('$memberID');</script>";     

          }
       
      $query = "INSERT into has_degree values('$memberID','$degreeID')";
      $stmt = $con->prepare($query);
      $stmt->execute();
      $query = "INSERT into member_phone values('$memberID',?)";
      $stmt = $con->prepare($query);
      $stmt->bind_Param("s", $_POST['phonenumber']);
      $stmt->execute();

       header("Location: login.php?logout=1");
  
        
      }
    }  
    
    else {
      echo "failed to prepare the SQL";
    }
 
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


<!--Pulling Awesome Font -->
<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
<form name='signupp' id='signupp' action = 'signup.php' method='post'>
<div class="container">
    <div class="row">
           <div class="col-md-offset-0.5 col-md-3"> 
          <h4>Please enter the following information to create an account.</h4>
            <div class="form-login">
            <label for="username">Username:</label>
            <input required type="text" name='username' id='username' class="form-control input-sm chat-input" placeholder="Username" />
            <label for="password">Password:</label>
            <input required type="password" name='password' id='password' class="form-control input-sm chat-input" placeholder="Password" />
            <label for="firstname">First Name:</label>
            <input required type="text" name='firstname' id='firstname' class="form-control input-sm chat-input" placeholder="First Name" />
            <label for="lastname">Last Name:</label>
            <input required type="text" name='lastname' id='lastname' class="form-control input-sm chat-input" placeholder="Last Name" />
             <label for="phonenumber">Phone Number:</label>
            <input required type="text" name='phonenumber' id='phonenumber' class="form-control input-sm chat-input" placeholder="Phone Number" />
            <label for="email">Email Address:</label>
            <input required type="text" name='email' id='email' class="form-control input-sm chat-input" placeholder="Email Address" />
            <label for="gradY">Graduation Year:</label>
            <input required type="text" name='gradY' id='gradY' class="form-control input-sm chat-input" placeholder="YYYY" />
            <div class="form-group">
                <label for="selF">Faculty:</label>
                <select name="selF" class="form-control" id="selF" required>
                  <option value='0' disabled selected>Select your faculty</option>
                  <option>Applied Science & En</option>
                  <option>Arts & Science</option>
                  <option>Computing</option>
                  <option>Medicine</option>
                  <option>Law</option>
                </select>
              </div>

              <div class="form-group">
                <label for="selD">Degree:</label>
                <select name="selD" class="form-control" id="selF" required>
                  <option value='0' disabled selected>Select your Degree</option>
                  <option>BEng</option>
                  <option>MEng</option>
                  <option>BSc</option>
                  <option>MSc</option>
                  <option>BA</option>
                  <option>MA</option>
                  <option>MD</option>
                </select>
              </div>
            <div class="form-group">
            <div class="wrapper">
            <span class="group-btn">     
            <input type='submit' id='signUpBtn' name='signUpBtn' value='Sign Up' class="btn btn-primary btn-md" />
            </span>
            </div>
            </div>
        </div>
    </div>
</div>
</form>


</body>
</html>