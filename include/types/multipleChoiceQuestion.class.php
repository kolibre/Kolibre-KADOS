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

require_once('label.class.php');
require_once('choices.class.php');

class multipleChoiceQuestion extends AbstractType {

    /**
     * @var (object)label
     */
    public $label;

    /**
     * @var (object)choices
     */
    public $choices;

    /**
     * @var NMTOKEN
     */
    public $id;

    /**
     * @var boolean
     */
    public $allowMultipleSelections;


    /******************** public functions ********************/

    /**
     * constructor for class multipleChoiceQuestion
     */
    function __construct($_label = NULL, $_choices = NULL, $_id = NULL, $_allowMultipleSelections = NULL) {
        if (is_a($_label, "label")) $this->setLabel($_label);
        if (is_a($_choices, "choices")) $this->setChoices($_choices);
        if (is_string($_id)) $this->setId($_id);
        if (is_bool($_allowMultipleSelections)) $this->setAllowMultipleSelections($_allowMultipleSelections);
    }


    /******************** class get set methods ********************/

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
     * getter for choices
     */
    function getChoices() {
        return $this->choices;
    }

    /**
     * setter for choices
     */
    function setChoices($_choices) {
        $this->choices = $_choices;
    }

    /**
     * resetter for choices
     */
    function resetChoices() {
        $this->choices = NULL;
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
     * getter for allowMultipleSelections
     */
    function getAllowMultipleSelections() {
        return $this->allowMultipleSelections;
    }

    /**
     * setter for allowMultipleSelections
     */
    function setAllowMultipleSelections($_allowMultipleSelections) {
        $this->allowMultipleSelections = $_allowMultipleSelections;
    }

    /**
     * resetter for allowMultipleSelections
     */
    function resetAllowMultipleSelections() {
        $this->allowMultipleSelections = NULL;
    }


    /******************** validator methods ********************/

    /**
     * validator for class multipleChoiceQuestion
     */
    function validate() {
        // label must occur exactly once
        if ($this->isInstanceOf($this->label, 'label') === false)
            return false;
        if ($this->label->validate() === false) {
            $this->error = __CLASS__ . '.' . $this->label->getError();
            return false;
        }

        // choices must occur exactly once
        if ($this->isInstanceOf($this->choices, 'choices') === false)
            return false;
        if ($this->choices->validate() === false) {
            $this->error = __CLASS__ . '.' . $this->choices->getError();
            return false;
        }

        // attribute id is required
        if ($this->isNoneEmptyString($this->id, 'id') === false)
            return false;

        // attribute allowMultipleSelections is optional
        if (!is_null($this->allowMultipleSelections)) {
            if ($this->isBoolean($this->allowMultipleSelections, 'allowMultipleSelections') === false)
                return false;
        }

        return true;
    }
}

?>
