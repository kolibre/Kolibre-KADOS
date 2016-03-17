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
     * @var object of something
     */
    public $updateBookmarkResult;


    /******************** public functions ********************/

    /**
     * constructor for class updateBookmarkResponse
     */
    function __construct($_updateBookmarkResult = NULL) {
        if (is_bool($_updateBookmarkResult)) $this->setUpdateBookmarkResult($_updateBookmarkResult);
    }


    /******************** class get set methods ********************/

    /**
     * getter for updateBookmarkResult
     */
    function getUpdateBookmarkResult() {
        return $this->updateBookmarkResult;
    }

    /**
     * setter for updateBookmarkResult
     */
    function setUpdateBookmarkResult($_updateBookmarkResult) {
        $this->updateBookmarkResult = $_updateBookmarkResult;
    }

    /**
     * resetter for updateBookmarkResult
     */
    function resetUpdateBookmarkResult() {
        $this->updateBookmarkResult = NULL;
    }


    /******************** validator methods ********************/


    /**
     * validator for class updateBookmarkResponse
     */
    function validate() {
        // updateBookmarkResult must occur exactly once
        if ($this->isBoolean($this->updateBookmarkResult, 'updateBookmarkResult') === false)
            return false;

        return true;
    }
}

?>
