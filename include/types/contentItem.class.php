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

class contentItem extends AbstractType {

    /**
     * @var (object)label
     */
    public $label;

    /**
     * @var string
     */
    public $id;

    /**
     * @var dateTime
     */
    public $lastModifiedDate;


    /******************** public functions ********************/

    /**
     * constructor for class contentItem
     */
    function __construct($_label = NULL, $_id = NULL, $_lastModifiedDate = NULL) {
        if (is_a($_label, "label")) $this->setLabel($_label);
        if (is_string($_id)) $this->setId($_id);
        if (is_string($_lastModifiedDate)) $this->setLastModifiedDate($_lastModifiedDate);
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
     * getter for lastModifiedDate
     */
    function getLastModifiedDate() {
        return $this->lastModifiedDate;
    }

    /**
     * setter for lastModifiedDate
     */
    function setLastModifiedDate($_lastModifiedDate) {
        $this->lastModifiedDate = $_lastModifiedDate;
    }

    /**
     * resetter for lastModifiedDate
     */
    function resetLastModifiedDate() {
        $this->lastModifiedDate = NULL;
    }

    /******************** validator methods ********************/

    /**
     * validator for class contentItem
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

        // attribute lastModifiedDate is optional
        if (!is_null($this->lastModifiedDate)) {
            if ($this->isNoneEmptyString($this->lastModifiedDate, 'lastModifiedDate') === false)
                return false;
        }

        return true;
    }
}

?>
