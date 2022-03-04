<?php 

$page_title = 'Register a Pet';
include ('includes/header.php');

//start of register_pet.php

// Page menu.
include ('includes/menu.php');

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

	$errors = array(); // Initialize error array.
	
	$ownerID = isset($_POST['ownerID']) ? $_POST['ownerID'] : false;
   if ($ownerID) {
      echo htmlentities($_POST['ownerID'], ENT_QUOTES, "UTF-8");
   } else {
     echo "owner ID is required";
     exit; 
   }
   
	// Check for a pet name.
	if (empty($_POST['name'])) {
		$errors[] = 'You forgot to enter the pet name.';
	} else {
		$name = escape_data($_POST['name']);
	}

	// Check for a species.
	if (empty($_POST['species'])) {
		$errors[] = 'You forgot to enter the species.';
	} else {
		$species = escape_data($_POST['species']);
	}

	// Check for a sex.
	$sex = escape_data($_POST['sex']);
	
	// Check for a birth date.
	if (empty($_POST['birth'])) {
		$errors[] = 'You forgot to enter the birth.';
	} else {
		$birth = escape_data($_POST['birth']);
	}

	if (empty($errors)) { // If everything's okay.
	
		// Register the student in the database.
		
		// Create the query.
		$query = "INSERT INTO pet (ownerid, name, species, sex, birth, regDate) VALUES ('$ownerID', '$name', '$species', '$sex', '$birth', CURDATE() )";		
		$result = mysqli_query ($dbcon, $query); // Run the query.
		if ($result) { // If it ran OK.
		
			// Send an email, if desired.
			
			// Print a message.
			echo '<h1 id="mainhead">Thank you!</h1>
		<p>Thank you for registering!</p><p><br /></p>';	
		
			// Include the footer and quit the script (to not show the form).
			include ('includes/footer.php'); 
			exit();
			
		} else { // If it did not run OK.
			echo '<h1 id="mainhead">System Error</h1>
			<p class="error">You could not be registered due to a system error. We apologize for any inconvenience.</p>'; // Public message.
			echo '<p>' . mysql_error() . '<br /><br />Query: ' . $query . '</p>'; // Debugging message.
			include ('includes/footer.php'); 
			exit();
		}
		
	} else { // Report the errors.
	
		echo '<h1 id="mainhead">Error!</h1>
		<p class="error">The following error(s) occurred:<br />';
		foreach ($errors as $msg) { // Print each error.
			echo " - $msg<br />\n";
		}
		echo '</p><p>Please try again.</p><p><br /></p>';
		
	} // End of if (empty($errors)) IF.

	mysqli_close($dbcon); // Close the database connection.
		
} // End of the main Submit conditional.

?>

<h2>Register a Pet</h2>
<form action="register_pet.php" method="post">


	<table cellpadding="5" cellspacing="0" border="0" align="center">
	<tr>

		<td><div class="text">Owner: </div></td>
		<td><select id="ownerID" name="ownerID">
		<?php
		require_once ('mysql_connect.php'); // Connect to the db.
		
		$query = "SELECT ownerID, fname, lname FROM owner";		
$result = mysqli_query ($dbcon, $query); // Run the query.
		echo"<option value='' selected>--- choose owner ---</option>";
while ($row = mysqli_fetch_array($result, MYSQL_ASSOC)) {
  echo"<option value=".$row['ownerID'].">".$row['lname'].", ". $row['fname'] . "</option>";
  		//$ownerid=$row['ownerID'];
		}
		//if (isset($_POST['ownerID'])) echo $_POST['ownerID'];
		mysqli_close($dbcon); // Close the database connection.
		?>
</select>

</td>
	</tr>
	<tr>
		<td><div class="text">Name: </div></td>
		<td><input type="text" name="name" size="20" maxlength="20" value="<?php if (isset($_POST['name'])) echo $_POST['name']; ?>" /></td>
	</tr>
	<tr>
		<td><div class="text">Species: </div></td>
		<td><input type="text" name="species" size="20" maxlength="45" value="<?php if (isset($_POST['species'])) echo $_POST['species']; ?>" /></td>
	</tr>
	<tr>
		<td><div class="text">Sex: </div></td>
		<td><input type="radio" id="m" name="sex" value="m" checked>
			<label for="m">Male</label></br>
			<input type="radio" id="f" name="sex" value="f">
			<label for="m">Female</label>
			
		</td>
	</tr>
	<tr>

		<td><div class="text">Date of Birth: </div></td>
		<td><input type="text" name="birth" size="20" maxlength="50" value="<?php if (isset($_POST['birth'])) echo $_POST['birth']; ?>" /></td>
	</tr>

	<tr>
		<td colspan="2" align="center"><input type="submit" value="Register" name="btn_Submit"><input type="reset" value="Reset" name="btn_Reset">	
	<input type="hidden" name="submitted" value="TRUE" /></td>
	</tr>
	

</table>

</form>
<!-- end of register_pet.php -->
<?php
include ('includes/footer.php');
?>