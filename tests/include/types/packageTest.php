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

require_once('package.class.php');
require_once('resourceRef.class.php');

class packageTest extends PHPUnit_Framework_TestCase
{
    /**
     * @group package
     * @group validate
     */
    public function testResourceRef()
    {
        $package = new package(NULL, 'uri', 'mimetype', 1234, '2016-03-11T14:23:23+00:00');
        $this->assertFalse($package->validate());
        $this->assertContains('package.resourceRef', $package->getError());

        $package->resourceRef = '';
        $this->assertFalse($package->validate());
        $this->assertContains('package.resourceRef', $package->getError());

        $package->resourceRef = array();
        $this->assertFalse($package->validate());
        $this->assertContains('package.resourceRef', $package->getError());

        $package->resourceRef = array('resourceRef');
        $this->assertFalse($package->validate());
        $this->assertContains('package.resourceRef', $package->getError());

        $resourceRef = new resourceRef('localURI');
        // check the resourceRef is correctly instantiated
        $this->assertTrue($resourceRef->validate());
        $package->resourceRef = array($resourceRef);
        $this->assertTrue($package->validate());
     }

    /**
     * @group package
     * @group validate
     */
    public function testUri()
    {
        $resourceRef = new resourceRef('localURI');
        // check the resourceRef is correctly instantiated
        $this->assertTrue($resourceRef->validate());

        $package = new package(array($resourceRef), NULL, 'mimetype', 1234, '2016-03-11T14:23:23+00:00');
        $this->assertFalse($package->validate());
        $this->assertContains('package.uri', $package->getError());

        $package->uri = 1;
        $this->assertFalse($package->validate());
        $this->assertContains('package.uri', $package->getError());

        $package->uri = '';
        $this->assertFalse($package->validate());
        $this->assertContains('package.uri', $package->getError());

        $package->uri = 'uri';
        $this->assertTrue($package->validate());
    }

    /**
     * @group package
     * @group validate
     */
    public function testMimeType()
    {
        $resourceRef = new resourceRef('localURI');
        // check the resourceRef is correctly instantiated
        $this->assertTrue($resourceRef->validate());

        $package = new package(array($resourceRef), 'uri', NULL, 1234, '2016-03-11T14:23:23+00:00');
        $this->assertFalse($package->validate());
        $this->assertContains('package.mimeType', $package->getError());

        $package->mimeType = 1;
        $this->assertFalse($package->validate());
        $this->assertContains('package.mimeType', $package->getError());

        $package->mimeType = '';
        $this->assertFalse($package->validate());
        $this->assertContains('package.mimeType', $package->getError());

        $package->mimeType = 'mimeType';
        $this->assertTrue($package->validate());
    }

    /**
     * @group package
     * @group validate
     */
    public function testSize()
    {
        $resourceRef = new resourceRef('localURI');
        // check the resourceRef is correctly instantiated
        $this->assertTrue($resourceRef->validate());

        $package = new package(array($resourceRef), 'uri', 'mimeType', NULL, '2016-03-11T14:23:23+00:00');
        $this->assertFalse($package->validate());

        $package->size = '';
        $this->assertFalse($package->validate());
        $this->assertContains('package.size', $package->getError());

        $package->size = -14;
        $this->assertFalse($package->validate());
        $this->assertContains('package.size', $package->getError());

        $package->size = 15;
        $this->assertTrue($package->validate());
    }

    /**
     * @group package
     * @group validate
     */
    public function testLastModifiedDate()
    {
        $resourceRef = new resourceRef('localURI');
        // check the resourceRef is correctly instantiated
        $this->assertTrue($resourceRef->validate());

        $package = new package(array($resourceRef), 'uri', 'mimeType', 1234, NULL);
        $this->assertFalse($package->validate());

        $package->lastModifiedDate = 124;
        $this->assertFalse($package->validate());
        $this->assertContains('package.lastModifiedDate', $package->getError());

        $package->lastModifiedDate = '2016-03-11T14:23:23Z';
        $this->assertTrue($package->validate());

        $package->lastModifiedDate = '2016-03-11T14:23:23+00:00';
        $this->assertTrue($package->validate());
    }
}

?>
