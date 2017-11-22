<?php

/**
 * Takes in array of Request ID's, add return array of status of those request ID
 * Example of array : $Request_ID_List = array('50212017343','50211017342','50210017342','50206017341','50205017341');
 * Example Execute : getReportRequestList(array('50212017343','50211017342','50210017342','50206017341','50205017341'));
 */

 function getReportRequestList($Request_ID_List)
 { 	  
	 include_once ('.config.inc.php'); 
	 $serviceUrl = "https://mws.amazonservices.in";
	 
	 $config = array (
	  'ServiceURL' => $serviceUrl,
	  'ProxyHost' => null,
	  'ProxyPort' => -1,
	  'MaxErrorRetry' => 3,
	);
	
	 $service = new MarketplaceWebService_Client(
		 AWS_ACCESS_KEY_ID, 
		 AWS_SECRET_ACCESS_KEY, 
		 $config,
		 APPLICATION_NAME,
		 APPLICATION_VERSION);

	 $parameters_request_list = array (
	   'Merchant' => MERCHANT_ID,
	   'ReportRequestIdList' =>  array('Id' => $Request_ID_List), //Pass the array of request Ids
	 );
	 $request_report_list = new MarketplaceWebService_Model_GetReportRequestListRequest($parameters_request_list);
	 return invokeGetReportRequestList($service, $request_report_list);
 }
                                                
 function invokeGetReportRequestList(MarketplaceWebService_Interface $service, $request) 
 {
	  try 
	  {
			$report_request_list = array();
			$response = $service->getReportRequestList($request);
			//Create Array to store report request
			$report_request = array( );
			//Get report request from amazon server, and store in array
			if ($response->isSetGetReportRequestListResult()) 
			{ 
				  $getReportRequestListResult = $response->getGetReportRequestListResult();
				  
				  if ($getReportRequestListResult->isSetNextToken()) 
						$getReportRequestListResult->getNextToken();
				  
				  if ($getReportRequestListResult->isSetHasNext()) 
						$getReportRequestListResult->getHasNext();
				  
				  $reportRequestInfoList = $getReportRequestListResult->getReportRequestInfoList();
				  
				  foreach ($reportRequestInfoList as $reportRequestInfo) 
				  {
						if ($reportRequestInfo->isSetReportRequestId()) 
							  $report_request['ReportRequestId'] = $reportRequestInfo->getReportRequestId();
						
						if ($reportRequestInfo->isSetScheduled()) 
							  $report_request['Scheduled'] = $reportRequestInfo->getScheduled();
						
						if ($reportRequestInfo->isSetSubmittedDate()) 
							  $report_request['SubmittedDate'] = getTime($reportRequestInfo->getSubmittedDate()) ;
						
						if ($reportRequestInfo->isSetReportProcessingStatus()) 
							  $report_request['ReportProcessingStatus'] = $reportRequestInfo->getReportProcessingStatus() ;
						
						if ($reportRequestInfo->isSetGeneratedReportId()) 
							  $report_request['GeneratedReportId'] = $reportRequestInfo->getGeneratedReportId() ;
						
						if ($reportRequestInfo->isSetStartedProcessingDate()) 
							  $report_request['StartedProcessingDate'] = getTime($reportRequestInfo->getStartedProcessingDate()) ;
						
						if ($reportRequestInfo->isSetCompletedDate())
							  $report_request['CompletedDate'] = getTime($reportRequestInfo->getCompletedDate()) ;
				   }
			}
			if ($response->isSetResponseMetadata()) { 
                    $responseMetadata = $response->getResponseMetadata();
                    if ($responseMetadata->isSetRequestId()) 
                    {
                        $report_request['RequestId'] = $responseMetadata->getRequestId();
                    }
            } 
			//return report request associative array
			$report_request_list[]=	$report_request;
			return $report_request_list[0]; 
		} 
	   
		catch (MarketplaceWebService_Exception $ex) {
			echo("Caught Exception: " . $ex->getMessage() . "\n");
			echo("Response Status Code: " . $ex->getStatusCode() . "\n");
			echo("Error Code: " . $ex->getErrorCode() . "\n");
			echo("Error Type: " . $ex->getErrorType() . "\n");
			echo("Request ID: " . $ex->getRequestId() . "\n");
			echo("XML: " . $ex->getXML() . "\n");
			echo("ResponseHeaderMetadata: " . $ex->getResponseHeaderMetadata() . "\n");
  		}
 }

 ?>
