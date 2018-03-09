<?php
/**
 * Created by Julius Alvarado.
 * User: Lab916
 * Date: 3/8/2018
 * Time: 4:14 PM
 */


include_once(".config.inc.php");

//          -- Fields --
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
    echo " ( LAB 916 - mws auth token was not in the query string ) ";
    echo " ( ERROR - The FBA report will not get generated. ) ";
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

$labRequestReport = invokeRequestReport($service, $requestGetReportListModel);

function invokeRequestReport(MarketplaceWebService_Interface $service, $request) {
    try {
        $response = $service->requestReport($request);

        echo("<br>=====================<br>");
        echo(' ~ "RequestReport" response ~ ');
        echo("<br>=====================<br>");

        if ($response->isSetRequestReportResult()) {
            echo "Request Report result:<br>";
            $requestReportResult = $response->getRequestReportResult();

            if ($requestReportResult->isSetReportRequestInfo()) {
                $reportRequestInfo = $requestReportResult->getReportRequestInfo();
            }
        }

        return 1;
    }
    catch (MarketplaceWebService_Exception $ex) {

        return -1;
    }
}

function invokeGetReportList(MarketplaceWebService_Interface $service, $request) {

}