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
require_once('bookmarkSet.class.php');

class setBookmarks extends AbstractType {

    /**
     * @var string
     */
    public $contentID;

    /**
     * @var (object)bookmarkSet
     */
    public $bookmarkSet;


    /******************** public functions ********************/

    /**
     * constructor for class setBookmarks
     */
    function __construct($_contentID = NULL, $_bookmarkSet = NULL) {
        if (is_string($_contentID)) $this->setContentID($_contentID);
        if (is_a($_bookmarkSet, "bookmarkSet")) $this->setBookmarkSet($_bookmarkSet);
    }


    /******************** class get set methods ********************/

    /**
     * getter for contentID
     */
    function getContentID() {
        return $this->contentID;
    }

    /**
     * setter for contentID
     */
    function setContentID($_contentID) {
        $this->contentID = $_contentID;
    }

    /**
     * resetter for contentID
     */
    function resetContentID() {
        $this->contentID = NULL;
    }

    /**
     * getter for bookmarkSet
     */
    function getBookmarkSet() {
        return $this->bookmarkSet;
    }

    /**
     * setter for bookmarkSet
     */
    function setBookmarkSet($_bookmarkSet) {
        $this->bookmarkSet = $_bookmarkSet;
    }

    /**
     * resetter for bookmarkSet
     */
    function resetBookmarkSet() {
        $this->bookmarkSet = NULL;
    }


    /******************** validator methods ********************/

    /**
     * validator for class setBookmarks
     */
    function validate() {
        // contentID must occur exactly once
        if ($this->isNoneEmptyString($this->contentID, 'contentID') === false)
            return false;

        // bookmarkSet must occur exactly once
        if ($this->isInstanceOf($this->bookmarkSet, 'bookmarkSet') === false)
            return false;
        if ($this->bookmarkSet->validate() === false) {
            $this->error = __CLASS__ . '.' . $this->bookmarkSet->getError();
            return false;
        }

        return true;
    }
}

?>
