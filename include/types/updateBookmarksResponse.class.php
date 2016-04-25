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

class updateBookmarksResponse extends AbstractType {

    /**
     * @var boolean
     */
    public $updateBookmarksResult;


    /******************** public functions ********************/

    /**
     * constructor for class updateBookmarksResponse
     */
    function __construct($_updateBookmarksResult = NULL) {
        if (is_bool($_updateBookmarksResult)) $this->setUpdateBookmarkResult($_updateBookmarksResult);
    }


    /******************** class get set methods ********************/

    /**
     * getter for updateBookmarksResult
     */
    function getUpdateBookmarkResult() {
        return $this->updateBookmarksResult;
    }

    /**
     * setter for updateBookmarksResult
     */
    function setUpdateBookmarkResult($_updateBookmarksResult) {
        $this->updateBookmarksResult = $_updateBookmarksResult;
    }

    /**
     * resetter for updateBookmarksResult
     */
    function resetUpdateBookmarkResult() {
        $this->updateBookmarksResult = NULL;
    }


    /******************** validator methods ********************/


    /**
     * validator for class updateBookmarksResponse
     */
    function validate() {
        // updateBookmarksResult must occur exactly once
        if ($this->isBoolean($this->updateBookmarksResult, 'updateBookmarksResult') === false)
            return false;

        return true;
    }
}

?>
