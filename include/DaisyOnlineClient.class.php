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

// include class definitions
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

// include class map
require_once('classmap.php');

class DaisyOnlineClient
{
    // instance of SoapClient
    private $soapClient = null;

    private $serviceAttributes = null;
    private $readingSystemAttributes = null;

    // place holders to store fault information from last operation
    private $operationFailed = null;
    private $faultType = null;
    private $faultString = null;

    public function __construct($wsdl_url, $wsdl_cache_disable = null)
    {
        if ($wsdl_cache_disable)
        {
            // set wsdl cache ttl to zero, only during development
            ini_set('soap.wsdl_cache_ttl', '0');
        }

        // client options
        global $classmap;
        $options['classmap'] = $classmap;
        $options['soap_version'] = SOAP_1_1;

        // create soap client in WSDL mode
        $this->soapClient = new SoapClient($wsdl_url, $options);

        // setup reading system attributes
        $this->setupReadingSystemAttributes();
    }

    private function setupReadingSystemAttributes()
    {
        $manufacturer = 'Kolibre';
        $model = 'PHP SoapClient';
        $serialnumber = 'NA';
        $version = '0.1';
        $config = new config();
        $config->setSupportsMultipleSelections(false);
        $config->setPreferredUILanguage('en');
        $config->setBandwidth('8000000');
        $scf = new supportedContentFormats(array('DAISY 2.02', 'ANSI/NISO Z39.86-2005'));
        $config->setSupportedContentFormats($scf);
        $scpf = new supportedContentProtectionFormats();
        $config->setSupportedContentProtectionFormats($scpf);
        $smt = new supportedMimeTypes();
        $config->setSupportedMimeTypes($smt);
        $sit = new supportedInputTypes(array(new input('TEXT_ALPHANUMERIC')));
        $config->setSupportedInputTypes($sit);
        $config->setRequiresAudioLabels(false);
        $this->readingSystemAttributes = new readingSystemAttributes($manufacturer, $model, $serialnumber, $version, $config);
    }

    private function extractFaultType($fault)
    {
        $type = '';
        if (isset($fault->detail))
        {
            $vars = get_object_vars($fault->detail);
            foreach ($vars as $name => $value)
            {
                $type = $name;
                break;
            }
        }
        return $type;
    }

    public function operationFailed()
    {
        return $this->operationFailed;
    }

    public function getFaultType()
    {
        return $this->faultType;
    }

    public function getFaultString()
    {
        return $this->faultString;
    }

    public function logOn($username, $password)
    {
        $this->operationFailed = false;
        try
        {
            $input = new logOn($username, $password);
            $logOnResponse = $this->soapClient->logOn($input);
        }
        catch (SoapFault $f)
        {
            $this->operationFailed = true;
            $this->faultType = $this->extractFaultType($f);
            $this->faultString = $f->faultstring;
            return null;
        }

        return $logOnResponse->logOnResult;
    }

    public function logOff()
    {
        $this->operationFailed = false;
        try
        {
            $input = new logOff();
            $logOffResponse = $this->soapClient->logOff($input);
        }
        catch (SoapFault $f)
        {
            $this->operationFailed = true;
            $this->faultType = $this->extractFaultType($f);
            $this->faultString = $f->faultstring;
            return null;
        }

        return $logOffResponse->logOffResult;
    }

    public function getServiceAttributes()
    {
        $this->operationFailed = false;
        try
        {
            $input = new getServiceAttributes();
            $getServiceAttributesResponse = $this->soapClient->getServiceAttributes($input);
        }
        catch (SoapFault $f)
        {
            $this->operationFailed = true;
            $this->faultType = $this->extractFaultType($f);
            $this->faultString = $f->faultstring;
            return null;
        }

        $this->serviceAttributes = $getServiceAttributesResponse->getServiceAttributes();
        return $getServiceAttributesResponse->getServiceAttributes();
    }

    public function setReadingSystemAttributes()
    {
        $this->operationFailed = false;
        try
        {
            $input = new setReadingSystemAttributes($this->readingSystemAttributes);
            $setReadingSystemAttributesResponse = $this->soapClient->setReadingSystemAttributes($input);
        }
        catch (SoapFault $f)
        {
            $this->operationFailed = true;
            $this->faultType = $this->extractFaultType($f);
            $this->faultString = $f->faultstring;
            return null;
        }

        return $setReadingSystemAttributesResponse->setReadingSystemAttributesResult;
    }

    public function getContentList($name, $firstItem = 0, $lastItem = -1)
    {
        $this->operationFailed = false;
        try
        {
            $input = new getContentList($name, $firstItem, $lastItem);
            $getContentListResponse = $this->soapClient->getContentList($input);
        }
        catch (SoapFault $f)
        {
            $this->operationFailed = true;
            $this->faultType = $this->extractFaultType($f);
            $this->faultString = $f->faultstring;
            return null;
        }

        return $getContentListResponse->getContentList();
    }

