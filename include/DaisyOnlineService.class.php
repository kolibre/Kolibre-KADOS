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

$includePath = dirname(realpath(__FILE__)) . '/types';
set_include_path(get_include_path() . PATH_SEPARATOR . $includePath);

require_once('log4php/Logger.php');

require_once('logOn.class.php');
require_once('logOnResponse.class.php');
require_once('logOff.class.php');
require_once('logOffResponse.class.php');
require_once('getServiceAttributes.class.php');
require_once('getServiceAttributesResponse.class.php');
require_once('setReadingSystemAttributes.class.php');
require_once('setReadingSystemAttributesResponse.class.php');
require_once('getContentList.class.php');
require_once('getContentListResponse.class.php');
require_once('getContentMetadata.class.php');
require_once('getContentMetadataResponse.class.php');
require_once('issueContent.class.php');
require_once('issueContentResponse.class.php');
require_once('getContentResources.class.php');
require_once('getContentResourcesResponse.class.php');
require_once('getServiceAnnouncements.class.php');
require_once('getServiceAnnouncementsResponse.class.php');
require_once('markAnnouncementsAsRead.class.php');
require_once('markAnnouncementsAsReadResponse.class.php');
require_once('setBookmarks.class.php');
require_once('setBookmarksResponse.class.php');
require_once('getBookmarks.class.php');
require_once('getBookmarksResponse.class.php');
require_once('returnContent.class.php');
require_once('returnContentResponse.class.php');
require_once('getQuestions.class.php');
require_once('getQuestionsResponse.class.php');
require_once('getKeyExchangeObject.class.php');
require_once('getKeyExchangeObjectResponse.class.php');

class DaisyOnlineService
{
    private $serviceAttributes = array();
    private $readingSystemAttributes = null;

    // operation currently invoked by client
    private $sessionCurrentOperation;

    // stack containing invoked operations
    private $sessionInvokedOperations = array();

    // stack containing operations to be invoked in initialization sequence
    private $sessionInitializationStack = array();

    // stack containing content identifiers for which metadata has been retrieved
    private $sessionContentMetadataRequests = array();

    // boolean indicating if a user has successfully logged on
    private $sessionUserLoggedOn = false;

    // boolean indicating if a session has been established
    private $sessionEstablished = false;

    // username for the active client/device in this session
    private $sessionUsername = null;

    // boolean indicating if session handling is disabled, use with debugging and testing only
    private $sessionHandleDisabled = false;

    // boolean indicating if cookie check is disabled in session handling, use with debugging and testing only
    private $sessionHandleCookieDisabled = false;

    // logger instance
    private $logger = null;

    // adapter instance
    private $adapter = null;

    // adapter file to include in wakeup
    private $adapterIncludeFile = null;

    public function __construct($inifile = null)
    {
        // setup logger
        $this->logger = Logger::getLogger('kolibre.daisyonline.daisyonlineservice');

        // parse settings file
        if (is_null($inifile))
            $inifile = realpath(dirname(__FILE__)) . '/../service.ini';
        $settings = parse_ini_file($inifile, true);

        // setup service attributes
        if (array_key_exists('Service', $settings))
            $this->setupServiceAttributes($settings['Service']);
        else
        {
            $this->logger->error("Group 'Service' is missing in ini file");
            die("Group 'Service' not found in ini file, please make sure the settings file is correct.");
        }

        // setup adapter
        if (array_key_exists('Adapter', $settings))
            $this->setupAdapter($settings['Adapter']);
        else
        {
            $this->logger->error("Group 'Adapter' is missing in ini file");
            die("Group 'Adapter' not found in ini file, please make sure the settings file is correct.");
        }
    }

    /**
     * Invoked when restoring object from session
     */
    public function __wakeup()
    {
        // setup logger
        $this->logger = Logger::getLogger('kolibre.daisyonline.daisyonlineservice');

        if (!is_null($this->adapterIncludeFile)) require_once($this->adapterIncludeFile);
        $this->adapter = unserialize($this->adapter);
    }

    /**
     * Invoked when storing object to session
     */
    public function __sleep()
    {
        $this->adapter = serialize($this->adapter);
        $instance_variables_to_serialize = array();
        array_push($instance_variables_to_serialize, 'serviceAttributes');
        array_push($instance_variables_to_serialize, 'readingSystemAttributes');
        array_push($instance_variables_to_serialize, 'sessionCurrentOperation');
        array_push($instance_variables_to_serialize, 'sessionInvokedOperations');
        array_push($instance_variables_to_serialize, 'sessionInitializationStack');
        array_push($instance_variables_to_serialize, 'sessionContentMetadataRequests');
        array_push($instance_variables_to_serialize, 'sessionUserLoggedOn');
        array_push($instance_variables_to_serialize, 'sessionEstablished');
        array_push($instance_variables_to_serialize, 'sessionUsername');
        array_push($instance_variables_to_serialize, 'adapter');
        array_push($instance_variables_to_serialize, 'adapterIncludeFile');
        return $instance_variables_to_serialize;
    }

    /**
     * Disables the internal session handling.
     *
     * Warning! Do not invoke this function unless your are testing or debugging this class.
     */
    public function disableInternalSessionHandling()
    {
        $this->sessionHandleDisabled = true;
    }

    /**
     * Disables check for chookie in session handling.
     *
     * Warning. Don not invoke this functin unless you are testing or debugging this class.
     */
    public function disableCookieCheckInSessionHandle()
    {
        $this->sessionHandleCookieDisabled = true;
    }

    /**
     * Log function logRequestAndResponse, log SOAP request and response, invoked from service.php
     * @param string $request, SOAP request
     * @param string $response, SOAP response
     */
    public function logRequestAndResponse($request, $response, $timestamp)
    {
        $ip = $this->getClientIP();
        $this->adapter->logSoapRequestAndResponse($request, $response, $timestamp, $ip);
    }

