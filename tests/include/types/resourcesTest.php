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
require_once('package.class.php');

class resourcesTest extends PHPUnit_Framework_TestCase
{
    /**
     * @group resources
     * @group validate
     */
    public function testResource()
    {
        $instance = new resources(NULL, NULL,'2016-03-11T14:23:23+00:00');
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
        $resource = array(new resource('uri', null, 1, 'localURI','2016-03-11T14:23:23+00:00'));
        $instance->resource = $resource;
        $this->assertFalse($instance->validate());
        $this->assertContains('resources.resource', $instance->getError());
        $resource = array(new resource('uri', 'mimeType', 1, 'localURI','2016-03-11T14:23:23+00:00'));
        $instance->resource = $resource;
        $instance->lastModifiedDate = '2016-03-11T14:23:23+00:00';
        $this->assertTrue($instance->validate());
    }

    /**
     * @group resources
     * @group validate
     */
    public function testPackage()
    {   
        $instance = new resources();
        $resource = array(new resource('uri', 'mimeType', 1, 'localURI','2016-03-11T14:23:23+00:00'));
        $this->assertTrue($resource[0]->validate());
        $instance->resource = $resource;
        $instance->lastModifiedDate = '2016-03-11T14:23:23+00:00';
        $this->assertTrue($instance->validate());
        $instance->package = 'package';
        $this->assertFalse($instance->validate());
        $this->assertContains('resources.package', $instance->getError());
        $instance->package = array();
        $this->assertFalse($instance->validate());
        $this->assertContains('resources.package', $instance->getError());
        $instance->package = array('package');
        $this->assertFalse($instance->validate());
        $this->assertContains('resources.package', $instance->getError());
        $package = array(new package(NULL, 'uri', 'mimetype', 1234, '2016-03-11T14:23:23Z'));
        $instance->package = $package;
        $this->assertFalse($instance->validate());
        // invalid resource since resourceRef is null and not resourceRef object
        $this->assertContains('resources.resource', $instance->getError());
        $resourceRef = new resourceRef('localURI');
        $package_array = array(new package($resourceRef, 'uri', 'mimetype', 1234, '2016-03-11T14:23:23Z'));
        $instance->package = $package_array;
        $this->assertTrue($instance->validate());
        
    }

    /**
     * @group resources
     * @group validate
     */
    public function testLastModifiedDate()
    {
        $resource = array(new resource('uri', 'mimeType', 1, 'localURI','2016-03-11T14:23:23+00:00'));
        $instance = new resources($resource);
        $instance->lastModifiedDate = 1;
        $this->assertFalse($instance->validate());
        $this->assertContains('resources.lastModifiedDate', $instance->getError());
        $instance->lastModifiedDate = '';
        $this->assertFalse($instance->validate());
        $this->assertContains('resources.lastModifiedDate', $instance->getError());
        $instance->lastModifiedDate = 'lastModifiedDate';
        $this->assertFalse($instance->validate());
        $this->assertContains('resources.lastModifiedDate', $instance->getError());
        $instance->lastModifiedDate = '2016-03-11T14:23:23+00:00';
        $this->assertTrue($instance->validate());
    }
}

?>
