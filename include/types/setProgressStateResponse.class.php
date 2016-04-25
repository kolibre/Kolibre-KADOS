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

class setProgressStateResponse extends AbstractType {

    /**
     * @var boolean
     */
    public $progressStateResult;


    /******************** public functions ********************/

    /**
     * constructor for class setProgressStateResponse
     */
    function __construct($_progressStateResult = NULL) {
        if (is_bool($_progressStateResult)) $this->setProgressStateResult($_progressStateResult);
    }


    /******************** class get set methods ********************/

    /**
     * getter for progressStateResult
     */
    function getProgressStateResult() {
        return $this->progressStateResult;
    }

    /**
     * setter for progressStateResult
     */
    function setProgressStateResult($_progressStateResult) {
        $this->progressStateResult = $_progressStateResult;
    }

    /**
     * resetter for progressStateResult
     */
    function resetProgressStateResult() {
        $this->progressStateResult = NULL;
    }


    /******************** validator methods ********************/


    /**
     * validator for class setProgressStateResponse
     */
    function validate() {
        // progressStateResult must occur exactly once
        if ($this->isBoolean($this->progressStateResult, 'progressStateResult') === false)
            return false;

        return true;
    }
}

?>
