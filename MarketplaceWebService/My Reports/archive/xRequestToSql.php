<?php
/**
 * Convert a tab delimited file into an associated array.
 * The first row should contain the array keys.
 * 
 * Example:	 	echo '<pre>';
				print_r(csv_to_array('myCSVFile.csv'));
				echo '</pre>';
 * 
 * @param string $filename Path to the CSV file
 * @param string $header_exist If the first line is header or not
 * @param string $delimiter The separator used in the file
 * @return array
 */
require (".SqlConfig.php");// Add this line to access MySql Credentials

function request_to_sql($request , $table = 'mws_report_request')
{
	
	// Create connection
	$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
	// Check connection
	if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
	} 
	
	$sql = ("INSERT INTO `$table` (`".implode("` , `",array_keys($request))."`) "
		    . "VALUES ('".implode("' , '",array_values($request))."')");
			
			
	if ($conn->query($sql) === TRUE) {
    		echo "Request Updated";
  		} else {
    		echo "Error: ". "<br>" . $conn->error . $sql;
		}

}

//request_to_sql(array('field1'=>'value1','field2'=>'value2','field3'=>'value3'));
//INSERT INTO `mws_report_request` (`RequestId`, `SubmittedDate`, `StartDate`, `EndDate`, `ReportType`, `ReportProcessingStatus`, `ReportRequestId`, `Scheduled`) VALUES ('a', '2017-06-01 00:00:00', '2017-06-15 00:00:00', '2017-06-13 00:00:00', 'bb', 'ccc', 'ddd', 'reef');

?>