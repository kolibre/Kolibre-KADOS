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

require_once('resources.class.php');

class resourcesTest extends PHPUnit_Framework_TestCase
{
    /**
     * @group resources
     * @group validate
     */
    public function testResource()
    {
        $instance = new resources();
        $this->assertFalse($instance->validate());
        $this->assertContains('resources.resource', $instance->getError());
        $instance->resource = 'resource';
        $this->assertFalse($instance->validate());
        $this->assertContains('resources.resource', $instance->getError());
        $instance->resource = array();
        $this->assertFalse($instance->validate());
        $this->assertContains('resources.resource', $instance->getError());
        $instance->resource = array('resource');
        $this->assertFalse($instance->validate());
        $this->assertContains('resources.resource', $instance->getError());
        $resource = array(new resource('uri', 'mimeType', 1, 'localURI'));
        $instance->resource = $resource;
        $this->assertTrue($instance->validate());
    }

    /**
     * @group resources
     * @group validate
     */
    public function testReturnBy()
    {
        $resource = array(new resource('uri', 'mimeType', 1, 'localURI'));
        $instance = new resources($resource);
        $instance->returnBy = 1;
        $this->assertFalse($instance->validate());
        $this->assertContains('resources.returnBy', $instance->getError());
        $instance->returnBy = '';
        $this->assertFalse($instance->validate());
        $this->assertContains('resources.returnBy', $instance->getError());
        $instance->returnBy = 'returnBy';
        $this->assertTrue($instance->validate());
    }

    /**
     * @group resources
     * @group validate
     */
    public function testLastModifiedDate()
    {
        $resource = array(new resource('uri', 'mimeType', 1, 'localURI'));
        $instance = new resources($resource);
        $instance->lastModifiedDate = 1;
        $this->assertFalse($instance->validate());
        $this->assertContains('resources.lastModifiedDate', $instance->getError());
        $instance->lastModifiedDate = '';
        $this->assertFalse($instance->validate());
        $this->assertContains('resources.lastModifiedDate', $instance->getError());
        $instance->lastModifiedDate = 'lastModifiedDate';
        $this->assertTrue($instance->validate());
    }
}

?>
