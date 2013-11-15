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

require_once('contentList.class.php');

class contentListTest extends PHPUnit_Framework_TestCase
{
    /**
     * @group contentList
     * @group validate
     */
    public function testlabel()
    {
        $instance = new contentList(null, null, 0, null, null, 'id');
        $instance->label = 'label';
        $this->assertFalse($instance->validate());
        $this->assertContains('contentList.label', $instance->getError());
        $instance->label = new label('text', null, 'lang');
        $this->assertTrue($instance->validate());
    }

    /**
     * @group contentList
     * @group validate
     */
    public function testContentItem()
    {
        $instance = new contentList(null, null, 0, null, null, 'id');
        $instance->contentItem = 'contentItem';
        $this->assertFalse($instance->validate());
        $this->assertContains('contentList.contentItem', $instance->getError());
        $instance->contentItem = array('contentItem');
        $this->assertFalse($instance->validate());
        $this->assertContains('contentList.contentItem', $instance->getError());
        $instance->contentItem = array();
        $this->assertTrue($instance->validate());
        $instance->contentItem = array(new contentItem(new label('text', null, 'lang'), 'id'));
        $this->assertTrue($instance->validate());
    }

    /**
     * @group contentList
     * @group validate
     */
    public function testTotalItems()
    {
        $instance = new contentList(null, null, null, null, null, 'id');
        $this->assertFalse($instance->validate());
        $this->assertContains('contentList.totalItems', $instance->getError());
        $instance->totalItems = 'totalItems';
        $this->assertFalse($instance->validate());
        $this->assertContains('contentList.totalItems', $instance->getError());
        $instance->totalItems = -1;
        $this->assertFalse($instance->validate());
        $this->assertContains('contentList.totalItems', $instance->getError());
        $instance->totalItems = 0;
        $this->assertTrue($instance->validate());
    }

    /**
     * @group contentList
     * @group validate
     */
    public function testFirstItem()
    {
        $instance = new contentList(null, null, 0, null, 1, 'id');
        $this->assertFalse($instance->validate());
        $this->assertContains('contentList.firstItem', $instance->getError());
        $instance->firstItem = 'firstItem';
        $this->assertFalse($instance->validate());
        $this->assertContains('contentList.firstItem', $instance->getError());
        $instance->firstItem = -1;
        $this->assertFalse($instance->validate());
        $this->assertContains('contentList.firstItem', $instance->getError());
        $instance->firstItem = 0;
        $this->assertTrue($instance->validate());
    }

    /**
     * @group contentList
     * @group validate
     */
    public function testlastItem()
    {
        $instance = new contentList(null, null, 0, 1, null, 'id');
        $this->assertFalse($instance->validate());
        $this->assertContains('contentList.lastItem', $instance->getError());
        $instance->lastItem = 'lastItem';
        $this->assertFalse($instance->validate());
        $this->assertContains('contentList.lastItem', $instance->getError());
        $instance->lastItem = -1;
        $this->assertFalse($instance->validate());
        $this->assertContains('contentList.lastItem', $instance->getError());
        $instance->lastItem = 0;
        $this->assertFalse($instance->validate());
        $this->assertContains('contentList.lastItem', $instance->getError());
        $instance->lastItem = 1;
        $this->assertFalse($instance->validate());
        $this->assertContains('contentList.lastItem', $instance->getError());
        $instance->lastItem = 2;
        $this->assertTrue($instance->validate());
    }

    /**
     * @group contentList
     * @group validate
     */
    public function testId()
    {
        $instance = new contentList(null, null, 0, null, null, null);
        $this->assertFalse($instance->validate());
        $this->assertContains('contentList.id', $instance->getError());
        $instance->id = 1;
        $this->assertFalse($instance->validate());
        $this->assertContains('contentList.id', $instance->getError());
        $instance->id = '';
        $this->assertFalse($instance->validate());
        $this->assertContains('contentList.id', $instance->getError());
        $instance->id = 'id';
        $this->assertTrue($instance->validate());
    }
}

?>
