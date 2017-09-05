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

require_once('hiliteStart.class.php');

class hiliteStartTest extends PHPUnit_Framework_TestCase
{
    /**
     * @group hiliteStart
     * @group validate
     */
    public function testNcxRef()
    {
        $instance = new hiliteStart(null,'URI','timeOffset');
        $this->assertFalse($instance->validate());
        $this->assertContains('hiliteStart.ncxRef', $instance->getError());
        $instance->ncxRef = '';
        $this->assertFalse($instance->validate());
        $this->assertContains('hiliteStart.ncxRef', $instance->getError());
        $instance->ncxRef = 'ncxRef';
        $this->assertTrue($instance->validate());
    }

    /**
     * @group hiliteStart
     * @group validate
     */
    public function testURI()
    {
        $instance = new hiliteStart('ncxRef',null,'timeOffset');
        $this->assertFalse($instance->validate());
        $this->assertContains('hiliteStart.URI', $instance->getError());
        $instance->URI = '';
        $this->assertFalse($instance->validate());
        $this->assertContains('hiliteStart.URI', $instance->getError());
        $instance->URI = 'URI';
        $this->assertTrue($instance->validate());
    }

    /**
     * @group hiliteStart
     * @group validate
     */
    public function testTimeOffset()
    {
        $instance = new hiliteStart('ncxRef','URI');
        $this->assertFalse($instance->validate());
        $this->assertContains('hiliteStart.timeOffset', $instance->getError());
        $instance->timeOffset = '';
        $this->assertFalse($instance->validate());
        $this->assertContains('hiliteStart.timeOffset', $instance->getError());
        $instance->timeOffset = 'timeOffset';
        $this->assertTrue($instance->validate());
        $instance->charOffset = 1;
        $this->assertTrue($instance->validate());
    }

    /**
     * @group hiliteStart
     * @group validate
     */
    public function testCharOffset()
    {
        $instance = new hiliteStart('ncxRef','URI');
        $this->assertFalse($instance->validate());
        $this->assertContains('hiliteStart.charOffset', $instance->getError());
        $instance->charOffset = -1;
        $this->assertFalse($instance->validate());
        $this->assertContains('hiliteStart.charOffset', $instance->getError());
        $instance->charOffset = 1;
        $this->assertTrue($instance->validate());
        $instance->timeOffset = 'timeOffset';
        $this->assertTrue($instance->validate());
    }
}

?>
