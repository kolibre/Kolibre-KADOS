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

$includePath = dirname(realpath(__FILE__)) . '/../../../include/types';
set_include_path(get_include_path() . PATH_SEPARATOR . $includePath);

require_once('bookmarkSet.class.php');

class bookmarkSetTest extends PHPUnit_Framework_TestCase
{
    /**
     * @group bookmarkSet
     * @group validate
     */
    public function testTitle()
    {
        $instance = new bookmarkSet(null,'uid');
        $this->assertFalse($instance->validate());
        $this->assertContains('bookmarkSet.title', $instance->getError());
        $instance->title = '';
        $this->assertFalse($instance->validate());
        $this->assertContains('bookmarkSet.title', $instance->getError());
        $instance->title = new title('text');
        $this->assertTrue($instance->validate());
    }

    /**
     * @group bookmarkSet
     * @group validate
     */
    public function testUid()
    {
        $instance = new bookmarkSet(new title('text'),null);
        $this->assertFalse($instance->validate());
        $this->assertContains('bookmarkSet.uid', $instance->getError());
        $instance->uid = '';
        $this->assertFalse($instance->validate());
        $this->assertContains('bookmarkSet.uid', $instance->getError());
        $instance->uid = 'uid';
        $this->assertTrue($instance->validate());
    }

    /**
     * @group bookmarkSet
     * @group validate
     */
    public function testLastmark()
    {
        $instance = new bookmarkSet(new title('text'),'uid');
        $this->assertTrue($instance->validate());
        $instance->lastmark = '';
        $this->assertFalse($instance->validate());
        $this->assertContains('bookmarkSet.lastmark', $instance->getError());
        $instance->lastmark = new lastmark('ncxRef','URI','timeOffset');
        $this->assertTrue($instance->validate());
    }

    /**
     * @group bookmarkSet
     * @group validate
     */
    public function testBookmark()
    {
        $instance = new bookmarkSet(new title('text'),'uid');
        $this->assertTrue($instance->validate());
        $instance->bookmark = '';
        $this->assertFalse($instance->validate());
        $this->assertContains('bookmarkSet.bookmark', $instance->getError());
        $instance->bookmark = array(new bookmark('ncxRef','URI','timeOffset'));
        $this->assertTrue($instance->validate());
    }

    /**
     * @group bookmarkSet
     * @group validate
     */
    public function testHilite()
    {
        $instance = new bookmarkSet(new title('text'),'uid');
        $this->assertTrue($instance->validate());
        $instance->hilite = '';
        $this->assertFalse($instance->validate());
        $this->assertContains('bookmarkSet.hilite', $instance->getError());
        $instance->hilite = array(new hilite(new hiliteStart('ncxRef','URI','timeOffset'), new hiliteEnd('ncxRef','URI','timeOffset')));
        $this->assertTrue($instance->validate());
    }

    /**
     * @group bookmarkSet
     * @group addBookmark
     * @group addHilite
     */
    public function testAddBookmarkAndHilite()
    {
        $instance = new bookmarkSet(new title('text'),'uid');
        $this->assertNull($instance->bookmark);
        $this->assertNull($instance->hilite);
        $bookmark = new bookmark('ncxRef','uri','timeOffset');
        $hilite = new hilite(new hiliteStart('ncxRef','uri','timeOffset'),new hiliteEnd('ncxRef','uri','timeOffset'));
        $instance->addBookmark($bookmark);
        $this->assertCount(1, $instance->bookmark);
        $this->assertArrayHasKey(1,$instance->bookmark);
        $instance->addHilite($hilite);
        $this->assertCount(1, $instance->hilite);
        $this->assertArrayHasKey(2,$instance->hilite);
        $instance->addBookmark($bookmark);
        $instance->addBookmark($bookmark);
        $this->assertCount(3, $instance->bookmark);
        $this->assertArrayHasKey(3,$instance->bookmark);
        $this->assertArrayHasKey(4,$instance->bookmark);
        $instance->addHilite($hilite);
        $this->assertCount(2, $instance->hilite);
        $this->assertArrayHasKey(5,$instance->hilite);
    }

    /**
     * @group bookmarkSet
     * @group addBookmarkUnlessExists
     * @group removeBookmarkIfExists
     */
    public function testAddRemoveBookmark()
    {
        $instance = new bookmarkSet(new title('text'),'uid');
        $this->assertNull($instance->bookmark);
        $bookmark1 = new bookmark('ncxRef','uri','timeOffset');
        $this->assertTrue($instance->addBookmarkUnlessExist($bookmark1));
        $this->assertFalse($instance->addBookmarkUnlessExist($bookmark1));
        $this->assertCount(1, $instance->bookmark);
        $this->assertArrayHasKey(1,$instance->bookmark);
        $bookmark2 = new bookmark('ncxRef','uri','timeOffset',1);
        $this->assertTrue($instance->addBookmarkUnlessExist($bookmark2));
        $this->assertFalse($instance->addBookmarkUnlessExist($bookmark2));
        $this->assertCount(2, $instance->bookmark);
        $this->assertArrayHasKey(1,$instance->bookmark);
        $this->assertArrayHasKey(2,$instance->bookmark);
        $bookmark3 = new bookmark('ncxRef','uri',null,2);
        $this->assertFalse($instance->removeBookmarkIfExist($bookmark3));
        $this->assertCount(2, $instance->bookmark);
        $this->assertTrue($instance->removeBookmarkIfExist($bookmark2));
        $this->assertTrue($instance->removeBookmarkIfExist($bookmark1));
        $this->assertCount(0, $instance->bookmark);
    }

    /**
     * @group bookmarkSet
     * @group addHilitekUnlessExists
     * @group removeHilitekIfExists
     */
    public function testAddRemoveHilite()
    {
        $instance = new bookmarkSet(new title('text'),'uid');
        $this->assertNull($instance->hilite);
        $hilite1 = new hilite(new hiliteStart('ncxRef','uri','timeOffset'),new hiliteEnd('ncxRef','uri','timeOffset'));
        $this->assertTrue($instance->addHiliteUnlessExist($hilite1));
        $this->assertFalse($instance->addHiliteUnlessExist($hilite1));
        $this->assertCount(1, $instance->hilite);
        $this->assertArrayHasKey(1,$instance->hilite);
        $hilite2 = new hilite(new hiliteStart('ncxRef','uri','timeOffset',1),new hiliteEnd('ncxRef','uri','timeOffset',1));
        $this->assertTrue($instance->addHiliteUnlessExist($hilite2));
        $this->assertFalse($instance->addHiliteUnlessExist($hilite2));
        $this->assertCount(2, $instance->hilite);
        $this->assertArrayHasKey(1,$instance->hilite);
        $this->assertArrayHasKey(2,$instance->hilite);
        $hilite3 = new hilite(new hiliteStart('ncxRef','uri',null,2),new hiliteEnd('ncxRef','uri',null,2));
        $this->assertFalse($instance->removeHiliteIfExist($hilite3));
        $this->assertCount(2, $instance->hilite);
        $this->assertTrue($instance->removeHiliteIfExist($hilite2));
        $this->assertTrue($instance->removeHiliteIfExist($hilite1));
        $this->assertCount(0, $instance->hilite);
    }
}

?>
