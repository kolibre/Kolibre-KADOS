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

require_once('resource.class.php');

class resourceTest extends PHPUnit_Framework_TestCase
{
    /**
     * @group resource
     * @group validate
     */
    public function testUri()
    {
        $instance = new resource(null, 'mimeType', 1, 'localURI');
        $this->assertFalse($instance->validate());
        $this->assertContains('resource.uri', $instance->getError());
        $instance->uri = 1;
        $this->assertFalse($instance->validate());
        $this->assertContains('resource.uri', $instance->getError());
        $instance->uri = '';
        $this->assertFalse($instance->validate());
        $this->assertContains('resource.uri', $instance->getError());
        $instance->uri = 'uri';
        $this->assertTrue($instance->validate());
    }

    /**
     * @group resource
     * @group validate
     */
    public function testMimeType()
    {
        $instance = new resource('uri', null, 1, 'localURI');
        $this->assertFalse($instance->validate());
        $this->assertContains('resource.mimeType', $instance->getError());
        $instance->mimeType = 1;
        $this->assertFalse($instance->validate());
        $this->assertContains('resource.mimeType', $instance->getError());
        $instance->mimeType = '';
        $this->assertFalse($instance->validate());
        $this->assertContains('resource.mimeType', $instance->getError());
        $instance->mimeType = 'mimeType';
        $this->assertTrue($instance->validate());
    }

    /**
     * @group resource
     * @group validate
     */
    public function testSize()
    {
        $instance = new resource('uri', 'mimeType', null, 'localURI');
        $this->assertFalse($instance->validate());
        $this->assertContains('resource.size', $instance->getError());
        $instance->size = 'size';
        $this->assertFalse($instance->validate());
        $this->assertContains('resource.size', $instance->getError());
        $instance->size = -1;
        $this->assertFalse($instance->validate());
        $this->assertContains('resource.size', $instance->getError());
        $instance->size = 0;
        $this->assertTrue($instance->validate());
    }

    /**
     * @group resource
     * @group validate
     */
    public function testLocalURI()
    {
        $instance = new resource('uri', 'mimeType', 1, null);
        $this->assertFalse($instance->validate());
        $this->assertContains('resource.localURI', $instance->getError());
        $instance->localURI = 1;
        $this->assertFalse($instance->validate());
        $this->assertContains('resource.localURI', $instance->getError());
        $instance->localURI = '';
        $this->assertFalse($instance->validate());
        $this->assertContains('resource.localURI', $instance->getError());
        $instance->localURI = 'localURI';
        $this->assertTrue($instance->validate());
    }

    /**
     * @group resource
     * @group validate
     */
    public function testLastModifiedDate()
    {
        $instance = new resource('uri', 'mimeType', 1, 'localURI');
        $instance->lastModifiedDate = 1;
        $this->assertFalse($instance->validate());
        $this->assertContains('resource.lastModifiedDate', $instance->getError());
        $instance->lastModifiedDate = '';
        $this->assertFalse($instance->validate());
        $this->assertContains('resource.lastModifiedDate', $instance->getError());
        $instance->lastModifiedDate = 'lastModifiedDate';
        $this->assertTrue($instance->validate());
    }
}

?>
