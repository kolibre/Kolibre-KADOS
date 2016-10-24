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
$serviceLogger = Logger::getLogger('kolibre.daisyonline.service');

// Include DaisyOnlineService class
require_once('DaisyOnlineService.class.php');

// Define wsdl filename
$wsdl_filename = 'do-wsdl-10.wsdl';

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
This service implements the DAISY Online Delivery protocol as specified in the Technical Recommendation approved in May 29, 2010.
<h3>Supported operations</h3>
<p class="operations">Required operations</p>
<ul>
<li>logOn</li>
<li>logOff</li>
<li>getServiceAttributes</li>
<li>setReadingSystemAttributes</li>
<li>getContentList</li>
<li>getContentMetadata</li>
<li>issueContent</li>
<li>getContentResources</li>
</ul>
<p class="operations">Optional operations</p>
<ul>
<li>returnContent</li>
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
?>
</ul>
<div class="note">
<h4>Note</h4>
<p>This is a demo service hosted by Kolibre and free to use for testing and demonstration purposes.</p>
<p>The service is pre-configured with five user accounts each containing three sample content. See list below for usernames and passwords</p>
<ul>
<li>user1:password</li>
<li>user2:password</li>
<li>user3:password</li>
<li>user4:password</li>
<li>user5:password</li>
</ul>
<p>If a user tries to return a content, the service will respond with a successful response, but the content will appear in the list of new content when the list is requested.</p>
<p>The service is also pre-configured to reset itself to its default state every day at 0:00 CET</p>
<div>
</body>
</html>
<?php
    die();
}

// Include class map
require_once('classmap.php');

// setup service options
$options['classmap'] = $classmap;
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
