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

class getContentList extends AbstractType {

    /**
     * @var NMTOKEN
     */
    public $id;

    /**
     * @var int
     */
    public $firstItem;

    /**
     * @var int
     */
    public $lastItem;


    /******************** public functions ********************/

    /**
     * constructor for class getContentList
     */
    function __construct($_id = NULL, $_firstItem = NULL, $_lastItem = NULL) {
        if (is_string($_id)) $this->setId($_id);
        if (is_int($_firstItem)) $this->setFirstItem($_firstItem);
        if (is_int($_lastItem)) $this->setLastItem($_lastItem);
    }


    /******************** class get set methods ********************/

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
     * getter for firstItem
     */
    function getFirstItem() {
        return $this->firstItem;
    }

    /**
     * setter for firstItem
     */
    function setFirstItem($_firstItem) {
        $this->firstItem = $_firstItem;
    }

    /**
     * resetter for firstItem
     */
    function resetFirstItem() {
        $this->firstItem = NULL;
    }

    /**
     * getter for lastItem
     */
    function getLastItem() {
        return $this->lastItem;
    }

    /**
     * setter for lastItem
     */
    function setLastItem($_lastItem) {
        $this->lastItem = $_lastItem;
    }

    /**
     * resetter for lastItem
     */
    function resetLastItem() {
        $this->lastItem = NULL;
    }


    /******************** validator methods ********************/

    /**
     * validator for class getContentList
     */
    function validate() {
        // id must occur exactly once
        if ($this->isNoneEmptyString($this->id, 'id') === false)
            return false;

        // firstItem must occur exactly once
        if ($this->isPositiveInteger($this->firstItem, 'firstItem') === false)
            return false;

        // lastItem must occur exactly once
        if ($this->isInteger($this->lastItem, 'lastItem', null, -1) === false)
            return false;

        // lastItem must be greater than or equal to firstItem, unless value is -1
        if ($this->lastItem != -1 && ($this->lastItem < $this->firstItem)) {
            $this->error = __CLASS__ . ".lastItem must be greater than or equal to " . __CLASS__ . ".firstItem";
            return false;
        }

        return true;
    }
}

?>
