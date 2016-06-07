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

    public function __construct($_serviceUrl, $_username, $_password)
    {
        $this->serviceUrl = $_serviceUrl;
        $this->username = $_username;
        $this->password = $_password;

        $this->client = new DaisyOnlineClient($this->serviceUrl);
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

        $result = $this->client->logOn($this->username, $this->password);
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

    public function getServiceAttributes()
    {
        $this->log('invoking operation ' . __FUNCTION__);

        $result = $this->client->getServiceAttributes();
        if ($this->client->operationFailed())
        {
            $this->log(__FUNCTION__ . ' failed');
            exit(1);
        }
        $this->log(__FUNCTION__ . ' successful');
        return $result;
    }

    public function setReadingSystemAttributes()
    {
        $this->log('invoking operation ' . __FUNCTION__);

        $result = $this->client->setReadingSystemAttributes();
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
$result = $testClient->getServiceAttributes();
$result = $testClient->setReadingSystemAttributes();

// issue new content
$contentList = $testClient->getContentList('new');
$contentItems = array();
if (is_array($contentList->contentItem)) $contentItems = $contentList->contentItem;
foreach ($contentItems as $contentItem)
{
    // get metadata
    $result = $testClient->getContentMetadata($contentItem->getId());

    // issue content
    $result = $testClient->issueContent($contentItem->getId());
}

// get resources for issued content
$contentList = $testClient->getContentList('issued');
$contentItems = array();
if (is_array($contentList->contentItem)) $contentItems = $contentList->contentItem;
foreach ($contentItems as $contentItem)
{
    $resources = $testClient->getContentResources($contentItem->getId());
    echo "Dowloading resources for content " . $contentItem->getId() . "\n";
    $tmpFolder = '/tmp/' . $contentItem->getId();
    if (!file_exists($tmpFolder))
    {
        mkdir($tmpFolder);
    }
    foreach ($resources->resource as $resource)
    {
        $tmpFile = $tmpFolder . '/' . $resource->getLocalURI();
        echo "Downloading file '" . $resource->getUri() . "' to " . $tmpFile . "\n";
        $content = file_get_contents($resource->getUri());
        file_put_contents($tmpFile, $content);
    }
}

// return expired content
$contentList = $testClient->getContentList('expired');
$contentItems = array();
if (is_array($contentList->contentItem)) $contentItems = $contentList->contentItem;
foreach ($contentItems as $contentItem)
{
    $result = $testClient->returnContent($contentItem->getId());
}

// return issued content
$contentList = $testClient->getContentList('issued');
$contentItems = array();
if (is_array($contentList->contentItem)) $contentItems = $contentList->contentItem;
foreach ($contentItems as $contentItem)
{
    $result = $testClient->returnContent($contentItem->getId());
}

// end session
$result = $testClient->logOff();

?>
