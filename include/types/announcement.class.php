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

class announcement extends AbstractType {

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
     *     NOTE: type should follow the following restrictions
     *     You can have one of the following value
     *     WARNING
     *     ERROR
     *     INFORMATION
     *     SYSTEM
     */
    public $type;

    /**
     * @var string
     *     NOTE: priority should follow the following restrictions
     *     Your value for rendering the announcement to the User should be
     *          HIGH (Immediately abort all current activities)
     *          MEDIUM (Wait until the current activity (e.g. streaming a file) is finished)
     *          LOW (Next time the Reading System is idle)
     */
    public $priority;


    /******************** public functions ********************/

    /**
     * constructor for class announcement
     */
    function __construct($_label = NULL, $_id = NULL, $_type = NULL, $_priority = NULL) {
        if (is_a($_label, "label")) $this->setLabel($_label);
        if (is_string($_id)) $this->setId($_id);
        if (is_string($_type)) $this->setType($_type);
        if (is_string($_priority)) $this->setPriority($_priority);
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
     * getter for type
     */
    function getType() {
        return $this->type;
    }

    /**
     * setter for type
     */
    function setType($_type) {
        $this->type = $_type;
    }

    /**
     * resetter for type
     */
    function resetType() {
        $this->type = NULL;
    }

    /**
     * getter for priority
     */
    function getPriority() {
        return $this->priority;
    }

    /**
     * setter for priority
     */
    function setPriority($_priority) {
        $this->priority = $_priority;
    }

    /**
     * resetter for priority
     */
    function resetPriority() {
        $this->priority = NULL;
    }


    /******************** validator methods ********************/

    /**
     * validator for class announcement
     */
    function validate() {
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

        // attribute type is optional
        if (!is_null($this->type)) {
            $allowedValues =  array("INFORMATION", "SYSTEM");
            if ($this->isString($this->type, 'type', $allowedValues) === false)
                return false;
        }

        // attribute priority is required
        if (in_array($this->priority, array("HIGH","MEDIUM","LOW")) === false)
            return false;
        
        return true;
    }
}

?>
