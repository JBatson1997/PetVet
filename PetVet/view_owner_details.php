<?php 
//view_owner_details.php
//Retrieves all the records for a particular owner.

$page_title = 'View the Onwer Details';
include ('includes/header.php');

// Page menu.
include ('includes/menu.php');

// Page header.
echo '<h1 id="mainhead">Owner Details</h1>';

require_once ('mysql_connect.php'); // Connect to the db.

		
// Create the query.
$query = "SELECT * FROM Owner WHERE ownerID=".$_REQUEST['ownerID'];		
$result = mysqli_query ($dbcon, $query); // Run the query.

	
	// Fetch and print all the records.
	
		echo '<table border="0" cellpadding="5" cellspacing="0" align="center">';
		while ($row = mysqli_fetch_array($result, MYSQL_ASSOC)) {
		echo'
		<tr valign="top">
			<td width="100"><b>Owner ID: </b> </td>
			<td>'.$row['ownerID'].'</td>
		</tr>
		<tr valign="top">
			<td><b>Name: </b></td>
			<td>'.$row['lname'].', '. $row['fname'] . '</td>
		</tr>
		<tr valign="top">
			<td><b>Address: </b></td>
			<td>'. $row['address'] . '</td>
		</tr>
		<tr valign="top">
			<td><b>Phone: </b></td>
			<td>'. $row['phone'] . '</td>
		</tr>
		<tr valign="top">
			<td><b>Email: </b></td>
			<td>'. $row['email'] .'</td>
		</tr>
		<tr valign="top">
			<td colspan="2"> </td>
		</tr>';
		$ownerid=$row['ownerID'];
		}
		mysqli_free_result ($result); // Free up the resources.
		
		echo '<tr valign="top">
			<td><b>Pets: </b></td>
			<td>';
				// Create the query.
				$petQuery = "SELECT petID, name, species, sex, DATE_FORMAT(birth, '%d %M, %Y') AS birth, DATE_FORMAT(death, '%d %M, %Y') AS death, DATE_FORMAT(regDate, '%d %M, %Y') AS regDate
				FROM pet 
				WHERE ownerID=".$ownerid." ORDER BY name ASC";		
				$petResult = mysqli_query ($dbcon, $petQuery); // Run the query.
				$numPets = mysqli_num_rows($petResult);

if ($numPets > 0) { // If it ran OK, display the records.

	echo "<p align='center'>Owner has registered $numPets pet(s).</p>\n";

	// Table header.
	echo '<table align="center" cellspacing="0" cellpadding="5" border="1">
	<tr><td align="left"><b>Pet ID</b></td><td align="left"><b>Name</b></td><td align="left"><b>Species</b></td><td align="left"><b>Sex</b></td><td align="left"><b>Birth</b></td><td align="left"><b>Death</b></td><td align="left"><b>Registered</b></td></tr>';
	
	// Fetch and print all the records.
	while ($row = mysqli_fetch_array($petResult, MYSQL_ASSOC)) {
		echo '<tr><td align="left">' . $row['petID'] . '</td><td align="left">' . $row['name'] . '</td><td align="left">' . $row['species'] . '</td><td align="left">' . $row['sex'] . '</td><td align="left">' . $row['birth'] . '</td><td align="left">' . $row['death'] . '</td><td align="left">' . $row['regDate'] . '</td></tr>
		';
	}

	echo '</table>';
	
	mysqli_free_result ($petResult); // Free up the resources.	

} else { // If it did not run OK.
	echo '<p class="error">Owner has no pets currently registered.</p>';
}


		echo '</td>
			</tr>
		</table>';
	//}

	echo '</table>';
	

mysqli_close($dbcon); // Close the database connection.

include ('includes/footer.php'); // Include the HTML footer.
?>