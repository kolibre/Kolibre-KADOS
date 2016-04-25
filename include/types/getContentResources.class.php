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

class getContentResources extends AbstractType {

    /**
     * @var string
     */
    public $contentID;

    /**
     * @var accessType
     */
    public $accessType;


    /******************** public functions ********************/

    /**
     * constructor for class getContentResources
     */
    function __construct($_contentID = NULL, $_accessType = NULL) {
        if (is_string($_contentID)) $this->setContentID($_contentID);
         if (is_string($_accessType)) $this->setAccessType($_accessType);
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
     * getter for accessType
     */
    function getAccessType() {
        return $this->accessType;
    }

    /**
     * setter for accessType
     */
    function setAccessType($_accessType) {
        $this->accessType = $_accessType;
    }

    /**
     * resetter for accessType
     */
    function resetAccessType() {
        $this->accessType = NULL;
    }


    /******************** validator methods ********************/

    /**
     * validator for class getContentResources
     */
    function validate() {
        // contentID must occur exactly once
        if ($this->isNoneEmptyString($this->contentID, 'contentID') === false)
            return false;

        if ($this->isString($this->accessType, 'accessType', array("STREAM", "DOWNLOAD","AUTOMATIC_DOWNLOAD")) === false)
            return false;
        
        return true;
    }
}

?>
