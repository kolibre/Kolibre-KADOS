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

require_once('KobraAdapter.class.php');

class KobraAdapterTest extends PHPUnit_Framework_TestCase
{
    protected static $sqliteDb;
    protected static $adapter;

    public static function setUpBeforeClass()
    {
        $databaseDriver = 'sqlite';
        if (array_key_exists('DATABASE_DRIVER', $_ENV))
        {
            switch ($_ENV['DATABASE_DRIVER'])
            {
                case 'pgsql':
                case 'postgres':
                case 'postgresql':
                    $databaseDriver = 'pgsql';
            }
        }
        $dsn = '';
        switch ($databaseDriver)
        {
            case 'pgsql':
                $output = array();
                $command = 'psql -c "DROP DATABASE kobra_test"';
                exec($command, $output);
                $output = array();
                $command = 'psql -c "CREATE DATABASE kobra_test"';
                exec($command, $output);
                $dumpfile = realpath(dirname(__FILE__)) . '/../../../data/db/kobra.postgresql.sql';
                $output = array();
                $command = "cat " . $dumpfile . " | psql kobra_test";
                exec($command, $output);
                $dsn = "pgsql:host=localhost;port=5432;dbname=kobra_test";
                break;
            default:
                self::$sqliteDb = realpath(dirname(__FILE__)) . '/kobra_test.sqlte3';
                if (file_exists(self::$sqliteDb)) unlink(self::$sqliteDb);
                $dumpfile = realpath(dirname(__FILE__)) . '/../../../data/db/kobra.sqlite3.sql';
                $output = array();
                $command = "sqlite3 " . self::$sqliteDb . " < $dumpfile";
                exec($command, $output);
                $dsn = "sqlite:" . self::$sqliteDb;
        }
        self::$adapter = new KobraAdapter($dsn);
        self::$adapter->setSecretKey('test');
        self::$adapter->setProtocolVersion(Adapter::DODP_V1);
    }

    public static function tearDownAfterClass()
    {
        if (array_key_exists('DATABASE_DRIVER', $_ENV))
        {
            switch ($_ENV['DATABASE_DRIVER'])
            {
                case 'pgsql':
                case 'postgres':
                case 'postgresql':
                    // Below fails with error message "There is 1 other session using the database."
                    // $output = array();
                    // $command = 'psql -c "DROP DATABASE kobra_test"';
                    // exec($command, $output);
                    break;
                default:
                    if (file_exists(self::$sqliteDb)) unlink(self::$sqliteDb);
            }
        }
    }

    /**
     * @group decrypt
     */
    public function testDecrypt()
    {
        $this->assertEquals("kolibre", self::$adapter->decrypt('Wz2fuBzjbhCrm/Dmx38DCgpWHigWf8aaEDlvpDCO5gImGDI='));
    }

    public function testAuthenticate()
    {
        $this->assertFalse(self::$adapter->authenticate('kolibre', 'erbilok'));
        $this->assertTrue(self::$adapter->authenticate('kolibre', 'kolibre'));
    }

    public function testContentListExists()
    {
        $this->assertFalse(self::$adapter->contentListExists('old'));
        $this->assertTrue(self::$adapter->contentListExists('new'));
        $this->assertTrue(self::$adapter->contentListExists('issued'));
        $this->assertTrue(self::$adapter->contentListExists('expired'));
    }

    public function testContentListId()
    {
        $this->assertLessThan(0, self::$adapter->contentListId('old'));
        $this->assertGreaterThan(0, self::$adapter->contentListId('new'));
        $this->assertGreaterThan(0, self::$adapter->contentListId('issued'));
        $this->assertGreaterThan(0, self::$adapter->contentListId('expired'));
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
        $this->assertCount(3, self::$adapter->contentList('new'));
        $this->assertEmpty(self::$adapter->contentList('issued'));
        $this->assertEmpty(self::$adapter->contentList('expired'));

        // filtered
        $this->assertCount(2, self::$adapter->contentList('new', array('Daisy 2.02')));
        $this->assertCount(2, self::$adapter->contentList('new', array('DAISY 2.02')));
        $this->assertCount(1, self::$adapter->contentList('new', array('Ansi/Niso Z39.86-2005')));
        $this->assertCount(1, self::$adapter->contentList('new', array('ANSI/NISO Z39.86-2005')));
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
        $this->assertTrue(self::$adapter->isValidDate('1970-01-01 00:00:00'));
    }

