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

require_once('ContentHelper.class.php');

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

    // boolean indicating if a user has successfully logged on
    private $sessionUserLoggedOn = false;

    // boolean indicating if a session has been established
    private $sessionEstablished = false;

    // placeholders for storing user information
    private $sessionUserId = null;
    private $sessionUsername = null;
    private $sessionUserLoggingEnabled = false;

    // logger instance
    private $logger = null;

    // database connection handler
    private $dbh = null;

    public function __construct()
    {
        // setup logger
        $this->logger = Logger::getLogger('kolibre.daisyonline.daisyonlineservice');

        // setup database connection
        try
        {
            $this->dbh = new PDO('sqlite:../data/db/demo.db');
            $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch (PDOException $e)
        {
            $this->logger->fatal($e->getMessage());
            die("DB Error: $msg\n");
        }

        // parse settings file
        $settings = parse_ini_file('../service.ini', true);

        // setup service attributes
        if (array_key_exists('Service', $settings))
            $this->setupServiceAttributes($settings['Service']);
        else
        {
            $this->logger->error("Group 'Service' is missing in ini file");
            die("Group 'Service' not found in ini file, please make sure the settings file is correct.");
        }
    }

    /**
     * Invoked when restoring object from session
     */
    public function __wakeup()
    {
        // setup logger
        $this->logger = Logger::getLogger('kolibre.daisyonline.daisyonlineservice');

        // setup database connection
        try
        {
            $this->dbh = new PDO('sqlite:../data/db/demo.db');
            $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch (PDOException $e)
        {
            $this->logger->fatal($e->getMessage());
            die("DB Error: $msg\n");
        }
    }

    /**
     * Invoked when storing object to session
     */
    public function __sleep()
    {
        $instance_variables_to_serialize = array();
        array_push($instance_variables_to_serialize, 'serviceAttributes');
        array_push($instance_variables_to_serialize, 'readingSystemAttributes');
        array_push($instance_variables_to_serialize, 'sessionCurrentOperation');
        array_push($instance_variables_to_serialize, 'sessionInvokedOperations');
        array_push($instance_variables_to_serialize, 'sessionInitializationStack');
        array_push($instance_variables_to_serialize, 'sessionUserLoggedOn');
        array_push($instance_variables_to_serialize, 'sessionEstablished');
        array_push($instance_variables_to_serialize, 'sessionUserId');
        array_push($instance_variables_to_serialize, 'sessionUsername');
        array_push($instance_variables_to_serialize, 'sessionUserLoggingEnabled');
        return $instance_variables_to_serialize;
    }

    /**
     * Log function logRequestAndResponse, log SOAP request and response, invoked from service.php
     * @param string $request, SOAP request
     * @param string $response, SOAP response
     */
    public function logRequestAndResponse($request, $response)
    {
        if ($this->sessionUserLoggingEnabled === false) return;

        try
        {
            $query = 'INSERT INTO userlog VALUES(:user_id, :datetime, :request, :response, :ip)';
            $values = array();
            $values[':user_id'] = $this->sessionUserId;
            $values[':datetime'] = date('Y-m-d H:i:s');
            $values[':request'] = $request;
            $values[':response'] = $response;
            $values[':ip'] = $this->getClientIP();
            $sth = $this->dbh->prepare($query);
            $sth->execute($values);
            $count = $sth->rowCount();
            $msg = "$count line(s) inserted";
            $this->logger->trace($msg);
        }
        catch (PDOException $e)
        {
            $msg = $e->getMessage();
            $this->logger->fatal($msg);
        }
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

        $host = $_SERVER['SERVER_NAME'];

        $port = '';
        switch ($protocol)
        {
            case 'http':
                if (!($_SERVER['SERVER_PORT'] == 80 || $_SERVER['SERVER_PORT'] == 443))
                    $port = $_SERVER['SERVER_PORT'];
                break;
            case 'https':
                if ($_SERVER['SERVER_PORT'] != 443)
                    $port = $_SERVER['SERVER_PORT'];
                break;
        }

        $path = dirname($_SERVER['SCRIPT_NAME']);

        return $protocol.'://'.$host.':'.$port.$path;
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
            $query = 'SELECT rowid, * FROM user WHERE username = :username AND password = :password';
            $sth = $this->dbh->prepare($query);
            $values = array(':username' => $username, ':password' => $password);
            $sth->execute($values);
            $users = $sth->fetchAll(PDO::FETCH_ASSOC);
        }
        catch (PDOException $e)
        {
            $this->logger->fatal($e->getMessage());
            throw new SoapFault('Server', 'Internal Server Error', '', '', 'logOn_internalServerErrorFault');
        }

        if (sizeof($users) == 0)
        {
            $msg = "No user found with username = '$username' and password = <hidden>";
            $this->logger->warn($msg);
            return new logOnResponse(false);
        }
        else if (sizeof($users) > 1)
        {
            $count = sizeof($users);
            $msg = "$count users found with username = '$username' and password = <hidden>";
            $this->logger->error($msg);
            return new logOnResponse(false);
        }

        // store user information
        $this->sessionUserId = $users[0]['rowid'];
        $this->sessionUsername = $username;
        $this->sessionUserLoggedOn = true;
        if ($users[0]['log'] == 1)
            $this->sessionUserLoggingEnabled = true;

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

        // set serviceProvider
        $serviceProvider = null;
        if (array_key_exists('serviceProvider', $this->serviceAttributes))
            $serviceProvider = new serviceProvider(null, $this->serviceAttributes['serviceProvider']);

        // set service
        $service = null;
        if (array_key_exists('service', $this->serviceAttributes))
            $service = new service(null, $this->serviceAttributes['service']);

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

        // check if we support the requested list
        $supportedListIds = ContentHelper::getSupportedContentLists($this->dbh);
        if (!in_array($listId, $supportedListIds))
        {
            $msg = "User '$this->sessionUsername' requested an unsupported content list '$listId'";
            $this->logger->warn($msg);
            $faultString = "contentList '$listId' does not exist";
            throw new SoapFault('Client', $faultString,'', '', 'getContentList_invalidParameterFault');
        }

        // get content list id
        $contentListId = ContentHelper::getContentListId($this->dbh, $listId);
        if ($contentListId === false)
        {
            $msg = "failed to retrieve id for content list '$listId'";
            $this->logger->fatal($msg);
            throw new SoapFault('Server', 'Internal Server Error', '', '', 'getContentList_internalServerErrorFault');
        }

        // fetch content for user
        $unfilteredContent = ContentHelper::getUserContent($this->dbh, $this->sessionUserId, $contentListId);
        if ($unfilteredContent === false)
        {
            $msg = "failed to retrieve '$listId' content for user '$this->sessionUsername'";
            $this->logger->fatal($msg);
            throw new SoapFault('Server', 'Internal Server Error', '', '', 'getContentList_internalServerErrorFault');
        }

        // filter content based on supported content formats
        $msg = "content items before filtering: ".sizeof($unfilteredContent);
        $this->logger->debug($msg);
        $sscf = ContentHelper::getServiceSupportedContentFormats($this->dbh);
        $cscf = $this->getClientSupportedContentFormats();
        $formatFilter = array_intersect_ukey($sscf, $cscf, "strcasecmp");
        $filteredContent = array();
        foreach ($unfilteredContent as $content)
        {
            $contentFormatId = ContentHelper::getDaisyFormatId($this->dbh, $content['rowid']);
            if (!in_array($contentFormatId, $formatFilter))
                continue;
            array_push($filteredContent, $content);
        }
        $msg = "content items after filtering: ".sizeof($filteredContent);
        $this->logger->debug($msg);

        // build contentList
        $contentList = new contentList();
        $contentList->setId($listId);

        // set label
        $langCode = $this->getClientLangCode();
        $description = ContentHelper::getContentListDescription($this->dbh, $listId);
        $contentListLabel = new label($description, null, $langCode);
        $contentList->setLabel($contentListLabel);

        $totalItems = sizeof($filteredContent);
        $firstItem = $input->getFirstItem();
        $lastItem = $input->getLastItem();

        // if firstItem or lastItem is invalid we must return an empty list with totalItems attribute set to
        // total number of items in list, thus we assume list will be empty
        $contentList->setFirstItem(0);
        $contentList->setLastItem(0);
        $contentList->setTotalItems($totalItems);

        // generate content list
        if ($lastItem == -1) $lastItem = $totalItems-1;
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
                for ($i = $firstItem; $i <= $lastItem; $i++)
                {
                    $content = $filteredContent[$i];
                    $filename = 'content_'.$content['rowid'].'.ogg';
                    $audio = new audio($this->getServiceMediaUri($filename));
                    $size = ContentHelper::getContentLabelAudioSize($this->dbh, $content['rowid']);
                    if ($size > 0) $audio->setSize($size);

                    $language = 'i-unknown';
                    $title = 'unknown';
                    foreach (ContentHelper::getContentMetadata($this->dbh, $content['rowid']) as $key => $value)
                    {
                        if ($key == 'dc:language')
                        {
                            $language = $value;
                        }
                        else if ($key == 'dc:title')
                        {
                            $title = $value;
                        }
                    }

                    $label = new label($title, $audio, $language);
                    $contentItem = new contentItem($label, 'con_'.$content['rowid']);
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
            $msg = "request is not valid " . $output->getError();
            $this->logger->warn($msg);
            throw new SoapFault('Client', $input->getError(), '', '', 'getContentMetadata_invalidParameterFault');
        }

        // parameters
        $contentId = ContentHelper::parseContentId($input->getContentID());

        // build contentMetadata
        $contentMetadata = new contentMetadata();

        // set category
        $category = ContentHelper::getContentCategory($this->dbh, $contentId);
        $contentMetadata->setCategory($category);

        // set requiresReturn
        $returnDate = ContentHelper::contentRequiresReturn($this->dbh, $this->sessionUserId, $contentId);
        if ($returnDate === false)
            $contentMetadata->setRequiresReturn(false);
        else
            $contentMetadata->setRequiresReturn(true);

        // samples not supported

        // build metadata
        $metadata = new metadata();
        $metadata->setIdentifier($input->getContentID());
        foreach (ContentHelper::getContentMetadata($this->dbh, $contentId) as $key => $value)
        {
            switch ($key)
            {
                case 'dc:title':
                    $metadata->setTitle($value);
                    break;
                case 'dc:identifier':
                    // the identifier is not the identifier found in the fileset
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
                    $metadata->addType($value);
                    break;
                case 'dc:subject':
                    $metadata->addSubject($value);
                    break;
                case 'dc:rights':
                    $metadata->addRights($value);
                    break;
                case 'dc:relation':
                    $metadata->addRelation($value);
                    break;
                case 'dc:language':
                    $metadata->addLanguage($value);
                    break;
                case 'dc:description':
                    $metadata->addDescription($value);
                    break;
                case 'dc:creator':
                    $metadata->addCreator($value);
                    break;
                case 'dc:coverage':
                    $metadata->addCoverage($value);
                    break;
                case 'dc:contributor':
                    $metadata->addContributor($value);
                    break;
                default:
                    if ($key == 'pdtb2:specVersion')
                        $metadata->addMeta(new meta($key, $value));
                    break;
            }
        }

        // fix to avoid validation error if mandatory metadata dc:title value is an empty string
        if (is_null($metadata->getTitle()) || strlen($metadata->getTitle()) == 0)
            $metadata->setTitle('unknown title');

        // fix to avoid validation error if mandatory metadata dc:format value is an empty string
        if (is_null($metadata->getFormat()) || strlen($metadata->getFormat()) == 0)
            $metadata->setFormat('unknown format');

        // get size
        $metadata->setSize((int)ContentHelper::getContentResourcesSize($this->dbh, $contentId));

        $contentMetadata->setMetadata($metadata);

        $output = new getContentMetadataResponse($contentMetadata);

        if ($output->validate() === false)
        {
            $msg = "failed to build response " . $output->getError();
            $this->logger->error($msg);
            $faultString = 'getContentMetadataResponse could not be built';
            throw new SoapFault('Server', $faultString,'', '', 'getContentMetadata_internalServerErrorFault');
        }

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
        $contentId = ContentHelper::parseContentId($input->getContentID());

        // check if content can be issued
        $issuable = ContentHelper::contentIssuable($this->dbh, $this->sessionUserId, $contentId);
        if ($issuable === false)
        {
            $msg = "User '$this->sessionUsername' not allowed to issue content with id '$contentId'";
            $this->logger->warn($msg);
            $faultString = 'User is not allowd to issue content';
            throw new SoapFault('Client', $faultString, '', '', 'issueContent_invalidParameterFault');
        }

        // if issued
        if (ContentHelper::contentInList($this->dbh, $this->sessionUserId, $contentId, 'issued'))
            return new issueContentResponse(true);
        // if expired
        if (ContentHelper::contentInList($this->dbh, $this->sessionUserId, $contentId, 'expired'))
            return new issueContentResponse(false);
        // if returned
        if (ContentHelper::contentInList($this->dbh, $this->sessionUserId, $contentId, 'returned'))
            return new issueContentResponse(false);

        // issue content
        if (!ContentHelper::issueContent($this->dbh, $this->sessionUserId, $contentId))
        {
            $msg = "issuing content with id '$contentId' failed";
            $this->logger->warn($msg);
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
        $contentId = ContentHelper::parseContentId($input->getContentID());

        // build resources
        $resources = new resources();

        // returnBy
        $returnDate = ContentHelper::contentRequiresReturn($this->dbh, $this->sessionUserId, $contentId);
        if ($returnDate !== false)
            $resources->setReturnBy(str_replace(' ', 'T', $returnDate));

        // resources
        $contentResources = ContentHelper::getContentResources($this->dbh, $this->sessionUserId, $contentId);
        if ($contentResources == false)
        {
            $msg = "failed to retrieve content resources for content with id '$contentId'";
            $this->logger->fatal($msg);
            throw new SoapFault ('Server', 'Internal Server Error', '', '', 'getContentResources_internalServerErrorFault');

        }

        if (sizeof($contentResources) == 0)
        {
            $msg = "User '$this->sessionUsername' is not allow to get resources for content with id '$contentId'";
            $this->logger->warn($msg);
            throw new SoapFault ('Client', $msg, '', '', 'getContentResources_invalidParameterFault');
        }

        foreach ($contentResources as $resource)
        {
            $uri = $this->getServiceResourceUri($contentId, $resource['filename']);
            $mimeType = $resource['mimetype'];
            $size = (int)$resource['size'];
            $localURI = $resource['filename'];
            $resources->addResource(new resource($uri, $mimeType, $size, $localURI));
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
        $contentId = ContentHelper::parseContentId($input->getContentID());

        // check if content requires return
        $return = ContentHelper::contentRequiresReturn($this->dbh, $this->sessionUserId, $contentId);
        if ($return === false)
        {
            $msg = "User '$this->sessionUsername' tried to return content with id '$contentId' which does not required return";
            $this->logger->warn($msg);
            $faultString = 'The content does not need to be returned';
            throw new SoapFault ('Client', $faultString, '', '', 'returnContent_invalidParameterFault');
        }

        // return content
        $returned = ContentHelper::returnContent($this->dbh, $this->sessionUserId, $contentId);
        if ($returned === false)
        {
            $msg = "returning content with id '$contentId' failed";
            $this->logger->fatal($msg);
            throw new SoapFault ('Server', 'Internal Server Error', '', '', 'returnContent_internalServerErrorFault');
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
     * Session function sessionHandle, control requests to service operations
     * @param string $operation, name of the invoked operation
     * @return SoapFault when necessary
     */
    private function sessionHandle($operation)
    {
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
            return;
        }

        // client must send HTTP Cookies in requests
        if (!isset($_COOKIE['PHPSESSID']))
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
            return;

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

        $msg = 'No active session';
        $this->logger->warn($msg);
        $this->sessionDestroy();
        $faultString = 'No session has been initialized, try initializing a session';
        throw new SoapFault ('Client', $faultString, '', '', $operation.'_noActiveSessionFault');
    }

    /**
     * Session function sessionDestroy, restore private session variables to default values
     */
    private function sessionDestroy()
    {
        $this->sessionUserLoggedOn = false;
        $this->sessionEstablished = false;

        // The following variables must reamin untouched as they are use in logging messages,
        // otherwise some logging messages will be incomplete
        // sessionUserId
        // sessionUsername
        // sessionCurrentOperation
    }

    /**
     * Service helper getServiceMediaUri
     * @param string $filename
     * @return string
     */
    private function getServiceMediaUri($filename)
    {
        return $this->getServiceBaseUri()."media/$filename";
    }

    /**
     * Service helper getServiceResourceUri
     * @param int $contentId
     * @param string $filename
     * @return string
     */
    private function getServiceResourceUri($contentId, $filename)
    {
        return $this->getServiceBaseUri()."content/$contentId/$filename";
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
     * @return array of strings
     */
    private function getClientSupportedContentFormats()
    {
        $contentFormat = $this->readingSystemAttributes->getConfig()->getSupportedContentFormats()->getContentFormat();
        if (is_null($contentFormat)) return array();
        return array_flip($contentFormat);
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
