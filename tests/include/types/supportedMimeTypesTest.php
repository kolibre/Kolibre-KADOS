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

require_once('supportedMimeTypes.class.php');

class supportedMimeTypesTest extends PHPUnit_Framework_TestCase
{
    /**
     * @group supportedMimeTypes
     * @group validate
     */
    public function testMimeType()
    {
        $instance = new supportedMimeTypes();
        $this->assertTrue($instance->validate());
        $instance->mimeType = 'mimeType';
        $this->assertFalse($instance->validate());
        $this->assertContains('supportedMimeTypes.mimeType', $instance->getError());
        $instance->mimeType = array('mimeType');
        $this->assertFalse($instance->validate());
        $this->assertContains('supportedMimeTypes.mimeType', $instance->getError());
        $instance->mimeType = array();
        $this->assertTrue($instance->validate());
        $instance->mimeType = array(new mimeType('type'));
        $this->assertTrue($instance->validate());
    }
}

?>
