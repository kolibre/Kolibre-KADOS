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

require_once('hilite.class.php');

class hiliteTest extends PHPUnit_Framework_TestCase
{
    /**
     * @group hilite
     * @group validate
     */
    public function testHiliteStart()
    {
        $instance = new hilite(null, new hiliteEnd('ncxRef','URI','timeOffset'));
        $this->assertFalse($instance->validate());
        $this->assertContains('hilite.hiliteStart', $instance->getError());
        $instance->hiliteStart = 1;
        $this->assertFalse($instance->validate());
        $this->assertContains('hilite.hiliteStart', $instance->getError());
        $instance->hiliteStart = new hiliteStart('ncxRef','URI','timeOffset');
        $this->assertTrue($instance->validate());
    }

    /**
     * @group hilite
     * @group validate
     */
    public function testHiliteEnd()
    {
        $instance = new hilite(new hiliteStart('ncxRef','URI','timeOffset'),null);
        $this->assertFalse($instance->validate());
        $this->assertContains('hilite.hiliteEnd', $instance->getError());
        $instance->hiliteEnd = 1;
        $this->assertFalse($instance->validate());
        $this->assertContains('hilite.hiliteEnd', $instance->getError());
        $instance->hiliteEnd = new hiliteEnd('ncxRef','URI','timeOffset');
        $this->assertTrue($instance->validate());
    }

    /**
     * @group hilite
     * @group validate
     */
    public function testNote()
    {
        $instance = new hilite(new hiliteStart('ncxRef','URI','timeOffset'),new hiliteEnd('ncxRef','URI','timeOffset'));
        $this->assertTrue($instance->validate());
        $instance->note = 1;
        $this->assertFalse($instance->validate());
        $this->assertContains('hilite.note', $instance->getError());
        $instance->note = new note();
        $this->assertTrue($instance->validate());
    }
}

?>
