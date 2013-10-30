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

class audio extends AbstractType {

    /**
     * @var anyURI
     */
    public $uri;

    /**
     * @var long
     */
    public $rangeBegin;

    /**
     * @var long
     */
    public $rangeEnd;

    /**
     * @var long
     */
    public $size;


    /******************** public functions ********************/

    /**
     * constructor for class audio
     */
    function __construct($_uri = NULL, $_rangeBegin = NULL, $_rangeEnd = NULL, $_size = NULL) {
        if (is_string($_uri)) $this->setUri($_uri);
        if (is_int($_rangeBegin)) $this->setRangeBegin($_rangeBegin);
        if (is_int($_rangeEnd)) $this->setRangeEnd($_rangeEnd);
        if (is_int($_size)) $this->setSize($_size);
    }


    /******************** class get set methods ********************/

    /**
     * getter for uri
     */
    function getUri() {
        return $this->uri;
    }

    /**
     * setter for uri
     */
    function setUri($_uri) {
        $this->uri = $_uri;
    }

    /**
     * resetter for uri
     */
    function resetUri() {
        $this->uri = NULL;
    }

    /**
     * getter for rangeBegin
     */
    function getRangeBegin() {
        return $this->rangeBegin;
    }

    /**
     * setter for rangeBegin
     */
    function setRangeBegin($_rangeBegin) {
        $this->rangeBegin = $_rangeBegin;
    }

    /**
     * resetter for rangeBegin
     */
    function resetRangeBegin() {
        $this->rangeBegin = NULL;
    }

    /**
     * getter for rangeEnd
     */
    function getRangeEnd() {
        return $this->rangeEnd;
    }

    /**
     * setter for rangeEnd
     */
    function setRangeEnd($_rangeEnd) {
        $this->rangeEnd = $_rangeEnd;
    }

    /**
     * resetter for rangeEnd
     */
    function resetRangeEnd() {
        $this->rangeEnd = NULL;
    }

    /**
     * getter for size
     */
    function getSize() {
        return $this->size;
    }

    /**
     * setter for size
     */
    function setSize($_size) {
        $this->size = $_size;
    }

    /**
     * resetter for size
     */
    function resetSize() {
        $this->size = NULL;
    }


    /******************** validator methods ********************/

    /**
     * validator for class audio
     */
    function validate() {
        // attribute uri is required
        if ($this->isNoneEmptyString($this->uri, 'uri') === false)
            return false;

        // attribute rangeBegin is optional
        if (!is_null($this->rangeBegin)) {
            if ($this->isInteger($this->rangeBegin, 'rangeBegin') === false)
                return false;
        }

        // attribute rangeEnd is optional
        if (!is_null($this->rangeEnd)) {
            if ($this->isInteger($this->rangeEnd, 'rangeEnd') === false)
                return false;
        }

        // attribute size is required
        if ($this->isInteger($this->size, 'size') === false)
            return false;

        // check precense of rangeEnd if rangeBegin is set
        if (!is_null($this->rangeBegin)) {
            if (is_null($this->rangeEnd)) {
                $this->error = __CLASS__ . ".rangeEnd must not be null when rangeBegin is set";
                return false;
            }
        }

        // check precense of rangeBegin if rangeEnd is set
        if (!is_null($this->rangeEnd)) {
            if (is_null($this->rangeBegin)) {
                $this->error = __CLASS__ . ".rangeBegin must not be null when rangeEnd is set";
                return false;
            }
        }

        return true;
    }
}

?>
