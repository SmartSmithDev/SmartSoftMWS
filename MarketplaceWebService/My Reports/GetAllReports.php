<?php

/**
 * This file reads all request sql entries and updates their data from request list
 * 
 */
 $debug = false;
require_once ("SqlToArray.php");
require_once ("GetReport.php");
require_once ("ArrayToSQL.php");
require_once ("GetAttribute.php");

//Read value of report request table into array
$reportRequests_list = sql_to_array('mws_report_request');

//Iterate through each request
foreach ($reportRequests_list as $request)
{
	$reportID = $request['GeneratedReportId'];
	if($debug)echo "<br>[DEBUG]\t  Report ID : ".$reportID  ."<br>";
	$reportType = $request['ReportType'];
	if($debug)echo "<br>[DEBUG]\t  Report Type : ".$reportType  ."<br>";
	$reportStatus = $request['ReportProcessingStatus'];
	if($debug)echo "<br>[DEBUG]\t  Report Status : ".$reportStatus  ."<br>";
	
	$table = getAttribute('mws_report_type' , 'Table Name' , 'ReportType' , $reportType); //Get Table Name
	$key = getAttribute('mws_report_type' , 'Key Name' , 'ReportType' , $reportType); //Get Key Name
	
	if($debug)echo "<br>[DEBUG]\t  Table Name : ".$table  ."<br>";
	if($debug)echo "<br>[DEBUG]\t  Key : ".$key  ."<br>";
	
	if($reportID!='')
	{
		$report_array = getReport($reportID);
		
		if($debug)
		{
			echo "<br>[DEBUG Array]<br>";
			echo '<pre>';
			print_r(($report_array));
			echo '</pre>';
			echo "<br>[DEBUG Data End]<br>";
		}
		//array_to_sql($report_array , 'testtable', true); //debug
		array_to_sql($report_array , $table , true , $key);
	}
}
?>