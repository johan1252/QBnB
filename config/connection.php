<?php
// used to connect to the database

//$host = "localhost";
//$host = "127.0.0.1";

if (file_exists('/var/www/html/index.php'))
{
	// Docker mySQL Container IP
	$host = "172.17.0.2";
}
else
{
	// XAMPP MYSQL IP, or 127.0.0.1
	$host = "localhost";
}

$db_name = "QBnB";
$username = "cisc332";
$password = "cisc332password";

try {
    $con = new mysqli($host,$username,$password, $db_name);
}
 
// show error
catch(Exception $exception){
    echo "Connection error: " . $exception->getMessage();
}
/*
 $con = new mysqli($host,$username,$password, $db_name);
 //$con = mysqli_connect($host,$username,$password, $db_name);
 // Check connection
 if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  die();
  }
   */
?>