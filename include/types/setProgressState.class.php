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

class setProgressState extends AbstractType {

    /**
     * @var string
     */
    public $contentID;

    /**
     * @var string
     *     NOTE: type should follow the following restrictions
     *     You can have one of the following value
     *     START
     *     PAUSE
     *     RESUME
     *     FINISH
     */
    public $state;


    /******************** public functions ********************/

    /**
     * constructor for class setProgress
     */
    function __construct($_contentID = NULL, $_state = NULL) {
        if (is_string($_contentID)) $this->setContentID($_contentID);
        if (is_string($_state)) $this->setState($_state);
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
     * getter for state
     */
    function getState() {
        return $this->state;
    }

    /**
     * setter for state
     */
    function setState($_state) {
        $this->state = $_state;
    }

    /**
     * resetter for state
     */
    function resetState() {
        $this->state = NULL;
    }


    /******************** validator methods ********************/

    /**
     * validator for class setProgress
     */
    function validate() {
        // contentID must occur exactly once
        if ($this->isNoneEmptyString($this->contentID, 'contentID') === false)
            return false;

        // state must occur exactly once
        $allowedValues = array('START', 'PAUSE', 'RESUME', 'FINISH');
        if ($this->isString($this->state, 'state', $allowedValues) === false)
            return false;

        return true;
    }
}

?>
