<?php
/**
 * Get $attr from $table, where $key = $value
 * Example:	 	echo getAttribute('mws_report_type' , 'Table Name' , 'ReportType' , '_GET_AMAZON_FULFILLED_SHIPMENTS_DATA_');
 * @param string $table as table name
 * @param string $attr 
 * @return string : Attribute Requested
 */
require_once (".SqlConfig.php");	// Add this line to access MySql Credentials
function getAttribute($table , $attr , $key , $value)
{
	// Create connection
	$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
	// Check connection
	if ($conn->connect_error) {
    	die("Connection failed: " . $conn->connect_error);
	} 
	
	$sql = "SELECT `$attr` FROM `$table` where `$key` = '$value'";		
	$result = mysqli_query($conn,$sql);
	
	if (!$result) {
    	echo "Could not successfully run query ($sql) from DB: " . mysql_error();
    	return;
	}
	if (mysqli_num_rows($result) == 0) {
    	echo "No rows found, nothing to print so am exiting";
    	return;
	}
	$row = mysqli_fetch_assoc($result);
	return $row["$attr"];
}				
?>