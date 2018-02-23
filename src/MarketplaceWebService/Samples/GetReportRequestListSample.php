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
 * Get Report Request List Sample
 */

include_once('.config.inc.php');

echo "<h2>GetReportRequestList API operation:</h2>";

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
 * sample for Get Report List Action
 ***********************************************************************/

// @TODO: set request. Action can be passed as MarketplaceWebService_Model_GetReportListRequest object or array of parameters
$parameters = array(
    'Merchant' => MERCHANT_ID,
    // 'MWSAuthToken' => '<MWS Auth Token>', // Optional
);

$request = new MarketplaceWebService_Model_GetReportRequestListRequest($parameters);

invokeGetReportRequestList($service, $request);

/**
 * Get Report List Action Sample
 * returns a list of reports; by default the most recent ten reports,
 * regardless of their acknowledgement status
 *
 * @param MarketplaceWebService_Interface $service instance of MarketplaceWebService_Interface
 * @param mixed $request MarketplaceWebService_Model_GetReportList or array of parameters
 */
function invokeGetReportRequestList(MarketplaceWebService_Interface $service, $request) {
    try {
        $response = $service->getReportRequestList($request);

        echo("Service Response<br>");
        echo("=============================================================================<br>");

        echo("        GetReportRequestListResponse<br>");
        if ($response->isSetGetReportRequestListResult()) {
            echo("            GetReportRequestListResult<br>");

            $getReportRequestListResult = $response->getGetReportRequestListResult();

            if ($getReportRequestListResult->isSetNextToken()) {
                echo("                NextToken<br>");
                echo("                    " . $getReportRequestListResult->getNextToken() . "<br>");
            }
            if ($getReportRequestListResult->isSetHasNext()) {
                echo("                HasNext<br>");
                echo("                    " . $getReportRequestListResult->getHasNext() . "<br>");
            }

            $reportRequestInfoList = $getReportRequestListResult->getReportRequestInfoList();

            foreach ($reportRequestInfoList as $reportRequestInfo) {
                echo("                ReportRequestInfo<br>");
                if ($reportRequestInfo->isSetReportRequestId()) {
                    echo("                    ReportRequestId<br>");
                    echo("                        " . $reportRequestInfo->getReportRequestId() . "<br>");
                }
                if ($reportRequestInfo->isSetReportType()) {
                    echo("                    ReportType<br>");
                    echo("                        " . $reportRequestInfo->getReportType() . "<br>");
                }
                if ($reportRequestInfo->isSetStartDate()) {
                    echo("                    StartDate<br>");
                    echo("                        " . $reportRequestInfo->getStartDate()->format(DATE_FORMAT) . "<br>");
                }
                if ($reportRequestInfo->isSetEndDate()) {
                    echo("                    EndDate<br>");
                    echo("                        " . $reportRequestInfo->getEndDate()->format(DATE_FORMAT) . "<br>");
                }
                // add start
                if ($reportRequestInfo->isSetScheduled()) {
                    echo("                    Scheduled<br>");
                    echo("                        " . $reportRequestInfo->getScheduled() . "<br>");
                }
                // add end
                if ($reportRequestInfo->isSetSubmittedDate()) {
                    echo("                    SubmittedDate<br>");
                    echo("                        " . $reportRequestInfo->getSubmittedDate()->format(DATE_FORMAT) . "<br>");
                }
                if ($reportRequestInfo->isSetReportProcessingStatus()) {
                    echo("                    ReportProcessingStatus<br>");
                    echo("                        " . $reportRequestInfo->getReportProcessingStatus() . "<br>");
                }
                // add start
                if ($reportRequestInfo->isSetGeneratedReportId()) {
                    echo("                    GeneratedReportId<br>");
                    echo("                        " . $reportRequestInfo->getGeneratedReportId() . "<br>");
                }
                if ($reportRequestInfo->isSetStartedProcessingDate()) {
                    echo("                    StartedProcessingDate<br>");
                    echo("                        " . $reportRequestInfo->getStartedProcessingDate()->format(DATE_FORMAT) . "<br>");
                }
                if ($reportRequestInfo->isSetCompletedDate()) {
                    echo("                    CompletedDate<br>");
                    echo("                        " . $reportRequestInfo->getCompletedDate()->format(DATE_FORMAT) . "<br>");
                }// add end
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

        echo("            ResponseHeaderMetadata: " . $response->getResponseHeaderMetadata() . "<br>");
    } catch (MarketplaceWebService_Exception $ex) {
        echo("Caught Exception: " . $ex->getMessage() . "<br>");
        echo("Response Status Code: " . $ex->getStatusCode() . "<br>");
        echo("Error Code: " . $ex->getErrorCode() . "<br>");
        echo("Error Type: " . $ex->getErrorType() . "<br>");
        echo("Request ID: " . $ex->getRequestId() . "<br>");
        echo("XML: " . $ex->getXML() . "<br>");
        echo("ResponseHeaderMetadata: " . $ex->getResponseHeaderMetadata() . "<br>");
    }
}

