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

class hiliteStart extends AbstractType {

    /**
     * @var anyURI
     */
    public $ncxRef;

    /**
     * @var anyURI
     */
    public $URI;

    // You may set only one from the following set
    // ---------------Start Choice----------------

    /**
     * @var string
     */
    public $timeOffset;

    /**
     * @var long
     */
    public $charOffset;
    // ----------------End Choice---------------


    /******************** public functions ********************/

    /**
     * constructor for class hiliteStart
     */
    function __construct($_ncxRef = NULL, $_URI = NULL, $_timeOffset = NULL, $_charOffset = NULL) {
        if (is_string($_ncxRef)) $this->setNcxRef($_ncxRef);
        if (is_string($_URI)) $this->setURI($_URI);
        if (is_string($_timeOffset)) $this->setTimeOffset($_timeOffset);
        if (is_int($_charOffset)) $this->setCharOffset($_charOffset);
    }


    /******************** class get set methods ********************/

    /**
     * getter for ncxRef
     */
    function getNcxRef() {
        return $this->ncxRef;
    }

    /**
     * setter for ncxRef
     */
    function setNcxRef($_ncxRef) {
        $this->ncxRef = $_ncxRef;
    }

    /**
     * resetter for ncxRef
     */
    function resetNcxRef() {
        $this->ncxRef = NULL;
    }

    /**
     * getter for URI
     */
    function getURI() {
        return $this->URI;
    }

    /**
     * setter for URI
     */
    function setURI($_URI) {
        $this->URI = $_URI;
    }

    /**
     * resetter for URI
     */
    function resetURI() {
        $this->URI = NULL;
    }

    /**
     * getter for timeOffset
     */
    function getTimeOffset() {
        return $this->timeOffset;
    }

    /**
     * setter for timeOffset
     */
    function setTimeOffset($_timeOffset) {
        $this->timeOffset = $_timeOffset;
    }

    /**
     * resetter for timeOffset
     */
    function resetTimeOffset() {
        $this->timeOffset = NULL;
    }

    /**
     * getter for charOffset
     */
    function getCharOffset() {
        return $this->charOffset;
    }

    /**
     * setter for charOffset
     */
    function setCharOffset($_charOffset) {
        $this->charOffset = $_charOffset;
    }

    /**
     * resetter for charOffset
     */
    function resetCharOffset() {
        $this->charOffset = NULL;
    }


    /******************** validator methods ********************/

    /**
     * validator for class hiliteStart
     */
    function validate() {
        // ncxRef must occur exactly once
        if ($this->isNoneEmptyString($this->ncxRef, 'ncxRef') === false)
            return false;

        // URI must occur exactly once
        if ($this->isNoneEmptyString($this->URI, 'URI') === false)
            return false;

        // timeOffset must occur zero or one times
        if (!is_null($this->timeOffset)) {
            if ($this->isNoneEmptyString($this->timeOffset, 'timeOffset') === false)
                return false;
        }

        // charOffset must occur zero or one times
        if (!is_null($this->charOffset)) {
            if ($this->isPositiveInteger($this->charOffset, 'charOffset') === false)
                return false;
        }

        // check that charOffset is present when timeOffset is null
        if (is_null($this->timeOffset)) {
            if (is_null($this->charOffset)) {
                $this->error = __CLASS__ . ".charOffset must be set when " . __CLASS__ . ".timeOffset is null";
                return false;
            }
        }

        return true;
    }
}

?>
