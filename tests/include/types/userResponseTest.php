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

require_once('userResponse.class.php');

class userResponseTest extends PHPUnit_Framework_TestCase
{
    /**
     * @group userResponse
     * @group validate
     */
    public function testData()
    {
        $instance = new userResponse('questionID');
        $this->assertFalse($instance->validate());
        $this->assertContains('userResponse must contain either a value attribute or a data element', $instance->getError());
        $instance->data = 1;
        $this->assertTrue($instance->validate());
        $instance->data_encoded = 1;
        $this->assertFalse($instance->validate());
        $this->assertContains('userResponse both data and data_encoded can not be set when value is not set', $instance->getError());
        $instance->data = null;
        $this->assertTrue($instance->validate());
    }

    /**
     * @group userResponse
     * @group validate
     */
    public function testQuestionID()
    {
        $instance = new userResponse(null, 'value');
        $this->assertFalse($instance->validate());
        $this->assertContains('userResponse.questionID', $instance->getError());
        $instance->questionID = 1;
        $this->assertFalse($instance->validate());
        $this->assertContains('userResponse.questionID', $instance->getError());
        $instance->questionID = '';
        $this->assertFalse($instance->validate());
        $this->assertContains('userResponse.questionID', $instance->getError());
        $instance->questionID = 'questionID';
        $this->assertTrue($instance->validate());

        // reserved value default
        $instance = new userResponse('default');
        $instance->value = 'value';
        $this->assertFalse($instance->validate());
        $this->assertContains('userResponse.value', $instance->getError());
        $instance->value = null;
        $instance->data = 'data';
        $this->assertFalse($instance->validate());
        $this->assertContains('userResponse.data', $instance->getError());
        $instance->data = null;
        $instance->data_encoded = 'data_encoded';
        $this->assertFalse($instance->validate());
        $this->assertContains('userResponse.data_encoded', $instance->getError());
        $instance->data_encoded = null;
        $this->assertTrue($instance->validate());
        $instance->value = '';
        $this->assertTrue($instance->validate());

        // reserved value search
        $instance = new userResponse('search');
        $instance->value = 'value';
        $this->assertFalse($instance->validate());
        $this->assertContains('userResponse.value', $instance->getError());
        $instance->value = null;
        $instance->data = 'data';
        $this->assertFalse($instance->validate());
        $this->assertContains('userResponse.data', $instance->getError());
        $instance->data = null;
        $instance->data_encoded = 'data_encoded';
        $this->assertFalse($instance->validate());
        $this->assertContains('userResponse.data_encoded', $instance->getError());
        $instance->data_encoded = null;
        $this->assertTrue($instance->validate());
        $instance->value = '';
        $this->assertTrue($instance->validate());

        // reserved value back
        $instance = new userResponse('back');
        $instance->value = 'value';
        $this->assertFalse($instance->validate());
        $this->assertContains('userResponse.value', $instance->getError());
        $instance->value = null;
        $instance->data = 'data';
        $this->assertFalse($instance->validate());
        $this->assertContains('userResponse.data', $instance->getError());
        $instance->data = null;
        $instance->data_encoded = 'data_encoded';
        $this->assertFalse($instance->validate());
        $this->assertContains('userResponse.data_encoded', $instance->getError());
        $instance->data_encoded = null;
        $this->assertTrue($instance->validate());
        $instance->value = '';
        $this->assertTrue($instance->validate());
    }

    /**
     * @group userResponse
     * @group validate
     */
    public function testValue()
    {
        $instance = new userResponse('questionID');
        $this->assertFalse($instance->validate());
        $this->assertContains('userResponse must contain either a value attribute or a data element', $instance->getError());
        $instance->value = 1;
        $this->assertFalse($instance->validate());
        $this->assertContains('userResponse.value', $instance->getError());
        $instance->value = '';
        $this->assertTrue($instance->validate());
        $instance->value = 'value';
        $this->assertTrue($instance->validate());
        $instance->data = 1;
        $this->assertFalse($instance->validate());
        $this->assertContains('userResponse can not contain a data element when the value attribute is set', $instance->getError());
        $instance->data = null;
        $instance->data_encoded = 1;
        $this->assertFalse($instance->validate());
        $this->assertContains('userResponse can not contain a data element when the value attribute is set', $instance->getError());
    }
}

?>
