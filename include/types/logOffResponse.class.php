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

class logOffResponse extends AbstractType {

    /**
     * @var boolean
     */
    public $logOffResult;


    /******************** public functions ********************/

    /**
     * constructor for class logOffResponse
     */
    function __construct($_logOffResult = NULL) {
        if (is_bool($_logOffResult)) $this->setLogOffResult($_logOffResult);
    }


    /******************** class get set methods ********************/

    /**
     * getter for logOffResult
     */
    function getLogOffResult() {
        return $this->logOffResult;
    }

    /**
     * setter for logOffResult
     */
    function setLogOffResult($_logOffResult) {
        $this->logOffResult = $_logOffResult;
    }

    /**
     * resetter for logOffResult
     */
    function resetLogOffResult() {
        $this->logOffResult = NULL;
    }


    /******************** validator methods ********************/

    /**
     * validator for class logOffResponse
     */
    function validate() {
        // logOffResult must occur exactly once
        if ($this->isBoolean($this->logOffResult, 'logOffResult') === false)
            return false;

        return true;
    }
}

?>