    public function getContentMetadata($contentId)
    {
        $this->operationFailed = false;
        try
        {
            $input = new getContentMetadata($contentId);
            $getContentMetadataResponse = $this->soapClient->getContentMetadata($input);
        }
        catch (SoapFault $f)
        {
            $this->operationFailed = true;
            $this->faultType = $this->extractFaultType($f);
            $this->faultString = $f->faultstring;
            return null;
        }

        return $getContentMetadataResponse->getContentMetadata();
    }

    public function issueContent($contentId)
    {
        $this->operationFailed = false;
        try
        {
            $input = new issueContent($contentId);
            $issueContentResponse = $this->soapClient->issueContent($input);
        }
        catch (SoapFault $f)
        {
            $this->operationFailed = true;
            $this->faultType = $this->extractFaultType($f);
            $this->faultString = $f->faultstring;
            return null;
        }

        return $issueContentResponse->issueContentResult;
    }

    public function getContentResources($contentId)
    {
        $this->operationFailed = false;
        try
        {
            $input = new getContentResources($contentId);
            $getContentResourcesResponse = $this->soapClient->getContentResources($input);
        }
        catch (SoapFault $f)
        {
            $this->operationFailed = true;
            $this->faultType = $this->extractFaultType($f);
            $this->faultString = $f->faultstring;
            return null;
        }

        return $getContentResourcesResponse->getResources();
    }

    public function returnContent($contentId)
    {
        $this->operationFailed = false;
        try
        {
            $input = new returnContent($contentId);
            $returnContentResponse = $this->soapClient->returnContent($input);
        }
        catch (SoapFault $f)
        {
            $this->operationFailed = true;
            $this->faultType = $this->extractFaultType($f);
            $this->faultString = $f->faultstring;
            return null;
        }

        return $returnContentResponse->returnContentResult;
    }

    public function getServiceAnnouncements()
    {
        $this->operationFailed = false;
        try
        {
            $input = new getServiceAnnouncements();
            $getServiceAnnouncementsResponse = $this->soapClient->getServiceAnnouncements($input);
        }
        catch (SoapFault $f)
        {
            $this->operationFailed = true;
            $this->faultType = $this->extractFaultType($f);
            $this->faultString = $f->faultstring;
            return null;
        }

        return $getServiceAnnouncementsResponse->getAnnouncements();
    }

    public function markAnnouncementsAsRead($read)
    {
        $this->operationFailed = false;
        try
        {
            $input = new markAnnouncementsAsRead($read);
            $markAnnouncementsAsReadResponse = $this->soapClient->markAnnouncementsAsRead($input);
        }
        catch (SoapFault $f)
        {
            $this->operationFailed = true;
            $this->faultType = $this->extractFaultType($f);
            $this->faultString = $f->faultstring;
            return null;
        }

        return $markAnnouncementsAsReadResponse->markAnnouncementsAsReadResult;
    }

    public function setBookmarks($contentId, $bookmarkSet)
    {
        $this->operationFailed = false;
        try
        {
            $input = new setBookmarks($contentId, $bookmarkSet);
            $setBookmarksResponse = $this->soapClient->setBookmarks($input);
        }
        catch (SoapFault $f)
        {
            $this->operationFailed = true;
            $this->faultType = $this->extractFaultType($f);
            $this->faultString = $f->faultstring;
            return null;
        }

        return $setBookmarksResponse->setBookmarksResult;
    }

    public function getBookmarks($contentId)
    {
        $this->operationFailed = false;
        try
        {
            $input = new getBookmarks($contentId);
            $getBookmarksResponse = $this->soapClient->getBookmarks($input);
        }
        catch (SoapFault $f)
        {
            $this->operationFailed = true;
            $this->faultType = $this->extractFaultType($f);
            $this->faultString = $f->faultstring;
            return null;
        }

        return $getBookmarksResponse->getBookmarkSet();
    }

    public function getQuestions($userResponses)
    {
        $this->operationFailed = false;
        try
        {
            $input = new getQuestions($userResponses);
            $getQuestionsResponse = $this->soapClient->getQuestions($input);
        }
        catch (SoapFault $f)
        {
            $this->operationFailed = true;
            $this->faultType = $this->extractFaultType($f);
            $this->faultString = $f->faultstring;
            return null;
        }

        return $getQuestionsResponse->getQuestions();
    }

    public function getKeyExchangeObject($keyName)
    {
        $this->operationFailed = false;
        try
        {
            $input = new getKeyExchangeObject($keyName);
            $getKeyExchangeObjectResponse = $this->soapClient->getKeyExchangeObject($input);
        }
        catch (SoapFault $f)
        {
            $this->operationFailed = true;
            $this->faultType = $this->extractFaultType($f);
            $this->faultString = $f->faultstring;
            return null;
        }

        return $getKeyExchangeObjectResponse->KeyExcahnge;
    }
}

?>
