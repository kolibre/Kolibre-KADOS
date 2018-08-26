<?php

/*
 * Copyright (C) 2013 Kolibre
 *
 * This file is part of Kolibre-KADOS.
 * Kolibre-KADOS is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 2.1 of the License, or
 * at your option) any later version.
 *
 * Kolibre-KADOS is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with Kolibre-KADOS. If not, see <http://www.gnu.org/licenses/>.
 */

// set include paths
set_include_path(get_include_path() . PATH_SEPARATOR . dirname(__FILE__) . '/..');
set_include_path(get_include_path() . PATH_SEPARATOR . dirname(__FILE__) . '/../include');
set_include_path(get_include_path() . PATH_SEPARATOR . dirname(__FILE__) . '/../include/types');

// Setup logging
require_once('vendor/autoload.php');
Logger::configure(dirname(__FILE__) . '/../log4php.xml');
if (array_key_exists('KADOS_LOG_LEVEL', $_ENV))
{
    $rootLogger = Logger::getRootLogger();
    switch ($_ENV['KADOS_LOG_LEVEL'])
    {
        case 'DEBUG':
            $rootLogger->setLevel(LoggerLevel::getLevelDebug());
            break;
        case 'INFO':
            $rootLogger->setLevel(LoggerLevel::getLevelInfo());
            break;
        case 'WARNING':
            $rootLogger->setLevel(LoggerLevel::getLevelWarn());
            break;
        case 'ERROR':
            $rootLogger->setLevel(LoggerLevel::getLevelError());
            break;
        case 'FATAL':
            $rootLogger->setLevel(LoggerLevel::getLevelFatal());
            break;
    }
}
$serviceLogger = Logger::getLogger('kolibre.daisyonline.service');

// Include DaisyOnlineService class
require_once('DaisyOnlineService.class.php');

// Define wsdl filename
$wsdl_filename = 'do-wsdl-20.wsdl';

// if WSDL file was requested (service.php?wsdl)
if (in_array('wsdl', array_map('strtolower', array_keys($_GET))))
{
    $serviceLogger->info('WSDL file requested');
    // Load wsdl data
    $wsdl_data = file_get_contents($wsdl_filename);

    // Get endpoint uri
    $endpointUri = DaisyOnlineService::getServiceBaseUri().basename($_SERVER['SCRIPT_NAME']);

    // Replace service endpoint value with correct enpoint uri and pass trough wsdl file
    header('Content-type: application/xml');
    die(str_replace('SERVICE_WSDL_URI_PLACEHOLDER', $endpointUri, $wsdl_data));
}

