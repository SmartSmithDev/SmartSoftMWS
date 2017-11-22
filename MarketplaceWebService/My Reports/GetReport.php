<?php
/**
* Get Report and put into sql
* @param boolean $multiple to define true if the query data is array of associative array
* Example : getreport('6753420395017356');
*/
function getReport($reportId)
{
	require_once("TdlToArray.php");
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
 
	$parameters = array (
		'Merchant' => MERCHANT_ID,
	    'Report' => @fopen('php://memory', 'rw+'),
	    'ReportId' => $reportId,
	);
 	$request = new MarketplaceWebService_Model_GetReportRequest($parameters);
	$report_array = invokeGetReport($service, $request, $reportId); //get report array
	return $report_array;
}
function invokeGetReport(MarketplaceWebService_Interface $service, $request, $reportId) 
{
	 try {
			$response = $service->getReport($request); //Get The Report from MWS
			$filename = "./reports/"."$reportId".".txt";
			// Write report to file
			$file = fopen($filename,"w");
			fwrite($file,(stream_get_contents($request->getReport()) . "\n"));
			fclose($file);
			//Convert Tab Delimited File to Associative array
			$report_array = tdl_to_array($filename , true , "\t"); 
			return $report_array;
			  
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
                                                                                
?>