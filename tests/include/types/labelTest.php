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

require_once('label.class.php');

class labelTest extends PHPUnit_Framework_TestCase
{
    /**
     * @group label
     * @group validate
     */
    public function testText()
    {
        $instance = new label(null, null, 'lang');
        $this->assertFalse($instance->validate());
        $this->assertContains('label.text', $instance->getError());
        $instance->text = 1;
        $this->assertFalse($instance->validate());
        $this->assertContains('label.text', $instance->getError());
        $instance->text = '';
        $this->assertFalse($instance->validate());
        $this->assertContains('label.text', $instance->getError());
        $instance->text = 'text';
        $this->assertTrue($instance->validate());
    }

    /**
     * @group label
     * @group validate
     */
    public function testAudio()
    {
        $instance = new label('text', null, 'lang');
        $instance->audio = 'audio';
        $this->assertFalse($instance->validate());
        $this->assertContains('label.audio', $instance->getError());
        $instance->audio = new audio('uri');
        $this->assertTrue($instance->validate());
    }

    /**
     * @group label
     * @group validate
     */
    public function testLang()
    {
        $instance = new label('text');
        $this->assertFalse($instance->validate());
        $this->assertContains('label.lang', $instance->getError());
        $instance->lang = 1;
        $this->assertFalse($instance->validate());
        $this->assertContains('label.lang', $instance->getError());
        $instance->lang = '';
        $this->assertFalse($instance->validate());
        $this->assertContains('label.lang', $instance->getError());
        $instance->lang = 'lang';
        $this->assertTrue($instance->validate());
    }

    /**
     * @group label
     * @group validate
     */
    public function testDir()
    {
        $instance = new label('text', null, 'lang');
        $instance->dir = 1;
        $this->assertFalse($instance->validate());
        $this->assertContains('label.', $instance->getError());
        $instance->dir = '';
        $this->assertFalse($instance->validate());
        $this->assertContains('label.', $instance->getError());
        $instance->dir = 'dir';
        $this->assertFalse($instance->validate());
        $this->assertContains('label.', $instance->getError());
        $instance->dir = 'ltr';
        $this->assertTrue($instance->validate());
        $instance->dir = 'rtl';
        $this->assertTrue($instance->validate());
    }
}

?>
