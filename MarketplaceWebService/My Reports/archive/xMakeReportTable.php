<?php
/**
 *Make table for FBA Reports 
 *
 *
 *
 *
 */
require_once (".SqlConfig.php");// Add this line to access MySql Credentials

function report_table($queryData , $table = "mws_report_type" )
{
	
	// Create connection
	$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
	// Check connection
	if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
	} 
	
	//Check if table exist already
		$query = "select 1 from `$table`";
		$result = mysqli_query($conn,$query);
		
		if(empty($result)) //Table Not exist Already
		{
			//Create table if not already exist
			$sql="create table  IF NOT EXISTS `$table`(`".implode("` varchar(10) NOT NULL,`"
			,array_keys($queryData))."`"." varchar(20) NOT NULL".") ";
		
			echo "<br>".$sql."<br>";
			//$run=mysqli_query($conn,$sql)or die("error in creating table");
		
			//Set Primary Key
			if ($key!='')
			{
				if(strpos($key, ',') !== false)
				{
					$keys = explode(",",$key);
					
				}
				else
				{
					$sql="ALTER TABLE `$table` ADD PRIMARY KEY (`$key`)";
					echo "<br>".$sql."<br>";
					//$run=mysqli_query($conn,$sql)or die("Error in setting Primary Key");
				}
			}
		}
		  	
}
?>