    public function testContentReturnDate()
    {
        $this->assertFalse(self::$adapter->contentReturnDate(10));
        $this->assertFalse(self::$adapter->contentReturnDate('con_10'));
        $pattern = '/\d{4}-\d{2}-\d{2}[ T]\d{2}:\d{2}:\d{2}/';
        $this->assertRegExp($pattern, self::$adapter->contentReturnDate(1));
        $this->assertRegExp($pattern, self::$adapter->contentReturnDate('con_1'));
        $this->assertRegExp($pattern, self::$adapter->contentReturnDate(2));
        $this->assertRegExp($pattern, self::$adapter->contentReturnDate('con_2'));
        $dateBefore = self::$adapter->contentReturnDate(1);
        sleep(1);
        $dateAfter = self::$adapter->contentReturnDate(1);
        $this->assertEquals($dateBefore, $dateAfter);
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
        $this->assertTrue(self::$adapter->contentInList(1, 'new'));
        $this->assertTrue(self::$adapter->contentInList('con_1', 'new'));
        $this->assertTrue(self::$adapter->contentInList(2, 'new'));
        $this->assertTrue(self::$adapter->contentInList('con_2', 'new'));
    }

    public function testContentIssuable()
    {
        $this->assertFalse(self::$adapter->contentIssuable(10));
        $this->assertFalse(self::$adapter->contentIssuable('con_10'));
        $this->assertTrue(self::$adapter->contentIssuable(1));
        $this->assertTrue(self::$adapter->contentIssuable('con_1'));
        $this->assertTrue(self::$adapter->contentIssuable(2));
        $this->assertTrue(self::$adapter->contentIssuable('con_2'));
    }

    public function testContentIssue()
    {
        $this->assertFalse(self::$adapter->contentIssue(10));
        $this->assertFalse(self::$adapter->contentIssue('con_10'));
        $this->assertTrue(self::$adapter->contentIssue(1));
        $this->assertTrue(self::$adapter->contentIssue('con_1'));
        $this->assertTrue(self::$adapter->contentIssue(2));
        $this->assertTrue(self::$adapter->contentIssue('con_2'));
    }

