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

require_once('getContentList.class.php');

class getContentListTest extends PHPUnit_Framework_TestCase
{
    /**
     * @group getContentList
     * @group validate
     */
    public function testId()
    {
        $instance = new getContentList(null, 0, -1);
        $this->assertFalse($instance->validate());
        $this->assertContains('getContentList.id', $instance->getError());
        $instance->id = 1;
        $this->assertFalse($instance->validate());
        $this->assertContains('getContentList.id', $instance->getError());
        $instance->id = '';
        $this->assertFalse($instance->validate());
        $this->assertContains('getContentList.id', $instance->getError());
        $instance->id = 'id';
        $this->assertTrue($instance->validate(), $instance->getError());
    }

    /**
     * @group getContentList
     * @group validate
     */
    public function testFirstItem()
    {
        $instance = new getContentList('id', null, -1);
        $this->assertFalse($instance->validate());
        $this->assertContains('getContentList.firstItem', $instance->getError());
        $instance->firstItem = 'firstItem';
        $this->assertFalse($instance->validate());
        $this->assertContains('getContentList.firstItem', $instance->getError());
        $instance->firstItem = -1;
        $this->assertFalse($instance->validate());
        $this->assertContains('getContentList.firstItem', $instance->getError());
        $instance->firstItem = 0;
        $this->assertTrue($instance->validate(), $instance->getError());
        $instance->firstItem = 1;
        $this->assertTrue($instance->validate(), $instance->getError());
    }

    /**
     * @group getContentList
     * @group validate
     */
    public function testLastItem()
    {
        $instance = new getContentList('id', 1, null);
        $this->assertFalse($instance->validate());
        $this->assertContains('getContentList.lastItem', $instance->getError());
        $instance->lastItem = 'lastItem';
        $this->assertFalse($instance->validate());
        $this->assertContains('getContentList.lastItem', $instance->getError());
        $instance->lastItem = 0;
        $this->assertFalse($instance->validate());
        $this->assertContains('getContentList.lastItem', $instance->getError());
        $instance->lastItem = -2;
        $this->assertFalse($instance->validate());
        $this->assertContains('getContentList.lastItem', $instance->getError());
        $instance->lastItem = -1;
        $this->assertTrue($instance->validate(), $instance->getError());
        $instance->lastItem = 1;
        $this->assertTrue($instance->validate(), $instance->getError());
        $instance->lastItem = 2;
        $this->assertTrue($instance->validate(), $instance->getError());
    }
}

?>
