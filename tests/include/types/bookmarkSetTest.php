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
}

?>