    /**
     * Service helper getServiceBaseUri
     * @return string
     */
    public function getServiceBaseUri($allowencrypted = false)
    {
        $protocol = 'http';
        if ($allowencrypted === true)
        {
            if (isset($_SERVER['HTTPS'])) $protocol = 'https';
        }

        $host = 'localhost';
        if (isset($_SERVER['SERVER_NAME'])) $host = $_SERVER['SERVER_NAME'];

        $port = '';
        if (isset($_SERVER['SERVER_PORT']))
        {
            switch ($protocol)
            {
                case 'http':
                    if ($_SERVER['SERVER_PORT'] != 80)
                        $port = ':' . $_SERVER['SERVER_PORT'];
                    break;
                case 'https':
                    if ($_SERVER['SERVER_PORT'] != 443)
                        $port = ':' . $_SERVER['SERVER_PORT'];
                    break;
            }
        }

        $path = '';
        if (isset($_SERVER['SCRIPT_NAME'])) $path = dirname($_SERVER['SCRIPT_NAME']);
        if (strlen($path) > 0 && substr($path, -1) != '/') $path .= '/';

        return "$protocol://$host$port$path";
    }

    /**
     * Service function logOn
     * @param object of logOn $input
     * @return object of logOnResponse
     */
    public function logOn($input)
    {
        $this->sessionHandle(__FUNCTION__);

        // return logOnResult = false if request is invalid
        if ($input->validate() === false)
        {
            $msg = "request is not valid " . $input->getError();
            $this->logger->warn($msg);
            return new logOnResponse(false);
        }

        $username = $input->getUsername();
        $password = $input->getPassword();

        try
        {
            if ($this->adapter->authenticate($username, $password) === false)
                return new logOnResponse(false);
        }
        catch (AdapterException $e)
        {
            $this->logger->fatal($e->getMessage());
            throw new SoapFault('Server', 'Internal Server Error', '', '', 'logOn_internalServerErrorFault');
        }

        // store user information
        $this->sessionUsername = $username;
        $this->sessionUserLoggedOn = true;

        $msg = "User '$username' logged on";
        $this->logger->info($msg);
        return new logOnResponse(true);
    }

    /**
     * Service function logOff
     * @param object of logOff $input
     * @return object of logOffResponse
     */
    public function logOff($input)
    {
        $this->sessionHandle(__FUNCTION__);

        $msg = "User '$this->sessionUsername' logged off";
        $this->logger->info($msg);
        return new logOffResponse(true);
    }

    /**
     * Service function getServiceAttributes
     * @param object of getServiceAttributes $input
     * @return object of getServiceAttributesResponse
     */
    public function getServiceAttributes($input)
    {
        $this->sessionHandle(__FUNCTION__);

        try
        {
            // set serviceProvider
            $serviceProvider = null;
            if (array_key_exists('serviceProvider', $this->serviceAttributes))
            {
                $serviceProvider = new serviceProvider(null, $this->serviceAttributes['serviceProvider']);
                $label = $this->adapter->label($this->serviceAttributes['serviceProvider'], Adapter::LABEL_SERVICE);
                if (is_array($label))
                    $serviceProvider->setLabel($this->createLabel($label));
            }

            // set service
            $service = null;
            if (array_key_exists('service', $this->serviceAttributes))
            {
                $service = new service(null, $this->serviceAttributes['service']);
                $label = $this->adapter->label($this->serviceAttributes['service'], Adapter::LABEL_SERVICE);
                if (is_array($label))
                    $service->setLabel($this->createLabel($label));
            }
        }
        catch (AdapterException $e)
        {
            $this->logger->fatal($e->getMessage());
            throw new SoapFault('Server', 'Internal Server Error', '', '', 'getServiceAttributes_internalServerErrorFault');
        }

        // set supportedContentSelectionMethods
        $supportedContentSelectionMethods = new supportedContentSelectionMethods();
        foreach ($this->serviceAttributes['supportedContentSelectionMethods'] as $method)
            $supportedContentSelectionMethods->addMethod($method);

        // set supportsServerSideBack
        $supportsServerSideBack = $this->serviceAttributes['supportsServerSideBack'];

        // set supportsSearch
        $supportsSearch = $this->serviceAttributes['supportsSearch'];

        // set supportedUplinkAudioCodecs
        $supportedUplinkAudioCodecs = new supportedUplinkAudioCodecs();
        foreach ($this->serviceAttributes['supportedUplinkAudioCodecs'] as $codec)
            $supportedUplinkAudioCodecs->addCodec($codec);

        // set supportsAudioLabels
        $supportsAudioLabels = $this->serviceAttributes['supportsAudioLabels'];

        // set supportedOptionalOperations
        $supportedOptionalOperations = new supportedOptionalOperations();
        foreach ($this->serviceAttributes['supportedOptionalOperations'] as $operation)
            $supportedOptionalOperations->addOperation($operation);

        $serviceAttributes = new serviceAttributes($serviceProvider,
            $service,
            $supportedContentSelectionMethods,
            $supportsServerSideBack,
            $supportsSearch,
            $supportedUplinkAudioCodecs,
            $supportsAudioLabels,
            $supportedOptionalOperations);

        $output = new getServiceAttributesResponse($serviceAttributes);

        if ($output->validate() === false)
        {
            $msg = "failed to build response " . $output->getError();
            $this->logger->error($msg);
            $faultString = 'getServiceAttributesResponse could not be built';
            throw new SoapFault('Server', $faultString,'', '', 'getServiceAttributes_internalServerErrorFault');
        }

        return $output;
    }

    /**
     * Service function setReadingSystemAttributes
     * @param object of setReadingSystemAttributes $input
     * @return object of setReadingSystemAttributesResponse
     */
    public function setReadingSystemAttributes($input)
    {
        $this->sessionHandle(__FUNCTION__);

        if ($input->validate() === false)
        {
            $msg = "request is not valid " . $input->getError();
            $this->logger->warn($msg);
            throw new SoapFault('Client', $input->getError(), '', '', 'setReadingSystemAttributes_invalidParameterFault');
        }

        // start backend session
        if ($this->adapter->startSession() === false)
        {
            $this->logger->warn("Backend session not active");
            return new setReadingSystemAttributesResponse(false);
        }

        // store reading system attributes
        $this->readingSystemAttributes = $input->getReadingSystemAttributes();
        $this->sessionEstablished = true;

        $msg = "User '$this->sessionUsername' established a session";
        $this->logger->info($msg);
        return new setReadingSystemAttributesResponse(true);
    }

