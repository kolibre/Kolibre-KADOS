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
        $settings['Service']['supportsServerSideBack'] = 1;
        $settings['Service']['supportsSearch'] = 1;
        $settings['Service']['supportedOptionalOperations'] = array();
        $settings['Service']['supportedOptionalOperations'][] = 'SERVICE_ANNOUNCEMENTS';
        $settings['Service']['supportedOptionalOperations'][] = 'SET_BOOKMARKS';
        $settings['Service']['supportedOptionalOperations'][] = 'GET_BOOKMARKS';
        $settings['Service']['supportedOptionalOperations'][] = 'DYNAMIC_MENUS';
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

    /**
     * @group daisyonlineservice
     * @group system
     * @depends testSessionEstablishment
     */
    public function testMarkAnnouncementsAsReadWithoutPriorCallToGetServiceAnnouncements()
    {
        $input = new markAnnouncementsAsRead();
        $this->assertTrue($this->callOperation('markAnnouncementsAsRead', $input, 'invalidOperationFault'));
    }

    /**
     * @group daisyonlineservice
     * @group system
     * @depends testMarkAnnouncementsAsReadWithoutPriorCallToGetServiceAnnouncements
     */
    public function testGetServiceAnnouncements()
    {
        $input = new getServiceAnnouncements();
        $output = self::$instance->getServiceAnnouncements($input);
        $this->assertCount(3, $output->announcements->announcement);

        // mark all announcments as read
        $read = new read();
        foreach ($output->announcements->announcement as $announcement)
        {
            $read->addItem($announcement->id);
        }
        $input = new markAnnouncementsAsRead($read);
        $output = self::$instance->markAnnouncementsAsRead($input);
        $this->assertTrue($output->markAnnouncementsAsReadResult);

        // check that announcements is empty
        $input = new getServiceAnnouncements();
        $output = self::$instance->getServiceAnnouncements($input);
        $this->assertNull($output->announcements->announcement);
    }

    /**
     * @group daisyonlineservice
     * @group system
     * @depends testSessionEstablishment
     */
    public function testGetBookmarks()
    {
        $bookmarkSet = new bookmarkSet(new title('text'),'uid', new lastmark('ncxRef','uri','timeOffset'));

        $input = new getBookmarks('id-without-bookmarks', 'ALL');
        $this->assertTrue($this->callOperation('getBookmarks', $input, 'invalidParameterFault'));

        $input = new setBookmarks('id-with-bookmarks', $bookmarkSet);
        $output = self::$instance->setBookmarks($input);
        $this->assertTrue($output->setBookmarksResult);

        $input = new getBookmarks('id-with-bookmarks', 'ALL');
        $output = self::$instance->getBookmarks($input);
        $this->assertEquals($output->bookmarkSet->title->text, "text");
        $this->assertEquals($output->bookmarkSet->uid, "uid");
        $this->assertEquals($output->bookmarkSet->lastmark->ncxRef, "ncxRef");
        $this->assertEquals($output->bookmarkSet->lastmark->URI, "uri");
        $this->assertEquals($output->bookmarkSet->lastmark->timeOffset, "timeOffset");
        $this->assertNull($output->bookmarkSet->lastmark->charOffset);
    }

    /**
     * @group daisyonlineservice
     * @group system
     * @depends testSessionEstablishment
     */
    public function testGetQuestions()
    {
        // requst search menu
        $input = new getQuestions();
        $input->userResponses = new userResponses(array(new userResponse('search')));
        $output = self::$instance->getQuestions($input);
        $this->assertCount(1, $output->questions->multipleChoiceQuestion);
        $this->assertArrayHasKey(1, $output->questions->multipleChoiceQuestion);
        $this->assertInstanceOf('multipleChoiceQuestion', $output->questions->multipleChoiceQuestion[1]);
        $this->assertEquals('search-by', $output->questions->multipleChoiceQuestion[1]->id);
        $this->assertCount(2, $output->questions->multipleChoiceQuestion[1]->choices->choice);
        $this->assertNull($output->questions->inputQuestion);
        $this->assertNull($output->questions->contentListRef);
        $this->assertNull($output->questions->label);
        // allow value to be an empty string as some reading system manufactures sends it through
        $input->userResponses = new userResponses(array(new userResponse('search', '')));
        $output = self::$instance->getQuestions($input);
        $this->assertCount(1, $output->questions->multipleChoiceQuestion);
        $this->assertArrayHasKey(1, $output->questions->multipleChoiceQuestion);
        $this->assertInstanceOf('multipleChoiceQuestion', $output->questions->multipleChoiceQuestion[1]);
        $this->assertEquals('search-by', $output->questions->multipleChoiceQuestion[1]->id);
        $this->assertCount(2, $output->questions->multipleChoiceQuestion[1]->choices->choice);
        $this->assertNull($output->questions->inputQuestion);
        $this->assertNull($output->questions->contentListRef);
        $this->assertNull($output->questions->label);

        // request back
        $input->userResponses = new userResponses(array(new userResponse('back')));
        $output = self::$instance->getQuestions($input);
        $this->assertCount(1, $output->questions->multipleChoiceQuestion);
        $this->assertArrayHasKey(1, $output->questions->multipleChoiceQuestion);
        $this->assertInstanceOf('multipleChoiceQuestion', $output->questions->multipleChoiceQuestion[1]);
        $this->assertEquals('main-menu', $output->questions->multipleChoiceQuestion[1]->id);
        $this->assertCount(2, $output->questions->multipleChoiceQuestion[1]->choices->choice);
        $this->assertNull($output->questions->inputQuestion);
        $this->assertNull($output->questions->contentListRef);
        $this->assertNull($output->questions->label);

        // request next menu
        $input->userResponses = new userResponses(array(new userResponse('main-menu', 'give-feedback')));
        $output = self::$instance->getQuestions($input);
        $this->assertCount(1, $output->questions->multipleChoiceQuestion);
        $this->assertArrayHasKey(1, $output->questions->multipleChoiceQuestion);
        $this->assertInstanceOf('multipleChoiceQuestion', $output->questions->multipleChoiceQuestion[1]);
        $this->assertCount(1, $output->questions->inputQuestion);
        $this->assertArrayHasKey(2, $output->questions->inputQuestion);
        $this->assertInstanceOf('inputQuestion', $output->questions->inputQuestion[2]);
        $this->assertNull($output->questions->contentListRef);
        $this->assertNull($output->questions->label);
    }
}

?>
