<?php
/**
* Request the report from MWS server
* The request will be created on MWS Server, and will be stored on local database
*/
include_once ('.config.inc.php'); 
require_once ('ArrayToSQL.php');

$ReportType = $_POST["ReportType"]; 	//'_GET_AMAZON_FULFILLED_SHIPMENTS_DATA_';
//$StartDate = $_POST["Syear"]."-".$_POST["Smonth"]."-".$_POST["Sdate"];	//'2017-06-01';
//$EndDate = $_POST["Eyear"]."-".$_POST["Emonth"]."-".$_POST["Edate"];	//'2017-07-01';
$StartDate = $_POST["StartDate"];	//'2017-06-01';
$EndDate = $_POST["EndDate"];	//'2017-07-01';

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
 
$marketplaceIdArray = array("Id" => array('A21TJRUUN4KGV'));

 $parameters = array (
   'Merchant' => MERCHANT_ID,
   'MarketplaceIdList' => $marketplaceIdArray,
   'ReportType' => $ReportType,	//'ReportType' => '_GET_AMAZON_FULFILLED_SHIPMENTS_DATA_',
   'StartDate' => $StartDate, 	//'StartDate' => '2017-06-01',
   'EndDate' => $EndDate, 	//'EndDate' => '2017-07-01',
   'ReportOptions' => 'ShowSalesChannel=true',
 );
 
 $request = new MarketplaceWebService_Model_RequestReportRequest($parameters);
 
 
 invokeRequestReport($service, $request);
 
  function invokeRequestReport(MarketplaceWebService_Interface $service, $request) 
  {
      try {
              $response = $service->requestReport($request);
              
                if ($response->isSetRequestReportResult()) { 
                    $requestReportResult = $response->getRequestReportResult();
                    
                    if ($requestReportResult->isSetReportRequestInfo()) {
                        
                        $reportRequestInfo = $requestReportResult->getReportRequestInfo();
                        $responseMetadata = $response->getResponseMetadata();
						  
						  $report_request = array(
						  "RequestId"=>$responseMetadata->getRequestId(), 
						  "SubmittedDate"=> in_time($reportRequestInfo->getSubmittedDate()), 
						  "StartDate"=> in_time($reportRequestInfo->getStartDate()), 
						  "EndDate"=> in_time($reportRequestInfo->getEndDate()), 
						  "ReportType"=> $reportRequestInfo->getReportType(), 
						  "ReportProcessingStatus"=> $reportRequestInfo->getReportProcessingStatus(), 
						  "ReportRequestId"=> $reportRequestInfo->getReportRequestId(), 
						  "Scheduled"=> $reportRequestInfo->getScheduled()
						  );
                      }
                } 
				//Store the request in SQL
				array_to_sql($report_request , 'mws_report_request');
				
     } catch (MarketplaceWebService_Exception $ex) {
         echo("Caught Exception: " . $ex->getMessage() . "\n");
         echo("Response Status Code: " . $ex->getStatusCode() . "\n");
         echo("Error Code: " . $ex->getErrorCode() . "\n");
         echo("Error Type: " . $ex->getErrorType() . "\n");
         echo("Request ID: " . $ex->getRequestId() . "\n");
         echo("XML: " . $ex->getXML() . "\n");
         echo("ResponseHeaderMetadata: " . $ex->getResponseHeaderMetadata() . "\n");
     }
 }
 //function to format time according to India
 function in_time($date)
 {
	 $date->setTimezone(new DateTimeZone('Asia/Kolkata'));
		return $date->format('Y-m-d H:i:s') . "\n";
 }
 
?>

                                                                                
