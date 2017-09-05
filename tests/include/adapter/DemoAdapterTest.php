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

$includePath = dirname(realpath(__FILE__)) . '/../../../include/adapter';
set_include_path(get_include_path() . PATH_SEPARATOR . $includePath);

require_once('DemoAdapter.class.php');

class DemoAdapterTest extends PHPUnit_Framework_TestCase
{
    protected static $database;
    protected static $adapter;

    public static function setUpBeforeClass()
    {
        self::$database = realpath(dirname(__FILE__)) . '/demo.db';
        if (file_exists(self::$database)) unlink(self::$database);

        $dump = realpath(dirname(__FILE__)) . '/../../../data/db/demo.sqlite.dump';
        $output = array();
        $command = "sqlite3 " . self::$database . " < $dump";
        exec($command, $output);
        self::$adapter = new DemoAdapter(self::$database);
    }

    public static function tearDownAfterClass()
    {
        if (file_exists(self::$database)) unlink(self::$database);
    }

    public function testAuthenticate()
    {
        $this->assertFalse(self::$adapter->authenticate('kolibre', 'erbilok'));
        $this->assertTrue(self::$adapter->authenticate('kolibre', 'kolibre'));
    }


    public function testContentListExists()
    {
        $this->assertTrue(self::$adapter->contentListExists('bookshelf'));
    }

    public function testContentListId()
    {
        $this->assertGreaterThan(0, self::$adapter->contentListId('bookshelf'));
    }

    public function testSupportedContentFormats()
    {
        $this->assertCount(3, self::$adapter->supportedContentFormats());
    }

    public function testContentFormatId()
    {
        $this->assertEquals(1, self::$adapter->contentFormatId(1));
        $this->assertEquals(2, self::$adapter->contentFormatId(2));
        $this->assertEquals(1, self::$adapter->contentFormatId('con_1'));
        $this->assertEquals(2, self::$adapter->contentFormatId('con_2'));
    }

    public function testContentList()
    {
        // unfiltered
        $this->assertCount(3, self::$adapter->contentList('bookshelf'));

        // filtered
        $this->assertCount(2, self::$adapter->contentList('bookshelf', array('Daisy 2.02')));

    }

    public function testLabelContentItem()
    {
        $this->assertArrayHasKey('text', $label = self::$adapter->label(1, Adapter::LABEL_CONTENTITEM));
        $this->assertArrayHasKey('lang', $label = self::$adapter->label(1, Adapter::LABEL_CONTENTITEM));
        $this->assertArrayHasKey('audio', $label = self::$adapter->label(1, Adapter::LABEL_CONTENTITEM));
        $this->assertArrayHasKey('uri', $label = self::$adapter->label(1, Adapter::LABEL_CONTENTITEM)['audio']);
        $this->assertArrayHasKey('size', $label = self::$adapter->label(1, Adapter::LABEL_CONTENTITEM)['audio']);
    }

    public function testContentExists()
    {
        $this->assertFalse(self::$adapter->contentExists(10));
        $this->assertFalse(self::$adapter->contentExists('con_10'));
        $this->assertTrue(self::$adapter->contentExists(1));
        $this->assertTrue(self::$adapter->contentExists('con_1'));
        $this->assertTrue(self::$adapter->contentExists(2));
        $this->assertTrue(self::$adapter->contentExists('con_2'));
    }

    public function testContentAccessible()
    {
        $this->assertFalse(self::$adapter->contentAccessible(10));
        $this->assertFalse(self::$adapter->contentAccessible('con_10'));
        $this->assertTrue(self::$adapter->contentAccessible(1));
        $this->assertTrue(self::$adapter->contentAccessible('con_1'));
        $this->assertTrue(self::$adapter->contentAccessible(2));
        $this->assertTrue(self::$adapter->contentAccessible('con_2'));
    }

    public function testContentCategory()
    {
        $this->assertFalse(self::$adapter->contentCategory(10));
        $this->assertFalse(self::$adapter->contentCategory('con_10'));
        $this->assertEquals('BOOK', self::$adapter->contentCategory(1));
        $this->assertEquals('BOOK', self::$adapter->contentCategory('con_1'));
        $this->assertEquals('BOOK', self::$adapter->contentCategory(2));
        $this->assertEquals('BOOK', self::$adapter->contentCategory('con_2'));
    }

