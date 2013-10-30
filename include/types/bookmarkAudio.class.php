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

class bookmarkAudio extends AbstractType {

    /**
     * @var anyURI
     */
    public $src;

    /**
     * @var string
     */
    public $clipBegin;

    /**
     * @var string
     */
    public $clipEnd;


    /******************** public functions ********************/

    /**
     * constructor for class bookmarkAudio
     */
    function __construct($_src = NULL, $_clipBegin = NULL, $_clipEnd = NULL) {
        if (is_string($_src)) $this->setSrc($_src);
        if (is_string($_clipBegin)) $this->setClipBegin($_clipBegin);
        if (is_string($_clipEnd)) $this->setClipEnd($_clipEnd);
    }


    /******************** class get set methods ********************/

    /**
     * getter for src
     */
    function getSrc() {
        return $this->src;
    }

    /**
     * setter for src
     */
    function setSrc($_src) {
        $this->src = $_src;
    }

    /**
     * resetter for src
     */
    function resetSrc() {
        $this->src = NULL;
    }

    /**
     * getter for clipBegin
     */
    function getClipBegin() {
        return $this->clipBegin;
    }

    /**
     * setter for clipBegin
     */
    function setClipBegin($_clipBegin) {
        $this->clipBegin = $_clipBegin;
    }

    /**
     * resetter for clipBegin
     */
    function resetClipBegin() {
        $this->clipBegin = NULL;
    }

    /**
     * getter for clipEnd
     */
    function getClipEnd() {
        return $this->clipEnd;
    }

    /**
     * setter for clipEnd
     */
    function setClipEnd($_clipEnd) {
        $this->clipEnd = $_clipEnd;
    }

    /**
     * resetter for clipEnd
     */
    function resetClipEnd() {
        $this->clipEnd = NULL;
    }


    /******************** validator methods ********************/

    /**
     * validator for class bookmarkAudio
     */
    function validate() {
        // attribute src required
        if ($this->isNoneEmptyString($this->src, 'src') === false)
            return false;

        // attribute clipBegin is optional
        if (!is_null($this->clipBegin)) {
            if ($this->isNoneEmptyString($this->clipBegin, 'clipBegin') === false)
                return false;
        }

        // attribute clipEnd is optional
        if (!is_null($this->clipEnd)) {
            if ($this->isNoneEmptyString($this->clipEnd, 'clipEnd') === false)
                return false;
        }

        // check precense of clipEnd when clipBegin is set
        if (!is_null($this->clipBegin)) {
            if (is_null($this->clipEnd)) {
                $this->error = __CLASS__ . ".clipEnd must not be null when " . __CLASS__ . ".clipBegin is set";
                return false;
            }
        }

        // check precense of clipBegin when clipEnd is set
        if (!is_null($this->clipEnd)) {
            if (is_null($this->clipBegin)) {
                $this->error = __CLASS__ . ".clipBegin must not be null when " . __CLASS__ . ".clipEnd is set";
                return false;
            }
        }

        return true;
    }
}

?>
