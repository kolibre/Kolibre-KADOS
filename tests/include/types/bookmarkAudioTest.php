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

require_once('bookmarkAudio.class.php');

class bookmarkAudioTest extends PHPUnit_Framework_TestCase
{
    /**
     * @group bookmarkAudio
     * @group validate
     */
    public function testSrc()
    {
        $instance = new bookmarkAudio(null,'clipBegin','clipEnd');
        $this->assertFalse($instance->validate());
        $this->assertContains('bookmarkAudio.src', $instance->getError());
        $instance->src = '';
        $this->assertFalse($instance->validate());
        $this->assertContains('bookmarkAudio.src', $instance->getError());
        $instance->src = 'src';
        $this->assertTrue($instance->validate());
    }

    /**
     * @group bookmarkAudio
     * @group validate
     */
    public function testClipBegin()
    {
        $instance = new bookmarkAudio('src',null,'clipEnd');
        $this->assertFalse($instance->validate());
        $this->assertContains('bookmarkAudio.clipBegin', $instance->getError());
        $instance->clipBegin = '';
        $this->assertFalse($instance->validate());
        $this->assertContains('bookmarkAudio.clipBegin', $instance->getError());
        $instance->clipBegin = 'clipBegin';
        $this->assertTrue($instance->validate());
    }

    /**
     * @group bookmarkAudio
     * @group validate
     */
    public function testClipEnd()
    {
        $instance = new bookmarkAudio('src','clipBegin',null);
        $this->assertFalse($instance->validate());
        $this->assertContains('bookmarkAudio.clipEnd', $instance->getError());
        $instance->clipEnd = '';
        $this->assertFalse($instance->validate());
        $this->assertContains('bookmarkAudio.clipEnd', $instance->getError());
        $instance->clipEnd = 'clipEnd';
        $this->assertTrue($instance->validate());
    }
}

?>
