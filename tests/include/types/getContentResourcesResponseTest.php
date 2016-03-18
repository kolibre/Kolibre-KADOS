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

require_once('getContentResourcesResponse.class.php');

class getContentResourcesResponseTest extends PHPUnit_Framework_TestCase
{
    /**
     * @group getContentResourcesResponse
     * @group validate
     */
    public function testResources()
    {
        $instance = new getContentResourcesResponse();
        $this->assertFalse($instance->validate());
        $this->assertContains('getContentResourcesResponse.resources', $instance->getError());
        $instance->resources = 'resources';
        $this->assertFalse($instance->validate());
        $this->assertContains('getContentResourcesResponse.resources', $instance->getError());
        $resource = array(new resource('uri', 'mimeType', 1, 'localURI', '2016-03-11T14:23:23+00:00'));
        $instance->resources = new resources($resource, null, '2016-03-11T14:23:23+00:00');
        $this->assertTrue($instance->validate());
    }
}

?>
