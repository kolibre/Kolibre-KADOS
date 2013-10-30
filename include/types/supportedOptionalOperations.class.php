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

class supportedOptionalOperations extends AbstractType {

    /**
     * @var array[0, unbounded] of string
     *     NOTE: operation should follow the following restrictions
     *     You can have one of the following value
     *     SET_BOOKMARKS
     *     GET_BOOKMARKS
     *     DYNAMIC_MENUS
     *     SERVICE_ANNOUNCEMENTS
     *     PDTB2_KEY_PROVISION
     */
    public $operation;


    /******************** public functions ********************/

    /**
     * constructor for class supportedOptionalOperations
     */
    function __construct($_operation = NULL) {
        if (is_array($_operation)) $this->setOperation($_operation);
    }


    /******************** class get set methods ********************/

    /**
     * getter for operation
     */
    function getOperation() {
        return $this->operation;
    }

    /**
     * setter for operation
     */
    function setOperation($_operation) {
        $this->operation = $_operation;
    }

    /**
     * resetter for operation
     */
    function resetOperation() {
        $this->operation = NULL;
    }


    /****************************** get set methods for arrays **********************************/

    /**
     * get the ith element of operation
     */
    function getOperationAt($i) {
        if ($this->sizeofOperation() > $i)
            return $this->operation[$i];
        else return NULL;
    }

    /**
     * set the ith element of operation
     */
    function setOperationAt($i, $_operation) {
        $this->operation[$i] = $_operation;
    }

    /**
     * add to operation
     */
    function addOperation($_operation) {
        if (is_array($this->operation))
            array_push($this->operation, $_operation);
        else {
            $this->operation = array();
            $this->addOperation($_operation);
        }
    }

    /**
     * get the size of the operation array
     */
    function sizeofOperation() {
        return sizeof($this->operation);
    }

    /**
     * remove the ith element of operation
     */
    function removeOperationAt($i) {
        if ($this->sizeofOperation() > $i)
            unset($this->operation[$i]);
    }


    /******************** validator methods ********************/

    /**
     * validator for class supportedOptionalOperations
     */
    function validate() {
        // operation must occur zero or more times
        if (!is_null($this->operation)) {
            $allowedValues = array('SET_BOOKMARKS', 'GET_BOOKMARKS', 'DYNAMIC_MENUS', 'SERVICE_ANNOUNCEMENTS', 'PDTB2_KEY_PROVISION');
            if ($this->isArrayOfString($this->operation, 'operation', $allowedValues) === false)
                return false;
        }

        return true;
    }
}

?>
