<?php
require("tdltoarray.php");
include_once ('.config.inc.php'); 
require_once("ArrayToSQL.php");


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
 

 $reportId = '6753420395017356';
 
 $parameters = array (
   'Merchant' => MERCHANT_ID,
   'Report' => @fopen('php://memory', 'rw+'),
   'ReportId' => $reportId,
//   'MWSAuthToken' => '<MWS Auth Token>', // Optional
 );
 $request = new MarketplaceWebService_Model_GetReportRequest($parameters);

invokeGetReport($service, $request);

  function invokeGetReport(MarketplaceWebService_Interface $service, $request) 
  {
	  global $reportId;
      try {
              $response = $service->getReport($request);
              $filename = "$reportId".".txt";
              // Write report to file
			  $file = fopen($filename,"w");
			  fwrite($file,(stream_get_contents($request->getReport()) . "\n"));
			  fclose($file);
			  
			  $report_array = tdl_to_array($filename);
			  
			  echo '<pre>';
			  //print_r($report_array);
			  echo '</pre>';
			  
			  //request_to_sql($report_array , 'mws_order_report');
			  array_to_sql($report_array , 'mws_order_report',true);
				

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
                                                                                
