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
      font-size: 15px;
      color: #303030;
      font-weight: 600;
      margin-bottom: 30px;
  }

</style>  
  
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
<div class="page-header" style = "margin-left: 29px;">
  <h2>New Property Listing</h2>
</div>
 <?php
if($_SESSION['id']){
 if(isset($_GET['createProp'])){
include_once 'config/connection.php'; 

     $query = "SELECT district_id
                    FROM
                    district 
                    WHERE
                    district_name = ?
                      ;";
                     $stmt = $con->prepare($query);
                     $stmt->bind_Param("s", $_GET['districtValue']); 
                // Execute the query
                      $stmt->execute();
                    // Get the number of rows returned
                     $result = $stmt->get_result();

                      $row = $result->fetch_assoc();
                      $districtID = $row['district_id'];

      $query = "INSERT into property values(null,'$districtID',?,?,?,'Ottawa','Ontario',?,?,?,?);
                ";

      $stmt = $con->prepare($query);
       $stmt->bind_Param("sssssss", $_GET['streetnumber'], $_GET['streetname'], $_GET['aptnumber'], $_GET['postalcode'], $_GET['seltype'], $_GET['numGeusts'], $_GET['price']); 

      $stmt->execute();
      
      $query = "SELECT property_id from property where district_id = '$districtID' AND street_number = ? AND  street_name =? AND apt_number = ? AND postal_code = ? AND type = ? AND num_guests =? AND price= ?;";
      $stmt = $con->prepare($query);
       $stmt->bind_Param("sssssss", $_GET['streetnumber'], $_GET['streetname'], $_GET['aptnumber'], $_GET['postalcode'], $_GET['seltype'], $_GET['numGeusts'], $_GET['price']); 

      $stmt->execute();
      $result = $stmt->get_result();

        $row = $result->fetch_assoc();
        $propertyID = $row['property_id'];


        $query = "INSERT into supplies values ('$propertyID', ?)";
          $stmt = $con->prepare($query);
            $stmt->bind_Param("s", $_SESSION['id']); 

          $stmt->execute();

      header("Location: myListings.php");
     
      } 


      if(isset($_GET['feature1'])){
        $query = $query . " AND EXISTS(SELECT * from has_feature h WHERE h.property_id = prop.property_id AND feature_id ='1')";}
                if(isset($_GET['feature2'])){$query = "INSERT INTO has_feature VALUES($propertyID, '2');";          
      $stmt = $con->prepare($query);
      $stmt->execute();}
                if(isset($_GET['feature3'])){$query = "INSERT INTO has_feature VALUES($propertyID, '3');";          
      $stmt = $con->prepare($query);
      $stmt->execute();}
                if(isset($_GET['feature4'])){$query = "INSERT INTO has_feature VALUES($propertyID, '4');";          
      $stmt = $con->prepare($query);
      $stmt->execute();}
                if(isset($_GET['feature5'])){$query = "INSERT INTO has_feature VALUES($propertyID, '5');";          
      $stmt = $con->prepare($query);
      $stmt->execute();}
                if(isset($_GET['feature6'])){$query = "INSERT INTO has_feature VALUES($propertyID, '6');";          
      $stmt = $con->prepare($query);
      $stmt->execute();}
                if(isset($_GET['feature7'])){$query = "INSERT INTO has_feature VALUES($propertyID, '7');";          
      $stmt = $con->prepare($query);
      $stmt->execute();}
                if(isset($_GET['feature8'])){$query = "INSERT INTO has_feature VALUES($propertyID, '8');";          
      $stmt = $con->prepare($query);
      $stmt->execute();}
                if(isset($_GET['feature9'])){$query = "INSERT INTO has_feature VALUES($propertyID, '9');";          
      $stmt = $con->prepare($query);
      $stmt->execute();}
                if(isset($_GET['feature10'])){$query = "INSERT INTO has_feature VALUES($propertyID, '10');";          
      $stmt = $con->prepare($query);
      $stmt->execute();}
                if(isset($_GET['feature11'])){$query = "INSERT INTO has_feature VALUES($propertyID, '11');";          
      $stmt = $con->prepare($query);
      $stmt->execute();}
                if(isset($_GET['feature12'])){$query = "INSERT INTO has_feature VALUES($propertyID, '12');";          
      $stmt = $con->prepare($query);
      $stmt->execute();}
                if(isset($_GET['feature13'])){$query = "INSERT INTO has_feature VALUES($propertyID, '13');";          
      $stmt = $con->prepare($query);
      $stmt->execute();}
                if(isset($_GET['feature14'])){$query = "INSERT INTO has_feature VALUES($propertyID, '14');";          
      $stmt = $con->prepare($query);
      $stmt->execute();}
                if(isset($_GET['feature15'])){$query = "INSERT INTO has_feature VALUES($propertyID, '15');";          
      $stmt = $con->prepare($query);
      $stmt->execute();}
                if(isset($_GET['feature16'])){$query = "INSERT INTO has_feature VALUES($propertyID, '16');";          
      $stmt = $con->prepare($query);
      $stmt->execute();}
                if(isset($_GET['feature17'])){$query = "INSERT INTO has_feature VALUES($propertyID, '17');";          
      $stmt = $con->prepare($query);
      $stmt->execute();}
                if(isset($_GET['feature18'])){$query = "INSERT INTO has_feature VALUES($propertyID, '18');";          
      $stmt = $con->prepare($query);
      $stmt->execute();}
                if(isset($_GET['feature19'])){$query = "INSERT INTO has_feature VALUES($propertyID, '19');";          
      $stmt = $con->prepare($query);
      $stmt->execute();}
                if(isset($_GET['feature20'])){$query = "INSERT INTO has_feature VALUES($propertyID, '20');";          
      $stmt = $con->prepare($query);
      $stmt->execute();}
                if(isset($_GET['feature21'])){$query = "INSERT INTO has_feature VALUES($propertyID, '21');";          
      $stmt = $con->prepare($query);
      $stmt->execute();}
                if(isset($_GET['feature22'])){$query = "INSERT INTO has_feature VALUES($propertyID, '22');";          
      $stmt = $con->prepare($query);
      $stmt->execute();}
                if(isset($_GET['feature23'])){$query = "INSERT INTO has_feature VALUES($propertyID, '23');";          
      $stmt = $con->prepare($query);
      $stmt->execute();}





        }   

 
