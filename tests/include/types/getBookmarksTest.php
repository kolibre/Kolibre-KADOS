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

require_once('getBookmarks.class.php');

class getBookmarksTest extends PHPUnit_Framework_TestCase
{
    /**
     * @group getBookmarks
     * @group validate
     */
    public function testContentID()
    {
        $instance = new getBookmarks(NULL, 'ALL');
        $this->assertFalse($instance->validate());
        $this->assertContains('getBookmarks.contentID', $instance->getError());
        $instance->contentID = 1;
        $this->assertFalse($instance->validate());
        $this->assertContains('getBookmarks.contentID', $instance->getError());
        $instance->contentID = '';
        $this->assertFalse($instance->validate());
        $this->assertContains('getBookmarks.contentID', $instance->getError());
        $instance->contentID = 'contentID';
        $this->assertTrue($instance->validate());
    }

    /**
     * @group getBookmarks
     * @group validate
     */
    public function testAction()
    {
        $instance = new getBookmarks('contentID', NULL);
        $this->assertFalse($instance->validate());
        $this->assertContains('getBookmarks.action', $instance->getError());
        $instance->action = 1;
        $this->assertFalse($instance->validate());
        $this->assertContains('getBookmarks.action', $instance->getError());
        $instance->action = '';
        $this->assertFalse($instance->validate());
        $this->assertContains('getBookmarks.action', $instance->getError());
        $instance->action = 'action';
        $this->assertFalse($instance->validate());
        $this->assertContains('getBookmarks.action', $instance->getError());
        $instance->action = 'LASTMARK';
        $this->assertTrue($instance->validate());
        $instance->action = 'HILITE';
        $this->assertTrue($instance->validate());
        $instance->action = 'BOOKMARK';
        $this->assertTrue($instance->validate());
        $instance->action = 'ALL';
        $this->assertTrue($instance->validate());
    }
}

?>
