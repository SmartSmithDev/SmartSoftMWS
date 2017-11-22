<?php
/**
 * Get data from sql table into an associated array.
 * 
 * Example:	 	echo '<pre>';
				print_r(sqltoarray('mws_report_request'));
				echo '</pre>';
 * @param string $table as table name
 * @return array
 */
require_once (".SqlConfig.php");// Add this line to access MySql Credentials
function sql_to_array($table)
{
	// Create connection
	$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
	// Check connection
	if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
	} 
	
	$sql = "SELECT * FROM `$table`";
			
	$result = mysqli_query($conn,$sql);
	//If no Result came out
	if (!$result) {
    	echo "Could not successfully run query ($sql) from DB: " . mysql_error();
    	return;
	}
	// If there are no rows in table
	if (mysqli_num_rows($result) == 0) {
    	echo "No rows found, nothing to print so am exiting";
    	return;
	}
	//Creare array to store Result Data
	$result_data = array();
	// Get SQL Table in associative array
	while ($row = mysqli_fetch_assoc($result)) {
		$result_data[] = $row;
	}
	// Return the associative array
	return $result_data;
}			
?>