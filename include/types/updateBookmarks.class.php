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
require_once('bookmarkObject.class.php');

class updateBookmarks extends AbstractType {

    /**
     * @var string
     */
    public $contentID;

    /**
     * @var string with the following allowed values
     *      REPLACE_ALL
     *      ADD
     *      REMOVE
     */
    public $action;

    /**
     * @var (object) bookmarkObject
     */
    public $bookmarkObject;


    /******************** public functions ********************/

    /**
     * constructor for class updateBookmark
     */
    function __construct($_contentID = NULL, $_action = NULL, $_bookmarkObject) {
        if (is_string($_contentID)) $this->setContentID($_contentID);
        if (is_string($_action)) $this->setAction($_action);
        if(is_a($_bookmarkObject, 'bookmarkObject')) $this->setBookmarkObject($_bookmarkObject);
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
     * getter for action
     */
    function getAction() {
        return $this->action;
    }

    /**
     * setter for action
     */
    function setAction($_action) {
        $this->action = $_action;
    }

    /**
     * resetter for action
     */
    function resetAction() {
        $this->action = NULL;
    }

    /**
     * getter for bookmarkObject
     */
    function getBookmarkObject() {
        return $this->bookmarkObject;
    }

    /**
     * setter for bookmarkObject
     */
    function setBookmarkObject($_bookmarkObject) {
        $this->bookmarkObject = $_bookmarkObject;
    }

    /**
     * resetter for bookmarkObject
     */
    function resetBookmarkObject() {
        $this->bookmarkObject = NULL;
    }


    /******************** validator methods ********************/

    /**
     * validator for class updateBookmark
     */
    function validate() {
        // contentID must occur exactly once
        if ($this->isNoneEmptyString($this->contentID, 'contentID') === false)
            return false;

        // action must occur exactly once
        if ($this->isString($this->action, 'action', array("REPLACE_ALL","ADD", "REMOVE")) === false)
            return false;

        // bookmarkObject must occur exactly once
        if ($this->isInstanceOf($this->bookmarkObject, 'bookmarkObject') === false)
            return false;
        if ($this->bookmarkObject->validate() === false) {
            $this->error = __CLASS__ . '.' . $this->label->getError();
            return false;
        }

        return true;
    }
}

?>
