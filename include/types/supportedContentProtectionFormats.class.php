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

class supportedContentProtectionFormats extends AbstractType {

    /**
     * @var array[0, unbounded] of string
     *     NOTE: protectionFormat should follow the following restrictions
     *     You can have one of the following value
     *     PDTB2
     */
    public $protectionFormat;


    /******************** public functions ********************/

    /**
     * constructor for class supportedContentProtectionFormats
     */
    function __construct($_protectionFormat = NULL) {
        if (is_array($_protectionFormat)) $this->setProtectionFormat($_protectionFormat);
    }


    /******************** class get set methods ********************/

    /**
     * getter for protectionFormat
     */
    function getProtectionFormat() {
        return $this->protectionFormat;
    }

    /**
     * setter for protectionFormat
     */
    function setProtectionFormat($_protectionFormat) {
        $this->protectionFormat = $_protectionFormat;
    }

    /**
     * resetter for protectionFormat
     */
    function resetProtectionFormat() {
        $this->protectionFormat = NULL;
    }


    /****************************** get set methods for arrays **********************************/

    /**
     * get the ith element of protectionFormat
     */
    function getProtectionFormatAt($i) {
        if ($this->sizeofProtectionFormat() > $i)
            return $this->protectionFormat[$i];
        else return NULL;
    }

    /**
     * set the ith elemenent of protectionFormat
     */
    function setProtectionFormatAt($i, $_protectionFormat) {
        $this->protectionFormat[$i] = $_protectionFormat;
    }

    /**
     * add to protectionFormat
     */
    function addProtectionFormat($_protectionFormat) {
        if (is_array($this->protectionFormat))
            array_push($this->protectionFormat, $_protectionFormat);
        else {
            $this->protectionFormat = array();
            $this->addProtectionFormat($_protectionFormat);
        }
    }

    /**
     * get the size of the protectionFormat array
     */
    function sizeofProtectionFormat() {
        return sizeof($this->protectionFormat);
    }

    /**
     * remove the ith element of protectionFormat
     */
    function removeProtectionFormatAt($i) {
        if ($this->sizeofProtectionFormat() > $i)
            unset($this->protectionFormat[$i]);
    }


    /******************** validator methods ********************/

    /**
     * validator for class supportedContentProtectionFormats
     */
    function validate() {
        // protectionFormat must occur zero or more times
        if (!is_null($this->protectionFormat)) {
            $allowedValues = array('PDTB2');
            if ($this->isArrayOfString($this->protectionFormat, 'protectionFormat', $allowedValues) === false)
                return false;
        }

        return true;
    }
}

?>
