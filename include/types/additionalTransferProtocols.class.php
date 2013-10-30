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

class additionalTransferProtocols extends AbstractType {

    /**
     * @var array[1, unbounded] of string
     */
    public $protocol;


    /******************** public functions ********************/

    /**
     * constructor for class additionalTransferProtocols
     */
    function __construct($_protocol = NULL) {
        if (is_array($_protocol)) $this->setProtocol($_protocol);
    }


    /******************** class get set methods ********************/

    /**
     * getter for protocol
     */
    function getProtocol() {
        return $this->protocol;
    }

    /**
     * setter for protocol
     */
    function setProtocol($_protocol) {
        $this->protocol = $_protocol;
    }

    /**
     * resetter for protocol
     */
    function resetProtocol() {
        $this->protocol = NULL;
    }


    /****************************** get set methods for arrays **********************************/

    /**
     * get the ith element of protocol
     */
    function getProtocolAt($i) {
        if ($this->sizeofProtocol() > $i)
            return $this->protocol[$i];
        else return NULL;
    }

    /**
     * set the ith elemenent of protocol
     */
    function setProtocolAt($i, $_protocol) {
        $this->protocol[$i] = $_protocol;
    }

    /**
     * add to protocol
     */
    function addProtocol($_protocol) {
        if (is_array($this->protocol))
            array_push($this->protocol, $_protocol);
        else {
            $this->protocol = array();
            $this->addProtocol($_protocol);
        }
    }

    /**
     * get the size of the protocol array
     */
    function sizeofProtocol() {
        return sizeof($this->protocol);
    }

    /**
     * remove the ith element of protocol
     */
    function removeProtocolAt($i) {
        if ($this->sizeofProtocol() > $i)
            unset($this->protocol[$i]);
    }


    /******************** validator methods ********************/

    /**
     * validator for class additionalTransferProtocols
     */
    function validate() {
        // protocol must occur one or more times
        if ($this->isNoneEmptyArray($this->protocol, 'protocol') === false)
            return false;
        if ($this->isArrayOfNoneEmptyString($this->protocol, 'protocol') === false)
            return true;

        return true;
    }
}

?>
