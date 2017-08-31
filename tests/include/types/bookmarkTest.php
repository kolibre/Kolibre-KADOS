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

require_once('bookmark.class.php');

class bookmarkTest extends PHPUnit_Framework_TestCase
{
    /**
     * @group bookmark
     * @group validate
     */
    public function testNcxRef()
    {
        $instance = new bookmark(null,'URI','timeOffset');
        $this->assertFalse($instance->validate());
        $this->assertContains('bookmark.ncxRef', $instance->getError());
        $instance->ncxRef = '';
        $this->assertFalse($instance->validate());
        $this->assertContains('bookmark.ncxRef', $instance->getError());
        $instance->ncxRef = 'ncxRef';
        $this->assertTrue($instance->validate());
    }

    /**
     * @group bookmark
     * @group validate
     */
    public function testURI()
    {
        $instance = new bookmark('ncxRef',null,'timeOffset');
        $this->assertFalse($instance->validate());
        $this->assertContains('bookmark.URI', $instance->getError());
        $instance->URI = '';
        $this->assertFalse($instance->validate());
        $this->assertContains('bookmark.URI', $instance->getError());
        $instance->URI = 'URI';
        $this->assertTrue($instance->validate());
    }

    /**
     * @group bookmark
     * @group validate
     */
    public function testTimeOffset()
    {
        $instance = new bookmark('ncxRef','URI');
        $this->assertFalse($instance->validate());
        $this->assertContains('bookmark.timeOffset', $instance->getError());
        $instance->timeOffset = '';
        $this->assertFalse($instance->validate());
        $this->assertContains('bookmark.timeOffset', $instance->getError());
        $instance->timeOffset = 'timeOffset';
        $this->assertTrue($instance->validate());
        $instance->charOffset = 1;
        $this->assertTrue($instance->validate());
    }

    /**
     * @group bookmark
     * @group validate
     */
    public function testCharOffset()
    {
        $instance = new bookmark('ncxRef','URI');
        $this->assertFalse($instance->validate());
        $this->assertContains('bookmark.charOffset', $instance->getError());
        $instance->charOffset = -1;
        $this->assertFalse($instance->validate());
        $this->assertContains('bookmark.charOffset', $instance->getError());
        $instance->charOffset = 1;
        $this->assertTrue($instance->validate());
        $instance->timeOffset = 'timeOffset';
        $this->assertTrue($instance->validate());
    }

    public function testNote()
    {
        $instance = new bookmark('ncxRef','URI','timeOffset');
        $this->assertTrue($instance->validate());
        $instance->note = 1;
        $this->assertFalse($instance->validate());
        $this->assertContains('bookmark.note', $instance->getError());
        $instance->note = new note();
        $this->assertTrue($instance->validate());
    }

    public function testLabel()
    {
        $instance = new bookmark('ncxRef','URI','timeOffset');
        $this->assertTrue($instance->validate());
        $instance->label = '';
        $this->assertFalse($instance->validate());
        $this->assertContains('bookmark.label', $instance->getError());
        $instance->label = 'label';
        $this->assertTrue($instance->validate());
    }

    public function testLang()
    {
        $instance = new bookmark('ncxRef','URI','timeOffset');
        $this->assertTrue($instance->validate());
        $instance->lang = '';
        $this->assertFalse($instance->validate());
        $this->assertContains('bookmark.lang', $instance->getError());
        $instance->lang = 'en';
        $this->assertTrue($instance->validate());
    }
}

?>
