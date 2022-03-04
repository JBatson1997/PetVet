<!-- start mysql_connect.php -->
<?php

// This file contains the database access information. 
// It creates a connection to MySQL and selects the database.

// Set the database access information as constants.
DEFINE ('DB_USER', 'root');
DEFINE ('DB_PASSWORD', '');  //change this for the account in the lab.
DEFINE ('DB_HOST', 'localhost');
DEFINE ('DB_NAME', 'petvet');

// Make the connnection.
$dbcon = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD);
if (!$dbcon)  {  
	die('Could not connect: ' . mysql_error());  
}

// Select database and create table
mysqli_select_db($dbcon, DB_NAME);


?>
<!-- end mysql_connect.php -->