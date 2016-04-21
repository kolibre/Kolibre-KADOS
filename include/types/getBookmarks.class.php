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

class getBookmarks extends AbstractType {

    /**
     * @var string
     */
    public $contentID;

    /**
     * @var string with the following allowed values
     *      LASTMARK
     *      HILITE
     *      BOOKMARK
     *      ALL
     */
    public $action;

    /******************** public functions ********************/

    /**
     * constructor for class getBookmarks
     */
    function __construct($_contentID = NULL, $_action = NULL) {
        if (is_string($_contentID)) $this->setContentID($_contentID);
        if (is_string($_action)) $this->setAction($_action);
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


    /******************** validator methods ********************/

    /**
     * validator for class getBookmarks
     */
    function validate() {
        // contentID must occur exactly once
        if ($this->isNoneEmptyString($this->contentID, 'contentID') === false)
            return false;

        // action must occur exactly once
        $allowedValues = array('LASTMARK', 'HILITE', 'BOOKMARK', 'ALL');
        if ($this->isString($this->action, 'action', $allowedValues) === false)
            return false;

        return true;
    }
}

?>
