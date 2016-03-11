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

require_once('categoryLabel.class.php');
require_once('label.class.php');
require_once('audio.class.php');

class categoryLabelTest extends PHPUnit_Framework_TestCase
{
    /**
     * @group categoryLabel
     * @group validate
     */
    public function testLabel()
    {   
        $categoryLabel = new categoryLabel(null);
        $this->assertFalse($categoryLabel->validate());
        $this->assertContains('categoryLabel.label', $categoryLabel->getError());

        $audio = new audio('uri',1,15,1234);
        // Check that audio is correctly instantiated
        $this->assertTrue($audio->validate());
        $label = new label('text', $audio, 'language','ltr');
        // Check that label iscorrectly instantiated
        $this->assertTrue($label->validate());

        $categoryLabel = new categoryLabel($label);
        $this->assertTrue($categoryLabel->validate());
        
    }

    
}

?>
