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

class DaisyOnlineServiceTest extends PHPUnit_Framework_TestCase
{
    protected static $inifile;
    protected static $instance;

    public static function setUpBeforeClass()
    {
        self::$inifile = realpath(dirname(__FILE__)) . '/service.ini';
        if (file_exists(self::$inifile)) unlink(self::$inifile);

        $settings = array();
        $settings['Service'] = array();
        $settings['Service']['supportedOptionalOperations'] = array();
        $settings['Service']['supportedOptionalOperations'][] = 'SERVICE_ANNOUNCEMENTS';
        $settings['Service']['supportedOptionalOperations'][] = 'SET_BOOKMARKS';
        $settings['Service']['supportedOptionalOperations'][] = 'GET_BOOKMARKS';
        $settings['Adapter'] = array();
        $settings['Adapter']['name'] = 'TestAdapter';
        $settings['Adapter']['path'] = realpath(dirname(__FILE__));

        self::write_ini_file($settings, self::$inifile);

        self::$instance = new DaisyOnlineService(self::$inifile);
        self::$instance->disableInternalSessionHandling();
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
     * @group operation
     */
    public function testLogOn()
    {
        // request is not valid
        $input = new logOn();
        $output = self::$instance->logOn($input);
        $this->assertFalse($output->logOnResult);

        // adapter throws exception on authenticate
        $input = new logOn('exception', 'exception');
        $this->assertTrue($this->callOperation('logOn', $input, 'internalServerErrorFault'));

        // adapter returns false on authenticate
        $input = new logOn('invalid', 'invalid');
        $output = self::$instance->logOn($input);
        $this->assertFalse($output->logOnResult);

        // adapter returns true on authenticate
        $input = new logOn('valid', 'valid');
        $output = self::$instance->logOn($input);
        $this->assertTrue($output->logOnResult);
    }

    /**
     * @group daisyonlineservice
     * @group operation
     */
    public function testLogOff()
    {
        $input = new logOff();
        $output = self::$instance->logOff($input);
        $this->assertTrue($output->logOffResult);
    }

    /**
     * @group daisyonlineservice
     * @group operation
     */
    public function testGetServiceAttributes()
    {
        // minimal settings
        $input = new getServiceAttributes($input);
        $output = self::$instance->getServiceAttributes($input);
        $this->assertNull($output->serviceAttributes->serviceProvider);
        $this->assertNull($output->serviceAttributes->service);
        $this->assertCount(1, $output->serviceAttributes->supportedContentSelectionMethods->method);
        $this->assertContains('OUT_OF_BAND', $output->serviceAttributes->supportedContentSelectionMethods->method);
        $this->assertFalse($output->serviceAttributes->supportsServerSideBack);
        $this->assertFalse($output->serviceAttributes->supportsSearch);
        $this->assertNull($output->serviceAttributes->supportedUplinkAudioCodecs->codec);
        $this->assertFalse($output->serviceAttributes->supportsAudioLabels);
        $this->assertCount(1, $output->serviceAttributes->supportedOptionalOperations->operation);
        $this->assertContains('SERVICE_ANNOUNCEMENTS', $output->serviceAttributes->supportedOptionalOperations->operation);

        // adapter throws exception on label
        $settings = array();
        $settings['Service'] = array();
        $settings['Service']['serviceProvider'] = 'label-exception';
        $settings['Adapter']['name'] = 'TestAdapter';
        $settings['Adapter']['path'] = realpath(dirname(__FILE__));
        self::write_ini_file($settings, self::$inifile);
        self::$instance = new DaisyOnlineService(self::$inifile);
        self::$instance->disableInternalSessionHandling();
        $input = new getServiceAttributes($input);
        $this->assertTrue($this->callOperation('getServiceAttributes', $input, 'internalServerErrorFault'));

        // full settings
        $settings = array();
        $settings['Service'] = array();
        $settings['Service']['serviceProvider'] = 'org-kolibre';
        $settings['Service']['service'] = 'org-kolibre-kados';
        $settings['Service']['supportedContentSelectionMethods'] = array('OUT_OF_BAND', 'BROWSE');
        $settings['Service']['supportsServerSideBack'] = 1;
        $settings['Service']['supportsSearch'] = 1;
        $settings['Service']['supportedUplinkAudioCodecs'] = array('codec 1', 'codec 2');
        $settings['Service']['supportsAudioLabels'] = 1;
        $settings['Service']['supportedOptionalOperations'] = array();
        $settings['Service']['supportedOptionalOperations'][] = 'SERVICE_ANNOUNCEMENTS';
        $settings['Service']['supportedOptionalOperations'][] = 'SET_BOOKMARKS';
        $settings['Service']['supportedOptionalOperations'][] = 'GET_BOOKMARKS';
        $settings['Service']['supportedOptionalOperations'][] = 'DYNAMIC_MENUS';
        $settings['Service']['supportedOptionalOperations'][] = 'PDTB2_KEY_PROVISION';
        $settings['Adapter']['name'] = 'TestAdapter';
        $settings['Adapter']['path'] = realpath(dirname(__FILE__));
        self::write_ini_file($settings, self::$inifile);
        self::$instance = new DaisyOnlineService(self::$inifile);
        self::$instance->disableInternalSessionHandling();
        $input = new getServiceAttributes($input);
        $output = self::$instance->getServiceAttributes($input);
        $this->assertEquals($output->serviceAttributes->serviceProvider->id, 'org-kolibre');
        $this->assertEquals($output->serviceAttributes->serviceProvider->label->text, 'text');
        $this->assertEquals($output->serviceAttributes->serviceProvider->label->audio->uri, 'uri');
        $this->assertEquals($output->serviceAttributes->serviceProvider->label->audio->rangeBegin, 0);
        $this->assertEquals($output->serviceAttributes->serviceProvider->label->audio->rangeEnd, 1);
        $this->assertEquals($output->serviceAttributes->serviceProvider->label->audio->size, 2);
        $this->assertEquals($output->serviceAttributes->serviceProvider->label->lang, 'en');
        $this->assertEquals($output->serviceAttributes->serviceProvider->label->dir, 'ltr');
        $this->assertEquals($output->serviceAttributes->service->id, 'org-kolibre-kados');
        $this->assertNull($output->serviceAttributes->service->label);
        $this->assertCount(2, $output->serviceAttributes->supportedContentSelectionMethods->method);
        $this->assertContains('OUT_OF_BAND', $output->serviceAttributes->supportedContentSelectionMethods->method);
        $this->assertContains('BROWSE', $output->serviceAttributes->supportedContentSelectionMethods->method);
        $this->assertTrue($output->serviceAttributes->supportsServerSideBack);
        $this->assertTrue($output->serviceAttributes->supportsSearch);
        $this->assertCount(2, $output->serviceAttributes->supportedUplinkAudioCodecs->codec);
        $this->assertContains('codec 1', $output->serviceAttributes->supportedUplinkAudioCodecs->codec);
        $this->assertContains('codec 2', $output->serviceAttributes->supportedUplinkAudioCodecs->codec);
        $this->assertTrue($output->serviceAttributes->supportsAudioLabels);
        $this->assertCount(5, $output->serviceAttributes->supportedOptionalOperations->operation);
        $this->assertContains('SERVICE_ANNOUNCEMENTS', $output->serviceAttributes->supportedOptionalOperations->operation);
        $this->assertContains('SET_BOOKMARKS', $output->serviceAttributes->supportedOptionalOperations->operation);
        $this->assertContains('GET_BOOKMARKS', $output->serviceAttributes->supportedOptionalOperations->operation);
        $this->assertContains('DYNAMIC_MENUS', $output->serviceAttributes->supportedOptionalOperations->operation);
        $this->assertContains('PDTB2_KEY_PROVISION', $output->serviceAttributes->supportedOptionalOperations->operation);
    }

    /**
     * @group daisyonlineservice
     * @group operation
     */
    public function testSetReadingSystemAttributes()
    {
        // request is not valid
        $input = new setReadingSystemAttributes();
        $this->assertTrue($this->callOperation('setReadingSystemAttributes', $input, 'invalidParameterFault'));

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

        // adapter returns false on start_session
        $input = new setReadingSystemAttributes($readingSystemAttributes);
        $output = self::$instance->setReadingSystemAttributes($input);
        $this->assertFalse($output->setReadingSystemAttributesResult);

        // adapter returns true on start_session
        $output = self::$instance->setReadingSystemAttributes($input);
        $this->assertTrue($output->setReadingSystemAttributesResult);
    }

    /**
     * @group daisyonlineservice
     * @group operation
     */
    public function testGetContentList()
    {
        // request is not valid
        $input = new getContentList();
        $this->assertTrue($this->callOperation('getContentList', $input, 'invalidParameterFault'));

        // adapter throws exception on contentListExists
        $input = new getContentList('exception-list-exists', 0, -1);
        $this->assertTrue($this->callOperation('getContentList', $input, 'internalServerErrorFault'));

        // adapter returns false on contentListExists
        $input = new getContentList('invalid-list', 0, -1);
        $this->assertTrue($this->callOperation('getContentList', $input, 'invalidParameterFault'));

        // adapter throws exception on contentList
        $input = new getContentList('exception-list', 0, -1);
        $this->assertTrue($this->callOperation('getContentList', $input, 'internalServerErrorFault'));

        // adapter throws exception on label contentlist
        $input = new getContentList('empty-list-label-exception', 0, -1);
        $output = self::$instance->getContentList($input);
        $this->assertEquals($output->contentList->totalItems, 0);
        $this->assertNull($output->contentList->firstItem);
        $this->assertNull($output->contentList->lastItem);
        $this->assertNull($output->contentList->contentItem);

        // empty list with content list label
        $input = new getContentList('empty-list-label', 0, -1);
        $output = self::$instance->getContentList($input);
        $this->assertEquals($output->contentList->totalItems, 0);
        $this->assertNull($output->contentList->firstItem);
        $this->assertNull($output->contentList->lastItem);
        $this->assertNull($output->contentList->contentItem);
        $this->assertEquals($output->contentList->label->text, 'text');
        $this->assertEquals($output->contentList->label->audio->uri, 'uri');
        $this->assertEquals($output->contentList->label->audio->rangeBegin, 0);
        $this->assertEquals($output->contentList->label->audio->rangeEnd, 1);
        $this->assertEquals($output->contentList->label->audio->size, 2);
        $this->assertEquals($output->contentList->label->lang, 'en');
        $this->assertEquals($output->contentList->label->dir, 'ltr');

        // adapter throws exception on label contentitem
        $input = new getContentList('valid-list-label-exception', 0, -1);
        $this->assertTrue($this->callOperation('getContentList', $input, 'internalServerErrorFault'));

        // sublist first item greater than total items
        $input = new getContentList('sublist-first-item-exceed-total-items', 3, -1);
        $output = self::$instance->getContentList($input);
        $this->assertEquals($output->contentList->totalItems, 3);
        $this->assertNull($output->contentList->firstItem);
        $this->assertNull($output->contentList->lastItem);
        $this->assertNull($output->contentList->contentItem);

        // sublist last item greater than total items
        $input = new getContentList('sublist-last-item-exceed-total-items', 0, 3);
        $output = self::$instance->getContentList($input);
        $this->assertEquals($output->contentList->totalItems, 3);
        $this->assertNull($output->contentList->firstItem);
        $this->assertNull($output->contentList->lastItem);
        $this->assertNull($output->contentList->contentItem);

        // sublist with single item
        $input = new getContentList('sublist-single-item', 1, 1);
        $output = self::$instance->getContentList($input);
        $this->assertEquals($output->contentList->totalItems, 3);
        $this->assertEquals($output->contentList->firstItem, 1);
        $this->assertEquals($output->contentList->lastItem, 1);
        $this->assertCount(1, $output->contentList->contentItem);

        // sublist with multiple items
        $input = new getContentList('sublist-multiple-items', 1, -1);
        $output = self::$instance->getContentList($input);
        $this->assertEquals($output->contentList->totalItems, 3);
        $this->assertEquals($output->contentList->firstItem, 1);
        $this->assertEquals($output->contentList->lastItem, 2);
        $this->assertCount(2, $output->contentList->contentItem);

        // complete list
        $input = new getContentList('full-list', 0, -1);
        $output = self::$instance->getContentList($input);
        $this->assertEquals($output->contentList->totalItems, 3);
        $this->assertEquals($output->contentList->firstItem, 0);
        $this->assertEquals($output->contentList->lastItem, 2);
        $this->assertCount(3, $output->contentList->contentItem);
    }

    /**
     * @group daisyonlineservice
     * @group operation
     */
    public function testGetContentMetadata()
    {
        // request is not valid
        $input = new getContentMetadata();
        $this->assertTrue($this->callOperation('getContentMetadata', $input, 'invalidParameterFault'));

        // adapter throws exception on contentExists
        $input = new getContentMetadata('exception-content-exists');
        $this->assertTrue($this->callOperation('getContentMetadata', $input, 'internalServerErrorFault'));

        // adapter returns false on contentExists
        $input = new getContentMetadata('invalid-content-exists');
        $this->assertTrue($this->callOperation('getContentMetadata', $input, 'invalidParameterFault'));

        // adapter throws exception on contentAccessible
        $input = new getContentMetadata('exception-content-accessible');
        $this->assertTrue($this->callOperation('getContentMetadata', $input, 'internalServerErrorFault'));

        // adapter returns false on contentAccessible
        $input = new getContentMetadata('invalid-content-accessible');
        $this->assertTrue($this->callOperation('getContentMetadata', $input, 'invalidParameterFault'));

        // adapter throws exception on contentSample
        $input = new getContentMetadata('exception-content-sample');
        $this->assertTrue($this->callOperation('getContentMetadata', $input, 'internalServerErrorFault'));

        // adapter throws exception on contentCategory
        $input = new getContentMetadata('exception-content-category');
        $this->assertTrue($this->callOperation('getContentMetadata', $input, 'internalServerErrorFault'));

        // adapter throws exception on contentRetunDate
        $input = new getContentMetadata('exception-content-returndate');
        $this->assertTrue($this->callOperation('getContentMetadata', $input, 'internalServerErrorFault'));

        // adapter throws exception on contentMetadata
        $input = new getContentMetadata('exception-content-metadata');
        $this->assertTrue($this->callOperation('getContentMetadata', $input, 'internalServerErrorFault'));

        // required elements missing from metadata
        $input = new getContentMetadata('invalid-content-metadata');
        $this->assertTrue($this->callOperation('getContentMetadata', $input, 'internalServerErrorFault'));

        // metadata contains requried elements
        $input = new getContentMetadata('valid-content-metadata');
        $output = self::$instance->getContentMetadata($input);
        $this->assertEquals($output->contentMetadata->sample->id, 'sample');
        $this->assertEquals($output->contentMetadata->metadata->title, 'dc:title');
        $this->assertEquals($output->contentMetadata->metadata->identifier, 'valid-content-metadata');
        $this->assertEquals($output->contentMetadata->metadata->publisher, 'dc:publisher');
        $this->assertEquals($output->contentMetadata->metadata->format, 'dc:format');
        $this->assertEquals($output->contentMetadata->metadata->date, 'dc:date');
        $this->assertEquals($output->contentMetadata->metadata->source, 'dc:source');
        $this->assertCount(1, $output->contentMetadata->metadata->type);
        $this->assertContains('dc:type', $output->contentMetadata->metadata->type);
        $this->assertCount(1, $output->contentMetadata->metadata->subject);
        $this->assertContains('dc:subject', $output->contentMetadata->metadata->subject);
        $this->assertCount(1, $output->contentMetadata->metadata->rights);
        $this->assertContains('dc:rights', $output->contentMetadata->metadata->rights);
        $this->assertCount(1, $output->contentMetadata->metadata->relation);
        $this->assertContains('dc:relation', $output->contentMetadata->metadata->relation);
        $this->assertCount(1, $output->contentMetadata->metadata->language);
        $this->assertContains('dc:language', $output->contentMetadata->metadata->language);
        $this->assertCount(1, $output->contentMetadata->metadata->description);
        $this->assertContains('dc:description', $output->contentMetadata->metadata->description);
        $this->assertCount(1, $output->contentMetadata->metadata->creator);
        $this->assertContains('dc:creator', $output->contentMetadata->metadata->creator);
        $this->assertCount(1, $output->contentMetadata->metadata->coverage);
        $this->assertContains('dc:coverage', $output->contentMetadata->metadata->coverage);
        $this->assertCount(1, $output->contentMetadata->metadata->contributor);
        $this->assertContains('dc:contributor', $output->contentMetadata->metadata->contributor);
        $this->assertNull($output->contentMetadata->metadata->narrator);
        $this->assertEquals($output->contentMetadata->metadata->size, 1);
        $this->assertEquals($output->contentMetadata->category, 'category');
        $this->assertCount(1, $output->contentMetadata->metadata->meta);
        $this->assertEquals($output->contentMetadata->metadata->meta[0]->name, 'pdtb2:specVersion');
        $this->assertEquals($output->contentMetadata->metadata->meta[0]->content, 'PDTB2');
        $this->assertTrue($output->contentMetadata->requiresReturn);
    }

    /**
     * @group daisyonlineservice
     * @group operation
     */
    public function testIssueContent()
    {
        // request is not valid
        $input = new issueContent();
        $this->assertTrue($this->callOperation('issueContent', $input, 'invalidParameterFault'));

        // adapter throws exception on contentExists
        $input = new issueContent('exception-content-exists');
        $this->assertTrue($this->callOperation('issueContent', $input, 'internalServerErrorFault'));

        // adapter returns false on contentExists
        $input = new issueContent('invalid-content-exists');
        $this->assertTrue($this->callOperation('issueContent', $input, 'invalidParameterFault'));

        // adapter throws exception on contentAccessible
        $input = new issueContent('exception-content-accessible');
        $this->assertTrue($this->callOperation('issueContent', $input, 'internalServerErrorFault'));

        // adapter returns false on contentAccessible
        $input = new issueContent('invalid-content-accessible');
        $this->assertTrue($this->callOperation('issueContent', $input, 'invalidParameterFault'));

        // adapter throws exception on contentIssuable
        $input = new issueContent('exception-content-issuable');
        $this->assertTrue($this->callOperation('issueContent', $input, 'internalServerErrorFault'));

        // adapter returns false on contentIssuable
        $input = new issueContent('invalid-content-issuable');
        $this->assertTrue($this->callOperation('issueContent', $input, 'invalidParameterFault'));

        // adapter throws exception on contentIssue
        $input = new issueContent('exception-content-issue');
        $this->assertTrue($this->callOperation('issueContent', $input, 'internalServerErrorFault'));

        // adapter return false on contentIssue
        $input = new issueContent('invalid-content-issue');
        $output = self::$instance->issueContent($input);
        $this->assertFalse($output->issueContentResult);

        // issue successful
        $input = new issueContent('valid-content-issue');
        $output = self::$instance->issueContent($input);
        $this->assertTrue($output->issueContentResult);
    }

    /**
     * @group daisyonlineservice
     * @group operation
     */
    public function testGetContentResouces()
    {
        // request is not valid
        $input = new getContentResources();
        $this->assertTrue($this->callOperation('getContentResources', $input, 'invalidParameterFault'));

        // adapter throws exception on contentExists
        $input = new getContentResources('exception-content-exists');
        $this->assertTrue($this->callOperation('getContentResources', $input, 'internalServerErrorFault'));

        // adapter returns false on contentExists
        $input = new getContentResources('invalid-content-exists');
        $this->assertTrue($this->callOperation('getContentResources', $input, 'invalidParameterFault'));

        // adapter throws exception on contentAccessible
        $input = new getContentResources('exception-content-accessible');
        $this->assertTrue($this->callOperation('getContentResources', $input, 'internalServerErrorFault'));

        // adapter returns false on contentAccessible
        $input = new getContentResources('invalid-content-accessible');
        $this->assertTrue($this->callOperation('getContentResources', $input, 'invalidParameterFault'));

        // adapter throws exception on contentReturnDate
        $input = new getContentResources('exception-content-returndate');
        $this->assertTrue($this->callOperation('getContentResources', $input, 'internalServerErrorFault'));

        // adapter throws exception on contentLastModifiedDate
        $input = new getContentResources('exception-content-lastmodifieddate');
        $this->assertTrue($this->callOperation('getContentResources', $input, 'internalServerErrorFault'));

        // adapter throws exception on contentResources
        $input = new getContentResources('exception-content-resources');
        $this->assertTrue($this->callOperation('getContentResources', $input, 'internalServerErrorFault'));

        // adapter returns empty resources
        $input = new getContentResources('empty-content-resources');
        $this->assertTrue($this->callOperation('getContentResources', $input, 'invalidOperationFault'));

        // required elements missing from resources
        $input = new getContentResources('invalid-content-resources');
        $this->assertTrue($this->callOperation('getContentResources', $input, 'internalServerErrorFault'));

        // resources contains required elements
        $input = new getContentResources('valid-content-resources');
        $output = self::$instance->getContentResources($input);
        $this->assertEquals($output->resources->returnBy, '1970-01-01T00:00:00');
        $this->assertEquals($output->resources->lastModifiedDate, '1970-01-01T00:00:00');
        $this->assertCount(3, $output->resources->resource);
        foreach ($output->resources->resource as $resource)
        {
            $this->assertEquals($resource->uri, 'uri');
            $this->assertEquals($resource->mimeType, 'mimeType');
            $this->assertEquals($resource->size, 1);
            $this->assertEquals($resource->localURI, 'localURI');
            $this->assertEquals($resource->lastModifiedDate, '1970-01-01T00:00:00');
        }
    }

    /**
     * @group daisyonlineservice
     * @group operation
     */
    public function testReturnContent()
    {
        // request is not valid
        $input = new returnContent();
        $this->assertTrue($this->callOperation('returnContent', $input, 'invalidParameterFault'));

        // adapter throws exception on contentExists
        $input = new returnContent('exception-content-exists');
        $this->assertTrue($this->callOperation('returnContent', $input, 'internalServerErrorFault'));

        // adapter returns false on contentExists
        $input = new returnContent('invalid-content-exists');
        $this->assertTrue($this->callOperation('returnContent', $input, 'invalidParameterFault'));

        // adapter throws exception on contentAccessible
        $input = new returnContent('exception-content-accessible');
        $this->assertTrue($this->callOperation('returnContent', $input, 'internalServerErrorFault'));

        // adapter returns false on contentAccessible
        $input = new returnContent('invalid-content-accessible');
        $this->assertTrue($this->callOperation('returnContent', $input, 'invalidParameterFault'));

        // adapter throws exception on contentReturnable
        $input = new returnContent('exception-content-returnable');
        $this->assertTrue($this->callOperation('returnContent', $input, 'internalServerErrorFault'));

        // adapter returns false on contentReturnable
        $input = new returnContent('invalid-content-returnable');
        $this->assertTrue($this->callOperation('returnContent', $input, 'invalidParameterFault'));

        // adapter throws exception on contentReturn
        $input = new returnContent('exception-content-return');
        $this->assertTrue($this->callOperation('returnContent', $input, 'internalServerErrorFault'));

        // adapter returns false on contentReturn
        $input = new returnContent('invalid-content-return');
        $this->assertTrue($this->callOperation('returnContent', $input, 'internalServerErrorFault'));

        // return successful
        $input = new returnContent('valid-content-return');
        $output = self::$instance->returnContent($input);
        $this->assertTrue($output->returnContentResult);
    }

    /**
     * @group daisyonlineservice
     * @group operation
     */
    public function testGetServiceAnnouncements()
    {
        // to ensure we are using announcment logic for protocol version 1
        self::$instance->setProtocolVersion(1);

        // adapter throws exception
        // TODO: figure out how to trigger internal server error
        //  $input = new getServiceAnnouncements();
        //  $this->assertTrue($this->callOperation('getServiceAnnouncements', $input, 'internalServerErrorFault'));

        // return successful
        $input = new getServiceAnnouncements();
        $output = self::$instance->getServiceAnnouncements($input);
        $this->assertCount(2, $output->announcements->announcement);
        foreach ($output->announcements->announcement as $announcement)
        {
            $this->assertEquals($announcement->label->text, 'text');
            $this->assertEquals($announcement->label->audio->uri, 'uri');
            $this->assertEquals($announcement->label->audio->rangeBegin, 0);
            $this->assertEquals($announcement->label->audio->rangeEnd, 1);
            $this->assertEquals($announcement->label->audio->size, 2);
            $this->assertEquals($announcement->label->lang, 'en');
            $this->assertEquals($announcement->label->dir, 'ltr');
            $this->assertContains('valid-identifier', $announcement->id);
            $this->assertEquals($announcement->type, 'INFORMATION');
            $this->assertEquals($announcement->priority, 1);
        }
    }

    /**
     * @group daisyonlineservice
     * @group operation
     */
    public function testMarkAnnouncementsAsRead()
    {
        // request is not valid
        $input = new markAnnouncementsAsRead();
        $this->assertTrue($this->callOperation('markAnnouncementsAsRead', $input, 'invalidParameterFault'));

        // adapter throws exception
        $input = new markAnnouncementsAsRead(new read(array('exception-mark-as-read')));
        $this->assertTrue($this->callOperation('markAnnouncementsAsRead', $input, 'internalServerErrorFault'));

        // announcement does not exist
        $input = new markAnnouncementsAsRead(new read(array('valid-announcement-id', 'nonexisting-announcement-id')));
        $this->assertTrue($this->callOperation('markAnnouncementsAsRead', $input, 'invalidParameterFault'));

        // announcement could not be marked as read
        $input = new markAnnouncementsAsRead(new read(array('valid-announcement-id', 'invalid-announcement-id')));
        $this->assertTrue($this->callOperation('markAnnouncementsAsRead', $input, 'invalidParameterFault'));

        // return successful
        $input = new markAnnouncementsAsRead(new read(array('valid-announcement-id-1', 'valid-announcement-id-2')));
        $output = self::$instance->markAnnouncementsAsRead($input);
        $this->assertTrue($output->markAnnouncementsAsReadResult);
    }

    /**
     * @group daisyonlineservice
     * @group operation
     */
    public function testSetBookmarks()
    {
        $bookmarkSet = new bookmarkSet(new title('text'),'uid');

        // request is not valid
        $input = new setBookmarks();
        $this->assertTrue($this->callOperation('setBookmarks', $input, 'invalidParameterFault'));

        // adapter throws exception
        $input = new setBookmarks('exception-set-bookmarks', $bookmarkSet);
        $this->assertTrue($this->callOperation('setBookmarks', $input, 'internalServerErrorFault'));

        // update unsuccessful
        $input = new setBookmarks('invalid-set-bookmarks', $bookmarkSet);
        $output = self::$instance->setBookmarks($input);
        $this->assertFalse($output->setBookmarksResult);

        // update successful
        $input = new setBookmarks('valid-set-bookmarks', $bookmarkSet);
        $output = self::$instance->setBookmarks($input);
        $this->assertTrue($output->setBookmarksResult);
    }

    /**
     * @group daisyonlineservice
     * @group operation
     */
    public function testGetBookmarks()
    {
        // request is not valid
        $input = new getBookmarks();
        $this->assertTrue($this->callOperation('getBookmarks', $input, 'invalidParameterFault'));

        // adapter throws exception
        $input = new getBookmarks('exception-get-bookmarks', 'ALL');
        $this->assertTrue($this->callOperation('getBookmarks', $input, 'internalServerErrorFault'));

        // no bookmarks found
        $input = new getBookmarks('invalid-get-bookmarks', 'ALL');
        $this->assertTrue($this->callOperation('getBookmarks', $input, 'invalidParameterFault'));

        // update successful
        $input = new getBookmarks('valid-get-bookmarks', 'ALL');
        $output = self::$instance->getBookmarks($input);
        $this->assertEquals($output->bookmarkSet->title->text, "text");
        $this->assertEquals($output->bookmarkSet->uid, "uid");
        $this->assertEquals($output->bookmarkSet->lastmark->ncxRef, "ncxRef");
        $this->assertEquals($output->bookmarkSet->lastmark->URI, "uri");
        $this->assertEquals($output->bookmarkSet->lastmark->timeOffset, "00:00");
        $this->assertNull($output->bookmarkSet->lastmark->charOffset);
    }
}

?>
