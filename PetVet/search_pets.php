<?php 

$page_title = 'Search for Pet';
include ('includes/header.php');

// Page menu.
include ('includes/menu.php');

//start of search_pets.php


?>

<h2>Search for Pet</h2>
<form action="search_pets.php" method="post">


	<table cellpadding="5" cellspacing="0" border="0" align="center">
	<tr>

		<td><div class="text">Owner ID: </div></td>
		<td><input type="text" name="ownerid" size="10" maxlength="8" value="<?php if (isset($_POST['ownerid'])) echo $_POST['ownerid']; ?>" /></td>
	</tr>
	<tr>
		<td><div class="text">Pet Name: </div></td>
		<td><input type="text" name="petname" size="20" maxlength="20" value="<?php if (isset($_POST['petname'])) echo $_POST['petname']; ?>" /></td>
	</tr>
	<tr>
		<td><div class="text">Species: </div></td>
		<td><input type="text" name="species" size="20" maxlength="20" value="<?php if (isset($_POST['species'])) echo $_POST['species']; ?>" /></td>
	</tr>
	<tr>
		<td colspan="2" align="center"><input type="submit" value="Search" name="btn_Submit"><input type="reset" value="Reset" name="btn_Reset">	
	<input type="hidden" name="submitted" value="TRUE" /></td>
	</tr>
	

</table>

</form>


<?php
// Check if the form has been submitted.
if (isset($_POST['submitted'])) {

	require_once ('mysql_connect.php'); // Connect to the db.
	
	// Create a function for escaping the data.
	function escape_data ($data) {
		global $dbc; // Need the connection.
		
		//if magic quotes is enabled strip the slashes
		if (ini_get('magic_quotes_gpc')) {
			$data = stripslashes($data);
		}
		
		//escape what could be problematic characters
		return mysqli_real_escape_string(mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD), trim($data));
	} // End of function.

	//Create query dynamically depending on what was submitted
	$petQuery = "SELECT owner.ownerid, CONCAT(owner.lname, ', ', owner.fname) AS ownername, pet.petid, pet.name, pet.species, DATE_FORMAT(regDate, '%d %M, %Y') AS regDate, pet.photo FROM owner, pet WHERE owner.ownerid = pet.ownerid";		


	//Check for an owner id.
	if (!empty($_POST['ownerid'])) {
		$sid = escape_data($_POST['ownerid']);
		$petQuery = $petQuery." AND pet.ownerid=".$sid;
	} else {
		$petQuery = $petQuery." AND pet.ownerid IS NOT NULL";
	}

	//Check for a pet name.
	if (!empty($_POST['petname'])) {
		$pname = escape_data($_POST['petname']);
		$petQuery = $petQuery." AND pet.name LIKE '".$pname."%'";
	}

	//Check for a species.
	if (!empty($_POST['species'])) {
		$species = escape_data($_POST['species']);
		$petQuery = $petQuery." AND species LIKE '%".$species."%'";
	}

	$petQuery = $petQuery." ORDER BY pet.name ASC;";

	//echo 'query: '.$petQuery;

	$petResult = mysqli_query ($dbcon, $petQuery); // Run the query.
	$pnum = mysqli_num_rows($petResult);


	if ($pnum > 0) { // If it ran OK, display the records.

		echo "<p align='center'><b>".$pnum."</b> pet matches found.</p>";

	// Table header.
	echo '<table align="center" cellspacing="0" cellpadding="5" border="1">
	<tr><td align="left"><b>Owner</b></td><td align="left"><b>Name</b></td><td align="left"><b>Photo</b></td><td align="left"><b>Species</b></td><td align="left"><b>Date Registered</b></td><td align="left"><b>More Details</b></td></tr>';
	
	// Fetch and print all the records.
	while ($row = mysqli_fetch_array($petResult, MYSQL_ASSOC)) {
		echo '<tr><td align="left">' . $row['ownername'] . '</td><td align="left">' . $row['name'] . '</td><td align="left"><img src="images/'.$row['photo'].'"></td><td align="left">' . $row['species'] . '</td><td align="left">' . $row['regDate'] . '</td><td align="left">(not enabled)</td></tr>';
	}

	echo '</table>';
	
	mysqli_free_result ($petResult); // Free up the resources.	

} else { // If it did not run OK.
	echo '<p class="error">There are currently no registered students.</p>';
}

mysqli_close($dbcon); // Close the database connection.

	
} // End of the main Submit conditional.

?>

<!-- end of search_course.php -->
<?php
include ('includes/footer.php');
?>