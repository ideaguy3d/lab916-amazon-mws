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
 * Report  Sample
 */

include_once('.config.inc.php');

/************************************************************************
 * Uncomment to configure the client instance. Configuration settings
 * are:
 *
 * - MWS endpoint URL
 * - Proxy host and port.
 * - MaxErrorRetry.
 ***********************************************************************/
// IMPORTANT: Uncomment the approiate line for the country you wish to
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
    'MaxErrorRetry' => 10,
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
    APPLICATION_VERSION);

/************************************************************************
 * Uncomment to try out Mock Service that simulates MarketplaceWebService
 * responses without calling MarketplaceWebService service.
 *
 * Responses are loaded from local XML files. You can tweak XML files to
 * experiment with various outputs during development
 *
 * XML files available under MarketplaceWebService/Mock tree
 *
 ***********************************************************************/
// $service = new MarketplaceWebService_Mock();

/************************************************************************
 * Setup request parameters and uncomment invoke to try out
 * sample for Report Action
 ***********************************************************************/

// Constructing the MarketplaceId array which will be passed in as the the MarketplaceIdList
// parameter to the RequestReportRequest object.
$marketplaceIdArray = array("Id" => array('', ''));

// @TODO: set request. Action can be passed as MarketplaceWebService_Model_ReportRequest object or array of parameters
$parameters = array(
    'Merchant' => MERCHANT_ID,
    // 'MarketplaceIdList' => $marketplaceIdArray,
    'ReportType' => '_GET_MERCHANT_LISTINGS_DATA_',
    'ReportOptions' => 'ShowSalesChannel=true',
    'MWSAuthToken' => '', // Optional
);

$requestReportListParameters = array(
    'Merchant' => MERCHANT_ID,
);

$request = new MarketplaceWebService_Model_RequestReportRequest($parameters);
$reportRequestListModel = new MarketplaceWebService_Model_GetReportRequestListRequest($requestReportListParameters);

//-- Using ReportOptions:
$request->setReportOptions('ShowSalesChannel=true');

//invokeRequestReport($service, $request);

/**
 * Get Report List Action Sample:
 * returns a list of reports; by default the most recent ten reports,
 * regardless of their acknowledgement status
 *
 * @param MarketplaceWebService_Interface $service instance of MarketplaceWebService_Interface
 * @param mixed $request MarketplaceWebService_Model_GetReportList or array of parameters
 */
function invokeRequestReport(MarketplaceWebService_Interface $service, $request) {
    try {
        $response = $service->requestReport($request);

        echo("<br>Service Response<br>\n");
        echo("=============================================================================<br>\n");

        echo("<br>        RequestReportResponse<br>\n");

        if ($response->isSetRequestReportResult()) {
            echo("            RequestReportResult\n");
            $requestReportResult = $response->getRequestReportResult();

            if ($requestReportResult->isSetReportRequestInfo()) {
                $reportRequestInfo = $requestReportResult->getReportRequestInfo();
                echo("                ReportRequestInfo<br>\n");
                if ($reportRequestInfo->isSetReportRequestId()) {
                    echo("                    ReportRequestId<br>\n");
                    echo("                        " . $reportRequestInfo->getReportRequestId() . "<br>\n");
                }
                if ($reportRequestInfo->isSetReportType()) {
                    echo("                    ReportType<br>\n");
                    echo("                        " . $reportRequestInfo->getReportType() . "<br>\n");
                }
                if ($reportRequestInfo->isSetStartDate()) {
                    echo("                    StartDate<br>\n");
                    echo("                        " . $reportRequestInfo->getStartDate()->format(DATE_FORMAT) . "<br>\n");
                }
                if ($reportRequestInfo->isSetEndDate()) {
                    echo("                    EndDate<br>\n");
                    echo("                        " . $reportRequestInfo->getEndDate()->format(DATE_FORMAT) . "<br>\n");
                }
                if ($reportRequestInfo->isSetSubmittedDate()) {
                    echo("                    SubmittedDate<br>\n");
                    echo("                        " . $reportRequestInfo->getSubmittedDate()->format(DATE_FORMAT) . "<br>\n");
                }
                if ($reportRequestInfo->isSetReportProcessingStatus()) {
                    echo("                    ReportProcessingStatus<br>\n");
                    echo("                        " . $reportRequestInfo->getReportProcessingStatus() . "<br>\n");
                }
            }
        }

        if ($response->isSetResponseMetadata()) {
            echo("<br>            ResponseMetadata<br>\n");
            $responseMetadata = $response->getResponseMetadata();
            if ($responseMetadata->isSetRequestId()) {
                echo("                RequestId<br>\n");
                echo("                    " . $responseMetadata->getRequestId() . "<br>\n");
            }
        }

        echo("            ResponseHeaderMetadata: " . $response->getResponseHeaderMetadata() . "<br>\n");
    }
    catch (MarketplaceWebService_Exception $ex) {
        echo("Caught Exception: " . $ex->getMessage() . "<br>\n");
        echo("Response Status Code: " . $ex->getStatusCode() . "<br>\n");
        echo("Error Code: " . $ex->getErrorCode() . "<br>\n");
        echo("Error Type: " . $ex->getErrorType() . "<br>\n");
        echo("Request ID: " . $ex->getRequestId() . "<br>\n");
        echo("XML: " . $ex->getXML() . "<br>\n");
        echo("ResponseHeaderMetadata: " . $ex->getResponseHeaderMetadata() . "\n");
    }
}

function invokeGetReportRequestList(MarketplaceWebService_Interface $service, $requestReportRequestListModel) {
    try {
        $response = $service->getReportRequestList($requestReportRequestListModel);

        echo("Service Response<br>");
        echo("=============================================================================<br>");

        echo("        GetReportRequestListResponse<br>");
        if($response->isSetGetReportRequestListResult()) {
            echo("            GetReportRequestListResult<br>");
            $getReportRequestListResult = $response->getGetReportRequestListResult();

            if($getReportRequestListResult->isSetNextToken()) {
                echo("                NextToken<br>");
                echo " ~ " . $getReportRequestListResult->getNextToken() . "<br>";
            }

            if($getReportRequestListResult->isSetHasNext()) {
                echo("                HasNext<br>");
                echo " ~ " . $getReportRequestListResult.getHasNext() . "<br>";
            }

            $reportRequestInfoList = $getReportRequestListResult->getReportRequestInfoList();

            foreach($reportRequestInfoList as $reportRequestInfo) {
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

        if($response->isSetResponseMetadata()) {

        }
    }
    catch (MarketplaceWebService_Exception $ex) {

    }
}

// end of PHP file