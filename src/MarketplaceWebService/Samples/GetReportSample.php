<?php
/**
 *  PHP Version 5
 *
 * @category    Amazon
 * @package     MarketplaceWebService
 * @copyright   Copyright 2009 Amazon Technologies, Inc.
 * @link        http://aws.amazon.com
 * @license     http://aws.amazon.com/apache2.0  Apache License, Version 2.0
 * @version     2009-01-01
 */
/*******************************************************************************
 *  Marketplace Web Service PHP5 Library
 *  Generated: Thu May 07 13:07:36 PDT 2009
 *
 */

/**
 * Get Report  Sample
 */

include_once('.config.inc.php');

echo "<h2>GetReport operation</h2><hr>";

/************************************************************************
 * Uncomment to configure the client instance. Configuration settings
 * are:
 *
 * - MWS endpoint URL
 * - Proxy host and port.
 * - MaxErrorRetry.
 ***********************************************************************/
// IMPORTANT: Uncomment the appropriate line for the country you wish to
// sell in:
// United States:
$serviceUrl = "https://mws.amazonservices.com";
// United Kingdom
//$serviceUrl = "https://mws.amazonservices.co.uk";
// Germany
//$serviceUrl = "https://mws.amazonservices.de";
// France
//$serviceUrl = "https://mws.amazonservices.fr";
// Italy
//$serviceUrl = "https://mws.amazonservices.it";
// Japan
//$serviceUrl = "https://mws.amazonservices.jp";
// China
//$serviceUrl = "https://mws.amazonservices.com.cn";
// Canada
//$serviceUrl = "https://mws.amazonservices.ca";
// India
//$serviceUrl = "https://mws.amazonservices.in";

$config = array(
    'ServiceURL' => $serviceUrl,
    'ProxyHost' => null,
    'ProxyPort' => -1,
    'MaxErrorRetry' => 3,
);

/************************************************************************
 * Instantiate Implementation of MarketplaceWebService
 *
 * AWS_ACCESS_KEY_ID and AWS_SECRET_ACCESS_KEY constants
 * are defined in the .config.inc.php located in the same
 * directory as this sample
 ***********************************************************************/
$service = new MarketplaceWebService_Client(
    AWS_ACCESS_KEY_ID,
    AWS_SECRET_ACCESS_KEY,
    $config,
    APPLICATION_NAME,
    APPLICATION_VERSION
);

/************************************************************************
 * Setup request parameters and uncomment invoke to try out
 * sample for Get Report Action
 ***********************************************************************/
// @TODO: set request. Action can be passed as MarketplaceWebService_Model_GetReportRequest object or array of parameters
$reportId = '8272391414017570';

$parameters = array(
    'Merchant' => MERCHANT_ID,
    'Report' => @fopen('php://memory', 'rw+'),
    'ReportId' => $reportId,
    //'MWSAuthToken' => '', // Optional
);

$request = new MarketplaceWebService_Model_GetReportRequest($parameters);

invokeGetReport($service, $request);

/**
 * Get Report Action Sample
 * The GetReport operation returns the contents of a report. Reports can potentially be
 * very large (>100MB) which is why we only return one report at a time, and in a
 * streaming fashion.
 *
 * @param MarketplaceWebService_Interface $service instance of MarketplaceWebService_Interface
 * @param mixed $request MarketplaceWebService_Model_GetReport or array of parameters
 */
function invokeGetReport(MarketplaceWebService_Interface $service, $request) {
    try {
        $response = $service->getReport($request);

        echo("Service Response<br>");
        echo("=============================================================================<br>");

        echo("        GetReportResponse<br>");

        if ($response->isSetGetReportResult()) {
            $getReportResult = $response->getGetReportResult();
            echo("            GetReport");
            if ($getReportResult->isSetContentMd5()) {
                echo("                ContentMd5");
                echo("                " . $getReportResult->getContentMd5() . "<br>");
            }
        }

        if ($response->isSetResponseMetadata()) {
            echo("            ResponseMetadata<br>");
            $responseMetadata = $response->getResponseMetadata();
            if ($responseMetadata->isSetRequestId()) {
                echo("                RequestId<br>");
                echo("                    " . $responseMetadata->getRequestId() . "<br>");
            }
        }

        echo("        Report Contents<br>");
        echo(stream_get_contents($request->getReport()) . "<br>");

        echo("            ResponseHeaderMetadata: " . $response->getResponseHeaderMetadata() . "<br>");
    }
    catch (MarketplaceWebService_Exception $ex) {
        echo("Caught Exception: " . $ex->getMessage() . "<br>");
        echo("Response Status Code: " . $ex->getStatusCode() . "<br>");
        echo("Error Code: " . $ex->getErrorCode() . "<br>");
        echo("Error Type: " . $ex->getErrorType() . "<br>");
        echo("Request ID: " . $ex->getRequestId() . "<br>");
        echo("XML: " . $ex->getXML() . "<br>");
        echo("ResponseHeaderMetadata: " . $ex->getResponseHeaderMetadata() . "<br>");
    }
}
                                                                                
