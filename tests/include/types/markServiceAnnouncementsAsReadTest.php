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

require_once('markAnnouncementsAsRead.class.php');

class markAnnouncementsAsReadTest extends PHPUnit_Framework_TestCase
{
    /**
     * @group markAnnouncementsAsRead
     * @group validate
     */
    public function testRead()
    {
        $instance = new markAnnouncementsAsRead();
        $this->assertFalse($instance->validate());
        $this->assertContains('markAnnouncementsAsRead.read', $instance->getError());
        $instance->read = 1;
        $this->assertFalse($instance->validate());
        $this->assertContains('markAnnouncementsAsRead.read', $instance->getError());
        $instance->read = new read();
        $this->assertTrue($instance->validate());
    }
}

?>
