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

class setBookmarksResponse extends AbstractType {

    /**
     * @var boolean
     */
    public $setBookmarksResult;


    /******************** public functions ********************/

    /**
     * constructor for class setBookmarksResponse
     */
    function __construct($_setBookmarksResult = NULL) {
        if (is_bool($_setBookmarksResult)) $this->setSetBookmarksResult($_setBookmarksResult);
    }


    /******************** class get set methods ********************/

    /**
     * getter for setBookmarksResult
     */
    function getSetBookmarksResult() {
        return $this->setBookmarksResult;
    }

    /**
     * setter for setBookmarksResult
     */
    function setSetBookmarksResult($_setBookmarksResult) {
        $this->setBookmarksResult = $_setBookmarksResult;
    }

    /**
     * resetter for setBookmarksResult
     */
    function resetSetBookmarksResult() {
        $this->setBookmarksResult = NULL;
    }


    /******************** validator methods ********************/

    /**
     * validator for class setBookmarksResponse
     */
    function validate() {
        // setBookmarksResult must occur exactly once
        if ($this->isBoolean($this->setBookmarksResult, 'setBookmarksResult') === false)
            return false;

        return true;
    }
}

?>