    /**
     * Service function getContentList
     * @param object of getContentList $input
     * @return object of getContentListResponse
     */
    public function getContentList($input)
    {
        $this->sessionHandle(__FUNCTION__);

        if ($input->validate() === false)
        {
            $msg = "request is not valid " . $input->getError();
            $this->logger->warn($msg);
            throw new SoapFault('Client', $input->getError(), '', '', 'getContentList_invalidParameterFault');
        }

        $listId = $input->getId();

        // check if the requested list exists
        try
        {
            if ($this->adapter->contentListExists($listId) === false)
            {
                $msg = "User '$this->sessionUsername' requested an unsupported content list '$listId'";
                $this->logger->warn($msg);
                $faultString = "contentList '$listId' does not exist";
                throw new SoapFault('Client', $faultString,'', '', 'getContentList_invalidParameterFault');
            }
        }
        catch (AdapterException $e)
        {
            $this->logger->fatal($e->getMessage());
            throw new SoapFault('Server', 'Internal Server Error', '', '', 'getContentList_internalServerErrorFault');
        }

        // fetch content for the requested list
        try
        {
            $contentFormat = $this->getClientSupportedContentFormats();
            $protectionFormat = $this->getClientSupportedProtectionFormats();
            $mimeType = $this->getClientSupportedMimeTypes();
            $contentItems = $this->adapter->contentList($listId, $contentFormat, $protectionFormat, $mimeType);
        }
        catch (AdapterException $e)
        {
            $this->logger->fatal($e->getMessage());
            throw new SoapFault('Server', 'Internal Server Error', '', '', 'getContentList_internalServerErrorFault');
        }

        // build contentList
        $contentList = new contentList();
        $contentList->setId($listId);

        // set label
        try
        {
            $label = $this->adapter->label($listId, Adapter::LABEL_CONTENTLIST, $this->getClientLangCode());
            if (is_array($label))
                $contentList->setLabel($this->createLabel($label));
        }
        catch (AdapterException $e)
        {
            $this->logger->fatal($e->getMessage());
        }

        $totalItems = sizeof($contentItems);
        $firstItem = $input->getFirstItem();
        $lastItem = $input->getLastItem();

        // if firstItem or lastItem is invalid we must return an empty list with totalItems attribute set to
        // total number of items in list, thus we assume list will be empty
        $contentList->setTotalItems($totalItems);

        // generate content list
        if ($totalItems > 0)
        {
            if ($firstItem >= $totalItems || $lastItem >= $totalItems)
            {
                // return an empty content list with totalItems attribute set to total number of items in list
                $msg = 'Client requested an out-of-bounds sublist';
                $this->logger->warn($msg);
            }
            else
            {
                if ($lastItem == -1) $lastItem = $totalItems-1;
                for ($i = $firstItem; $i <= $lastItem; $i++)
                {
                    $contentId = $contentItems[$i];
                    $contentItem = new contentItem(null, $contentId);
                    try
                    {
                        $label = $this->adapter->label($contentId, Adapter::LABEL_CONTENTITEM, $this->getClientLangCode());
                        if (is_array($label))
                            $contentItem->setLabel($this->createLabel($label));
                        else
                            $this->logger->warn("Content with id '$contentId' has no label");

                        $lastModifiedDate = $this->adapter->contentLastModifiedDate($contentId);
                        if (is_string($lastModifiedDate))
                            $contentItem->setLastModifiedDate($lastModifiedDate);
                    }
                    catch (AdapterException $e)
                    {
                        $this->logger->fatal($e->getMessage());
                    }
                    $contentList->addContentItem($contentItem);
                }

                $contentList->setFirstItem($firstItem);
                $contentList->setLastItem($lastItem);
            }
        }

        $output = new getContentListResponse($contentList);

        if ($output->validate() === false)
        {
            $msg = "failed to build response " . $output->getError();
            $this->logger->error($msg);
            $faultString = 'getContentListResponse could not be built';
            throw new SoapFault('Server', $faultString,'', '', 'getContentList_internalServerErrorFault');
        }

        return $output;
    }

