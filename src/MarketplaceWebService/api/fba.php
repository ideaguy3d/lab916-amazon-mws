<?php
/**
 * Created by Julius Alvarado.
 * User: Lab916
 * Date: 3/8/2018
 * Time: 4:14 PM
 */


include_once (".config.inc.php");

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

if($mwsAuthToken === null) {
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



