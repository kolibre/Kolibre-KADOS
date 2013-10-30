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

class setReadingSystemAttributesResponse extends AbstractType {

    /**
     * @var boolean
     */
    public $setReadingSystemAttributesResult;


    /******************** public functions ********************/

    /**
     * constructor for class setReadingSystemAttributesResponse
     */
    function __construct($_setReadingSystemAttributesResult = NULL) {
        if (is_bool($_setReadingSystemAttributesResult)) $this->setSetReadingSystemAttributesResult($_setReadingSystemAttributesResult);
    }


    /******************** class get set methods ********************/

    /**
     * getter for setReadingSystemAttributesResult
     */
    function getSetReadingSystemAttributesResult() {
        return $this->setReadingSystemAttributesResult;
    }

    /**
     * setter for setReadingSystemAttributesResult
     */
    function setSetReadingSystemAttributesResult($_setReadingSystemAttributesResult) {
        $this->setReadingSystemAttributesResult = $_setReadingSystemAttributesResult;
    }

    /**
     * resetter for setReadingSystemAttributesResult
     */
    function resetSetReadingSystemAttributesResult() {
        $this->setReadingSystemAttributesResult = NULL;
    }


    /******************** validator methods ********************/

    /**
     * validator for class setReadingSystemAttributesResponse
     */
    function validate() {
        // setReadingSystemAttributesResult must occur exactly once
        if ($this->isBoolean($this->setReadingSystemAttributesResult, 'setReadingSystemAttributesResult') === false)
            return false;

        return true;
    }
}

?>