    /**
     * Service function getContentMetadata
     * @param object of getContentMetadata $input
     * @return object of getContentMetadataResponse
     */
    public function getContentMetadata($input)
    {
        $this->sessionHandle(__FUNCTION__);

        if ($input->validate() === false)
        {
            $msg = "request is not valid " . $input->getError();
            $this->logger->warn($msg);
            throw new SoapFault('Client', $input->getError(), '', '', 'getContentMetadata_invalidParameterFault');
        }

        // parameters
        $contentId = $input->getContentID();

        // check if the requested content exists and is accessible
        try
        {
            if ($this->adapter->contentExists($contentId) === false)
            {
                $msg = "User '$this->sessionUsername' requested metadata for a nonexistent content '$contentId'";
                $this->logger->warn($msg);
                $faultString = "content '$contentId' does not exist";
                throw new SoapFault('Client', $faultString,'', '', 'getContentMetadata_invalidParameterFault');
            }

            if ($this->adapter->contentAccessible($contentId) === false)
            {
                $msg = "User '$this->sessionUsername' requested metadata for an inaccessible content '$contentId'";
                $this->logger->warn($msg);
                $faultString = "content '$contentId' not accessible";
                throw new SoapFault('Client', $faultString,'', '', 'getContentMetadata_invalidParameterFault');
            }
        }
        catch (AdapterException $e)
        {
            $this->logger->fatal($e->getMessage());
            throw new SoapFault('Server', 'Internal Server Error', '', '', 'getContentMetadata_internalServerErrorFault');
        }

        // build contentMetadata
        $contentMetadata = new contentMetadata();

        try
        {
            // set sample [optional]
            $sample = $this->adapter->contentSample($contentId);
            if (is_string($sample)) $contentMetadata->setSample(new sample($sample));

            // set category [optional]
            $category = $this->adapter->contentCategory($contentId);
            if (is_string($category)) $contentMetadata->setCategory($category);

            // set requiresRetrn [mandatory]
            $returnDate = $this->adapter->contentReturnDate($contentId);
            if (is_string($returnDate)) $contentMetadata->setRequiresReturn(true);
            else $contentMetadata->setRequiresReturn(false);

            // build metadata
            $metadata = new metadata();
            $metadata->setIdentifier($input->getContentID());
            $metadataValues = $this->adapter->contentMetadata($contentId);
            if (is_array($metadataValues) === false) {
                $this->logger->warn("Content with id '$contentId' has no metadata");
                throw new SoapFault('Server', 'Internal Server Error', '', '', 'getContentMetadata_internalServerErrorFault');
            }
            foreach ($metadataValues as $key => $value)
            {
                switch ($key)
                {
                case 'size':
                    $metadata->setSize($value);
                    break;
                case 'dc:title':
                    $metadata->setTitle($value);
                    break;
                case 'dc:identifier':
                    // the identifier is not the identifier found in metadata
                    break;
                case 'dc:publisher':
                    $metadata->setPublisher($value);
                    break;
                case 'dc:format':
                    $metadata->setFormat($value);
                    break;
                case 'dc:date':
                    $metadata->setDate($value);
                    break;
                case 'dc:source':
                    $metadata->setSource($value);
                    break;
                case 'dc:type':
                    if (is_array($value))
                        foreach ($value as $subvalue) $metadata->addType($subvalue);
                    else
                        $metadata->addType($value);
                    break;
                case 'dc:subject':
                    if (is_array($value))
                        foreach ($value as $subvalue) $metadata->addSubject($subvalue);
                    else
                        $metadata->addSubject($value);
                    break;
                case 'dc:rights':
                    if (is_array($value))
                        foreach ($value as $subvalue) $metadata->addRights($subvalue);
                    else
                        $metadata->addRights($value);
                    break;
                case 'dc:relation':
                    if (is_array($value))
                        foreach ($value as $subvalue) $metadata->addRelation($subvalue);
                    else
                        $metadata->addRelation($value);
                    break;
                case 'dc:language':
                    if (is_array($value))
                        foreach ($value as $subvalue) $metadata->addLanguage($subvalue);
                    else
                        $metadata->addLanguage($value);
                    break;
                case 'dc:description':
                    if (is_array($value))
                        foreach ($value as $subvalue) $metadata->addDescription($subvalue);
                    else
                        $metadata->addDescription($value);
                    break;
                case 'dc:creator':
                    if (is_array($value))
                        foreach ($value as $subvalue) $metadata->addCreator($subvalue);
                    else
                        $metadata->addCreator($value);
                    break;
                case 'dc:coverage':
                    if (is_array($value))
                        foreach ($value as $subvalue) $metadata->addCoverage($subvalue);
                    else
                        $metadata->addCoverage($value);
                    break;
                case 'dc:contributor':
                    if (is_array($value))
                        foreach ($value as $subvalue) $metadata->addContributor($subvalue);
                    else
                        $metadata->addContributor($value);
                    break;
                default:
                    if ($key == 'pdtb2:specVersion')
                        $metadata->addMeta(new meta($key, $value));
                    break;
                }
            }
        }
        catch (AdapterException $e)
        {
            $this->logger->fatal($e->getMessage());
            throw new SoapFault('Server', 'Internal Server Error', '', '', 'getContentMetadata_internalServerErrorFault');
        }

        $contentMetadata->setMetadata($metadata);

        $output = new getContentMetadataResponse($contentMetadata);

        if ($output->validate() === false)
        {
            $msg = "failed to build response " . $output->getError();
            $this->logger->error($msg);
            $faultString = 'getContentMetadataResponse could not be built';
            throw new SoapFault('Server', $faultString,'', '', 'getContentMetadata_internalServerErrorFault');
        }

        // store content identifier in sessionContentMetadataRequests
        array_push($this->sessionContentMetadataRequests, $contentId);

        return $output;
    }

    /**
     * Service function issueContent
     * @param object of issueContent $input
     * @return object of issueContentResponse
     */
    public function issueContent($input)
    {
        $this->sessionHandle(__FUNCTION__);

        if ($input->validate() === false)
        {
            $msg = "request is not valid " . $input->getError();
            $this->logger->warn($msg);
            throw new SoapFault('Client', $input->getError(), '', '', 'issueContent_invalidParameterFault');
        }

        // parameters
        $contentId = $input->getContentID();

        // check if the requested content exists and is accessible
        try
        {
            if ($this->adapter->contentExists($contentId) === false)
            {
                $msg = "User '$this->sessionUsername' requested issuing of a nonexistent content '$contentId'";
                $this->logger->warn($msg);
                $faultString = "content '$contentId' does not exist";
                throw new SoapFault('Client', $faultString,'', '', 'issueContent_invalidParameterFault');
            }

            if ($this->adapter->contentAccessible($contentId) === false)
            {
                $msg = "User '$this->sessionUsername' requested issuing of an inaccessible content '$contentId'";
                $this->logger->warn($msg);
                $faultString = "content '$contentId' not accessible";
                throw new SoapFault('Client', $faultString,'', '', 'issueContent_invalidParameterFault');
            }
        }
        catch (AdapterException $e)
        {
            $this->logger->fatal($e->getMessage());
            throw new SoapFault('Server', 'Internal Server Error', '', '', 'issueContent_internalServerErrorFault');
        }

        // check if a prior call to getContentMetadata has been made
        if (!$this->sessionHandleDisabled && in_array($contentId, $this->sessionContentMetadataRequests) === false)
        {
            $this->logger->warn("No prior call to getContentMetadata for content '$contentId'");
            $faultString = "Metadata for content has not been requested, call getContentMetadata for content '$contentId'";
            throw new SoapFault('Client', $faultString, '', '', 'issueContent_invalidOperatonFault');
        }

        // check if content is issuable and issue content
        try
        {
            if ($this->adapter->contentIssuable($contentId) === false)
            {
                $msg = "User '$this->sessionUsername' not allowed to issue content with id '$contentId'";
                $this->logger->warn($msg);
                $faultString = "content '$contentId' is not issuable";
                throw new SoapFault('Client', $faultString, '', '', 'issueContent_invalidParameterFault');
            }

            if ($this->adapter->contentIssue($contentId) === false)
            {
                $this->logger->warn("Issuing content '$contentId' failed");
                return new issueContentResponse(false);
            }
        }
        catch (AdapterException $e)
        {
            $this->logger->fatal($e->getMessage());
            throw new SoapFault('Server', 'Internal Server Error', '', '', 'issueContent_internalServerErrorFault');
        }

        return new issueContentResponse(true);
    }

