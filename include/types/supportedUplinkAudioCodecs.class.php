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

class supportedUplinkAudioCodecs extends AbstractType {

    /**
     * @var array[0, unbounded] of string
     */
    public $codec;


    /******************** public functions ********************/

    /**
     * constructor for class supportedUplinkAudioCodecs
     */
    function __construct($_codec = NULL) {
        if (is_array($_codec)) $this->setCodec($_codec);
    }


    /******************** class get set methods ********************/

    /**
     * getter for codec
     */
    function getCodec() {
        return $this->codec;
    }

    /**
     * setter for codec
     */
    function setCodec($_codec) {
        $this->codec = $_codec;
    }

    /**
     * resetter for codec
     */
    function resetCodec() {
        $this->codec = NULL;
    }


    /****************************** get set methods for arrays **********************************/

    /**
     * get the ith element of codec
     */
    function getCodecAt($i) {
        if ($this->sizeofCodec() > $i)
            return $this->codec[$i];
        else return NULL;
    }

    /**
     * set the ith element of codec
     */
    function setCodecAt($i, $_codec) {
        $this->codec[$i] = $_codec;
    }

    /**
     * add to codec
     */
    function addCodec($_codec) {
        if (is_array($this->codec))
            array_push($this->codec, $_codec);
        else {
            $this->codec = array();
            $this->addCodec($_codec);
        }
    }

    /**
     * get the size of the codec array
     */
    function sizeofCodec() {
        return sizeof($this->codec);
    }

    /**
     * remove the ith element of codec
     */
    function removeCodecAt($i) {
        if ($this->sizeofCodec() > $i)
            unset($this->codec[$i]);
    }


    /******************** validator methods ********************/

    /**
     * validator for class supportedUplinkAudioCodecs
     */
    function validate() {
        // codec must occur zero or more times
        if (!is_null($this->codec)) {
            if ($this->isArrayOfNoneEmptyString($this->codec, 'codec') === false)
                return false;
        }

        return true;
    }
}

?>