// if info page was requested (no POST)
if ($_SERVER['REQUEST_METHOD'] != 'POST')
{
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtmlitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style>
.heading {
    width: 100%;
    padding: 10px 0px;
    margin-top: 0px;
    font-size: 26px;
    font-weight: bold;
    color: #406685;
    background-color: #58c9e4;
}
.operations {
    padding-left: 10px;
}
.note {
    border:2px solid;
    border-radius:10px;
    background-color:#dddddd;
    padding: 0px 10px;
}
</style>
<title>DAISYOnlineService</title>
</head>
<body>
<p class="heading">&nbsp;&nbsp;DAISYOnlineService&nbsp;&nbsp;v<?php echo DaisyOnlineService::getVersion();?></p>
This service implements the DAISY Online Delivery protocol version 2 as specified in the specification issued in October 26th, 2015.
<h3>Supported operations</h3>
<p class="operations">Required operations</p>
<ul>
<li>logOn</li>
<li>logOff</li>
<li>getContentList</li>
<li>getContentResources</li>
<li>returnContent</li>
</ul>
<p class="operations">Optional operations</p>
<ul>
<?php
    $DaisyOnlineService = new DaisyOnlineService();
    $operations = $DaisyOnlineService->getServiceSupportedOptionalOperations();
    if (in_array('SERVICE_ANNOUNCEMENTS', $operations))
        echo "<li>getServiceAnnouncements</li>\n<li>markAnnouncementsAsRead</li>\n";
    if (in_array('SET_BOOKMARKS', $operations))
        echo "<li>setBookmarks</li>\n";
    if (in_array('GET_BOOKMARKS', $operations))
        echo "<li>getBookmarks</li>\n";
    if (in_array('DYNAMIC_MENUS', $operations))
        echo "<li>getQuestions</li>\n";
    if (in_array('PDTB2_KEY_PROVISION', $operations))
        echo "<li>getKeyExchangeObject</li>\n";
    $operationsExtra = $DaisyOnlineService->getServiceSupportedOptionalOperationsExtra();
    if (in_array('PROGRESS_STATE', $operationsExtra))
        echo "<li>setProgressState</li>\n";
    if (in_array('TERMS_OF_SERVICE', $operationsExtra))
        echo "<li>getTermsOfService</li>\n<li>acceptTermsOfService</li>";
    if (in_array('USER_CREDENTIALS', $operationsExtra))
        echo "<li>getUserCredentials</li>\n";
    if (in_array('ADD_CONTENT', $operationsExtra))
        echo "<li>addContentToBookshelf</li>\n";
?>
</ul>
<div class="note">
<h4>Note</h4>
<p>This is a demo service hosted by Kolibre and free to use for testing and demonstration purposes.</p>
<p>The service is pre-configured with 10 user accounts. Five accounts each with three sample contents populated in contents lists and five accounts with empty content lists that can be populated through the dynamic menus. See lists below for usernames and passwords</p>
<p>User accounts with populated content lists:</p>
<ul>
<li>user1:password</li>
<li>user2:password</li>
<li>user3:password</li>
<li>user4:password</li>
<li>user5:password</li>
</ul>
<p>User accounts with empty content lists:</p>
<ul>
<li>user6:password</li>
<li>user7:password</li>
<li>user8:password</li>
<li>user9:password</li>
<li>user10:password</li>
</ul>
<p>The dynamic menus provided with this service can be used to explore the demo library, consisting of only three sample contents, and add contents to a user's own content list. It's also possible to provide feedback but we don't not store nor evaluate the feedback. It's only for pure demonstration of dynamic menus. Below is an illustration of the dynamic menu structure.</p>
<img src="dynamic-menus-structure.png" alt="Dynamic menu structure"/>
<p>The service is also pre-configured to reset itself to its default state every day at 0:00 CET</p>
<div>
</body>
</html>
<?php
    die();
}

// Include class map
require_once('classmap.php');

// Include type map
// overrides PHP's inbuilt encoding/decoding for specific types
require_once('typemap.php');

// setup service options
$options['classmap'] = $classmap;
$options['typemap'] = $typemap;
$options['soap_version'] = SOAP_1_1;
$options['cache_wsdl'] = WSDL_CACHE_MEMORY;
$options['features'] = SOAP_SINGLE_ELEMENT_ARRAYS;

// create class persistence service in WSDL mode
$service = new SoapServer($wsdl_filename, $options);
$service->setClass('DaisyOnlineService');
$service->setPersistence(SOAP_PERSISTENCE_SESSION);

if(!isset($HTTP_RAW_POST_DATA)) $HTTP_RAW_POST_DATA = '';

// create timestamp for incoming request
$timestamp = time();

// Start output buffering to capture response data before it is sent to the client
ob_start();

// process client request and place response data in output buffer
$serviceLogger->trace('Handle request');
$service->handle();

// read data from output buffer
$RESPONSE_DATA = ob_get_contents();
if (isset($_SESSION['_bogus_session_name']))
{
    $daisyonlineservice = $_SESSION['_bogus_session_name'];
    if (is_a($daisyonlineservice, 'DaisyOnlineService'))
    {
        // log request and response
        $serviceLogger->trace('Log SOAP request and response');
        $daisyonlineservice->logRequestAndResponse($HTTP_RAW_POST_DATA, $RESPONSE_DATA, $timestamp);
    }
}

?>
