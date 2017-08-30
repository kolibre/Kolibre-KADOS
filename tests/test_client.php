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

// set include path
set_include_path(get_include_path() . PATH_SEPARATOR . '../include');
set_include_path(get_include_path() . PATH_SEPARATOR . '../include/types');

require_once('DaisyOnlineClient.class.php');

$serviceUrl = '';
$username = '';
$password = '';

$options = getopt('s:u:p:');

foreach ($options as $option => $value)
{
    switch ($option)
    {
    case 's':
        $serviceUrl = $value;
        break;
    case 'u':
        $username = $value;
        break;
    case 'p':
        $password = $value;
        break;
    }
}

if (empty($serviceUrl) || empty($username) || empty($password))
{
    echo "usage: $argv[0] -s <service-url> -u <username> -p <password>\n";
    exit(1);
}

class TestClient
{
    private $serviceUrl = null;
    private $username = null;
    private $password = null;
    private $client = null;
    private $logEnable = true;
    private $config = null;
    private $readingSystemAttributes = null;
    private $manufacturer = null;
    private $model = null;
    private $serialnumber = null;
    private $version = null;
    private $termsAccepted = false;

    public function __construct($_serviceUrl, $_username, $_password)
    {
        $this->serviceUrl = $_serviceUrl;
        $this->username = $_username;
        $this->password = $_password;
        $this->manufacturer = 'Kolibre';
        $this->model = 'PHP SoapClient';
        $this->serialnumber = 'NA';
        $this->version = '0.1';
        $this->config = $this->setupConfig();
        $this->readingSystemAttributes = new readingSystemAttributes($this->manufacturer, $this->model, $this->serialnumber, $this->version, $this->config);
        $this->client = new DaisyOnlineClient($this->serviceUrl);
    }

    private function setupConfig()
    {
        $config = new config();
        $config->setAccessConfig("STREAM_ONLY");
        $config->setSupportsMultipleSelections(false);
        $config->setSupportsAdvancedDynamicMenus(false);
        $config->setPreferredUILanguage('en');
        $config->setBandwidth('8000000');
        $scf = new supportedContentFormats(array('DAISY 2.02', 'ANSI/NISO Z39.86-2005'));
        $config->setSupportedContentFormats($scf);
        $scpf = new supportedContentProtectionFormats();
        $config->setSupportedContentProtectionFormats($scpf);
        $keyRing = new keyRing();
        $config->setKeyRing($keyRing);
        $smt = new supportedMimeTypes();
        $config->setSupportedMimeTypes($smt);
        $sit = new supportedInputTypes(array(new input('TEXT_ALPHANUMERIC')));
        $config->setSupportedInputTypes($sit);
        $config->setRequiresAudioLabels(false);
        $additionalTransferProtocols = new additionalTransferProtocols(array('protocol'));
        $config->setAdditionalTransferProtocols($additionalTransferProtocols);
        return $config;
    }

    private function log($msg)
    {
        if ($this->logEnable) echo "$msg\n";
    }

    private function acceptTermsOfServiceIfFaultReturned()
    {
        if ($this->termsAccepted) {
            return false;
        }

        if ($this->client->operationFailed() && $this->client->getFaultType() != "termsOfServiceNotAcceptedFault")
        {
            return false;
        }

        $this->log('terms of service not accepted, initializing accept sequence');

        $this->log('invoking operation getTermsOfService');
        $result = $this->client->getTermsOfService();
        if ($this->client->operationFailed())
        {
            $this->log('getTermsOfService failed');
            exit(1);
        }
        $this->log('getTermsOfService successful');

        $this->log("accepting terms: \n---\n" . $result->text. "\n---");

        $this->log('invoking operation acceptTermsOfService');
        $result = $this->client->acceptTermsOfService();
        if ($this->client->operationFailed())
        {
            $this->log('acceptTermsOfService failed');
            exit(1);
        }
        $this->log('acceptTermsOfService successful');
        if ($result === true)
        {
            $this->termsAccepted = true;
        }

        return $result;
    }

