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

require_once('getContentResources.class.php');

class getContentResourcesTest extends PHPUnit_Framework_TestCase
{
    /**
     * @group getContentResources
     * @group validate
     */
    public function testContentID()
    {
        $instance = new getContentResources(NULL, "STREAM");
        $this->assertFalse($instance->validate());
        $this->assertContains('getContentResources.contentID', $instance->getError());
        $instance->contentID = 1;
        $this->assertFalse($instance->validate());
        $this->assertContains('getContentResources.contentID', $instance->getError());
        $instance->contentID = '';
        $this->assertFalse($instance->validate());
        $this->assertContains('getContentResources.contentID', $instance->getError());
        $instance->contentID = 'contentID';
        $this->assertTrue($instance->validate());
    }

    /**
     * @group getContentResources
     * @group validate
     */
    public function testAccessType()
    {
        $instance = new getContentResources('contentID',NULL);
        $this->assertFalse($instance->validate());
        $this->assertContains('getContentResources.accessType', $instance->getError());
        $instance->accessType = 1;
        $this->assertFalse($instance->validate());
        $this->assertContains('getContentResources.accessType', $instance->getError());
        $instance->accessType = '';
        $this->assertFalse($instance->validate());
        $this->assertContains('getContentResources.accessType', $instance->getError());
        $instance->accessType = 'STREAM';
        $this->assertTrue($instance->validate());
    }
}

?>