    public function testIsValidDate()
    {
        $this->assertFalse(self::$adapter->isValidDate(null));
        $this->assertFalse(self::$adapter->isValidDate(''));
        $this->assertFalse(self::$adapter->isValidDate('YYYY-MM-DD hh:mm:ss'));
        $this->assertFalse(self::$adapter->isValidDate('0000-00-00 00:00:00'));
        $this->assertTrue(self::$adapter->isValidDate('1970-01-01T00:00:00+00:00'));
    }

    public function testContentReturnDate()
    {
        $this->assertFalse(self::$adapter->contentReturnDate(10));
        $this->assertFalse(self::$adapter->contentReturnDate('con_10'));
        $pattern = '/\d{4}\-\d{2}\-\d{2}T\d{2}:\d{2}:\d{2}(\+\d{2}:\d{2}|Z)/';
        $this->assertRegExp($pattern, self::$adapter->contentReturnDate(1));
        $this->assertRegExp($pattern, self::$adapter->contentReturnDate('con_1'));
        $this->assertRegExp($pattern, self::$adapter->contentReturnDate(2));
        $this->assertRegExp($pattern, self::$adapter->contentReturnDate('con_2'));
        $dateBefore = self::$adapter->contentReturnDate(1);
        sleep(1);
        $dateAfter = self::$adapter->contentReturnDate(1);
        $this->assertFalse($dateBefore == $dateAfter);
    }

    public function testContentMetadata()
    {
        $this->assertEmpty(self::$adapter->contentMetadata(10));
        $this->assertEmpty(self::$adapter->contentMetadata('con_10'));
        $this->assertArrayHasKey('size', self::$adapter->contentMetadata(1));
        $this->assertArrayHasKey('size', self::$adapter->contentMetadata('con_1'));
        $this->assertArrayHasKey('dc:title', self::$adapter->contentMetadata(1));
        $this->assertArrayHasKey('dc:title', self::$adapter->contentMetadata('con_1'));
        $this->assertArrayHasKey('dc:format', self::$adapter->contentMetadata(1));
        $this->assertArrayHasKey('dc:format', self::$adapter->contentMetadata('con_1'));
        $this->assertArrayHasKey('size', self::$adapter->contentMetadata(2));
        $this->assertArrayHasKey('size', self::$adapter->contentMetadata('con_2'));
        $this->assertArrayHasKey('dc:title', self::$adapter->contentMetadata(2));
        $this->assertArrayHasKey('dc:title', self::$adapter->contentMetadata('con_2'));
        $this->assertArrayHasKey('dc:format', self::$adapter->contentMetadata(2));
        $this->assertArrayHasKey('dc:format', self::$adapter->contentMetadata('con_2'));
    }

    public function testContentInList()
    {
        $this->assertFalse(self::$adapter->contentInList(10, 'new'));
        $this->assertFalse(self::$adapter->contentInList('con_10', 'new'));
        $this->assertFalse(self::$adapter->contentInList(1, 'old'));
        $this->assertFalse(self::$adapter->contentInList('con_1', 'old'));
        $this->assertFalse(self::$adapter->contentInList(1, 'issued'));
        $this->assertFalse(self::$adapter->contentInList('con_1', 'issued'));
        $this->assertFalse(self::$adapter->contentInList(2, 'issued'));
        $this->assertFalse(self::$adapter->contentInList('con_2', 'issued'));
        $this->assertTrue(self::$adapter->contentInList(1, 'bookshelf'));
        $this->assertTrue(self::$adapter->contentInList('con_1', 'bookshelf'));
        $this->assertTrue(self::$adapter->contentInList(2, 'bookshelf'));
        $this->assertTrue(self::$adapter->contentInList('con_2', 'bookshelf'));
    }

    public function testContentResources()
    {
        $this->assertEmpty(self::$adapter->contentResources(10));
        $this->assertEmpty(self::$adapter->contentResources('con_10'));
        foreach (self::$adapter->contentResources(1) as $resource)
        {
            $this->assertArrayHasKey('uri', $resource);
            $this->assertArrayHasKey('mimeType', $resource);
            $this->assertArrayHasKey('size', $resource);
            $this->assertArrayHasKey('localURI', $resource);
        }
        foreach (self::$adapter->contentResources('con_1') as $resource)
        {
            $this->assertArrayHasKey('uri', $resource);
            $this->assertArrayHasKey('mimeType', $resource);
            $this->assertArrayHasKey('size', $resource);
            $this->assertArrayHasKey('localURI', $resource);
        }
        foreach (self::$adapter->contentResources(2) as $resource)
        {
            $this->assertArrayHasKey('uri', $resource);
            $this->assertArrayHasKey('mimeType', $resource);
            $this->assertArrayHasKey('size', $resource);
            $this->assertArrayHasKey('localURI', $resource);
        }
        foreach (self::$adapter->contentResources('con_2') as $resource)
        {
            $this->assertArrayHasKey('uri', $resource);
            $this->assertArrayHasKey('mimeType', $resource);
            $this->assertArrayHasKey('size', $resource);
            $this->assertArrayHasKey('localURI', $resource);
        }
    }

