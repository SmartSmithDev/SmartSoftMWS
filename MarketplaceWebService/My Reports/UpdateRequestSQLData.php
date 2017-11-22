<?php
/**
 * A function that takes table details and associative array and updates sql table.
 */
require_once (".SqlConfig.php");// Add this line to access MySql Credentials
function UpdateSqlData($request , $primeKey , $keyValue , $table = 'mws_report_request')
{
	// Create connection
	$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
	// Check connection
	if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
	} 
	//Create array to store key value pairs to update
	$updates = array();
	if (count($request) > 0) {
            foreach ($request as $key => $value) {
               $value = "'$value'";
               $updates[] = "$key = $value"; //Create key value pairs
            }
        }
	// Join all key value pair	
	$implodeArray = implode(', ', $updates);
	
	$sql = ("update `$table` SET  $implodeArray " 
			. " where `$primeKey` = '$keyValue'");
			
	if ($conn->query($sql) === TRUE) {
    	echo "<br>Request Updated for key : $keyValue<br>";
  	} else {
    	echo "Error: ". "<br>" . $conn->error . $sql;
		echo "<br><br>The sql query is : ".$sql."<br><br>";
	}
}
?>