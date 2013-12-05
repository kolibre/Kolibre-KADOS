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

require_once('serviceProvider.class.php');

class serviceProviderTest extends PHPUnit_Framework_TestCase
{
    /**
     * @group serviceProvider
     * @group validate
     */
    public function testId()
    {
        $instance = new serviceProvider();
        $this->assertFalse($instance->validate());
        $this->assertContains('serviceProvider.id', $instance->getError());
        $instance->id = 1;
        $this->assertFalse($instance->validate());
        $this->assertContains('serviceProvider.id', $instance->getError());
        $instance->id = '';
        $this->assertFalse($instance->validate());
        $this->assertContains('serviceProvider.id', $instance->getError());
        $instance->id = 'id';
        $this->assertTrue($instance->validate());
    }

    /**
     * @group serviceProvider
     * @group validate
     */
    public function testLabel()
    {
        $instance = new serviceProvider(null, 'id');
        $instance->label = 'label';
        $this->assertFalse($instance->validate());
        $this->assertContains('serviceProvider.label', $instance->getError());
        $instance->label = new label('text', null, 'lang');
        $this->assertTrue($instance->validate());
    }
}

?>
