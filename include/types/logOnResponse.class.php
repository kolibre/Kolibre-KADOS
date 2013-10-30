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

class logOnResponse extends AbstractType {

    /**
     * @var boolean
     */
    public $logOnResult;


    /******************** public functions ********************/

    /**
     * constructor for class logOnResponse
     */
    function __construct($_logOnResult = NULL) {
        if (is_bool($_logOnResult)) $this->setLogOnResult($_logOnResult);
    }


    /******************** class get set methods ********************/

    /**
     * getter for logOnResult
     */
    function getLogOnResult() {
        return $this->logOnResult;
    }

    /**
     * setter for logOnResult
     */
    function setLogOnResult($_logOnResult) {
        $this->logOnResult = $_logOnResult;
    }

    /**
     * resetter for logOnResult
     */
    function resetLogOnResult() {
        $this->logOnResult = NULL;
    }


    /******************** validator methods ********************/


    /**
     * validator for class logOnResponse
     */
    function validate() {
        // logOnResult must occur exactly once
        if ($this->isBoolean($this->logOnResult, 'logOnResult') === false)
            return false;

        return true;
    }
}

?>
