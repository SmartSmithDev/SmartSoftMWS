<?php
/**
* Put associative array values into table
* Input associative array of data, and insert data into SQL Database
* Create table if not already exist with given parameters.
* @param array $queryData associative array of data, or array of associative array of data
* @param string $table name of table, in which to insert data
* @param boolean $multiple to define true if the query data is array of associative array
* that is, true if multiple data entries are provided
*/
require_once (".SqlConfig.php");// Add this line to access MySql Credentials
function array_to_sql($queryData , $table , $multiple = false , $key='')
{
	// Create connection
	$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
	// Check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} 
	//Separate Multiple Keys
	$keyArray = explode("," , $key);
	
	//If single Entry
	if(!$multiple)
	{
		//Check if table exist already
		$query = "select 1 from `$table`";
		$result = mysqli_query($conn,$query);
		
		if(empty($result)) //Table Not exist Already
		{
			//Create table if not already exist
			$sql="create table  IF NOT EXISTS `$table`(`".implode("` varchar(10) NOT NULL,`"
			,array_keys($queryData))."`"." varchar(20) NOT NULL".") ";
			//Run the Query
			$run=mysqli_query($conn,$sql)or die("error in creating table");
			
			//Set Primary Key
			if ($key!='')
			{
				//$sql="ALTER TABLE `$table` ADD PRIMARY KEY (`$key`)";
				$sql="ALTER TABLE `$table` ADD PRIMARY KEY (`".implode("` , `",$keyArray)."`)";
				$run=mysqli_query($conn,$sql)or die("Error in setting Primary Key");
			}
		}
		
		if (count($queryData) > 0) {
			foreach ($queryData as $key => $value) {
				//Escape Special Characters in query data for use in SQL.
				$value = $conn->real_escape_string($value);
				$value = "'$value'";
				$updates[] = "`$key` = $value";
			}
		}
		
		$implodeArray = implode(', ', $updates);
		
		$sql = ("INSERT INTO `$table` (`".implode("` , `",array_keys($queryData))."`) "
		. "VALUES ('".implode("' , '",array_values($queryData))."')"
		);//. " ON DUPLICATE KEY UPDATE $implodeArray");
		
		if ($conn->query($sql) === TRUE) {
			echo "Request Updated";
		} else {
			echo "Error: ". "<br>" . $conn->error . $sql;
			echo "<br><br>The query is : $sql <br><br>"	 ; 
		}
	}
	//If multiple data
	else
	{
		//Check if table exist already
		$query = "select 1 from `$table`";
		$result = mysqli_query($conn,$query);
		if(empty($result)) //Table Not exist Already
		{
			echo $table." Table Does Not Exist <br>";
			//Create table from first entry, if not exist
			
			if (isset($queryData[0])) {
				$sql="create table  IF NOT EXISTS `$table`(`".implode("` varchar(100) NOT NULL,`"
				,array_keys($queryData[0]))."`"." varchar(100) NOT NULL".") ";
				//Run the Query
				$run=mysqli_query($conn,$sql)or die("error in creating table");
				
				if ($key!='')
				{
					//$sql="ALTER TABLE `$table` ADD PRIMARY KEY (`$key`)";
					$sql="ALTER TABLE `$table` ADD PRIMARY KEY (`".implode("` , `",$keyArray)."`)";
					$run=mysqli_query($conn,$sql)or die("Error in setting Primary Key");
				}
			}
			else
			{
				echo "No Data in this report";
				return;
			}
		}
		
		//Iterate through each entry, and transfer into sql database
		foreach($queryData as $queryEntry)
		{
			if (count($queryEntry) > 0) {
				foreach ($queryEntry as $key => $value) {
					//Escape Special Characters in query data for use in SQL.
					$queryEntry = str_replace('\'','??',$queryEntry);
					$value = str_replace('\'',"\\'",$value);
					$value = mysqli_real_escape_string($conn,$value);
					$value = "'$value'";
					$updates[] = "`$key` = $value";
				}
			}
			
			$implodeArray = implode(', ', $updates);
			
			$sql = ("INSERT INTO `$table` (`".implode("` , `",array_keys($queryEntry))."`) "
			. "VALUES ('".implode("' , '",array_values($queryEntry))."')"
			. " ON DUPLICATE KEY UPDATE $implodeArray");
			
			//echo "<br>".$sql;
			
			//Run the Query
			if ($conn->query($sql) === TRUE) {
				echo "<br>Request Updated<br>";
			} else {
				echo "Error: ". "<br>" . $conn->error . $sql;
				echo "<br>The query is : $sql <br>"	 ; 
			}
		}				  
	}		
}
?>