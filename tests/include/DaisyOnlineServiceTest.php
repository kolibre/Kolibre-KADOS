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
require_once('config.class.php');

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
        $settings['Service']['supportedOptionalOperationsExtra'] = array();
        $settings['Service']['supportedOptionalOperationsExtra'][] = 'PROGRESS_STATE';
        $settings['Service']['supportedOptionalOperationsExtra'][] = 'PROGRESS_STATE';
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
        // build readingSystemAttributes
        $accessConfig = "STREAM_ONLY";
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
        $this->assertTrue($readingSystemAttributes->validate());

        // adapter throws exception on authenticate
        $input = new logOn('exception', 'exception', $readingSystemAttributes);
        $this->assertTrue($this->callOperation('logOn', $input, 'internalServerErrorFault'));

        // adapter returns false on authenticate
        $input = new logOn('invalid', 'invalid', $readingSystemAttributes);
        $this->assertTrue($this->callOperation('logOn', $input, 'unauthorizedFault'));

        // adapter returns true on authenticate
        $input = new logOn('valid', 'valid', $readingSystemAttributes);
        $output = self::$instance->logOn($input);
        $this->assertInstanceOf('serviceAttributes',$output->serviceAttributes);

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
     * @group getContentList
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
        foreach ($output->contentList->contentItem as $contentItem)
        {
            $this->assertInstanceOf('label', $contentItem->label);
            $this->assertEquals($contentItem->label->text, 'text');
            $this->assertInstanceOf('audio', $contentItem->label->audio);
            $this->assertEquals($contentItem->label->audio->uri, 'uri');
            $this->assertEquals($contentItem->label->audio->rangeBegin, 0);
            $this->assertEquals($contentItem->label->audio->rangeEnd, 1);
            $this->assertEquals($contentItem->label->audio->size, 2);
            $this->assertEquals($contentItem->label->lang, 'en');
            $this->assertEquals($contentItem->label->dir, 'ltr');
            $this->assertInstanceOf('sample', $contentItem->sample);
            $this->assertEquals($contentItem->sample->id, 'sample');
            $this->assertInstanceOf('metadata', $contentItem->metadata);
            $this->assertEquals($contentItem->metadata->title, 'title');
            $this->assertContains('valid-identifier', $contentItem->metadata->identifier);
            $this->assertEquals($contentItem->metadata->format, 'format');
            $this->assertEquals($contentItem->metadata->size, 1);
            $this->assertInstanceOf('categoryLabel', $contentItem->categoryLabel);
            $this->assertInstanceOf('subCategoryLabel', $contentItem->subCategoryLabel);
            $this->assertEquals($contentItem->accessPermission, 'STREAM_AND_DOWNLOAD_AUTOMATIC_ALLOWED');
            $this->assertNull($contentItem->lastmark);
            $this->assertNull($contentItem->multipleChoiceQuestion);
            $this->assertContains('valid-identifier', $contentItem->id);
            $this->assertEquals($contentItem->firstAccessedDate, '1970-01-01T00:00:00+00:00');
            $this->assertEquals($contentItem->lastAccessedDate, '1970-01-01T00:00:00+00:00');
            $this->assertEquals($contentItem->lastModifiedDate, '1970-01-01T00:00:00+00:00');
            $this->assertEquals($contentItem->category, 'category');
            $this->assertEquals($contentItem->subCategory, 'subCategory');
            $this->assertEquals($contentItem->returnBy, '1970-01-01T00:00:00+00:00');
            $this->assertFalse($contentItem->hasBookmarks);
        }
    }

    /**
     * @group daisyonlineservice
     * @group operation
     * @group getContentResources
     */
    public function testGetContentResouces()
    {
        // request is not valid
        $input = new getContentResources();
        $this->assertTrue($this->callOperation('getContentResources', $input, 'invalidParameterFault'));

        // adapter throws exception on contentExists
        $input = new getContentResources('exception-content-exists',"STREAM");
        $this->assertTrue($this->callOperation('getContentResources', $input, 'internalServerErrorFault'));

        // adapter returns false on contentExists
        $input = new getContentResources('invalid-content-exists',"STREAM");
        $this->assertTrue($this->callOperation('getContentResources', $input, 'invalidParameterFault'));

        // adapter throws exception on contentAccessible
        $input = new getContentResources('exception-content-accessible',"STREAM");
        $this->assertTrue($this->callOperation('getContentResources', $input, 'internalServerErrorFault'));

        // adapter returns false on contentAccessible
        $input = new getContentResources('invalid-content-accessible',"STREAM");
        $this->assertTrue($this->callOperation('getContentResources', $input, 'invalidParameterFault'));

        // adapter throws exception on contentLastModifiedDate
        $input = new getContentResources('exception-content-lastmodifieddate',"STREAM");
        $this->assertTrue($this->callOperation('getContentResources', $input, 'internalServerErrorFault'));

        // adapter throws exception on contentResources
        $input = new getContentResources('exception-content-resources',"STREAM");
        $this->assertTrue($this->callOperation('getContentResources', $input, 'internalServerErrorFault'));

        // adapter returns empty resources
        $input = new getContentResources('empty-content-resources',"STREAM");
        $this->assertTrue($this->callOperation('getContentResources', $input, 'invalidOperationFault'));

        // required elements missing from resources
        $input = new getContentResources('invalid-content-resources',"STREAM");
        $this->assertTrue($this->callOperation('getContentResources', $input, 'internalServerErrorFault'));

        // resources contains required elements
        $input = new getContentResources('valid-content-resources',"STREAM");
        $output = self::$instance->getContentResources($input);
        $this->assertEquals($output->resources->lastModifiedDate, '1970-01-01T00:00:00+00:00');
        $this->assertCount(3, $output->resources->resource);
        foreach ($output->resources->resource as $resource)
        {
            $this->assertEquals($resource->uri, 'uri');
            $this->assertEquals($resource->mimeType, 'mimeType');
            $this->assertEquals($resource->size, 1);
            $this->assertEquals($resource->localURI, 'localURI');
            $this->assertEquals($resource->lastModifiedDate, '1970-01-01T00:00:00+00:00');
            $this->assertEquals($resource->serverSideHash, 'md5');
        }
        $this->assertNull($output->resources->package);

        // resources contains required elements and package
        $input = new getContentResources('valid-content-resources-with-package',"STREAM");
        $output = self::$instance->getContentResources($input);
        $this->assertEquals($output->resources->lastModifiedDate, '1970-01-01T00:00:00+00:00');
        $this->assertCount(3, $output->resources->resource);
        foreach ($output->resources->resource as $resource)
        {
            $this->assertEquals($resource->uri, 'uri');
            $this->assertEquals($resource->mimeType, 'mimeType');
            $this->assertEquals($resource->size, 1);
            $this->assertEquals($resource->localURI, 'localURI');
            $this->assertEquals($resource->lastModifiedDate, '1970-01-01T00:00:00+00:00');
            $this->assertEquals($resource->serverSideHash, 'md5');
        }
        $this->assertCount(1, $output->resources->package);
        foreach ($output->resources->package as $package)
        {
            $this->assertEquals($package->uri, 'uri');
            $this->assertEquals($package->mimeType, 'mimeType');
            $this->assertEquals($package->size, 1);
            $this->assertEquals($package->lastModifiedDate, '1970-01-01T00:00:00+00:00');
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
            $this->assertEquals($announcement->priority, 'LOW');
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
    public function testSetProgressState()
    {
        // request is not valid
        $input = new setProgressState();
        $this->assertTrue($this->callOperation('setProgressState', $input, 'invalidParameterFault'));

        // adapter throws exception on contentExists
        $input = new setProgressState('exception-content-state', 'START');
        $this->assertTrue($this->callOperation('setProgressState', $input, 'internalServerErrorFault'));

        // adapter returns false
        $input = new setProgressState('invalid-content-id', 'START');
        $output = self::$instance->setProgressState($input);
        $this->assertFalse($output->setProgressStateResult);

        // adapter returns true
        $input = new setProgressState('valid-content-id', 'START');
        $output = self::$instance->setProgressState($input);
        $this->assertTrue($output->setProgressStateResult);
    }
}

?>
