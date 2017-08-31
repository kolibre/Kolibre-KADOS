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

require_once('title.class.php');

class titleTest extends PHPUnit_Framework_TestCase
{
    /**
     * @group title
     * @group validate
     */
    public function testText()
    {
        $instance = new title();
        $this->assertFalse($instance->validate());
        $this->assertContains('title.text', $instance->getError());
        $instance->text = '';
        $this->assertFalse($instance->validate());
        $this->assertContains('title.text', $instance->getError());
        $instance->text = 'text';
        $this->assertTrue($instance->validate());
    }

    /**
     * @group title
     * @group validate
     */
    public function testAudio()
    {
        $instance = new title('text');
        $this->assertTrue($instance->validate());
        $instance->audio = '';
        $this->assertFalse($instance->validate());
        $this->assertContains('title.audio', $instance->getError());
        $instance->audio = new bookmarkAudio('src','clipBegin','clipEnd');
        $this->assertTrue($instance->validate());
    }
}

?>
