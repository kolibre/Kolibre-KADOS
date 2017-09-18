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

require_once('choice.class.php');

class choiceTest extends PHPUnit_Framework_TestCase
{
    /**
     * @group choice
     * @group validate
     */
    public function testLabel()
    {
        $instance = new choice(null, 'id');
        $this->assertFalse($instance->validate());
        $this->assertContains('choice.label', $instance->getError());
        $instance->label = 1;
        $this->assertFalse($instance->validate());
        $this->assertContains('choice.label', $instance->getError());
        $instance->label = new label('text',null,'en');
        $this->assertTrue($instance->validate());
    }

    /**
     * @group choice
     * @group validate
     */
    public function testID()
    {
        $instance = new choice(new label('text',null,'en'), null);
        $this->assertFalse($instance->validate());
        $this->assertContains('choice.id', $instance->getError());
        $instance->id = 1;
        $this->assertFalse($instance->validate());
        $this->assertContains('choice.id', $instance->getError());
        $instance->id = '';
        $this->assertFalse($instance->validate());
        $this->assertContains('choice.id', $instance->getError());
        $instance->id = 'id';
        $this->assertTrue($instance->validate());
    }
}

?>