?>

<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
 <form name='login' id='login' action='addNewBooking.php' method='get' >
<div style="width: 300px !important; min-width: 300px; max-width: 300px;">
    <div class="row">
       <div style = "margin-left: 30px;" class="col-md-10">
            <div class="form-login">
            
            <label for="streetnumber">Street Number:</label>

            <input required type="text" name='streetnumber' id='streetnumber' class="form-control input-sm chat-input" />

            <label for="streetname">Street Name:</label>

            <input required type="text" name='streetname' id='streetname' class="form-control input-sm chat-input" />
            
            <label for="aptnumber">Apt Number:</label>

            <input required type="text" name='aptnumber' id='aptnumber' class="form-control input-sm chat-input" />
            
            <label for="city">City:</label>

            <input disabled type="text" name='city' id='city' class="form-control input-sm chat-input" value= "Ottawa"  />
            
            <label for="province">Province:</label>

            <input disabled type="text" name='province' id='province' class="form-control input-sm chat-input" value= "Ontario"  />

            <label for="postalcode">Postal Code:</label>

            <input required type="text" name='postalcode' id='postalcode' class="form-control input-sm chat-input"  />
            
            <label for="price">Price:</label>

            <input required type="text" name='price' id='price' class="form-control input-sm chat-input"  />
                                   
            </br>
            
            </div>
        
   


 <div style = "margin-left: 0px;" class="form-group">
 <label for="sel1">District*:</label>
    <select name="districtValue" class="form-control" id="sel1">
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

<div style = "margin-left: 0px;" class="form-group">
    <label for="sel2"># Guests:</label>
    <select name="numGeusts" class="form-control" id="numGeusts">
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

<div style = "margin-left: 0px;" class="form-group">
    <label for="seltype"># Type:</label>
    <select name="seltype" class="form-control" id="seltype">
      <option value='0'>N/A</option>
    <option>House</option>
      <option>Apartment</option>
      <option>Town House</option>
      <option>Duplex</option>
      
    </select>
  </div>
  </div>
 </div>
</div>
<div style = "margin-left: 32px;" class="col-md-100" >
<b> Features: </b>
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
 <div style = "margin-left: 32px;">
<div class="wrapper">
            <span class="group-btn">     
                <input type='submit' id='createProp' name='createProp' value='Create New Property' class="btn btn-primary btn-md" />
            </span>
            </div>

</form>
</div>

</body>
</html>