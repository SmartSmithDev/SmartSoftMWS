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
function tdl_to_array($filename='file.txt',  $header_exist=true, $delimiter=",")
{
	$debug = false;
	if(!file_exists($filename) || !is_readable($filename))
		return FALSE;
	
	$header = NULL;
	$data = array();
	if (($handle = fopen($filename, 'r')) !== FALSE)
	{
		while (($row = fgetcsv($handle, 1000, $delimiter)) !== FALSE)
		{
			//If there exist some rows
			if($row != array(null))
			{
				if($debug)
				{
					echo "<br>[DEBUG Array]<br>";
					echo '<pre>';
					print_r(($row));
					echo '</pre>';
					echo "<br>[DEBUG Data End]<br>";
				}
				if($header_exist)
				{
					if(!$header)
					$header = array_map('trim', $row);
					else
						$data[] = array_combine($header, $row);
				}
				else
					$data[] = $row;
			}
		}
		fclose($handle);
	}
	return $data;	//Return associative array
}

?>