    public function setUsername($_username)
    {
        $this->username = $_username;
    }

    public function setPassword($_password)
    {
        $this->password = $_password;
    }

    public function logOn()
    {
        $this->log('invoking operation ' . __FUNCTION__);

        $result = $this->client->logOn($this->username, $this->password,$this->readingSystemAttributes);
        if ($this->client->operationFailed())
        {
            $this->log(__FUNCTION__ . ' failed');
            exit(1);
        }
        $this->log(__FUNCTION__ . ' successful');
        return $result;
    }

    public function logOff()
    {
        $this->log('invoking operation ' . __FUNCTION__);

        $result = $this->client->logOff();
        if ($this->client->operationFailed())
        {
            $this->log(__FUNCTION__ . ' failed');
            exit(1);
        }
        $this->log(__FUNCTION__ . ' successful');
        return $result;
    }

    public function getContentList($id, $firstItem = 0, $lastItem = -1)
    {
        $this->log('invoking operation ' . __FUNCTION__);

        $result = $this->client->getContentList($id, $firstItem, $lastItem);
        if ($this->acceptTermsOfServiceIfFaultReturned())
        {
            // retry operation
            $this->log('retrying ' . __FUNCTION__);
            $result = $this->client->getContentList($id, $firstItem, $lastItem);
        }
        if ($this->client->operationFailed())
        {
            $this->log(__FUNCTION__ . ' failed');
            exit(1);
        }
        $this->log(__FUNCTION__ . ' successful');
        return $result;
    }

    public function getContentMetadata($contentID)
    {
        $this->log('invoking operation ' . __FUNCTION__);

        $result = $this->client->getContentMetadata($contentID);
        if ($this->client->operationFailed())
        {
            $this->log(__FUNCTION__ . ' failed');
            exit(1);
        }
        $this->log(__FUNCTION__ . ' successful');
        return $result;
    }

    public function issueContent($contentID)
    {
        $this->log('invoking operation ' . __FUNCTION__);

        $result = $this->client->issueContent($contentID);
        if ($this->client->operationFailed())
        {
            $this->log(__FUNCTION__ . ' failed');
            exit(1);
        }
        $this->log(__FUNCTION__ . ' successful');
        return $result;
    }

    public function getContentResources($contentID)
    {
        $this->log('invoking operation ' . __FUNCTION__);

        $result = $this->client->getContentResources($contentID);
        if ($this->acceptTermsOfServiceIfFaultReturned())
        {
            // retry operation
            $this->log('retrying ' . __FUNCTION__);
            $result = $this->client->getContentResources($contentID);
        }
        if ($this->client->operationFailed())
        {
            $this->log(__FUNCTION__ . ' failed');
            exit(1);
        }
        $this->log(__FUNCTION__ . ' successful');
        return $result;
    }

    public function returnContent($contentID)
    {
        $this->log('invoking operation ' . __FUNCTION__);

        $result = $this->client->returnContent($contentID);
        if ($this->acceptTermsOfServiceIfFaultReturned())
        {
            // retry operation
            $this->log('retrying ' . __FUNCTION__);
            $result = $this->client->returnContent($contentID);
        }
        if ($this->client->operationFailed())
        {
            $this->log(__FUNCTION__ . ' failed');
            exit(1);
        }
        $this->log(__FUNCTION__ . ' successful');
        return $result;
    }

    public function getServiceAnnouncements()
    {
        $this->log('invoking operation ' . __FUNCTION__);
        $result = $this->client->getServiceAnnouncements();
        if ($this->acceptTermsOfServiceIfFaultReturned())
        {
            // retry operation
            $this->log('retrying ' . __FUNCTION__);
            $result = $this->client->getServiceAnnouncements();
        }
        if ($this->client->operationFailed())
        {
            $this->log(__FUNCTION__ . ' failed');
            exit(1);
        }
        $this->log(__FUNCTION__ . ' successful');
        return $result;
    }