    public function testContentReturnDateAfterIssued()
    {
        $this->assertFalse(self::$adapter->contentReturnDate(10));
        $this->assertFalse(self::$adapter->contentReturnDate('con_10'));
        $pattern = '/\d{4}-\d{2}-\d{2}[ T]\d{2}:\d{2}:\d{2}/';
        $this->assertRegExp($pattern, self::$adapter->contentReturnDate(1));
        $this->assertRegExp($pattern, self::$adapter->contentReturnDate('con_1'));
        $this->assertRegExp($pattern, self::$adapter->contentReturnDate(2));
        $this->assertRegExp($pattern, self::$adapter->contentReturnDate('con_2'));
        $dateBefore = self::$adapter->contentReturnDate(1);
        sleep(1);
        $dateAfter = self::$adapter->contentReturnDate(1);
        $this->assertEquals($dateBefore, $dateAfter);
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
        $this->assertContains('announcement_1.ogg', $label['audio']['uri']);
        $this->assertArrayHasKey('size', $label['audio']);
        $this->assertEquals(24677, $label['audio']['size']);
        $label = self::$adapter->label(1, Adapter::LABEL_ANNOUNCEMENT, 'sv');
        $this->assertArrayHasKey('text', $label);
        $this->assertContains('Välkommen', $label['text']);
        $this->assertArrayHasKey('lang', $label);
        $this->assertEquals('sv', $label['lang']);
        $this->assertArrayHasKey('audio', $label);
        $this->assertArrayHasKey('uri', $label['audio']);
        $this->assertContains('announcement_2.ogg', $label['audio']['uri']);
        $this->assertArrayHasKey('size', $label['audio']);
        $this->assertEquals(27392, $label['audio']['size']);
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

    public function testLabelQuestions()
    {
        $label = self::$adapter->label(1, Adapter::LABEL_CHOICEQUESTION);
        $this->assertArrayHasKey('text', $label);
        $this->assertContains('What would you like to do?', $label['text']);
        $this->assertArrayHasKey('lang', $label);
        $this->assertEquals('en', $label['lang']);
        $this->assertArrayHasKey('audio', $label);
        $this->assertArrayHasKey('uri', $label['audio']);
        $this->assertContains("question_1.ogg", $label['audio']['uri']);
        $this->assertArrayHasKey('size', $label['audio']);
        $this->assertEquals(13817, $label['audio']['size']);
        $label = self::$adapter->label('que_2', Adapter::LABEL_CHOICE, 'sv');
        $this->assertArrayHasKey('text', $label);
        $this->assertContains('Söka i biblioteket.', $label['text']);
        $this->assertArrayHasKey('lang', $label);
        $this->assertEquals('sv', $label['lang']);
        $this->assertArrayHasKey('audio', $label);
        $this->assertArrayHasKey('uri', $label['audio']);
        $this->assertContains("question_4.ogg", $label['audio']['uri']);
        $this->assertArrayHasKey('size', $label['audio']);
        $this->assertEquals(14255, $label['audio']['size']);
        $label = self::$adapter->label(40, Adapter::LABEL_INPUTQUESTION);
        $this->assertArrayHasKey('text', $label);
        $this->assertContains('How would you rate this service?', $label['text']);
        $this->assertArrayHasKey('lang', $label);
        $this->assertEquals('en', $label['lang']);
        $this->assertArrayHasKey('audio', $label);
        $this->assertArrayHasKey('uri', $label['audio']);
        $this->assertContains("question_27.ogg", $label['audio']['uri']);
        $this->assertArrayHasKey('size', $label['audio']);
        $this->assertEquals(17356, $label['audio']['size']);
    }

    public function testMenuDefault()
    {
        $menu = self::$adapter->menuDefault();
        $this->assertCount(1, $menu);
        $this->assertArrayHasKey('type', $menu[0]);
        $this->assertEquals('multipleChoiceQuestion', $menu[0]['type']);
        $this->assertArrayHasKey('id', $menu[0]);
        $this->assertEquals('que_1', $menu[0]['id']);
        $this->assertArrayHasKey('choices', $menu[0]);
        $this->assertCount(3, $menu[0]['choices']);
        $this->assertEquals('que_2', $menu[0]['choices'][0]);
        $this->assertEquals('que_3', $menu[0]['choices'][1]);
        $this->assertEquals('que_4', $menu[0]['choices'][2]);
        $this->assertArrayHasKey('allowMultipleSelections', $menu[0]);
        $this->assertEquals(0, $menu[0]['allowMultipleSelections']);
    }

    public function testMenuSearch()
    {
        $menu = self::$adapter->menuSearch();
        $this->assertCount(1, $menu);
        $this->assertArrayHasKey('type', $menu[0]);
        $this->assertEquals('multipleChoiceQuestion', $menu[0]['type']);
        $this->assertArrayHasKey('id', $menu[0]);
        $this->assertEquals('que_20', $menu[0]['id']);
        $this->assertArrayHasKey('choices', $menu[0]);
        $this->assertCount(2, $menu[0]['choices']);
        $this->assertEquals('que_21', $menu[0]['choices'][0]);
        $this->assertEquals('que_22', $menu[0]['choices'][1]);
        $this->assertArrayHasKey('allowMultipleSelections', $menu[0]);
        $this->assertEquals(0, $menu[0]['allowMultipleSelections']);
    }

    public function testMenuNext()
    {
        // label endpoint for feedback, either en or sv label might be returned
        $responses = array(array('questionID' => 'que_40', 'value' => 'que_42')); // rate excellent
        $label = self::$adapter->menuNext($responses);
        $this->assertArrayHasKey('text', $label);
        $this->assertArrayHasKey('lang', $label);
        $this->assertArrayHasKey('audio', $label);
        $this->assertArrayHasKey('uri', $label['audio']);
        $this->assertContainsAny(array('question_39.ogg', 'question_40.ogg'), $label['audio']['uri']);
        $this->assertArrayHasKey('size', $label['audio']);
        $responses = array(array('questionID' => 'que_41', 'value' => 'this rocks')); // optional feedback 'this rocks'
        $label = self::$adapter->menuNext($responses);
        $this->assertArrayHasKey('text', $label);
        $this->assertArrayHasKey('lang', $label);
        $this->assertArrayHasKey('audio', $label);
        $this->assertArrayHasKey('uri', $label['audio']);
        $this->assertContainsAny(array('question_39.ogg', 'question_40.ogg'), $label['audio']['uri']);
        $this->assertArrayHasKey('size', $label['audio']);

        // feedback menu
        $responses = array(array('questionID' => 'que_1', 'value' => 'que_4')); // select feedback
        $menu = self::$adapter->menuNext($responses);
        $this->assertCount(2, $menu);
        $this->assertArrayHasKey('type', $menu[0]);
        $this->assertEquals('multipleChoiceQuestion', $menu[0]['type']);
        $this->assertArrayHasKey('id', $menu[0]);
        $this->assertEquals('que_40', $menu[0]['id']);
        $this->assertArrayHasKey('choices', $menu[0]);
        $this->assertCount(4, $menu[0]['choices']);
        $this->assertEquals('que_42', $menu[0]['choices'][0]);
        $this->assertEquals('que_43', $menu[0]['choices'][1]);
        $this->assertEquals('que_44', $menu[0]['choices'][2]);
        $this->assertEquals('que_45', $menu[0]['choices'][3]);
        $this->assertArrayHasKey('allowMultipleSelections', $menu[0]);
        $this->assertEquals(0, $menu[0]['allowMultipleSelections']);
        $this->assertArrayHasKey('type', $menu[1]);
        $this->assertEquals('inputQuestion', $menu[1]['type']);
        $this->assertArrayHasKey('id', $menu[1]);
        $this->assertEquals('que_41', $menu[1]['id']);
        $this->assertArrayHasKey('inputTypes', $menu[1]);
        $this->assertCount(1, $menu[1]['inputTypes']);
        $this->assertEquals('TEXT_ALPHANUMERIC', $menu[1]['inputTypes'][0]);
        $this->assertArrayNotHasKey('defaultValue', $menu[1]);

        // search endpoint
        $this->assertCount(0, self::$adapter->contentList('search'));
        $responses = array(array('questionID' => 'que_24', 'value' => 'light')); // search by title 'light'
        $contentListRef = self::$adapter->menuNext($responses);
        $this->assertEquals('search', $contentListRef);
        $this->assertCount(1, self::$adapter->contentList('search'));
        $responses = array(array('questionID' => 'que_24', 'value' => 'zorro')); // search by title 'zorro'
        $contentListRef = self::$adapter->menuNext($responses);
        $this->assertEquals('search', $contentListRef);
        $this->assertCount(0, self::$adapter->contentList('search'));
        $responses = array(array('questionID' => 'que_23', 'value' => 'Henry James')); // search by author 'Henry James'
        $contentListRef = self::$adapter->menuNext($responses);
        $this->assertEquals('search', $contentListRef);
        $this->assertCount(1, self::$adapter->contentList('search'));
        $responses = array(array('questionID' => 'que_23', 'value' => 'zorro')); // search by author 'zorro'
        $contentListRef = self::$adapter->menuNext($responses);
        $this->assertEquals('search', $contentListRef);
        $this->assertCount(0, self::$adapter->contentList('search'));

        // browse endpoint
        $this->assertCount(0, self::$adapter->contentList('browse'));
        $responses = array(array('questionID' => 'que_30', 'value' => 'que_31')); // browse by title
        $contentListRef = self::$adapter->menuNext($responses);
        $this->assertEquals('browse', $contentListRef);
        $this->assertCount(3, self::$adapter->contentList('browse'));
        $responses = array(array('questionID' => 'que_30', 'value' => 'que_32')); // browse by daisy2
        $contentListRef = self::$adapter->menuNext($responses);
        $this->assertEquals('browse', $contentListRef);
        $this->assertCount(2, self::$adapter->contentList('browse'));
        $responses = array(array('questionID' => 'que_30', 'value' => 'que_33')); // browse by daisy3
        $contentListRef = self::$adapter->menuNext($responses);
        $this->assertEquals('browse', $contentListRef);
        $this->assertCount(1, self::$adapter->contentList('browse'));
    }

    public function testAddContentViaDynamicMenus()
    {
        // add content to booskhelf
        $issuedBefore = count(self::$adapter->contentList('issued'));
        $responses = array(array('questionID' => 'que_24', 'value' => 'light')); // search by title 'light'
        $contentListRef = self::$adapter->menuNext($responses);
        $this->assertEquals('search', $contentListRef);
        $this->assertCount(1, self::$adapter->contentList('search'));
        $responses = array(array('questionID' => 'que_30', 'value' => 'que_31')); // browse by title
        $contentListRef = self::$adapter->menuNext($responses);
        $this->assertEquals('browse', $contentListRef);
        $this->assertCount(3, self::$adapter->contentList('browse'));
        $this->assertTrue(self::$adapter->contentIssue('con_1')); // issue content
        $issuedAfter = count(self::$adapter->contentList('issued'));
        $this->assertEquals($issuedBefore+1, $issuedAfter);
    }

    private function assertContainsAny($needles, $haystack)
    {
        $result = false;
        foreach ($needles as $needle)
        {
            if (strpos($haystack, $needle) !== false)
            {
                $result = true;
                break;
            }
        }

        $needles = implode(' or ', $needles);
        $msg = "Failed asserting that $haystack' contains any of '$needles'.";
        $this->assertTrue($result, $msg);
    }
}

?>
