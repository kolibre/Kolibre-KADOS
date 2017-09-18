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

require_once('inputQuestion.class.php');

class inputQuestionTest extends PHPUnit_Framework_TestCase
{
    /**
     * @group inputQuestion
     * @group validate
     */
    public function testInputTypes()
    {
        $instance = new inputQuestion(null,new label('text',null,'en'),'id');
        $this->assertFalse($instance->validate());
        $this->assertContains('inputQuestion.inputTypes', $instance->getError());
        $instance->inputTypes = 'inputTypes';
        $this->assertFalse($instance->validate());
        $this->assertContains('inputQuestion.inputTypes', $instance->getError());
        $instance->inputTypes = new inputTypes(array(new input('TEXT_NUMERIC')));
        $this->assertTrue($instance->validate());
    }

    /**
     * @group inputQuestion
     * @group validate
     */
    public function testLabel()
    {
        $instance = new inputQuestion(new inputTypes(array(new input('TEXT_NUMERIC'))),null,'id');
        $this->assertFalse($instance->validate());
        $this->assertContains('inputQuestion.label', $instance->getError());
        $instance->label = 'label';
        $this->assertFalse($instance->validate());
        $this->assertContains('inputQuestion.label', $instance->getError());
        $instance->label = new label('text',null,'en');
        $this->assertTrue($instance->validate());        
    }

    /**
     * @group inputQuestion
     * @group validate
     */
    public function testID()
    {
        $instance = new inputQuestion(new inputTypes(array(new input('TEXT_NUMERIC'))),new label('text',null,'en'),null);
        $this->assertFalse($instance->validate());
        $this->assertContains('inputQuestion.id', $instance->getError());
        $instance->id = 1;
        $this->assertFalse($instance->validate());
        $this->assertContains('inputQuestion.id', $instance->getError());
        $instance->id = '';
        $this->assertFalse($instance->validate());
        $this->assertContains('inputQuestion.id', $instance->getError());
        $instance->id = 'id';
        $this->assertTrue($instance->validate());
    }

    /**
     * @group inputQuestion
     * @group validate
     */
    public function testDefaultValue()
    {
        $instance = new inputQuestion(new inputTypes(array(new input('TEXT_NUMERIC'))),new label('text',null,'en'),'id');
        $this->assertTrue($instance->validate());        
        $instance->defaultValue = 1;
        $this->assertFalse($instance->validate());
        $this->assertContains('inputQuestion.defaultValue', $instance->getError());
        $instance->defaultValue = '';
        $this->assertFalse($instance->validate());
        $this->assertContains('inputQuestion.defaultValue', $instance->getError());
        $instance->defaultValue = 'defaultValue';
        $this->assertTrue($instance->validate());        
    }
}

?>
