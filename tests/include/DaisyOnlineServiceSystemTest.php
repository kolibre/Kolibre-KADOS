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
        $supportsMultipleSelections = false;
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
            $supportsMultipleSelections,
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
        $input = new logOn('valid', 'valid');
        $output = self::$instance->logOn($input);
        $this->assertTrue($output->logOnResult);
        $input = new getServiceAttributes($input);
        $output = self::$instance->getServiceAttributes($input);
        $this->assertInstanceOf('getServiceAttributesResponse', $output);
        $input = new setReadingSystemAttributes(self::$rsa);
        $output = self::$instance->setReadingSystemAttributes($input);
        $this->assertTrue($output->setReadingSystemAttributesResult);
    }

    /**
     * @group daisyonlineservice
     * @group system
     * @depends testSessionEstablishment
     */
    public function testIssueNewContent()
    {
        $issuedItemsBefore = 1;
        $newItemsBefore = 2;

        $input = new getContentList('issued', 0, -1);
        $output = self::$instance->getContentList($input);
        $this->assertCount($issuedItemsBefore, $output->contentList->contentItem);

        $input = new getContentList('new', 0, -1);
        $output = self::$instance->getContentList($input);
        $this->assertCount($newItemsBefore, $output->contentList->contentItem);
        $contentItems = $output->contentList->contentItem;

        // trying to issue content before retrieving metadata should fail
        foreach ($contentItems as $contentItem)
        {
            $input = new issueContent($contentItem->id);
            $this->assertTrue($this->callOperation('issueContent', $input, 'invalidOperationFault'));
        }

        // retrieve metadata for all items before issueing
        foreach ($contentItems as $contentItem)
        {
            $input = new getContentMetadata($contentItem->id);
            $output = self::$instance->getContentMetadata($input);
            $this->assertInstanceOf('getContentMetadataResponse', $output);
        }

        foreach ($contentItems as $contentItem)
        {
            $input = new issueContent($contentItem->id);
            $output = self::$instance->issueContent($input);
            $this->assertTrue($output->issueContentResult);
        }

        $input = new getContentList('issued', 0, -1);
        $output = self::$instance->getContentList($input);
        $this->assertCount($issuedItemsBefore+$newItemsBefore, $output->contentList->contentItem);

        $input = new getContentList('new', 0, -1);
        $output = self::$instance->getContentList($input);
        $this->assertNull($output->contentList->contentItem);
    }

    /**
     * @group daisyonlineservice
     * @group system
     * @depends testSessionEstablishment
     * @depends testIssueNewContent
     */
    public function testReturnIssuedContent()
    {
        $returnedItemsBefore = 0;
        $issuedItemsBefore = 3;

        $input = new getContentList('returned', 0, -1);
        $output = self::$instance->getContentList($input);
        $this->assertNull($output->contentList->contentItem);

        $input = new getContentList('issued', 0, -1);
        $output = self::$instance->getContentList($input);
        $this->assertCount($issuedItemsBefore, $output->contentList->contentItem);
        $contentItems = $output->contentList->contentItem;

        foreach ($contentItems as $contentItem)
        {
            $input = new returnContent($contentItem->id);
            $output = self::$instance->returnContent($input);
            $this->assertTrue($output->returnContentResult);
        }

        $input = new getContentList('returned', 0, -1);
        $output = self::$instance->getContentList($input);
        $this->assertCount($issuedItemsBefore, $output->contentList->contentItem);

        $input = new getContentList('issued', 0, -1);
        $output = self::$instance->getContentList($input);
        $this->assertNull($output->contentList->contentItem);
    }

    /**
     * @group daisyonlineservice
     * @group system
     * @depends testSessionEstablishment
     * @depends testReturnIssuedContent
     */
    public function testReturnExpiredContent()
    {
        $returnedItemsBefore = 3;
        $expiredItemsBefore = 1;

        $input = new getContentList('returned', 0, -1);
        $output = self::$instance->getContentList($input);
        $this->assertCount($returnedItemsBefore, $output->contentList->contentItem);

        $input = new getContentList('expired', 0, -1);
        $output = self::$instance->getContentList($input);
        $this->assertCount($expiredItemsBefore, $output->contentList->contentItem);
        $contentItems = $output->contentList->contentItem;

        foreach ($contentItems as $contentItem)
        {
            $input = new returnContent($contentItem->id);
            $output = self::$instance->returnContent($input);
            $this->assertTrue($output->returnContentResult);
        }

        $input = new getContentList('returned', 0, -1);
        $output = self::$instance->getContentList($input);
        $this->assertCount($returnedItemsBefore+$expiredItemsBefore, $output->contentList->contentItem);

        $input = new getContentList('expired', 0, -1);
        $output = self::$instance->getContentList($input);
        $this->assertNull($output->contentList->contentItem);
    }
}

?>