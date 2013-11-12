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
        $this->assertFalse(self::$adapter->contentListExists('old'));
        $this->assertTrue(self::$adapter->contentListExists('new'));
        $this->assertTrue(self::$adapter->contentListExists('issued'));
        $this->assertTrue(self::$adapter->contentListExists('expired'));
        $this->assertTrue(self::$adapter->contentListExists('returned'));
    }

    public function testContentListId()
    {
        $this->assertLessThan(0, self::$adapter->contentListId('old'));
        $this->assertGreaterThan(0, self::$adapter->contentListId('new'));
        $this->assertGreaterThan(0, self::$adapter->contentListId('issued'));
        $this->assertGreaterThan(0, self::$adapter->contentListId('expired'));
        $this->assertGreaterThan(0, self::$adapter->contentListId('returned'));
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
        $this->assertCount(2, self::$adapter->contentList('new'));
        $this->assertEmpty(self::$adapter->contentList('issued'));
        $this->assertEmpty(self::$adapter->contentList('expired'));
        $this->assertEmpty(self::$adapter->contentList('returned'));

        // filtered
        $this->assertCount(1, self::$adapter->contentList('new', array('Daisy 2.02')));
        $this->assertCount(1, self::$adapter->contentList('new', array('DAISY 2.02')));
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
}

?>