    public function testContentReturnable()
    {
        $this->assertFalse(self::$adapter->contentReturnable(10));
        $this->assertFalse(self::$adapter->contentReturnable('con_10'));
        $this->assertTrue(self::$adapter->contentReturnable(1));
        $this->assertTrue(self::$adapter->contentReturnable('con_1'));
        $this->assertTrue(self::$adapter->contentReturnable(2));
        $this->assertTrue(self::$adapter->contentReturnable('con_2'));
    }

    public function testContentReturn()
    {
        $this->assertFalse(self::$adapter->contentReturn(10));
        $this->assertFalse(self::$adapter->contentReturn('con_10'));
        $this->assertTrue(self::$adapter->contentReturn(1));
        $this->assertTrue(self::$adapter->contentReturn('con_1'));
        $this->assertTrue(self::$adapter->contentReturn(2));
        $this->assertTrue(self::$adapter->contentReturn('con_2'));
    }

    public function testLabelAnnouncement()
    {
        $label = self::$adapter->label(1, Adapter::LABEL_ANNOUNCEMENT);
        $this->assertArrayHasKey('text', $label);
        $this->assertContains('Welcome', $label['text']);
        $this->assertArrayHasKey('lang', $label);
        $this->assertEquals('en', $label['lang']);
        $this->assertArrayHasKey('audio', $label);
        $this->assertArrayHasKey('uri', $label['audio']);
        $this->assertArrayHasKey('size', $label['audio']);
        $label = self::$adapter->label(1, Adapter::LABEL_ANNOUNCEMENT, 'sv');
        $this->assertArrayHasKey('text', $label);
        $this->assertContains('Välkommen', $label['text']);
        $this->assertArrayHasKey('lang', $label);
        $this->assertEquals('sv', $label['lang']);
        $this->assertArrayHasKey('audio', $label);
        $this->assertArrayHasKey('uri', $label['audio']);
        $this->assertArrayHasKey('size', $label['audio']);
    }

    public function testAnnouncements()
    {
        $announcements = self::$adapter->announcements();
        $this->assertCount(2, $announcements);

        foreach ($announcements as $announcementId)
        {
            $this->assertTrue(self::$adapter->announcementRead($announcementId));
        }

        $announcements = self::$adapter->announcements();
        $this->assertCount(0, $announcements);
    }

    public function testAnnouncementInfo()
    {
        $this->assertFalse(self::$adapter->announcementInfo(10));
        $this->assertFalse(self::$adapter->announcementInfo('ann_10'));
        $info = self::$adapter->announcementInfo(1);
        $this->assertArrayHasKey('type', $info);
        $this->assertEquals('INFORMATION', $info['type']);
        $this->assertArrayHasKey('priority', $info);
        $this->assertEquals('MEDIUM', $info['priority']);
        $info = self::$adapter->announcementInfo('ann_1');
        $this->assertArrayHasKey('type', $info);
        $this->assertEquals('INFORMATION', $info['type']);
        $this->assertArrayHasKey('priority', $info);
        $this->assertEquals('MEDIUM', $info['priority']);
        $info = self::$adapter->announcementInfo(2);
        $this->assertArrayHasKey('type', $info);
        $this->assertEquals('INFORMATION', $info['type']);
        $this->assertArrayHasKey('priority', $info);
        $this->assertEquals('LOW', $info['priority']);
        $info = self::$adapter->announcementInfo('ann_2');
        $this->assertArrayHasKey('type', $info);
        $this->assertEquals('INFORMATION', $info['type']);
        $this->assertArrayHasKey('priority', $info);
        $this->assertEquals('LOW', $info['priority']);
    }

    public function testAnnouncementExists()
    {
        $this->assertFalse(self::$adapter->announcementExists(10));
        $this->assertFalse(self::$adapter->announcementExists('ann_10'));
        $this->assertTrue(self::$adapter->announcementExists(1));
        $this->assertTrue(self::$adapter->announcementExists('ann_1'));
        $this->assertTrue(self::$adapter->announcementExists(2));
        $this->assertTrue(self::$adapter->announcementExists('ann_2'));
    }

