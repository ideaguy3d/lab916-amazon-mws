<?php

include_once('.config.inc.php');

echo "<h2>RequestReport operation:</h2><hr>";

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
    'MaxErrorRetry' => 10,
);

/************************************************************************
 * Instantiate Implementation of MarketplaceWebService
 *
 * AWS_ACCESS_KEY_ID and AWS_SECRET_ACCESS_KEY constants
 * are defined in the .config.inc.php located in the same
 * directory as this sample
 ***********************************************************************/
$serviceRequestReport = new MarketplaceWebService_Client(
    AWS_ACCESS_KEY_ID,
    AWS_SECRET_ACCESS_KEY,
    $config,
    APPLICATION_NAME,
    APPLICATION_VERSION
);
$serviceGetReportRequestList = new MarketPlaceWebService_Client(
    AWS_ACCESS_KEY_ID,
    AWS_SECRET_ACCESS_KEY,
    $config,
    APPLICATION_NAME,
    APPLICATION_VERSION
);

// Constructing the MarketplaceId array which will be passed in as the the MarketplaceIdList
// parameter to the RequestReportRequest object. #Optional
$marketplaceIdArray = array("Id" => array('', ''));

$paramsRequestReport = array(
    'Merchant' => MERCHANT_ID,
    // 'MarketplaceIdList' => $marketplaceIdArray,
    'ReportType' => '_GET_FLAT_FILE_ALL_ORDERS_DATA_BY_ORDER_DATE_',
    'ReportOptions' => 'ShowSalesChannel=true',
    // 'MWSAuthToken' => '', // Optional
);
$paramsGetRequestReportList = array(
    'Merchant' => MERCHANT_ID,
);

$requestRequestReportModel = new MarketplaceWebService_Model_RequestReportRequest($paramsRequestReport);
$requestGetReportRequestListModel = new MarketplaceWebService_Model_GetReportRequestListRequest($paramsGetRequestReportList);

//-- Using ReportOptions:
$requestRequestReportModel->setReportOptions('ShowSalesChannel=true');

/** -- Action Invocations -- **/
invokeRequestReport($serviceRequestReport, $requestRequestReportModel);
echo "<br><hr><br>";
invokeGetReportRequestList($serviceGetReportRequestList, $requestGetReportRequestListModel);

/**
 * RequestReport Action
 * Creates a report request, and sends request to Amazon MWS
 * regardless of their acknowledgement status
 *
 * @param MarketplaceWebService_Interface $service instance of MarketplaceWebService_Interface
 * @param mixed $request MarketplaceWebService_Model_GetReportList or array of parameters
 */
