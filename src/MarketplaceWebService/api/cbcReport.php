<?php
/**
 * Created by Julius Alvarado
 * User: Lab916
 * Date: 2/16/2018
 * Time: 12:42 PM
 */

include_once('.config.inc.php');

echo "<style>html, body {color: #2f2f2f;}</style>";

echo "<h2>Lab916 Amazon MWS API for <b>Flat File All Orders Report by Order Date</b></h2><hr>";

// United States:
$serviceUrl = "https://mws.amazonservices.com";

$config = array(
    'ServiceURL' => $serviceUrl,
    'ProxyHost' => null,
    'ProxyPort' => -1,
    'MaxErrorRetry' => 10,
);

$service = new MarketplaceWebService_Client(
    AWS_ACCESS_KEY_ID,
    AWS_SECRET_ACCESS_KEY,
    $config,
    APPLICATION_NAME,
    APPLICATION_VERSION
);

//-- Get the last 30 days:
$curDate = date("Y-m-d\Th:i:s");
$oneMonthAgo = strtotime("-1 Month");
$oneMonthAgoDate = date("Y-m-d\Th:i:s", $oneMonthAgo);

echo "<br><br> 1 month ago date = $oneMonthAgoDate <br><br>";

$paramsRequestReport = array(
    'Merchant' => MERCHANT_ID,
    'ReportType' => '_GET_FLAT_FILE_ALL_ORDERS_DATA_BY_ORDER_DATE_',
    'StartDate' => $oneMonthAgoDate,
    'EndDate' => $curDate,
    'ReportOptions' => 'ShowSalesChannel=true',
);

$paramsGetReportList = [
    'Merchant' => MERCHANT_ID,
    'AvailableToDate' => new DateTime('now', new DateTimeZone('UTC')),
    'AvailableFromDate' => new DateTime('-6 months', new DateTimeZone('UTC')),
    'Acknowledged' => false,
];

$requestRequestReportModel = new MarketplaceWebService_Model_RequestReportRequest($paramsRequestReport);
$requestGetReportListModel = new MarketplaceWebService_Model_GetReportListRequest($paramsGetReportList);

/** -- Action Invocations -- **/
invokeRequestReport($service, $requestRequestReportModel);
echo "<br><hr><br>";
$labGetReportList = invokeGetReportList($service, $requestGetReportListModel);
echo "<br><hr><br>";


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
 * GetReportList Action
 * returns a list of reports created in the last 90 days
 *
 * @param MarketplaceWebService_Interface $service - Instance of MarketplaceWebService_Interface
 * @param mixed $request - Instance of MarketplaceWebService_Model_
 *
 * @return array - An array holding all the response results
 */
function invokeGetReportList(MarketplaceWebService_Interface $service, $request) {
    try {
        $response = $service->getReportList($request);
        $rr = []; // response result

        echo("<br>===========================<br>");
        echo(' ~ "GetReportList" response ~<br>');
        echo("===========================<br>");

        if ($response->isSetGetReportListResult()) {
            $getReportListResult = $response->getGetReportListResult();

            if ($getReportListResult->isSetNextToken()) {
                $rr["nextToken"] = $getReportListResult->getNextToken();
            }

            if ($getReportListResult->isSetHasNext()) {
                $rr["hasNext"] = $getReportListResult->getHasNext(); //
            }

            $reportInfoList = $getReportListResult->getReportInfoList();
            $count = 0;
            foreach ($reportInfoList as $info) {
                $rrTemp = [];
                $count++; // go to next row
                echo "<h4 style='margin-bottom: 0.5em'>$count) GetReportList info:</h4>";

                if ($info->isSetReportId()) {
                    $rrTemp["reportId"] = $info->getReportId();
                    echo "<strong> reportId: </strong> " . $rrTemp["reportId"];
                }

                if ($info->isSetReportType()) {
                    $rrTemp["reportType"] = $info->getReportType();
                    //echo "<br> <strong> reportType: </strong>" . $rrTemp["reportType"];
                }

                if ($info->isSetReportRequestId()) {
                    $rrTemp["reportRequestId"] = $info->getReportRequestId();
                    //echo "<br> <strong>reportRequestInfo</strong>" . $rrTemp["reportRequestId"];
                }

                if ($info->isSetAvailableDate()) {
                    $rrTemp["availableDate"] = $info->getAvailableDate()->format(DATE_FORMAT);
                    //echo "<br> <strong> availableDate: </strong>" . $rrTemp["availableDate"];
                }

                if ($info->isSetAcknowledged()) {
                    $rrTemp["acknowledged"] = $info->getAcknowledged() ? 'true' : 'false';
                    //echo "<br> <strong>acknowledged: </strong>" . $rrTemp["acknowledged"];
                }

                if ($info->isSetAcknowledgedDate()) {
                    $rrTemp["acknowledgedDate"] = $info->getAcknowledgedDate() . format(DATE_FORMAT);
                    echo "<br> <strong> acknowledgedDate: </strong>" . $rr["acknowledgedDate"];
                }

                $rr["row" . $count] = $rrTemp;
            }
        }

        if ($response->isSetResponseMetadata()) {
            $rr["responseMetaData"] = $response->getResponseMetadata();
            if ($rr["responseMetaData"]->isSetRequestId())
                $rr["requestId"] = $rr["responseMetaData"]->getRequestId();
        }

        $rr["responseHeaderMetaData"] = $response->getResponseHeaderMetadata();
        return $rr;
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


$reportId = $labGetReportList["row1"]["reportId"];
$paramsGetReport = [
    'Merchant' => MERCHANT_ID,
    'Report' => @fopen('php://memory', 'rw+'),
    'ReportId' => $reportId,
];
$requestGetReport = new MarketplaceWebService_Model_GetReportRequest($paramsGetReport);

echo "<h2>ID Of this report: $reportId</h2>";

invokeGetReport($service, $requestGetReport);

/**
 * GetReport Action
 * Returns the contents of the report and the Content-MD5 header for
 * the returned report body
 *
 * @param MarketplaceWebService_Interface $service - Instance of MarketplaceWebService_Interface
 * @param mixed $request - An array of parameters
 */
function invokeGetReport(MarketplaceWebService_Interface $service, $request) {
    try {
        echo("<br>====================<br>");
        echo(' ~ "GetReport" response ~<br>');
        echo("====================<br>");

        $response = $service->getReport($request);
        $rr = [];

        if ($response->isSetGetReportResult()) {
            $getReportResult = $response->getGetReportResult();
            echo "<h3 style='margin-bottom: 0.5em;'>GetReport data</h3>";
            if ($getReportResult->isSetContentMd5()) {
                echo("<h4>ContentMD5:</h4>");
                $rr["contentMd5"] = $getReportResult->getContentMd5();
                echo "Content-MD5: " . $rr["contentMd5"];
            }
        }

        if ($response->isSetResponseMetadata()) {
            echo "<h3>ResponseMetadata</h3>";
            $responseMetadata = $response->getResponseMetadata();
            if ($responseMetadata->isSetRequestId()) {
                $rr["requestId"] = $responseMetadata->getRequestId();
                echo "requestId: " . $rr["requestId"];
            }
        }

        echo "<h2>Report Contents</h2>";
        $rr["reportStream"] = stream_get_contents($request->getReport());
        echo $rr["reportStream"];
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

