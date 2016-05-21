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
        if ($this->client->operationFailed())
        {
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
$result = $testClient->logOn();

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

// end session
$result = $testClient->logOff();

?>
