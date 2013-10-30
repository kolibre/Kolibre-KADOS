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

class supportedContentFormats extends AbstractType {

    /**
     * @var array[0, unbounded] of string
     */
    public $contentFormat;


    /******************** public functions ********************/

    /**
     * constructor for class supportedContentFormats
     */
    function __construct($_contentFormat = NULL) {
        if (is_array($_contentFormat)) $this->setContentFormat($_contentFormat);
    }


    /******************** class get set methods ********************/

    /**
     * getter for contentFormat
     */
    function getContentFormat() {
        return $this->contentFormat;
    }

    /**
     * setter for contentFormat
     */
    function setContentFormat($_contentFormat) {
        $this->contentFormat = $_contentFormat;
    }

    /**
     * resetter for contentFormat
     */
    function resetContentFormat() {
        $this->contentFormat = NULL;
    }


    /****************************** get set methods for arrays **********************************/

    /**
     * get the ith element of contentFormat
     */
    function getContentFormatAt($i) {
        if ($this->sizeofContentFormat() > $i)
            return $this->contentFormat[$i];
        else return NULL;
    }

    /**
     * set the ith elemenent of contentFormat
     */
    function setContentFormatAt($i, $_contentFormat) {
        $this->contentFormat[$i] = $_contentFormat;
    }

    /**
     * add to contentFormat
     */
    function addContentFormat($_contentFormat) {
        if (is_array($this->contentFormat))
            array_push($this->contentFormat, $_contentFormat);
        else {
            $this->contentFormat = array();
            $this->addContentFormat($_contentFormat);
        }
    }

    /**
     * get the size of the contentFormat array
     */
    function sizeofContentFormat() {
        return sizeof($this->contentFormat);
    }

    /**
     * remove the ith element of contentFormat
     */
    function removeContentFormatAt($i) {
        if ($this->sizeofContentFormat() > $i)
            unset($this->contentFormat[$i]);
    }


    /******************** validator methods ********************/

    /**
     * validator for class supportedContentFormats
     */
    function validate() {
        // contentFormat must occur zero or more times
        if (!is_null($this->contentFormat)) {
            if ($this->isArrayOfNoneEmptyString($this->contentFormat, 'contentFormat') === false)
                return false;
        }

        return true;
    }
}

?>