    public function markAnnouncementsAsRead($read)
    {
        $this->log('invoking operation ' . __FUNCTION__);
        $result = $this->client->markAnnouncementsAsRead($read);
        if ($this->acceptTermsOfServiceIfFaultReturned())
        {
            // retry operation
            $this->log('retrying ' . __FUNCTION__);
            $result = $this->client->markAnnouncementsAsRead($read);
        }
        if ($this->client->operationFailed())
        {
            $this->log(__FUNCTION__ . ' failed');
            exit(1);
        }
        $this->log(__FUNCTION__ . ' successful');
        return $result;
    }

    public function setProgressState($contentID, $state)
    {
        $this->log('invoking operation ' . __FUNCTION__);

        $result = $this->client->setProgressState($contentID, $state);
        if ($this->acceptTermsOfServiceIfFaultReturned())
        {
            // retry operation
            $this->log('retrying ' . __FUNCTION__);
            $result = $this->client->setProgressState($contentID, $state);
        }
        if ($this->client->operationFailed())
        {
            if ($this->client->getFaultType() == 'operationNotSupportedFault')
            {
                $this->log(__FUNCTION__ . ' is not supported');
                return 'operationNotSupportedFault';
            }
            $this->log(__FUNCTION__ . ' failed');
            exit(1);
        }
        $this->log(__FUNCTION__ . ' successful');
        return $result;
    }
}

# SOAP requests

$testClient = new TestClient($serviceUrl, $username, $password);

// establish session
$serviceAttributes = $testClient->logOn();

// get contentList for bookshelf
$contentList = $testClient->getContentList('bookshelf');
$contentItems = array();
if (is_array($contentList->contentItem))
    $contentItems = $contentList->contentItem;

//get resources for content items
foreach ($contentItems as $contentItem)
{
    $result = $testClient->getContentResources($contentItem->getId());
}

// set progress state for each countent item
foreach ($contentItems as $contentItem)
{

    $result = $testClient->setProgressState($contentItem->getId(), 'START');
    if (is_string($result) && $result == 'operationNotSupportedFault')
    {
        // $this->log('setProgressState operation is not supported, not trying anymore');
        break;
    }

    // this is where downloading should take place

    $result = $testClient->setProgressState($contentItem->getId(), 'FINISH');
    if (is_string($result) && $result == 'operationNotSupportedFault')
    {
        // $this->log('setProgressState operation is not supported, not trying anymore');
        break;
    }
}

//return content
foreach ($contentItems as $contentItem)
{
    $result = $testClient->returnContent($contentItem->getId());
}

//return content again
foreach ($contentItems as $contentItem)
{
    $result = $testClient->returnContent($contentItem->getId());
}

// request service announcements and mark them as read
if (!is_null($serviceAttributes->getSupportedOptionalOperations()))
{
    // operation is a string in PHP if it only contains one item, and an array if it contains
    // two or more items
    $operations = $serviceAttributes->getSupportedOptionalOperations()->getOperation();
    if ((is_string($operations) && $operations == 'SERVICE_ANNOUNCEMENTS') || (is_array($operations) && in_array('SERVICE_ANNOUNCEMENTS', $operations)))
    {
        $announcements = $testClient->getServiceAnnouncements();

        $read = new read();
        if (!is_null($announcements->getAnnouncement()))
        {
            foreach ($announcements->getAnnouncement() as $announcement)
            {
                $read->addItem($announcement->getId());
            }
            $numAnnouncements = count($read->getItem());
            echo "marking $numAnnouncements announcement(s) as read...\n";
            $ressult = $testClient->markAnnouncementsAsRead($read);
        }
        else
        {
            echo "no unread announcements returned\n";
        }
    }
}

// end session
$result = $testClient->logOff();

?>
