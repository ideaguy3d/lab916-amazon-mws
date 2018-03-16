<?php
/**
 * Created by Julius Alvarado.
 * User: Lab916
 * Date: 3/8/2018
 * Time: 4:14 PM
 */


include_once("fba-config.php");

// -- Fields --
$mwsAuthToken = isset($_GET["mws-auth-token"]) ? $_GET["mws-auth-token"] : null;

$serviceUrl = "https://mws.amazonservices.com"; // United States
$config = array(
    'ServiceURL' => $serviceUrl,
    'ProxyHost' => null,
    'ProxyPort' => -1,
    'MaxErrorRetry' => 10,
    'MWSAuthToken' => $mwsAuthToken
);

if ($mwsAuthToken === null) {
    echo " ( LAB 916 - mws auth token was not in the query string - ";
    echo " - ERROR - The FBA report will not get generated. ) ";
}

echo "<h2>Lab916 AmazonMWS client</h2><hr>";

$service = new MarketplaceWebService_Client(
    AWS_ACCESS_KEY_ID,
    AWS_SECRET_ACCESS_KEY,
    $config,
    APPLICATION_NAME,
    APPLICATION_VERSION
);

//-- Get the last 30 days:
$curDate = date('Y-m-d\Th:i:s');
$oneMonthAgo = strtotime("-1 Month");
$oneMonthAgoDate = date("Y-m-d\Th:i:s", $oneMonthAgo);

$paramsRequestReport = [
    'Merchant' => MERCHANT_ID,
    'ReportType' => '_GET_FLAT_FILE_ALL_ORDERS_DATA_BY_ORDER_DATE_',
    'StartDate' => $oneMonthAgoDate,
    'EndDate' => $curDate,
    'ReportOptions' => 'ShowSalesChannel=true',
    'MWSAuthToken' => $mwsAuthToken,
];
$paramsGetReportList = [
    'Merchant' => MERCHANT_ID,
    'AvailableToDate' => new DateTime('now', new DateTimeZone('UTC')),
    'AvailableFromDate' => new DateTime('-6 months', new DateTimeZone('UTC')),
    'MWSAuthToken' => $mwsAuthToken,
];

$requestRequestReportModel = new MarketplaceWebService_Model_RequestReportRequest($paramsRequestReport);
$requestGetReportListModel = new MarketplaceWebService_Model_GetReportListRequest($paramsGetReportList);


/**------------------------
 * -- Action Invocations --
 * ------------------------**/

$labRequestReport = invokeRequestReport($service, $requestRequestReportModel);
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
 *
 * @return int - -1 if fail, 1 is success
 */
