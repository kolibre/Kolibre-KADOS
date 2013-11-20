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

require_once('mimeType.class.php');

class supportedMimeTypes extends AbstractType {

    /**
     * @var array[0, unbounded] of (object)mimeType
     */
    public $mimeType;


    /******************** public functions ********************/

    /**
     * constructor for class supportedMimeTypes
     */
    function __construct($_mimeType = NULL) {
        if (is_array($_mimeType)) $this->setMimeType($_mimeType);
    }


    /******************** class get set methods ********************/

    /**
     * getter for mimeType
     */
    function getMimeType() {
        return $this->mimeType;
    }

    /**
     * setter for mimeType
     */
    function setMimeType($_mimeType) {
        $this->mimeType = $_mimeType;
    }

    /**
     * resetter for mimeType
     */
    function resetMimeType() {
        $this->mimeType = NULL;
    }


    /****************************** get set methods for arrays **********************************/

    /**
     * get the ith element of mimeType
     */
    function getMimeTypeAt($i) {
        if ($this->sizeofMimeType() > $i)
            return $this->mimeType[$i];
        else return NULL;
    }

    /**
     * set the ith elemenent of mimeType
     */
    function setMimeTypeAt($i, $_mimeType) {
        $this->mimeType[$i] = $_mimeType;
    }

    /**
     * add to mimeType
     */
    function addMimeType($_mimeType) {
        if (is_array($this->mimeType))
            array_push($this->mimeType, $_mimeType);
        else {
            $this->mimeType = array();
            $this->addMimeType($_mimeType);
        }
    }

    /**
     * get the size of the mimeType array
     */
    function sizeofMimeType() {
        return sizeof($this->mimeType);
    }

    /**
     * remove the ith element of mimeType
     */
    function removeMimeTypeAt($i) {
        if ($this->sizeofMimeType() > $i)
            unset($this->mimeType[$i]);
    }


    /******************** validator methods ********************/

    /**
     * validator for class supportedMimeTypes
     */
    function validate() {
        // mimeType must occur zero or more times
        if (!is_null($this->mimeType)) {
            if ($this->isArrayOfInstanceOf($this->mimeType, 'mimeType') === false)
                return false;
            foreach ($this->mimeType as $index => $mimeType) {
                if ($mimeType->validate() === false) {
                    $this->error = __CLASS__ . '.' . $mimeType->getError();
                    $this->error = str_replace('mimeType', "mimeType[$index]", $this->error);
                    return false;
                }
            }
        }

        return true;
    }
}

?>
