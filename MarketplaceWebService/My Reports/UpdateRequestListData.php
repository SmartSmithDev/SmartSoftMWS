<?php

/**
 * This file reads all sql entries and updates their data from request list
 * 
 *	echo '<pre>';
 *	//print_r($requestServerData);
 *	echo '</pre>';
 */
require_once ("SqlToArray.php");
require_once ("GetReportRequestList.php");
require_once ("UpdateRequestSQLData.php");

//Read value of report request table into array
$reportRequests_list = sql_to_array('mws_report_request');

//Iterate through each request
foreach ($reportRequests_list as $request)
{
	$requestStatus = $request['ReportProcessingStatus']; //Get Request Status
	
	if($requestStatus == '_SUBMITTED_' or $requestStatus == '_IN_PROGRESS_')
	{
		$requestServerData = getReportRequestList($request['ReportRequestId']);
		UpdateSqlData($requestServerData , 'ReportRequestId' , $request['ReportRequestId']);
	}
}

?>