function invokeRequestReport(MarketplaceWebService_Interface $service, $request) {
    try {
        $response = $service->requestReport($request);

        echo("<br>===========================<br>");
        echo('<br> ~ "RequestReport" response ~ <br>');
        echo("<br>===========================<br>");

        if ($response->isSetRequestReportResult()) {
            echo "Request Report result:<br>";
            $requestReportResult = $response->getRequestReportResult();

            if ($requestReportResult->isSetReportRequestInfo()) {
                $reportRequestInfo = $requestReportResult->getReportRequestInfo();
                echo "<br>Report Request Info:";

                if ($reportRequestInfo->isSetReportRequestId()) {
                    echo "<br>Report Request ID: " . $reportRequestInfo->getReportRequestId();
                }

                if ($reportRequestInfo->isSetStartDate()) {
                    echo "<br>Start Date: " . $reportRequestInfo->getStartDate()->format(DATE_FORMAT);
                }

                if ($reportRequestInfo->isSetEndDate()) {
                    echo "<br>End Date: " . $reportRequestInfo->getEndDate()->format(DATE_FORMAT);
                }

                if ($reportRequestInfo->isSetSubmittedDate()) {
                    echo "<br> Submitted Date: " . $reportRequestInfo->getSubmittedDate()->format(DATE_FORMAT);
                }

                if ($reportRequestInfo->isSetReportProcessingStatus()) {
                    echo "<br> Report Processing Status: " . $reportRequestInfo->getReportProcessingStatus();
                }
            }
        }

        if ($response->isSetResponseMetadata()) {
            echo "<br> ResponseMetadata:";
            $responseMetaData = $response->getResponseMetadata();
            if ($responseMetaData->isSetRequestId()) {
                echo "<br>RequestId: " . $responseMetaData->getRequestId();
            }
        }

        echo "<br>Response Meta Data Header: " . $response->getResponseHeaderMetadata() . "<br>";

        // We hit the end of the try block so everything succeeded.
        return 1;
    }
    catch (MarketplaceWebService_Exception $ex) {
        echo("<h1>Caught Exception:</h1> " . $ex->getMessage() . "<br>\n");
        echo("Response Status Code: " . $ex->getStatusCode() . "<br>\n");
        echo("Error Code: " . $ex->getErrorCode() . "<br>\n");
        echo("Error Type: " . $ex->getErrorType() . "<br>\n");
        echo("Request ID: " . $ex->getRequestId() . "<br>\n");
        echo("XML: " . $ex->getXML() . "<br>\n");
        echo("ResponseHeaderMetadata: " . $ex->getResponseHeaderMetadata() . "\n");

        return -1;
    }
}

/**
 * GetReportList Action
 * returns a list of reports created in the last 90 days
 *
 * @param MarketplaceWebService_Interface $service - Instance of MarketplaceWebService_Interface
 * @param mixed $request - Instance of MarketplaceWebService_Model_
 *
 * @return array - An array holding all the response results.
 */
function invokeGetReportList(MarketplaceWebService_Interface $service, $request) {
    try {
        $response = $service->getReportList($request);
        $rr = [];

        echo("<br>===========================<br>");
        echo(' ~ "GetReportList" response ~ ');
        echo("<br>===========================<br>");

        if ($response->isSetGetReportListResult()) {
            $getReportListResult = $response->getGetReportListResult();
            $reportInfoList = $getReportListResult->getReportInfoList();

            $count = 0;
            foreach ($reportInfoList as $info) {
                $rrTemp = [];
                $count++;
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

        return $rr;
    }
    catch (MarketplaceWebService_Exception $ex) {
        echo("<h1>Caught Exception:</h1>  " . $ex->getMessage() . "<br>");
        echo("Response Status Code: " . $ex->getStatusCode() . "<br>");
        echo("Error Code: " . $ex->getErrorCode() . "<br>");
        echo("Error Type: " . $ex->getErrorType() . "<br>");
        echo("Request ID: " . $ex->getRequestId() . "<br>");
        echo("XML: " . $ex->getXML() . "<br>");
        echo("ResponseHeaderMetadata: " . $ex->getResponseHeaderMetadata() . "<br>");

        return [-1];
    }
}


/**
 * --------------------------------------------------------------------------------------
 * -------------------------------- Request Report --------------------------------------
 * --------------------------------------------------------------------------------------
 **/

$reportId = $labGetReportList["row9"]["reportId"];
$paramsGetReport = [
    'Merchant' => MERCHANT_ID,
    'Report' => @fopen('php://memory', 'rw+'),
    'ReportId' => $reportId,
    'MWSAuthToken' => $mwsAuthToken,
];
$requestGetReport = new MarketplaceWebService_Model_GetReportRequest($paramsGetReport);


echo "<h3>ID of this report: $reportId</h3>";
echo " - mws auth key = $mwsAuthToken <br>";

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
        echo(' ~ "GetReport" response ~ ');
        echo("<br>====================<br>");

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

        // This h2 elem is where my web scraper .explode()'s
        echo "<h2>Report Contents</h2>"; // EXTREMELY IMPORTANT
        echo(stream_get_contents($request->getReport()));
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

