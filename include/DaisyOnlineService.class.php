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

$filePath = dirname(realpath(__FILE__));
set_include_path(get_include_path() . PATH_SEPARATOR . $filePath . '/..');
set_include_path(get_include_path() . PATH_SEPARATOR . $filePath . '/types');

require_once('vendor/autoload.php');

require_once('logOn.class.php');
require_once('logOnResponse.class.php');
require_once('logOff.class.php');
require_once('logOffResponse.class.php');
require_once('getContentList.class.php');
require_once('getContentListResponse.class.php');
require_once('getContentResources.class.php');
require_once('getContentResourcesResponse.class.php');
require_once('getServiceAnnouncements.class.php');
require_once('getServiceAnnouncementsResponse.class.php');
require_once('markAnnouncementsAsRead.class.php');
require_once('markAnnouncementsAsReadResponse.class.php');
require_once('updateBookmarks.class.php');
require_once('updateBookmarksResponse.class.php');
require_once('getBookmarks.class.php');
require_once('getBookmarksResponse.class.php');
require_once('returnContent.class.php');
require_once('returnContentResponse.class.php');
require_once('getQuestions.class.php');
require_once('getQuestionsResponse.class.php');
require_once('getKeyExchangeObject.class.php');
require_once('getKeyExchangeObjectResponse.class.php');
require_once('addContentToBookshelf.class.php');
require_once('addContentToBookshelfResponse.class.php');
require_once('getUserCredentials.class.php');
require_once('getUserCredentialsResponse.class.php');
require_once('getTermsOfService.class.php');
require_once('getTermsOfServiceResponse.class.php');
require_once('acceptTermsOfService.class.php');
require_once('acceptTermsOfServiceResponse.class.php');
require_once('setProgressState.class.php');
require_once('setProgressStateResponse.class.php');

class DaisyOnlineService
{
    const VERSION = '0.2.2';

    private $optionalOperations = array();
    private $serviceAttributes = array();
    private $readingSystemAttributes = null;

    // operation currently invoked by client
    private $sessionCurrentOperation;

    // stack containing invoked operations
    private $sessionInvokedOperations = array();

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

    // boolean indicating if a call to getServieAnnouncements has been made
    private $sessionGetServiceAnnouncementsInvoked = false;

    // integer indicating protocol version (values are either 1 or 2)
    private $sessionProtocolVersion = null;