    /**
     * Service function getContentResources
     * @param object of getContentResources $input
     * @return object of getContentResourcesResponse
     */
    public function getContentResources($input)
    {
        $this->sessionHandle(__FUNCTION__);

        if ($input->validate() === false)
        {
            $msg = "request is not valid " . $input->getError();
            $this->logger->warn($msg);
            throw new SoapFault ('Client', $input->getError(), '', '', 'getContentResources_invalidParameterFault');
        }

        // parameters
        $contentId = $input->getContentID();

        // check if the requested content exists and is accessible
        try
        {
            if ($this->adapter->contentExists($contentId) === false)
            {
                $msg = "User '$this->sessionUsername' requested resources of a nonexistent content '$contentId'";
                $this->logger->warn($msg);
                $faultString = "content '$contentId' does not exist";
                throw new SoapFault('Client', $faultString,'', '', 'getContentResources_invalidParameterFault');
            }

            if ($this->adapter->contentAccessible($contentId) === false)
            {
                $msg = "User '$this->sessionUsername' requested resources of an inaccessible content '$contentId'";
                $this->logger->warn($msg);
                $faultString = "content '$contentId' not accessible";
                throw new SoapFault('Client', $faultString,'', '', 'getContentResources_invalidParameterFault');
            }
        }
        catch (AdapterException $e)
        {
            $this->logger->fatal($e->getMessage());
            throw new SoapFault('Server', 'Internal Server Error', '', '', 'getContentResources_internalServerErrorFault');
        }

        // build resources
        $resources = new resources();

        try
        {
            // set returnBy [optional]
            $returnDate = $this->adapter->contentReturnDate($contentId);
            if (is_string($returnDate)) $resources->setReturnBy($returnDate);

            // set lastModifiedDate [optional]
            $lastModifiedDate = $this->adapter->contentLastModifiedDate($contentId);
            if (is_string($lastModifiedDate)) $resources->setLastModifiedDate($lastModifiedDate);

            // build resource
            $contentResources = $this->adapter->contentResources($contentId);
            if (empty($contentResources))
            {
                $msg = "User '$this->sessionUsername' requested resources for non-issued content '$contentId'";
                $this->logger->warn($msg);
                $faultString = "content '$contentId' is not issued";
                throw new SoapFault('Client', $faultString,'', '', 'getContentResources_invalidOperationFault');
            }
            foreach ($contentResources as $resource)
                $resources->addResource($this->createResource($resource));

        }
        catch (AdapterException $e)
        {
            $this->logger->fatal($e->getMessage());
            throw new SoapFault('Server', 'Internal Server Error', '', '', 'getContentResources_internalServerErrorFault');
        }

        $output = new getContentResourcesResponse($resources);

        if ($output->validate() === false)
        {
            $msg = "failed to build response " . $output->getError();
            $this->logger->error($msg);
            $faultString = 'getContentResourcesResponse could not be built';
            throw new SoapFault('Server', $faultString, '', '', 'getContentResources_internalServerErrorFault');
        }

        return $output;
    }

    /**
     * Service function getServiceAnnouncements
     * @param object of getServiceAnnouncements $input
     * @return object of getServiceAnnouncementsResponse
     */
    public function getServiceAnnouncements($input)
    {
        $this->sessionHandle(__FUNCTION__);
        if (!in_array('SERVICE_ANNOUNCEMENTS', $this->serviceAttributes['supportedOptionalOperations']))
            throw new SoapFault ('Client', 'getServiceAnnouncements not supported', '', '', 'getServiceAnnouncements_operationNotSupportedFault');
    }

    /**
     * Service function markAnnouncementsAsRead
     * @param object of read $input
     * @return object of markAnnouncementsAsReadResponse
     */
    public function markAnnouncementsAsRead($input)
    {
        $this->sessionHandle(__FUNCTION__);
        if (!in_array('SERVICE_ANNOUNCEMENTS', $this->serviceAttributes['supportedOptionalOperations']))
            throw new SoapFault ('Client', 'markAnnouncementsAsRead not supported', '', '', 'markAnnouncementsAsRead_operationNotSupportedFault');
    }

