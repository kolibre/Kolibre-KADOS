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

$includePath = dirname(realpath(__FILE__)) . '/../../include';
set_include_path(get_include_path() . PATH_SEPARATOR . $includePath);

require_once('DaisyOnlineService.class.php');

class DaisyOnlineServiceSystem extends PHPUnit_Framework_TestCase
{
    protected static $rsa;
    protected static $inifile;
    protected static $instance;

    public static function setUpBeforeClass()
    {
        self::$inifile = realpath(dirname(__FILE__)) . '/service.ini';
        if (file_exists(self::$inifile)) unlink(self::$inifile);

        $settings = array();
        $settings['Service'] = array();
        $settings['Adapter'] = array();
        $settings['Adapter']['name'] = 'SystemTestAdapter';
        $settings['Adapter']['path'] = realpath(dirname(__FILE__));

        self::write_ini_file($settings, self::$inifile);

        self::$instance = new DaisyOnlineService(self::$inifile);
        self::$instance->disableCookieCheckInSessionHandle();

        // build readingSystemAttributes object
        $accessConfig = 'STREAM_ONLY';
        $supportsMultipleSelections = false;
        $supportsAdvancedDynamicMenus = false;
        $preferredUILanguage = 'preferredUILanguage';
        $bandwidth = null;
        $supportedContentFormats = new supportedContentFormats();
        $supportedContentProtectionFormats = new supportedContentProtectionFormats();
        $keyRing = null;
        $supportedMimeTypes = new supportedMimeTypes();
        $supportedInputTypes = new supportedInputTypes();
        $requiresAudioLabels = false;
        $additionalTransferProtocols = null;
        $config = new config(
            $accessConfig,
            $supportsMultipleSelections,
            $supportsAdvancedDynamicMenus,
            $preferredUILanguage,
            $bandwidth,
            $supportedContentFormats,
            $supportedContentProtectionFormats,
            $keyRing,
            $supportedMimeTypes,
            $supportedInputTypes,
            $requiresAudioLabels,
            $additionalTransferProtocols);
        $manufacturer = 'manufacturer';
        $model = 'model';
        $serialNumber = null;
        $version = 'version';
        $readingSystemAttributes = new readingSystemAttributes(
            $manufacturer,
            $model,
            $serialNumber,
            $version,
            $config);

        self::$rsa = $readingSystemAttributes;
    }

    public static function tearDownAfterClass()
    {
        if (file_exists(self::$inifile)) unlink(self::$inifile);
    }

    public static function write_ini_file($settings, $file)
    {
        $content = '';
        foreach ($settings as $group => $value)
        {
            $content .= "[$group]\n";
            foreach ($value as $key => $value)
            {
                if (is_array($value))
                {
                    foreach ($value as $key2 => $value)
                        $content .= "$key" . "[] = $value\n";
                }
                else
                    $content .= "$key = $value\n";
            }
        }

        if (!$handle = fopen($file, 'w'))
            return false;

        if (!fwrite($handle, $content))
            return false;

        fclose($handle);
        return true;
    }

    private function callOperation($operation, $input, $fault)
    {
        $soapFault = false;
        try
        {
            self::$instance->$operation($input);
        }
        catch (SoapFault $f)
        {
            $name = $operation .'_' . $fault;
            if ($f->_name == $name)
                $soapFault = true;
        }
        return $soapFault;
    }

    /**
     * @group daisyonlineservice
     * @group system
     */
    public function testSessionEstablishment()
    {
        $input = new logOn('valid', 'valid', self::$rsa);
        $output = self::$instance->logOn($input);
        $this->assertTrue($output->validate());
    }
   
    /**
     * @group daisyonlineservice
     * @group system
     * @depends testSessionEstablishment
     */
    public function testGetContentList()
    {
        $bookshelfItems = 2;

        $input = new getContentList('bookshelf', 0, -1);
        $output = self::$instance->getContentList($input);
        $this->assertCount($bookshelfItems, $output->contentList->contentItem);

        // check that all content items are set correctly
        $this->assertTrue($output->contentList->id == 'bookshelf');
        $this->assertTrue($output->contentList->totalItems == 2);
        foreach($output->contentList->contentItem as $contentItem)
        {
            $this->assertInstanceOf('label', $contentItem->label);
            $this->assertInstanceOf('metadata', $contentItem->metadata);
            $this->assertTrue($contentItem->accessPermission == "STREAM_AND_DOWNLOAD_AUTOMATIC_ALLOWED");
            $this->assertTrue($contentItem->lastModifiedDate == "2016-03-11T14:23:23+00:00"); 
            $this->assertTrue($contentItem->returnBy == "2016-03-11T14:23:23+00:00"); 
            foreach($contentItem->metadata as $metadata)
            {
                $this->assertTrue(is_string($contentItem->metadata->title));
                $this->assertTrue(is_string($contentItem->metadata->identifier));
            }
        } 
    }

    /**
     * @group daisyonlineservice
     * @group system
     * @depends testGetContentList
     */
    public function testGetContentResources()
    {
        $input = new getContentResources('id_1', 'STREAM');
        $output = self::$instance->getContentResources($input);
        $this->assertInstanceOf('getContentResourcesResponse', $output);

        // check that all resource properties are set correctly
        $this->assertTrue($output->resources->lastModifiedDate == "2016-03-11T14:23:23+00:00");
        foreach($output->resources->resource as $resource)
        {
            $this->assertInstanceOf('resource', $resource);
            $this->assertTrue($resource->uri == 'uri');
            $this->assertTrue($resource->mimeType == 'mimeType');
            $this->assertTrue($resource->localURI == 'localURI');
            $this->assertTrue($resource->uri == 'uri');
            $this->assertTrue($resource->lastModifiedDate == "2016-03-11T14:23:23+00:00");
        }
    }

    /**
     * @group daisyonlineservice
     * @group system
     * @depends testSessionEstablishment
     */
    public function testReturnContent()
    {
        $bookshelfItemsBefore = 2;
        $bookshelfItemsAfter = 0;

        //Check number of items in bookshelf
        $input = new getContentList('bookshelf', 0, -1);
        $output = self::$instance->getContentList($input);
        $this->assertCount($bookshelfItemsBefore, $output->contentList->contentItem);

        //return all items
        $contentItems = $output->contentList->contentItem;
        foreach ($contentItems as $contentItem)
        {
            $input = new returnContent($contentItem->id);
            $output = self::$instance->returnContent($input);
            $this->assertTrue($output->returnContentResult);
        }

        // check that bookshelf is empty
        $input = new getContentList('bookshelf', 0, -1);
        $output = self::$instance->getContentList($input);
        $this->assertNull($output->contentList->contentItem);
    }
}

?>
