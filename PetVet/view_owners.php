<?php # view_owners.php 
// This script retrieves all the owners from the owners table.

$page_title = 'View the Current Owners';
include ('includes/header.php');

// Page menu.
include ('includes/menu.php');

// Page header.
echo '<h1 id="mainhead">Registered Owners</h1>';

require_once ('mysql_connect.php'); // Connect to the db.
		
// Make the query.
$query = "SELECT ownerID, CONCAT(lname, ', ', fname) AS name, address, phone, email FROM owner ORDER BY lname ASC;";		
$result = mysqli_query ($dbcon, $query); // Run the query.
$num = mysqli_num_rows($result);

if ($num > 0) { // If it ran OK, display the records.

	echo "<p align='center'>There are currently $num registered owners.</p>\n";

	// Table header.
	echo '<table align="center" cellspacing="0" cellpadding="5" border="1">
	<tr><td align="left"><b>Name</b></td><td align="left"><b>Address</b></td><td align="left"><b>Action</b></td></tr>';
	
	// Fetch and print all the records.
	while ($row = mysqli_fetch_array($result, MYSQL_ASSOC)) {
		echo '<tr><td align="left">'. $row['name'] . '</td><td align="left">' . $row['address'] . '</td><td align="left"><a href="view_owner_details.php?ownerID='.$row['ownerID'].'">more details</a></td></tr>';
	}

	echo '</table>';
	
	mysqli_free_result ($result); // Free up the resources.	

} else { // If it did not run OK.
	echo '<p class="error">There are currently no registered students.</p>';
}

mysqli_close($dbcon); // Close the database connection.

include ('includes/footer.php'); // Include the HTML footer.
?>