    /**
     * Service function returnContent
     * @param object of returnContent $input
     * @return object of returnContentResponse
     */
    public function returnContent($input)
    {
        $this->sessionHandle(__FUNCTION__);

        if ($input->validate() === false)
        {
            $msg = "request is not valid " . $input->getError();
            $this->logger->warn($msg);
            throw new SoapFault ('Client', $input->getError(), '', '', 'returnContent_invalidParameterFault');
        }

        // parameters
        $contentId = $input->getContentID();

        // check if the requested content exists and is accessible
        try
        {
            if ($this->adapter->contentExists($contentId) === false)
            {
                $msg = "User '$this->sessionUsername' requested return of a nonexistent content '$contentId'";
                $this->logger->warn($msg);
                $faultString = "content '$contentId' does not exist";
                throw new SoapFault('Client', $faultString,'', '', 'returnContent_invalidParameterFault');
            }

            if ($this->adapter->contentAccessible($contentId) === false)
            {
                $msg = "User '$this->sessionUsername' requested return of an inaccessible content '$contentId'";
                $this->logger->warn($msg);
                $faultString = "content '$contentId' not accessible";
                throw new SoapFault('Client', $faultString,'', '', 'returnContent_invalidParameterFault');
            }
        }
        catch (AdapterException $e)
        {
            $this->logger->fatal($e->getMessage());
            throw new SoapFault('Server', 'Internal Server Error', '', '', 'returnContent_internalServerErrorFault');
        }

        // check if content is returnable and return content
        try
        {
            if ($this->adapter->contentReturnable($contentId) === false)
            {
                $msg = "User '$this->sessionUsername' tried to return a non borrowable content '$contentId'";
                $this->logger->warn($msg);
                $faultString = "content '$contentId' does not require return";
                throw new SoapFault ('Client', $faultString, '', '', 'returnContent_invalidParameterFault');
            }

            if ($this->adapter->contentReturn($contentId) === false)
            {
                $this->logger->warn("Returning content '$contentId' failed");
                throw new SoapFault ('Server', 'Internal Server Error', '', '', 'returnContent_internalServerErrorFault');
            }
        }
        catch (AdapterException $e)
        {
            $this->logger->fatal($e->getMessage());
            throw new SoapFault('Server', 'Internal Server Error', '', '', 'returnContent_internalServerErrorFault');
        }

        return new returnContentResponse(true);
    }

    /**
     * Service function setBookmarks
     * @param object of setBookmarks $input
     * @return object of setBookmarksResponse
     */
    public function setBookmarks($input)
    {
        $this->sessionHandle(__FUNCTION__);
        if (!in_array('SET_BOOKMARKS', $this->serviceAttributes['supportedOptionalOperations']))
            throw new SoapFault ('Client', 'setBookmarks not supported', '', '', 'setBookmarks_operationNotSupportedFault');
    }

    /**
     * Service function getBookmarks
     * @param object of getBookmarks $input
     * @return object of getBookmarksResponse
     */
    public function getBookmarks($input)
    {
        $this->sessionHandle(__FUNCTION__);
        if (!in_array('GET_BOOKMARKS', $this->serviceAttributes['supportedOptionalOperations']))
            throw new SoapFault ('Client', 'getBookmarks not supported', '', '', 'getBookmarks_operationNotSupportedFault');

    }

    /**
     * Service function getQuestions
     * @param object of getQuestions $input
     * @return object of getQuestionsResponse
     */
    public function getQuestions($input)
    {
        $this->sessionHandle(__FUNCTION__);
        if (!in_array('DYNAMIC_MENUS', $this->serviceAttributes['supportedOptionalOperations']))
            throw new SoapFault ('Client', 'getQuestions not supported', '', '', 'getQuestions_operationNotSupportedFault');
    }

    /**
     * Service function getKeyExchangeObject
     * @param object of getKeyExchangeObject $input
     * @return object of getKeyExchangeObjectResponse
     */
    public function getKeyExchangeObject($input)
    {
        $this->sessionHandle(__FUNCTION__);
        if (!in_array('PDTB2_KEY_PROVISION', $this->serviceAttributes['supportedOptionalOperations']))
            throw new SoapFault ('Client', 'getKeyExchangeObject not supported', '', '', 'getKeyExchangeObject_operationNotSupportedFault');
    }

    /**
     * Service helper
     *
     * Parses services settings and initializes private service attributes
     *
     * @param array $settings Service settings from service.ini
     */
    private function setupServiceAttributes($settings)
    {
        if (array_key_exists('serviceProvider', $settings))
            $this->serviceAttributes['serviceProvider'] = $settings['serviceProvider'];
        if (array_key_exists('service', $settings))
            $this->serviceAttributes['service'] = $settings['service'];
        $this->serviceAttributes['supportedContentSelectionMethods'] = array();
        if (array_key_exists('supportedContentSelectionMethods', $settings))
        {
            if (in_array('OUT_OF_BAND', $settings['supportedContentSelectionMethods']))
                array_push($this->serviceAttributes['supportedContentSelectionMethods'], 'OUT_OF_BAND');
            if (in_array('BROWSE', $settings['supportedContentSelectionMethods']))
                array_push($this->serviceAttributes['supportedContentSelectionMethods'], 'BROWSE');
        }
        if (sizeof($this->serviceAttributes['supportedContentSelectionMethods']) == 0)
        {
            $msg = 'No valid content selection method found in settings, using default OUT_OF_BAND';
            $this->logger->error($msg);
            array_push($this->serviceAttributes['supportedContentSelectionMethods'], 'OUT_OF_BAND');
        }
        $this->serviceAttributes['supportedOptionalOperations'] = array();
        if (array_key_exists('supportedOptionalOperations', $settings))
        {
            if (in_array('SERVICE_ANNOUNCEMENTS', $settings['supportedOptionalOperations']))
                array_push($this->serviceAttributes['supportedOptionalOperations'], 'SERVICE_ANNOUNCEMENTS');
            if (in_array('SET_BOOKMARKS', $settings['supportedOptionalOperations']))
                array_push($this->serviceAttributes['supportedOptionalOperations'], 'SET_BOOKMARKS');
            if (in_array('GET_BOOKMARKS', $settings['supportedOptionalOperations']))
                array_push($this->serviceAttributes['supportedOptionalOperations'], 'GET_BOOKMARKS');
            if (in_array('DYNAMIC_MENUS', $settings['supportedOptionalOperations']))
                array_push($this->serviceAttributes['supportedOptionalOperations'], 'DYNAMIC_MENUS');
            if (in_array('PDTB2_KEY_PROVISION', $settings['supportedOptionalOperations']))
                array_push($this->serviceAttributes['supportedOptionalOperations'], 'PDTB2_KEY_PROVISION');
        }
        $this->serviceAttributes['supportsServerSideBack'] = false;
        if (array_key_exists('supportsServerSideBack', $settings))
        {
            if (in_array('DYNAMIC_MENUS', $this->serviceAttributes['supportedOptionalOperations']))
                $this->serviceAttributes['supportsServerSideBack'] = true;
            else
            {
                $msg = "Reserved parameter 'search' supported in settings but DYNAMIC_MENUS not supported";
                $this->logger->warn($msg);
            }
        }
        $this->serviceAttributes['supportsSearch'] = false;
        if (array_key_exists('supportsSearch', $settings))
        {
            if (in_array('DYNAMIC_MENUS', $this->serviceAttributes['supportedOptionalOperations']))
                $this->serviceAttributes['supportsSearch'] = true;
            else
            {
                $msg = "Reserved parameter 'back' supported in settings but DYNAMIC_MENUS not supported";
                $this->logger->warn($msg);
            }
        }
        $this->serviceAttributes['supportedUplinkAudioCodecs'] = array();
        if (array_key_exists('supportedUplinkAudioCodecs', $settings))
        {
            if (in_array('DYNAMIC_MENUS', $this->serviceAttributes['supportedOptionalOperations']))
                $this->serviceAttributes['supportedUplinkAudioCodecs'] = $settings['supportedUplinkAudioCodecs'];
            else
            {
                $msg = "Uplink audio codes specified in settings but DYNAMIC_MENUS not supported";
                $this->logger->warn($msg);
            }
        }
        $this->serviceAttributes['supportsAudioLabels'] = false;
        if (array_key_exists('supportsAudioLabels', $settings))
            $this->serviceAttributes['supportsAudioLabels'] = true;
    }

