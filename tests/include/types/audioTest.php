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

require_once('audio.class.php');

class audioTest extends PHPUnit_Framework_TestCase
{
    /**
     * @group audio
     * @group validate
     */
    public function testUri()
    {
        $instance = new audio();
        $this->assertFalse($instance->validate());
        $this->assertContains('audio.uri', $instance->getError());
        $instance->uri = 1;
        $this->assertFalse($instance->validate());
        $this->assertContains('audio.uri', $instance->getError());
        $instance->uri = '';
        $this->assertFalse($instance->validate());
        $this->assertContains('audio.uri', $instance->getError());
        $instance->uri = 'anyURI';
        $this->assertTrue($instance->validate());
    }

    /**
     * @group audio
     * @group validate
     */
    public function testRangeBegin()
    {
        $instance = new audio('uri', null, 1);
        $this->assertFalse($instance->validate());
        $this->assertContains('audio.rangeBegin', $instance->getError());
        $instance->rangeBegin = 'rangeBegin';
        $this->assertFalse($instance->validate());
        $this->assertContains('audio.rangeBegin', $instance->getError());
        $instance->rangeBegin = -1;
        $this->assertFalse($instance->validate());
        $this->assertContains('audio.rangeBegin', $instance->getError());
        $instance->rangeBegin = 1;
        $this->assertTrue($instance->validate());
    }

    /**
     * @group audio
     * @group validate
     */
    public function testRangeEnd()
    {
        $instance = new audio('uri', 1);
        $this->assertFalse($instance->validate());
        $this->assertContains('audio.rangeEnd', $instance->getError());
        $instance->rangeEnd = 'rangeEnd';
        $this->assertFalse($instance->validate());
        $this->assertContains('audio.rangeEnd', $instance->getError());
        $instance->rangeEnd = -1;
        $this->assertFalse($instance->validate());
        $this->assertContains('audio.rangeEnd', $instance->getError());
        $instance->rangeEnd = 1;
        $this->assertTrue($instance->validate());
    }

    /**
     * @group audio
     * @group validate
     */
    public function testSize()
    {
        $instance = new audio('uri');
        $instance->size = 'size';
        $this->assertFalse($instance->validate());
        $this->assertContains('audio.size', $instance->getError());
        $instance->size = -1;
        $this->assertFalse($instance->validate());
        $this->assertContains('audio.size', $instance->getError());
        $instance->size = 1;
        $this->assertTrue($instance->validate());
    }
}

?>
