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

require_once('AbstractType.class.php');

require_once('inputTypes.class.php');
require_once('label.class.php');

class inputQuestion extends AbstractType {

    /**
     * @var (object)inputTypes
     */
    public $inputTypes;

    /**
     * @var (object)label
     */
    public $label;

    /**
     * @var NMTOKEN
     */
    public $id;

    /**
     * @var string
     */
    public $defaultValue;

    /******************** public functions ********************/

    /**
     * constructor for class inputQuestion
     */
    function __construct($_inputTypes = NULL, $_label = NULL, $_id = NULL, $_defaultValue = NULL) {
        if (is_a($_inputTypes, "inputTypes")) $this->setInputTypes($_inputTypes);
        if (is_a($_label, "label")) $this->setLabel($_label);
        if (is_string($_id)) $this->setId($_id);
        if (is_string($_defaultValue)) $this->setDefaultValue($_defaultValue);
    }


    /******************** class get set methods ********************/

    /**
     * getter for inputTypes
     */
    function getInputTypes() {
        return $this->inputTypes;
    }

    /**
     * setter for inputTypes
     */
    function setInputTypes($_inputTypes) {
        $this->inputTypes = $_inputTypes;
    }

    /**
     * resetter for inputTypes
     */
    function resetInputTypes() {
        $this->inputTypes = NULL;
    }

    /**
     * getter for label
     */
    function getLabel() {
        return $this->label;
    }

    /**
     * setter for label
     */
    function setLabel($_label) {
        $this->label = $_label;
    }

    /**
     * resetter for label
     */
    function resetLabel() {
        $this->label = NULL;
    }

    /**
     * getter for id
     */
    function getId() {
        return $this->id;
    }

    /**
     * setter for id
     */
    function setId($_id) {
        $this->id = $_id;
    }

    /**
     * resetter for id
     */
    function resetId() {
        $this->id = NULL;
    }

    /**
     * getter for defaultValue
     */
    function getDefaultValue() {
        return $this->defaultValue;
    }

    /**
     * setter for defaultValue
     */
    function setDefaultValue($_defaultValue) {
        $this->defaultValue = $_defaultValue;
    }

    /**
     * resetter for defaultValue
     */
    function resetDefaultValue() {
        $this->defaultValue = NULL;
    }


    /******************** validator methods ********************/

    /**
     * validator for class inputQuestion
     */
    function validate() {
        // inputTypes must occur exactly once
        if ($this->isInstanceOf($this->inputTypes, 'inputTypes') === false)
            return false;
        if ($this->inputTypes->validate() === false) {
            $this->error = __CLASS__ . '.' . $this->inputTypes->getError();
            return false;
        }

        // label must occur exactly once
        if ($this->isInstanceOf($this->label, 'label') === false)
            return false;
        if ($this->label->validate() === false) {
            $this->error = __CLASS__ . '.' . $this->label->getError();
            return false;
        }

        // attribute id is required
        if ($this->isNoneEmptyString($this->id, 'id') === false)
            return false;
        
        //defaultValue is optinal
        if(!is_null($this->defaultValue) === false)
            if ($this->isNoneEmptyString($this->defaultValue, 'defaultValue') === false)
                return false;
        return true;
    }
}

?>