    /**
     * Service helper
     *
     * Parses adapter settings and initializes adapter
     *
     * @param array $settings Adapter settings from service.ini
     */
    private function setupAdapter($settings)
    {
        if (!array_key_exists('name', $settings))
        {
            $this->logger->fatal('No adapter specified in settings file');
            die('No adapter specified in settings file');
        }
        $adapterClass = $settings['name'];

        if (array_key_exists('path', $settings))
        {
            $path = $settings['path'];
            $this->includeAdapter($path, $adapterClass);
        }

        $path = realpath(dirname(__FILE__)) . '/adapter';
        $this->includeAdapter($path, $adapterClass);

        if (!class_exists($adapterClass))
        {
            $this->logger->fatal("Could not find adapter class '$adapterClass'");
            die('Adapter class not found, please make sure adapter path is set');
        }
        $this->adapter = new $adapterClass;
    }

    /**
     * Service helper
     *
     * Search path for a file which name is the value of parameter name and has the
     * extension .class.php or .php. If file exists, append path to include paths and include
     * the file.
     *
     * @param string $path Path in which to look for an adapter file
     * @param string $name Name of the adapter to serach for
     */
    private function includeAdapter($path, $name)
    {
        if (!is_dir($path))
        {
            $this->logger-warn("Adapter path '$path' does not exists");
            return;
        }

        $file = "$path/$name.class.php";
        if (file_exists($file))
        {
            set_include_path(get_include_path() . PATH_SEPARATOR . $path);
            require_once($file);
            $this->adapterIncludeFile = $file;
            return;
        }

        $file = "$path/$name.php";
        if (file_exists($file))
        {
            set_include_path(get_include_path() . PATH_SEPARATOR . $path);
            require_once($file);
            $this->adapterIncludeFile = $file;
            return;
        }
    }

    /**
     * Session function sessionHandle, control requests to service operations
     * @param string $operation, name of the invoked operation
     * @return SoapFault when necessary
     */
    private function sessionHandle($operation)
    {
        if ($this->sessionHandleDisabled)
        {
            $this->logger->warn('Session handling disabled');
            return;
        }

        $this->logger->info("Operation '$operation' invoked");
        // session_start() is handled in service.php by SOAP_PERSISTENCE_SESSION

        // store current invoked operation in stack and variable
        $this->sessionCurrentOperation = $operation;
        array_push($this->sessionInvokedOperations, $operation);

        // when logOn is invoked, initialization sequence must be completed
        if ($operation == 'logOn')
        {
            // new session must be establish every time logOn is invoked
            $this->sessionDestroy();

            // clear session initialization stack
            $this->sessionInitializationStack = array();

            // push setReadingSystemAttributes and getServiceAttributes to stack
            array_push($this->sessionInitializationStack, 'setReadingSystemAttributes');
            array_push($this->sessionInitializationStack, 'getServiceAttributes');

            return;
        }

        // 4.2.1 logOff operation may be invoked anywhere within the initialization sequence
        if ($operation == 'logOff')
        {
            $this->sessionDestroy();
            return;
        }

        // client must send HTTP Cookies in requests
        if ($this->sessionHandleCookieDisabled)
        {
            $this->logger->warn('Cookie check in session handle disabled');
        }
        else if (!isset($_COOKIE['PHPSESSID']))
        {
            $msg = 'No cookie found in request';
            $this->logger->warn($msg);
            $this->sessionDestroy();
            $faultString = 'No cookie found in request, try initializing session again';
            throw new SoapFault ('Client', $faultString, '', '', $operation.'_noActiveSessionFault');
        }

        // a user must have successfully logged on at this point
        if ($this->sessionUserLoggedOn === false)
        {
            $msg = 'No user has logged on yet';
            $this->logger->warn($msg);
            $this->sessionDestroy();
            $faultString = 'No user has logged on yet, try initializing session again';
            throw new SoapFault ('Client', $faultString, '', '', $operation.'_noActiveSessionFault');
        }

        // if we are still in session initialization phase
        if (sizeof($this->sessionInitializationStack) > 0)
        {
            // next operation to be invoked
            $nextRequiredOperation = array_pop($this->sessionInitializationStack);

            if ($operation != $nextRequiredOperation)
            {
                $msg = "Expected a call to operation $nextRequiredOperation";
                $this->logger->warn($msg);
                $this->sessionDestroy();
                $faultString = "Expected a call to operation $nextRequiredOperation, try initializing session again";
                throw new SoapFault ('Client', $faultString, '', '', $operation.'_invalidOperationFault');
            }

            return;
        }

        // if session has been established
        if ($this->sessionEstablished === true)
        {
            if ($this->adapter->startSession() === false)
            {
                $this->logger->warn("Backend session not active anymore");
                $faultString = "Session has expired, try initialization as session again";
                $this->sessionDestroy();
                throw new SoapFault ('Client', $faultString, '', '', $operation.'_noActiveSessionFault');
            }
        }
    }

