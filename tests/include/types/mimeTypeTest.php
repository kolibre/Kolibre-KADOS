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

require_once('mimeType.class.php');

class mimeTypeTest extends PHPUnit_Framework_TestCase
{
    /**
     * @group mimeType
     * @group validate
     */
    public function testType()
    {
        $instance = new mimeType();
        $this->assertFalse($instance->validate());
        $this->assertContains('mimeType.type', $instance->getError());
        $instance->type = 1;
        $this->assertFalse($instance->validate());
        $this->assertContains('mimeType.type', $instance->getError());
        $instance->type = '';
        $this->assertFalse($instance->validate());
        $this->assertContains('mimeType.type', $instance->getError());
        $instance->type = 'type';
        $this->assertTrue($instance->validate());
    }

    /**
     * @group mimeType
     * @group validate
     */
    public function testLang()
    {
        $instance = new mimeType('type');
        $instance->lang = 1;
        $this->assertFalse($instance->validate());
        $this->assertContains('mimeType.lang', $instance->getError());
        $instance->lang = '';
        $this->assertFalse($instance->validate());
        $this->assertContains('mimeType.lang', $instance->getError());
        $instance->lang = 'lang';
        $this->assertTrue($instance->validate());
    }
}

?>