function invokeRequestReport(MarketplaceWebService_Interface $service, $request) {
    try {
        $response = $service->requestReport($request);

        echo("<br>=====================<br>\n");
        echo(' ~ "RequestReport" response ~<br>');
        echo("=====================<br>\n");

        echo("<br>        RequestReportResponse<br>\n");

        if ($response->isSetRequestReportResult()) {
            echo("            RequestReportResult\n");
            $requestReportResult = $response->getRequestReportResult();

            if ($requestReportResult->isSetReportRequestInfo()) {
                $reportRequestInfo = $requestReportResult->getReportRequestInfo();
                echo("                ReportRequestInfo<br><br>\n");
                if ($reportRequestInfo->isSetReportRequestId()) {
                    echo("                    ReportRequestId<br>\n");
                    echo("                        " . $reportRequestInfo->getReportRequestId() . "<br><br>\n");
                }
                if ($reportRequestInfo->isSetReportType()) {
                    echo("                    ReportType<br>\n");
                    echo("                        " . $reportRequestInfo->getReportType() . "<br><br>\n");
                }
                if ($reportRequestInfo->isSetStartDate()) {
                    echo("                    StartDate<br>\n");
                    echo("                        " . $reportRequestInfo->getStartDate()->format(DATE_FORMAT) . "<br><br>\n");
                }
                if ($reportRequestInfo->isSetEndDate()) {
                    echo("                    EndDate<br>\n");
                    echo("                        " . $reportRequestInfo->getEndDate()->format(DATE_FORMAT) . "<br><br>\n");
                }
                if ($reportRequestInfo->isSetSubmittedDate()) {
                    echo("                    SubmittedDate<br>\n");
                    echo("                        " . $reportRequestInfo->getSubmittedDate()->format(DATE_FORMAT) . "<br><br>\n");
                }
                if ($reportRequestInfo->isSetReportProcessingStatus()) {
                    echo("                    ReportProcessingStatus<br>\n");
                    echo("                        " . $reportRequestInfo->getReportProcessingStatus() . "<br><br>\n");
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

/**
 * GetReportRequestList Action
 * Returns a list of report requests that can be used to get
 * the ReportRequestId
 *
 * @param MarketplaceWebService_Interface $service - Instance of MarketplaceWebService_Interface
 * @param mixed $request instance - Instance of MarketplaceWebService_Model_GetReportRequestListRequest or array of params
 */
function invokeGetReportRequestList(MarketplaceWebService_Interface $service, $request) {
    try {
        $response = $service->getReportRequestList($request);
        $rr = []; // response results

        echo("<br>===========================<br>\n");
        echo(' ~ "GetReportRequestList" response ~<br>');
        echo("===========================<br>\n");

        if ($response->isSetGetReportRequestListResult()) {
            $getReportRequestListResult = $response->getGetReportRequestListResult();
            if ($getReportRequestListResult->isSetNextToken()) {
                $rr["nextToken"] = $getReportRequestListResult->getNextToken();
            }
            if ($getReportRequestListResult->isSetHasNext()) {
                $rr["hasNext"] = $getReportRequestListResult->getHasNext();
            }
            $reportRequestInfoList = $getReportRequestListResult->getReportRequestInfoList();

            $count = 0;
            foreach ($reportRequestInfoList as $reportRequestInfo) {
                $count++;
                echo "<h4 style='margin-bottom: -0.5em;'>$count) reportRequestInfo =</h4>";

                if ($reportRequestInfo->isSetReportRequestId()) {
                    $rr["reportRequestId"] = $reportRequestInfo->getReportRequestId();
                    echo "<br> <strong> reportRequestId:</strong> " . $rr["reportRequestId"];
                }
                if ($reportRequestInfo->isSetReportType()) {
                    $rr["reportType"] = $reportRequestInfo->getReportType();
                    echo "<br> <strong> reportType:</strong> " . $rr["reportType"];
                }
                if ($reportRequestInfo->isSetStartDate()) {
                    $rr["startDate"] = $reportRequestInfo->getStartDate()->format(DATE_FORMAT);
                    echo "<br> <strong> startDate:</strong> " . $rr["startDate"];
                }
                if ($reportRequestInfo->isSetEndDate()) {
                    $rr["endDate"] = $reportRequestInfo->getEndDate()->format(DATE_FORMAT);
                    echo "<br> <strong> endDate:</strong> " . $rr["endDate"];
                }
                if ($reportRequestInfo->isSetScheduled()) {
                    // convert boolean to a string
                    $rr["scheduled"] = $reportRequestInfo->getScheduled() ? 'true' : 'false';
                    echo "<br> <strong> scheduled:</strong> " . $rr["scheduled"];
                }
                if ($reportRequestInfo->isSetSubmittedDate()) {
                    $rr["submittedDate"] = $reportRequestInfo->getSubmittedDate()->format(DATE_FORMAT);
                    echo "<br> <strong> submittedDate:</strong> " . $rr["submittedDate"];
                }
                if ($reportRequestInfo->isSetReportProcessingStatus()) {
                    $rr["reportProcessingStatus"] = $reportRequestInfo->getReportProcessingStatus();
                    echo "<br> <strong>reportProcessingStatus</strong>: " . $rr["reportProcessingStatus"];
                }
                if ($reportRequestInfo->isSetGeneratedReportId()) {
                    $rr["generatedReportId"] = $reportRequestInfo->getGeneratedReportId();
                    echo "<br> <strong> generatedReportId:</strong> " . $rr["generatedReportId"];
                }
                if ($reportRequestInfo->isSetStartedProcessingDate()) {
                    $rr["startedProcessingDate"] = $reportRequestInfo->getStartedProcessingDate()->format(DATE_FORMAT);
                    echo "<br> <strong> startedProcessingDate:</strong> " . $rr["startedProcessingDate"];
                }
                if($reportRequestInfo->isSetCompletedDate()) {
                    $rr["completedDate"] = $reportRequestInfo->getCompletedDate()->format(DATE_FORMAT);
                    echo "<br> <strong> completedDate:</strong> " . $rr["completedDate"];
                }
            }
        }
    }
    catch (MarketplaceWebService_Exception $ex) {

    }
}

function invokeGetReprtList(MarketplaceWebService_Interface $service, $requestReportListModel) {
    // build something
}

// end of PHP file