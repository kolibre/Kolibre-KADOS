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

class returnContentResponse extends AbstractType {

    /**
     * @var boolean
     */
    public $returnContentResult;


    /******************** public functions ********************/

    /**
     * constructor for class returnContentResponse
     */
    function __construct($_returnContentResult = NULL) {
        if (is_bool($_returnContentResult)) $this->setReturnContentResult($_returnContentResult);
    }


    /******************** class get set methods ********************/

    /**
     * getter for returnContentResult
     */
    function getReturnContentResult() {
        return $this->returnContentResult;
    }

    /**
     * setter for returnContentResult
     */
    function setReturnContentResult($_returnContentResult) {
        $this->returnContentResult = $_returnContentResult;
    }

    /**
     * resetter for returnContentResult
     */
    function resetReturnContentResult() {
        $this->returnContentResult = NULL;
    }


    /******************** validator methods ********************/

    /**
     * validator for class returnContentResponse
     */
    function validate() {
        // returnContentResult must occur exactly once
        if ($this->isBoolean($this->returnContentResult, 'returnContentResult') === false)
            return false;

        return true;
    }
}

?>