    public function testGetBookmarks()
    {
        // create bookmarks
        $lastmark = new lastmark('ncxRex','uri','timeOffset');
        $bookmark = new bookmark('ncxRef','uri','timeOffset');
        $hilite = new hilite(new hiliteStart('ncxRef','uri','timeOffset'),new hiliteEnd('ncxRef','uri','timeOffset'));
        $bookmarkSet = new bookmarkSet(new title('text'),'uid',$lastmark);
        $bookmarkSet->addBookmark($bookmark);
        $bookmarkSet->addHilite($hilite);
        $lastmark = new lastmark('ncxRex','uri','timeOffset',2);
        $bookmark = new bookmark('ncxRef','uri','timeOffset',2);
        $hilite = new hilite(new hiliteStart('ncxRef','uri','timeOffset',2),new hiliteEnd('ncxRef','uri','timeOffset',2));
        $bookmarkSet2 = new bookmarkSet(new title('text'),'uid',$lastmark);
        $bookmarkSet2->addBookmark($bookmark);
        $bookmarkSet2->addHilite($hilite);

        // no bookmarks exists
        $this->assertFalse(self::$adapter->getBookmarks('con_1'));

        // add bookmark
        $this->assertTrue(self::$adapter->setBookmarks('con_1',json_encode($bookmarkSet)));

        // get bookmark without action (default to all)
        $output = self::$adapter->getBookmarks('con_1');
        $this->assertArrayHasKey('lastModifiedDate', $output);
        $this->assertArrayHasKey('bookmarkSet', $output);
        $bookmarkSetDecoded = bookmarkSet_from_json($output['bookmarkSet']);
        $this->assertNotNull($bookmarkSetDecoded->title);
        $this->assertNotNull($bookmarkSetDecoded->uid);
        $this->assertNotNull($bookmarkSetDecoded->lastmark);
        $this->assertNull($bookmarkSetDecoded->lastmark->charOffset);
        $this->assertNotNull($bookmarkSetDecoded->bookmark);
        $this->assertCount(1, $bookmarkSetDecoded->bookmark);
        $this->assertNotNull($bookmarkSetDecoded->hilite);
        $this->assertCount(1, $bookmarkSetDecoded->hilite);

        // get bookmark without action all
        $output = self::$adapter->getBookmarks('con_1', Adapter::BMGET_ALL);
        $this->assertArrayHasKey('lastModifiedDate', $output);
        $this->assertArrayHasKey('bookmarkSet', $output);
        $bookmarkSetDecoded = bookmarkSet_from_json($output['bookmarkSet']);
        $this->assertNotNull($bookmarkSetDecoded->title);
        $this->assertNotNull($bookmarkSetDecoded->uid);
        $this->assertNotNull($bookmarkSetDecoded->lastmark);
        $this->assertNotNull($bookmarkSetDecoded->bookmark);
        $this->assertNotNull($bookmarkSetDecoded->hilite);

        // get bookmark without action lastmark
        $output = self::$adapter->getBookmarks('con_1', Adapter::BMGET_LASTMARK);
        $this->assertArrayHasKey('lastModifiedDate', $output);
        $this->assertArrayHasKey('bookmarkSet', $output);
        $bookmarkSetDecoded = bookmarkSet_from_json($output['bookmarkSet']);
        $this->assertNotNull($bookmarkSetDecoded->title);
        $this->assertNotNull($bookmarkSetDecoded->uid);
        $this->assertNotNull($bookmarkSetDecoded->lastmark);
        $this->assertNull($bookmarkSetDecoded->bookmark);
        $this->assertNull($bookmarkSetDecoded->hilite);

        // get bookmark without action bookmark
        $output = self::$adapter->getBookmarks('con_1', Adapter::BMGET_BOOKMARK);
        $this->assertArrayHasKey('lastModifiedDate', $output);
        $this->assertArrayHasKey('bookmarkSet', $output);
        $bookmarkSetDecoded = bookmarkSet_from_json($output['bookmarkSet']);
        $this->assertNotNull($bookmarkSetDecoded->title);
        $this->assertNotNull($bookmarkSetDecoded->uid);
        $this->assertNull($bookmarkSetDecoded->lastmark);
        $this->assertNotNull($bookmarkSetDecoded->bookmark);
        $this->assertNull($bookmarkSetDecoded->hilite);

        // get bookmark without action hilite
        $output = self::$adapter->getBookmarks('con_1', Adapter::BMGET_HILITE);
        $this->assertArrayHasKey('lastModifiedDate', $output);
        $this->assertArrayHasKey('bookmarkSet', $output);
        $bookmarkSetDecoded = bookmarkSet_from_json($output['bookmarkSet']);
        $this->assertNotNull($bookmarkSetDecoded->title);
        $this->assertNotNull($bookmarkSetDecoded->uid);
        $this->assertNull($bookmarkSetDecoded->lastmark);
        $this->assertNull($bookmarkSetDecoded->bookmark);
        $this->assertNotNull($bookmarkSetDecoded->hilite);

        // update bookmark with add
        $this->assertTrue(self::$adapter->setBookmarks('con_1',json_encode($bookmarkSet2),Adapter::BMSET_ADD));
        $output = self::$adapter->getBookmarks('con_1');
        $this->assertArrayHasKey('lastModifiedDate', $output);
        $this->assertArrayHasKey('bookmarkSet', $output);
        $bookmarkSetDecoded = bookmarkSet_from_json($output['bookmarkSet']);
        $this->assertNotNull($bookmarkSetDecoded->title);
        $this->assertNotNull($bookmarkSetDecoded->uid);
        $this->assertNotNull($bookmarkSetDecoded->lastmark);
        $this->assertEquals(2,$bookmarkSetDecoded->lastmark->charOffset);
        $this->assertNotNull($bookmarkSetDecoded->bookmark);
        $this->assertCount(2, $bookmarkSetDecoded->bookmark);
        $this->assertNotNull($bookmarkSetDecoded->hilite);
        $this->assertCount(2, $bookmarkSetDecoded->hilite);

        // update bookmark with remove
        $this->assertTrue(self::$adapter->setBookmarks('con_1',json_encode($bookmarkSet),Adapter::BMSET_REMOVE));
        $output = self::$adapter->getBookmarks('con_1');
        $this->assertArrayHasKey('lastModifiedDate', $output);
        $this->assertArrayHasKey('bookmarkSet', $output);
        $bookmarkSetDecoded = bookmarkSet_from_json($output['bookmarkSet']);
        $this->assertNotNull($bookmarkSetDecoded->title);
        $this->assertNotNull($bookmarkSetDecoded->uid);
        $this->assertNull($bookmarkSetDecoded->lastmark);
        $this->assertNotNull($bookmarkSetDecoded->bookmark);
        $this->assertCount(1, $bookmarkSetDecoded->bookmark);
        $this->assertNotNull($bookmarkSetDecoded->hilite);
        $this->assertCount(1, $bookmarkSetDecoded->hilite);

        // update bookmark with replace
        $this->assertTrue(self::$adapter->setBookmarks('con_1',json_encode($bookmarkSet),Adapter::BMSET_REPLACE));
        $output = self::$adapter->getBookmarks('con_1');
        $this->assertArrayHasKey('lastModifiedDate', $output);
        $this->assertArrayHasKey('bookmarkSet', $output);
        $bookmarkSetDecoded = bookmarkSet_from_json($output['bookmarkSet']);
        $this->assertNotNull($bookmarkSetDecoded->title);
        $this->assertNotNull($bookmarkSetDecoded->uid);
        $this->assertNotNull($bookmarkSetDecoded->lastmark);
        $this->assertNull($bookmarkSetDecoded->lastmark->charOffset);
        $this->assertNotNull($bookmarkSetDecoded->bookmark);
        $this->assertCount(1, $bookmarkSetDecoded->bookmark);
        $this->assertNotNull($bookmarkSetDecoded->hilite);
        $this->assertCount(1, $bookmarkSetDecoded->hilite);
    }

    public function testContentAccessState()
    {
        $this->assertFalse(self::$adapter->contentAccessState(10, 'START'));
        $this->assertFalse(self::$adapter->contentAccessState('con_10', 'START'));
        $this->assertTrue(self::$adapter->contentAccessState(1, 'START'));
        $this->assertTrue(self::$adapter->contentAccessState('con_1', 'START'));
        $this->assertTrue(self::$adapter->contentAccessState(2, 'START'));
        $this->assertTrue(self::$adapter->contentAccessState('con_2', 'START'));
    }

    public function testTermsOfService()
    {
        $this->user = 1;
        $this->assertFalse(self::$adapter->termsOfServiceAccepted());
        $this->assertTrue(self::$adapter->termsOfServiceAccept());
        $this->assertTrue(self::$adapter->termsOfServiceAccepted());
        $label = self::$adapter->termsOfService();
        $this->assertArrayHasKey('text', $label);
        $this->assertArrayHasKey('lang', $label);
        $this->assertArrayHasKey('audio', $label);
        $this->assertArrayHasKey('uri', $label['audio']);
        $this->assertArrayHasKey('size', $label['audio']);
    }
}

?>