    // boolean indicating if terms of service are accepted
    private $sessionTermsOfServiceAccepted = null;

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
        array_push($instance_variables_to_serialize, 'optionalOperations');
        array_push($instance_variables_to_serialize, 'serviceAttributes');
        array_push($instance_variables_to_serialize, 'readingSystemAttributes');
        array_push($instance_variables_to_serialize, 'sessionCurrentOperation');
        array_push($instance_variables_to_serialize, 'sessionInvokedOperations');
        array_push($instance_variables_to_serialize, 'sessionContentMetadataRequests');
        array_push($instance_variables_to_serialize, 'sessionUserLoggedOn');
        array_push($instance_variables_to_serialize, 'sessionEstablished');
        array_push($instance_variables_to_serialize, 'sessionUsername');
        array_push($instance_variables_to_serialize, 'sessionGetServiceAnnouncementsInvoked');
        array_push($instance_variables_to_serialize, 'sessionProtocolVersion');
        array_push($instance_variables_to_serialize, 'sessionTermsOfServiceAccepted');
        array_push($instance_variables_to_serialize, 'adapter');
        array_push($instance_variables_to_serialize, 'adapterIncludeFile');
        return $instance_variables_to_serialize;
    }

    /**
     * Returns the current version
     */
    public static function getVersion()
    {
        return self::VERSION;
    }

    /**
     * Returns supportedOptionalOperations
     */
    public function getServiceSupportedOptionalOperations()
    {
        if (is_null($this->serviceAttributes) === false && array_key_exists('supportedOptionalOperations', $this->serviceAttributes))
            return $this->serviceAttributes['supportedOptionalOperations'];

        return array();
    }

    /**
     * Returns supportedOptionalOperations not listed in service attributes
     */
    public function getServiceSupportedOptionalOperationsExtra()
    {
        if (is_null($this->optionalOperations) === false && is_array($this->optionalOperations))
            return $this->optionalOperations;

        return array();
    }

    /**
     * Sets the protocol version to the user defined version.
     *
     * Warning! Do not invoke this function unless you are testing or debugging this class.
     */
    public function setProtocolVersion($version = 2)
    {
        if (is_int($version) && ($version == 1 || $version == 2))
            $this->sessionProtocolVersion = $version;
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
    public static function getServiceBaseUri($allowencrypted = false)
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
            throw new SoapFault('Client', $input->getError(), '', '', 'logOn_invalidParameterFault');
        }

        $username = $input->getUsername();
        $password = $input->getPassword();
        $readingSystemAttributes = $input->getReadingSystemAttributes();

        try
        {
            if ($this->adapter->authenticate($username, $password) === false)
                throw new SoapFault('Server', 'Invalid username or password', '', '', 'logOn_unauthorizedFault');
        }
        catch (AdapterException $e)
        {
            $this->logger->fatal($e->getMessage());
            throw new SoapFault('Server', 'Internal Server Error', '', '', 'logOn_internalServerErrorFault');
        }

        // start backend session
        if ($this->adapter->startSession() === false)
        {
            $this->logger->warn("Backend session not active");
                throw new SoapFault('Server', 'Backend session not active', '', '', 'logOn_internalServerErrorFault');
        }

        // build logOnResponse, i.e serviceAttributes
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
            throw new SoapFault('Server', 'Internal Server Error', '', '', 'logOn_internalServerErrorFault');
        }

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

        // set accessConfig
        $accessConfig = $this->serviceAttributes['accessConfig'];

        // set announcementsPullFrequency
        // TODO: make this configurable in service.ini
        $announcementsPullFrequency = 720;

        // set progressStateOperationAllowed
        // TODO: make this configurable in service.ini
        $progressStateOperationAllowed = false;

        $serviceAttributes = new serviceAttributes(
            $serviceProvider,
            $service,
            $supportsServerSideBack,
            $supportsSearch,
            $supportedUplinkAudioCodecs,
            $supportsAudioLabels,
            $supportedOptionalOperations,
            $accessConfig,
            $announcementsPullFrequency,
            $progressStateOperationAllowed);

        $output = new logOnResponse($serviceAttributes);

        if ($output->validate() === false)
        {
            $msg = "failed to build response " . $output->getError();
            $this->logger->error($msg);
            $faultString = 'logOnResponse could not be built';
            throw new SoapFault('Server', $faultString,'', '', 'logOn_internalServerErrorFault');
        }

        // store user information and reading system attributes
        $this->sessionUsername = $username;
        $this->sessionUserLoggedOn = true;
        $this->sessionEstablished = true;
        $this->readingSystemAttributes = $input->getReadingSystemAttributes();

        $msg = "User '$username' logged on and establised a session";
        $this->logger->info($msg);

        return $output;
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
                    $contentItem = new contentItem();
                    $contentItem->setId($contentId);
                    try
                    {
                        // label [mandatory]
                        $label = $this->adapter->label($contentId, Adapter::LABEL_CONTENTITEM, $this->getClientLangCode());
                        if (is_array($label))
                            $contentItem->setLabel($this->createLabel($label));
                        else
                            $this->logger->warn("Content with id '$contentId' has no label");

                        // sample [optional]
                        $sampleId = $this->adapter->contentSample($contentId);
                        if (is_string($sampleId))
                            $contentItem->setSample(new sample($sampleId));

                        // metadata [mandatory]
                        $metadata = $this->adapter->contentMetadata($contentId);
                        if (is_array($metadata) === false)
                        {
                            $this->logger->warn("Content with id '$contentId' has no metadata");
                            throw new SoapFault('Server', 'Internal Server Error', '', '', 'getContentList_internalServerErrorFault');
                        }
                        $contentItem->setMetadata($this->createMetaData($contentId, $metadata));

                        // categoryLabel [optional]
                        $categoryLabel = $this->adapter->label($contentId, Adapter::LABEL_CATEGORY, $this->getClientLangCode());
                        if (is_array($categoryLabel))
                            $contentItem->setCategoryLabel(new categoryLabel($this->createLabel($categoryLabel)));

                        // subCategoryLabel [optional]
                        $subCategoryLabel = $this->adapter->label($contentId, Adapter::LABEL_SUBCATEGORY, $this->getClientLangCode());
                        if (is_array($subCategoryLabel))
                            $contentItem->setSubCategoryLabel(new subCategoryLabel($this->createLabel($subCategoryLabel)));

                        // accessPermission [mandatory]
                        $accessPermission = $this->adapter->contentAccessMethod($contentId);
                        $contentItem->setAccessPermission($accessPermission);

                        // lastmark [optional]
                        if (in_array('SET_BOOKMARKS', $this->serviceAttributes['supportedOptionalOperations']))
                        {
                            $lastmark = $this->adapter->getBookmarks($contentId, Adapter::BMGET_LASTMARK);
                            if (is_array($lastmark))
                            {
                                // TODO: implement me when getBookmarks operation is implemented
                                $this->logger->warn("please implement me, lastmark not set");
                            }
                        }
                        // multipleChoiceQuestion [optional]
                        if (in_array('DYNAMIC_MENUS', $this->serviceAttributes['supportedOptionalOperations']))
                        {
                            // TODO: implement me when get
                            $question = $this->adapter->menuContentQuestion($contentId);
                            if (is_array($question))
                            {
                                // TODO: implement me when getQuestions operation is implemented
                                $this->logger->warn("please implement me, multipleChoiceQuestion not set");
                            }
                        }

                        // firstAccessDate and lastAccessDate [optional]
                        $accessDates = $this->adapter->contentAccessDate($contentId);
                        if (is_array($accessDates))
                        {
                            // first
                            if (array_key_exists('first', $accessDates) === false)
                                $this->logger->error("Required field 'first' is missing in access date");
                            else
                            {
                                if (is_string($accessDates['first']))
                                    $contentItem->setFirstAccessedDate($accessDates['first']);
                            }
                            // last
                            if (array_key_exists('last', $accessDates) === false)
                                $this->logger->error("Required field 'last' is missing in access date");
                            else
                            {
                                if (is_string($accessDates['last']))
                                    $contentItem->setLastAccessedDate($accessDates['last']);
                            }
                        }
                        // lastModifiedDate [mandatory]
                        $lastModifiedDate = $this->adapter->contentLastModifiedDate($contentId);
                        $contentItem->setLastModifiedDate($lastModifiedDate);

                        // category [optional]
                        $category = $this->adapter->contentCategory($contentId);
                        if (is_string($category))
                            $contentItem->setCategory($category);

                        // subCategory [optional]
                        $subCategory = $this->adapter->contentSubCategory($contentId);
                        if (is_string($subCategory))
                            $contentItem->setSubCategory($subCategory);

                        // returnBy [optional]
                        $returnDate = $this->adapter->contentReturnDate($contentId);
                        if (is_string($returnDate))
                            $contentItem->setReturnBy($returnDate);

                        // hasBookmarks [mandatory] assume no bookmarks
                        $contentItem->setHasBookmarks(false);
                        if (in_array('SET_BOOKMARKS', $this->serviceAttributes['supportedOptionalOperations']))
                        {
                            $bookmarks = $this->adapter->getBookmarks($contentId, Adapter::BMGET_ALL);
                            if (is_array($bookmarks))
                                $contentItem->setHasBookmarks(true);
                        }
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
        $accessType = $input->getAccessType();

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
            // set lastModifiedDate
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
            {
                if (array_key_exists('resourceRef', $resource) === true)
                    $resources->addPackage($this->createPackage($resource));
                else
                    $resources->addResource($this->createResource($resource));
            }

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

        // build announcements
        $announcements = new announcements();

        try
        {
            // build announcement
            $unreadAnnouncements = $this->adapter->announcements();
            foreach ($unreadAnnouncements as $announcementId)
            {
                $info = $this->adapter->announcementInfo($announcementId);
                $label = $this->adapter->label($announcementId, Adapter::LABEL_ANNOUNCEMENT, $this->getClientLangCode());
                $announcements->addAnnouncement($this->createAnnouncement($announcementId, $info, $label));
            }
        }
        catch (AdapterException $e)
        {
            $this->logger->fatal($e->getMessage());
            throw new SoapFault('Server', 'Internal Server Error', '', '', 'getServiceAnnouncements_internalServerErrorFault');
        }

        $output = new getServiceAnnouncementsResponse($announcements);

        if ($output->validate() === false)
        {
            $msg = "failed to build response " . $output->getError();
            $this->logger->error($msg);
            $faultString = 'getServiceAnnouncements could not be built';
            throw new SoapFault('Server', $faultString, '', '', 'getServiceAnnouncements_internalServerErrorFault');
        }

        // mark call to getServiceAnnouncemets as completed
        $this->sessionGetServiceAnnouncementsInvoked = true;

        return $output;
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

        // check if a prior call to getServiceAnnouncements has been made
        if (!$this->sessionHandleDisabled && $this->sessionGetServiceAnnouncementsInvoked === false)
        {
            $this->logger->warn("No prior call to getServiceAnnouncements");
            $faultString = "No previous call to getServiceAnnouncements operation within this session";
            throw new SoapFault('Client', $faultString, '', '', 'markAnnouncementsAsRead_invalidOperationFault');
        }

        if ($input->validate() === false)
        {
            $msg = "request is not valid " . $input->getError();
            $this->logger->warn($msg);
            throw new SoapFault ('Client', $input->getError(), '', '', 'markAnnouncementsAsRead_invalidParameterFault');
        }

        // parameters
        $read = $input->getRead();

        // check if the requested announcements exists
        try
        {
            foreach ($read->item as $announcementId)
            {
                if ($this->adapter->announcementExists($announcementId) === false)
                {
                    $msg = "User '$this->sessionUsername' requested mark announcement as read for a nonexistent announcement '$announcementId'";
                    $this->logger->warn($msg);
                    $faultString = "announcement '$announcementId' does not exist";
                    throw new SoapFault('Client', $faultString,'', '', 'markAnnouncementsAsRead_invalidParameterFault');
                }
            }
        }
        catch (AdapterException $e)
        {
            $this->logger->fatal($e->getMessage());
            throw new SoapFault('Server', 'Internal Server Error', '', '', 'markAnnouncementsAsRead_internalServerErrorFault');
        }

        // mark announcements as read
        try
        {
            foreach ($read->item as $announcementId)
            {
                if ($this->adapter->announcementRead($announcementId) === false) {
                    $msg = "User '$this->sessionUsername' unsuccessfully marked announcement '$announcementId' as read";
                    $this->logger->warn($msg);
                    $faultString = "announcement '$announcementId' could not be marked as read";
                    throw new SoapFault ('Client', $faultString, '', '', 'markAnnouncementsAsRead_invalidParameterFault');
                }
            }
        }
        catch (AdapterException $e)
        {
            $this->logger->fatal($e->getMessage());
            throw new SoapFault('Server', 'Internal Server Error', '', '', 'markAnnouncementsAsRead_internalServerErrorFault');
        }

        return new markAnnouncementsAsReadResponse(true);
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
     * Service function updateBookmarks
     * @param object of updateBookmarks $input
     * @return object of setBookmarksResponse
     */
    public function updateBookmarks($input)
    {
        $this->sessionHandle(__FUNCTION__);
        if (!in_array('SET_BOOKMARKS', $this->serviceAttributes['supportedOptionalOperations']))
            throw new SoapFault ('Client', 'updateBookmarks not supported', '', '', 'updateBookmarks_operationNotSupportedFault');

        if ($input->validate() === false)
        {
            $msg = "request is not valid " . $input->getError();
            $this->logger->warn($msg);
            throw new SoapFault ('Client', $input->getError(), '', '', 'updateBookmarks_invalidParameterFault');
        }

        // parameters
        $contentId = $input->getContentID();
        $action = $this->adapterSetBookmarksAction($input->getAction());
        $bookmarkObject = $input->getBookmarkObject();
        $bookmarkSet = json_encode($bookmarkObject->bookmarkSet);

        try
        {
            $result = $this->adapter->setBookmarks($contentId, $bookmarkSet, $action, $bookmarkObject->lastModifiedDate);
            if ($result === false)
            {
                $msg = "User '$this->sessionUsername' could not set bookmarks for content '$contentId'";
                $this->logger->warn($msg);
            }
        }
        catch (AdapterException $e)
        {
            $this->logger->fatal($e->getMessage());
            throw new SoapFault('Server', 'Internal Server Error', '', '', 'updateBookmarks_internalServerErrorFault');
        }

        return new updateBookmarksResponse($result);
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

        if ($input->validate() === false)
        {
            $msg = "request is not valid " . $input->getError();
            $this->logger->warn($msg);
            throw new SoapFault ('Client', $input->getError(), '', '', 'getBookmarks_invalidParameterFault');
        }

        // parameters
        $contentId = $input->getContentID();
        $action = null; // only available in protocol version 2
        if ($this->protocolVersion() == 2) $action = $this->adapterGetBookmarksAction($input->getAction());

        try
        {
            $bookmarks = $this->adapter->getBookmarks($contentId, $action);
            if ($bookmarks === false)
            {
                $msg = "No bookmarks found for user '$this->sessionUsername' and content '$contentId'";
                $this->logger->warn($msg);
                throw new SoapFault('Client', 'no bookmarks found for the given content id', '', '', 'getBookmarks_invalidParameterFault');
            }
        }
        catch (AdapterException $e)
        {
            $this->logger->fatal($e->getMessage());
            throw new SoapFault('Server', 'Internal Server Error', '', '', 'getBookmarks_internalServerErrorFault');
        }

        // build response according to protocol version
        require_once('bookmarkSet_serialize.php');
        $bookmarkSet = bookmarkSet_from_json($bookmarks['bookmarkSet']);
        if ($this->protocolVersion() == 1)
        {
            $output = new getBookmarksResponse($bookmarkSet);
        }
        else if ($this->protocolVersion() == 2 )
        {
            $bookmarkObject = new bookmarkObject($bookmarkSet);
            if (array_key_exists('lastModifiedDate', $bookmarks))
                $bookmarkObject->setLastModifiedDate($bookmarks['lastModifiedDate']);
            $output = new getBookmarksResponse($bookmarkObject);
        }

        if ($output->validate() === false)
        {
            $msg = "failed to build response " . $output->getError();
            $this->logger->error($msg);
            $faultString = 'getBookmarks could not be built';
            throw new SoapFault('Server', $faultString, '', '', 'getBookmarks_internalServerErrorFault');
        }

        return $output;
    }

    /**
     * Service function addContentToBookshelf
     * @param object of addContentToBookshelf $input
     * @return object of addContentToBookshelfResponse
     */
    public function addContentToBookshelf($input)
    {
        $this->sessionHandle(__FUNCTION__);
        if (!in_array('ADD_CONTENT', $this->optionalOperations))
            throw new SoapFault ('Client', 'addContentToBookshelf not supported', '', '', 'addContentToBookshelf_operationNotSupportedFault');

        if ($input->validate() === false)
        {
            $msg = "request is not valid " . $input->getError();
            $this->logger->warn($msg);
            throw new SoapFault ('Client', $input->getError(), '', '', 'addContentToBookshelf_invalidParameterFault');
        }

        // parameters
        $contentId = $input->getContentID();

        // check if the requested content exists and is accessible
        try
        {
            if ($this->adapter->contentExists($contentId) === false)
            {
                $msg = "User '$this->sessionUsername' tried to add an nonexistent content '$contentId' to bookshlf";
                $this->logger->warn($msg);
                $faultString = "content '$contentId' does not exist";
                throw new SoapFault('Client', $faultString,'', '', 'addContentToBookshelf_invalidParameterFault');
            }

            if ($this->adapter->contentAccessible($contentId) === false)
            {
                $msg = "User '$this->sessionUsername' tried to add an inaccessible content '$contentId' to bookshelf";
                $this->logger->warn($msg);
                $faultString = "content '$contentId' not accessible";
                throw new SoapFault('Client', $faultString,'', '', 'addContentToBookshelf_invalidParameterFault');
            }
        }
        catch (AdapterException $e)
        {
            $this->logger->fatal($e->getMessage());
            throw new SoapFault('Server', 'Internal Server Error', '', '', 'addContentToBookshelf_internalServerErrorFault');
        }

        // add content to bookshelf
        try
        {
            if ($this->adapter->contentAddBookshelf($contentId) === false)
            {
                $msg = "User '$this->sessionUsername' could not add content '$contentId' to bookshelf";
                $this->logger->warn($msg);
                throw new SoapFault('Client', 'content could not be added to user bookshelf', '', '', 'addContentToBookshelf_invalidParameterFault');
            }
        }
        catch (AdapterException $e)
        {
            $this->logger->fatal($e->getMessage());
            throw new SoapFault('Server', 'Internal Server Error', '', '', 'addContentToBookshelf_internalServerErrorFault');
        }

        return new addContentToBookshelfResponse(true);
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
     * Service function getUserCredentials
     * @param object of getUserCredentials $input
     * @return object of getUserCredentialsResponse
     */
    public function getUserCredentials($input)
    {
        if (!in_array('USER_CREDENTIALS', $this->optionalOperations))
            throw new SoapFault ('Client', 'getUserCredentials not supported', '', '', 'getUserCredentials_operationNotSupportedFault');

        if ($input->validate() === false)
        {
            $msg = "request is not valid " . $input->getError();
            $this->logger->warn($msg);
            throw new SoapFault ('Client', $input->getError(), '', '', 'getUserCredentials_invalidParameterFault');
        }

        try
        {
            $manufacturer = $input->getReadingSystemAttributes()->getManufacturer();
            $model = $input->getReadingSystemAttributes()->getModel();
            $serialNumber = $input->getReadingSystemAttributes()->getSerialNumber();
            $version = $input->getReadingSystemAttributes()->getVersion();
            $credentials = $this->adapter->userCredentials($manufacturer, $model, $serialNumber, $version);
            if ($credentials === false)
            {
                $msg = "No credentials found for reading system with serial '$serialNumber'";
                $this->logger->warn($msg);
                throw new SoapFault('Client', 'no credentials found for the given serial', '', '', 'getUserCredentials_invalidParameterFault');
            }
        }
        catch (AdapterException $e)
        {
            $this->logger->fatal($e->getMessage());
            throw new SoapFault('Server', 'Internal Server Error', '', '', 'getUserCredentials_internalServerErrorFault');
        }

        $output = new getUserCredentialsResponse(new credentials($credentials['username'],$credentials['password'],'RSAES-OAEP'));

        if ($output->validate() === false)
        {
            $msg = "failed to build response " . $output->getError();
            $this->logger->error($msg);
            $faultString = 'getUserCredentialsResponse could not be built';
            throw new SoapFault('Server', $faultString, '', '', 'getUserCredentials_internalServerErrorFault');
        }

        return $output;
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
     * Service function getTermsOfService
     * @param object of getTermsOfService $input
     * @return object of getTermsOfServiceResponse
     */
    public function getTermsOfService($input)
    {
        $this->sessionHandle(__FUNCTION__);
        if (!in_array('TERMS_OF_SERVICE', $this->optionalOperations))
            throw new SoapFault ('Client', 'getTermsOfService not supported', '', '', 'getTermsOfService_operationNotSupportedFault');

        // get terms
        try
        {
            $result = $this->adapter->termsOfService();
        }
        catch (AdapterException $e)
        {
            $this->logger->fatal($e->getMessage());
            throw new SoapFault('Server', 'Internal Server Error', '', '', 'getTermsOfService_internalServerErrorFault');
        }

        // build response
        $label = $this->createLabel($result);
        $output = new getTermsOfServiceResponse($label);

        if ($output->validate() === false)
        {
            $msg = "failed to build response " . $output->getError();
            $this->logger->error($msg);
            $faultString = 'getTermsOfServiceResponse could not be built';
            throw new SoapFault('Server', $faultString, '', '', 'getTermsOfService_internalServerErrorFault');
        }

        return $output;
    }

    /**
     * Service function acceptTermsOfService
     * @param object of acceptTermsOfService $input
     * @return object of acceptTermsOfServiceResponse
     */
    public function acceptTermsOfService($input)
    {
        $this->sessionHandle(__FUNCTION__);
        if (!in_array('TERMS_OF_SERVICE', $this->optionalOperations))
            throw new SoapFault ('Client', 'acceptTermsOfService not supported', '', '', 'acceptTermsOfService_operationNotSupportedFault');

        // accept terms
        $result = false;
        try
        {
            $result = $this->adapter->termsOfServiceAccept();
        }
        catch (AdapterException $e)
        {
            $this->logger->fatal($e->getMessage());
            throw new SoapFault('Server', 'Internal Server Error', '', '', 'acceptTermsOfService_internalServerErrorFault');
        }
        if ($result === true)
        {
            $this->sessionTermsOfServiceAccepted = true;
        }

        return new acceptTermsOfServiceResponse($result);
    }

    /**
     * Service function setProgressState
     * @param object of setProgressState $input
     * @return object of setProgressStateResponse
     */
    public function setProgressState($input)
    {
        $this->sessionHandle(__FUNCTION__);
        if (!in_array('PROGRESS_STATE', $this->optionalOperations))
            throw new SoapFault ('Client', 'setProgressState not supported', '', '', 'setProgressState_operationNotSupportedFault');

        if ($input->validate() === false)
        {
            $msg = "request is not valid " . $input->getError();
            $this->logger->warn($msg);
            throw new SoapFault ('Client', $input->getError(), '', '', 'setProgressState_invalidParameterFault');
        }

        // parameters
        $contentId = $input->getContentID();
        $state = $this->adapterProgressState($input->getState());

        // set progress state
        $result = false;
        try
        {
            $result = $this->adapter->contentAccessState($contentId, $state);
        }
        catch (AdapterException $e)
        {
            $this->logger->fatal($e->getMessage());
            throw new SoapFault('Server', 'Internal Server Error', '', '', 'setProgressState_internalServerErrorFault');
        }

        return new setProgressStateResponse($result);
    }

    /**
     * Returns the state enum defined in Adatapter for the string representation
     * @param string $state human readable state string
     * @return string
     */
    private function adapterProgressState($state)
    {
        switch ($state)
        {
            case 'START':
                return Adapter::STATE_START;
            case 'PAUSE':
                return Adapter::STATE_PAUSE;
            case 'RESUME':
                return Adapter::STATE_RESUME;
            case 'FINISH':
                return Adapter::STATE_FINISH;
        }

        // not possible
        return 0;
    }

    /**
     * Returns the action enum defined in Adapter for the string representation
     * @param string $state human readable state string
     * @return int
     */
    private function adapterGetBookmarksAction($action)
    {
        switch ($action)
        {
            case 'LASTMARK':
                return Adapter::BMGET_LASTMARK;
            case 'HILITE':
                return Adapter::BMGET_HILITE;
            case 'BOOKMARK':
                return Adapter::BMGET_BOOKMARK;
            case 'ALL':
                return Adapter::BMGET_ALL;
        }

        // not possible
        return 0;
    }

    /**
     * Returns the action enum defined in Adapter for the string representation
     * @param string $state human readable state string
     * @return int
     */
     private function adapterSetBookmarksAction($action)
     {
        switch ($action)
        {
            case 'REPLACE_ALL':
                return Adapter::BMSET_REPLACE;
            case 'ADD':
                return Adapter::BMSET_ADD;
            case 'REMOVE':
                return Adapter::BMSET_REMOVE;
        }

        // not possible
        return 0;
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
        // list of optional operation which should be listed in service attributes
        if (array_key_exists('supportedOptionalOperationsExtra', $settings))
        {
            if (in_array('PROGRESS_STATE', $settings['supportedOptionalOperationsExtra']))
                array_push($this->optionalOperations, 'PROGRESS_STATE');
            if (in_array('TERMS_OF_SERVICE', $settings['supportedOptionalOperationsExtra']))
                array_push($this->optionalOperations, 'TERMS_OF_SERVICE');
            if (in_array('USER_CREDENTIALS', $settings['supportedOptionalOperationsExtra']))
                array_push($this->optionalOperations, 'USER_CREDENTIALS');
            if (in_array('ADD_CONTENT', $settings['supportedOptionalOperationsExtra']))
                array_push($this->optionalOperations, 'ADD_CONTENT');
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
        $this->serviceAttributes['accessConfig'] = 'STREAM_AND_DOWNLOAD';
        if (array_key_exists('accessConfig', $settings))
        {
            $allowedValues = array('STREAM_ONLY', 'DOWNLOAD_ONLY', 'STREAM_AND_DOWNLOAD', 'STREAM_AND_RESTRICTED_DOWNLOAD', 'RESTRICTED_DOWNLOAD_ONLY');
            if (in_array($settings['accessConfig'], $allowedValues))
                $this->serviceAttributes['accessConfig'] = $settings['accessConfig'];
            else
            {
                $msg = "No valid access config found, defaulting to STREAM_AND_DOWNLOAD";
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

        if ($operation == 'logOn')
        {
            // new session must be establish every time logOn is invoked
            $this->sessionDestroy();
            return;
        }

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

        // if session has been established
        if ($this->sessionEstablished === true)
        {
            if ($this->adapter->startSession() === false)
            {
                $this->logger->warn("Backend session not active anymore");
                $faultString = "Session has expired, try initializing session again";
                $this->sessionDestroy();
                throw new SoapFault ('Client', $faultString, '', '', $operation.'_noActiveSessionFault');
            }
        }

        // if terms of service are accepted
        if (in_array('TERMS_OF_SERVICE', $this->optionalOperations))
        {
            if (is_null($this->sessionTermsOfServiceAccepted))
            {
                try
                {
                    $this->sessionTermsOfServiceAccepted = $this->adapter->termsOfServiceAccepted();
                }
                catch (AdapterException $e)
                {
                    $this->logger->fatal($e->getMessage());
                    throw new SoapFault('Server', 'Internal Server Error', '', '', $operation.'_internalServerErrorFault');
                }
            }

            if ($operation == 'getTermsOfService' || $operation == 'acceptTermsOfService')
            {
                // we don't want to return a soap fault if terms of service operations
                // are invoked
                return;
            }

            if ($this->sessionTermsOfServiceAccepted != true) {
                $this->logger->warn("Terms of Service not accepted");
                $faultString = "Terms of Service have not been accepted";
                throw new SoapFault ('Client', $faultString, '', '', $operation.'_termsOfServiceNotAcceptedFault');
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
        $this->sessionGetServiceAnnouncementsInvoked = false;
        $this->sessionProtocolVersion = null;

        // The following variables must reamin untouched as they are use in logging messages,
        // otherwise some logging messages will be incomplete
        // sessionUsername
        // sessionCurrentOperation
    }

    /**
     * Returns the current protocol version used by the client.
     */
    private function protocolVersion()
    {
        if (!is_null($this->sessionProtocolVersion))
            return $this->sessionProtocolVersion;
        else
        {
            if (in_array('getServiceAttributes', $this->sessionInvokedOperations))
                return 1;
        }

        return 2;
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

    private function createMetaData($contentId, $metadataValues)
    {
        // build metadata
        $metadata = new metadata();
        $metadata->setIdentifier($contentId);
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
                // 7.31 The value of the Dublin Core identifier element must match the Content Identifier of the Content item.
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
        return $metadata;
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
        $serverSideHash = null;

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

        // lastModifiedDate [mandatory]
        if (array_key_exists('lastModifiedDate', $resourceArray) === false)
            $this->logger->error("Required field 'lastModifiedDate' is missing in resource");
        else
            $lastModifiedDate = $resourceArray['lastModifiedDate'];

        // serverSideHash [optional]
        if (array_key_exists('serverSideHash', $resourceArray) === true)
            $serverSideHash = $resourceArray['serverSideHash'];


        $resource = new resource($uri, $mimeType, $size, $localURI, $lastModifiedDate, $serverSideHash);
        return $resource;
    }

    private function createPackage($resourceArray)
    {
        $resourceRef = array();
        $uri = null;
        $mimeType = null;
        $size = null;
        $lastModifiedDate = null;

        // resourceRef [mandatory]
        if (array_key_exists('resourceRef', $resourceArray) === false)
            $this->logger->error("Required field 'resourceRef' is missing in resource");
        else
        {
            if (is_array($resourceArray['resourceRef']) === false)
                $this->logger->error("Field 'resourceRef' is not an array");
            else
            {
                foreach ($resourceArray['resourceRef'] as $localURI)
                    array_push($resourceRef, new resourceRef($localURI));
            }
        }

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

        // lastModifiedDate [mandatory]
        if (array_key_exists('lastModifiedDate', $resourceArray) === false)
            $this->logger->error("Required field 'lastModifiedDate' is missing in resource");
        else
            $lastModifiedDate = $resourceArray['lastModifiedDate'];

        $package = new package($resourceRef, $uri, $mimeType, $size, $lastModifiedDate);
        return $package;
    }

    private function createAnnouncement($announcementId, $announcementArray, $labelArray)
    {
        $label = null;
        $id = null;
        $type = null;
        $priority = null;

        // label [mandatory]
        if (is_array($labelArray))
            $label = $this->createLabel($labelArray);

        // id [mandatory]
        $id = $announcementId;

        if (is_array($announcementArray)) {
            // type [optional]
            if (array_key_exists('type', $announcementArray) === true)
            {
                $type = $this->transformAnnouncementType($announcementArray['type']);
            }

            // priority [mandatory (version 2) / optional (version 1)]
            if (array_key_exists('priority', $announcementArray) === false)
            {
                if ($this->protocolVersion() == 2)
                    $this->logger->error("Required field 'priority' is missing in announcement");
            }
            else
                $priority = $this->transformAnnouncementPriority($announcementArray['priority']);
        }

        $announcement = new announcement($label, $id, $type, $priority);
        return $announcement;
    }

    /**
     * Transforms the type value according to protocol version.
     * Supported values in protocol version 1: [WARNING,ERROR,INFORMATION,SYSTEM]
     * Supported values in protocol verison 2: [INFORMATION,SYSTEM]
     */
    private function transformAnnouncementType($value)
    {
        if ($this->protocolVersion() == 2)
        {
            switch ($value)
            {
                case 'WARNING':
                return 'INFORMATION';
                case 'ERROR':
                return 'INFORMATION';
            }
        }

        return $value;
    }

    /**
     * Transforms the priority value according to protocol version.
     * Supported values in protocol version 1: [1,2,3]
     * Supported values in protocol verison 2: [HIGH,MEDIUM,LOW]
     */
     private function transformAnnouncementPriority($value)
     {
        if ($this->protocolVersion() == 1)
        {
            if (is_string($value))
            {
                switch ($value)
                {
                    case 'HIGH':
                    return 1;
                    case 'MEDIUM':
                    return 2;
                    case 'LOW':
                    return 3;
                }
            }
        }
        else if ($this->protocolVersion() == 2)
        {
            if (is_int($value))
            {
                switch ($value)
                {
                    case 1:
                    return 'HIGH';
                    case 2:
                    return 'MEDIUM';
                    case 3:
                    return 'LOW';
                }
            }
        }

        return $value;
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
