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

require_once('questions.class.php');

class questionsTest extends PHPUnit_Framework_TestCase
{
    /**
     * @group questions
     * @group validate
     */
    public function testMultipleChoiceQuestion()
    {
        $instance = new questions();
        $this->assertFalse($instance->validate());
        $this->assertContains('questions no required element set', $instance->getError());
        $instance->multipleChoiceQuestion = 1;
        $this->assertFalse($instance->validate());
        $this->assertContains('questions.multipleChoiceQuestion', $instance->getError());
        $instance->multipleChoiceQuestion = '';
        $this->assertFalse($instance->validate());
        $this->assertContains('questions.multipleChoiceQuestion', $instance->getError());
        $instance->multipleChoiceQuestion = array(new multipleChoiceQuestion(new label('text',null,'en'), new choices(array(new choice(new label('text',null,'en'), 'id'))), 'id'));
        $this->assertTrue($instance->validate());
        $instance->inputQuestion = array(new inputQuestion(new inputTypes(array(new input('TEXT_NUMERIC'))),new label('text',null,'en'),'id'));
        $this->assertTrue($instance->validate());
        $instance->contentListRef = 'contentListRef';
        $this->assertFalse($instance->validate());
        $this->assertContains('questions.contentListRef', $instance->getError());
        $instance->contentListRef = null;
        $instance->label = new label('text',null,'en');
        $this->assertFalse($instance->validate());
        $this->assertContains('questions.label', $instance->getError());
    }

    /**
     * @group questions
     * @group validate
     */
    public function testInputQuestion()
    {
        $instance = new questions();
        $this->assertFalse($instance->validate());
        $this->assertContains('questions no required element set', $instance->getError());
        $instance->inputQuestion = 1;
        $this->assertFalse($instance->validate());
        $this->assertContains('questions.inputQuestion', $instance->getError());
        $instance->inputQuestion = '';
        $this->assertFalse($instance->validate());
        $this->assertContains('questions.inputQuestion', $instance->getError());
        $instance->inputQuestion = array(new inputQuestion(new inputTypes(array(new input('TEXT_NUMERIC'))),new label('text',null,'en'),'id'));
        $this->assertTrue($instance->validate());
        $instance->multipleChoiceQuestion = array(new multipleChoiceQuestion(new label('text',null,'en'), new choices(array(new choice(new label('text',null,'en'), 'id'))), 'id'));
        $this->assertTrue($instance->validate());
        $instance->contentListRef = 'contentListRef';
        $this->assertFalse($instance->validate());
        $this->assertContains('questions.contentListRef', $instance->getError());
        $instance->contentListRef = null;
        $instance->label = new label('text',null,'en');
        $this->assertFalse($instance->validate());
        $this->assertContains('questions.label', $instance->getError());
    }

    /**
     * @group questions
     * @group validate
     */
    public function testContentListRef()
    {
        $instance = new questions();
        $this->assertFalse($instance->validate());
        $this->assertContains('questions no required element set', $instance->getError());
        $instance->contentListRef = 0;
        $this->assertFalse($instance->validate());
        $this->assertContains('questions.contentListRef', $instance->getError());
        $instance->contentListRef = '';
        $this->assertFalse($instance->validate());
        $this->assertContains('questions.contentListRef', $instance->getError());
        $instance->contentListRef = 'contentListRef';
        $this->assertTrue($instance->validate());
        $instance->label = new label('text',null,'en');
        $this->assertFalse($instance->validate());
        $this->assertContains('questions.label', $instance->getError());
    }

    /**
     * @group questions
     * @group validate
     */
    public function testLabel()
    {
        $instance = new questions();
        $this->assertFalse($instance->validate());
        $this->assertContains('questions no required element set', $instance->getError());
        $instance->label = '';
        $this->assertFalse($instance->validate());
        $this->assertContains('questions.label', $instance->getError());
        $instance->label = new label('text',null,'en');
        $this->assertTrue($instance->validate());
    }
}

?>
