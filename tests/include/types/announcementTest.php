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

require_once('announcement.class.php');

class announcementTest extends PHPUnit_Framework_TestCase
{
    /**
     * @group announcement
     * @group validate
     */
    public function testLabel()
    {
        $instance = new announcement(null, 'text', null, 'LOW');
        $this->assertFalse($instance->validate());
        $this->assertContains('announcement.label', $instance->getError());
        $instance->label = 'label';
        $this->assertFalse($instance->validate());
        $this->assertContains('announcement.label', $instance->getError());
        $instance->label = new label('text', null, 'lang');
        $this->assertTrue($instance->validate());
    }

    /**
     * @group announcement
     * @group validate
     */
    public function testId()
    {
        $instance = new announcement(new label('text', null, 'lang'), null, null, 'LOW');
        $this->assertFalse($instance->validate());
        $this->assertContains('announcement.id', $instance->getError());
        $instance->id = 1;
        $this->assertFalse($instance->validate());
        $this->assertContains('announcement.id', $instance->getError());
        $instance->id = '';
        $this->assertFalse($instance->validate());
        $this->assertContains('announcement.id', $instance->getError());
        $instance->id = 'id';
        $this->assertTrue($instance->validate());
    }

    /**
     * @group announcement
     * @group validate
     */
    public function testType()
    {
        $instance = new announcement(new label('text', null, 'lang'), 'id', null, 'LOW');
        $this->assertTrue($instance->validate());
        $instance->type = 1;
        $this->assertFalse($instance->validate());
        $this->assertContains('announcement.type', $instance->getError());
        $instance->type = '';
        $this->assertFalse($instance->validate());
        $this->assertContains('announcement.type', $instance->getError());
        $instance->type = 'type';
        $this->assertFalse($instance->validate());
        $this->assertContains('announcement.type', $instance->getError());
        $instance->type = 'INFORMATION';
        $this->assertTrue($instance->validate());
        $instance->type = 'SYSTEM';
        $this->assertTrue($instance->validate());
    }

    /**
     * @group announcement
     * @group validate
     */
    public function testPriority()
    {
        $instance = new announcement(new label('text', null, 'lang'), 'id', null, null);
        $this->assertFalse($instance->validate());
        $this->assertContains('announcement.priority', $instance->getError());
        $instance->priority = 1;
        $this->assertFalse($instance->validate());
        $this->assertContains('announcement.priority', $instance->getError());
        $instance->priority = '';
        $this->assertFalse($instance->validate());
        $this->assertContains('announcement.priority', $instance->getError());
        $instance->priority = 'priority';
        $this->assertFalse($instance->validate());
        $this->assertContains('announcement.priority', $instance->getError());
        $instance->priority = 'LOW';
        $this->assertTrue($instance->validate());
        $instance->priority = 'MEDIUM';
        $this->assertTrue($instance->validate());
        $instance->priority = 'HIGH';
        $this->assertTrue($instance->validate());
    }
}

?>