    /**
     * Session function sessionDestroy, restore private session variables to default values
     */
    private function sessionDestroy()
    {
        // stop backend session
        $this->adapter->stopSession();

        $this->sessionContentMetadataRequests = array();
        $this->sessionUserLoggedOn = false;
        $this->sessionEstablished = false;

        // The following variables must reamin untouched as they are use in logging messages,
        // otherwise some logging messages will be incomplete
        // sessionUsername
        // sessionCurrentOperation
    }

    private function createLabel($labelArray)
    {
        $text = null;
        $audio = null;
        $lang = null;
        $dir = null;

        // text [mandatory]
        if (array_key_exists('text', $labelArray) === false)
            $this->logger->error("Required field 'text' is missing in label");
        else
            $text = $labelArray['text'];

        // audio [optional]
        if (array_key_exists('audio', $labelArray) && is_array($labelArray['audio']))
            $audio = $this->createAudio($labelArray['audio']);

        // lang [mandatory]
        if (array_key_exists('lang', $labelArray) === false)
            $this->logger->error("Required field 'lang' is missing in label");
        else
            $lang = $labelArray['lang'];

        if (array_key_exists('dir', $labelArray))
            $dir = $labelArray['dir'];

        $label = new label($text, $audio, $lang, $dir);
        return $label;
    }

    private function createAudio($audioArray)
    {
        $uri = null;
        $rangeBegin = null;
        $rangeEnd = null;
        $size = null;

        // uri [mandatory]
        if (array_key_exists('uri', $audioArray) === false)
            $this->logger->error("Required field 'uri' is missing in audio");
        else
            $uri = $audioArray['uri'];

        // rangeBegin [optional]
        if (array_key_exists('rangeBegin', $audioArray))
            $rangeBegin = $audioArray['rangeBegin'];

        // rangeEnd [optional]
        if (array_key_exists('rangeEnd', $audioArray))
            $rangeEnd = $audioArray['rangeEnd'];

        // size [optional]
        if (array_key_exists('size', $audioArray))
            $size = $audioArray['size'];

        $audio = new audio($uri, $rangeBegin, $rangeEnd, $size);
        return $audio;
    }

    private function createResource($resourceArray)
    {
        $uri = null;
        $mimeType = null;
        $size = null;
        $localURI = null;
        $lastModifiedDate = null;

        // uri [mandatory]
        if (array_key_exists('uri', $resourceArray) === false)
            $this->logger->error("Required field 'uri' is missing in resource");
        else
            $uri = $resourceArray['uri'];

        // mimeType [mandatory]
        if (array_key_exists('mimeType', $resourceArray) === false)
            $this->logger->error("Required field 'mimeType' is missing in resource");
        else
            $mimeType = $resourceArray['mimeType'];

        // size [mandatory]
        if (array_key_exists('size', $resourceArray) === false)
            $this->logger->error("Required field 'size' is missing in resource");
        else
            $size = (int)$resourceArray['size'];

        // localURI [mandatory]
        if (array_key_exists('localURI', $resourceArray) === false)
            $this->logger->error("Required field 'localURI' is missing in resource");
        else
            $localURI = $resourceArray['localURI'];

        // lastModifiedDate [optional]
        if (array_key_exists('lastModifiedDate', $resourceArray))
            $lastModifiedDate = $resourceArray['lastModifiedDate'];

        $resource = new resource($uri, $mimeType, $size, $localURI, $lastModifiedDate);
        return $resource;
    }

    /**
     * Client function getClientIP
     * @return string
     */
    private function getClientIP()
    {
        $ip = '0.0.0.0';
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ip = trim($_SERVER['HTTP_X_FORWARDED_FOR']);
        else if (isset($_SERVER['REMOTE_ADDR']))
            $ip = trim($_SERVER['REMOTE_ADDR']);
        return $ip;
    }

    /**
     * Client function getClientSupportedContentFormats
     * @return null or array of strings
     */
    private function getClientSupportedContentFormats()
    {
        if (is_null($this->readingSystemAttributes))
            return null;

        if (is_a($this->readingSystemAttributes, 'readingSystemAttributes') === false)
            return null;

        $contentFormat = $this->readingSystemAttributes->config->supportedContentFormats->contentFormat;
        if (is_null($contentFormat))
            return null;

        return $contentFormat;
    }

    /**
     * Client function getClientSupportedProtectionFormats
     * @return null or array of strings
     */
    private function getClientSupportedProtectionFormats()
    {
        if (is_null($this->readingSystemAttributes))
            return null;

        if (is_a($this->readingSystemAttributes, 'readingSystemAttributes') === false)
            return null;

        $protectionFormat = $this->readingSystemAttributes->config->supportedContentProtectionFormats->protectionFormat;
        if (is_null($protectionFormat))
            return null;

        return $protectionFormat;
    }

    /**
     * Client function getClientSupportedMimeTypes
     * @return null or array of strings
     */
    private function getClientSupportedMimeTypes()
    {
        if (is_null($this->readingSystemAttributes))
            return null;

        if (is_a($this->readingSystemAttributes, 'readingSystemAttributes') === false)
            return null;

        $mimeType = $this->readingSystemAttributes->config->supportedMimeTypes->mimeType;

        if (is_null($mimeType))
            return null;

        $mimeTypes = array();
        foreach ($mimeType as $mimetype)
            array_push($mimeTypes, $mimetype->type);

        return $mimeTypes;
    }

    /**
     * Client function getClientLangCode
     * @return string
     */
    private function getClientLangCode()
    {
        if (is_null($this->readingSystemAttributes)) return 'en';

        // find put the preferred language
        $preferredUILanguage = $this->readingSystemAttributes->getConfig()->getPreferredUILanguage();

        // en-US will not work, cut out the first two letters, convert to lowercase
        $code = strtolower(substr($preferredUILanguage, 0, 2));
        return $code;
    }
}

?>
