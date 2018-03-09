<?php
/**
 * Created by Julius Alvarado.
 * User: Lab916
 * Date: 2/28/2018
 * Time: 5:57 PM
 */

$mwsAuthToken = isset($_GET["mws-auth-token"]) ? $_GET["mws-auth-token"] : null;

include_once (".config.inc.php");

// United States:
$serviceUrl = "https://mws.amazonservices.com";

// MWS Auth Token
$config = array(
    'ServiceURL' => $serviceUrl,
    'ProxyHost' => null,
    'ProxyPort' => -1,
    'MaxErrorRetry' => 10,
    'MWSAuthToken' => 'amzn.mws.eab0dfe5-9c2b-743b-6f84-05e4348b9f3f'
);

