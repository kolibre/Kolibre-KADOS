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

class supportedContentSelectionMethods extends AbstractType {

    /**
     * @var array[1, 2] of string
     *     NOTE: method should follow the following restrictions
     *     You can have one of the following value
     *     OUT_OF_BAND
     *     BROWSE
     */
    public $method;


    /******************** public functions ********************/

    /**
     * constructor for class supportedContentSelectionMethods
     */
    function __construct($_method = NULL) {
        if (is_array($_method)) $this->setMethod($_method);
    }


    /******************** class get set methods ********************/

    /**
     * getter for method
     */
    function getMethod() {
        return $this->method;
    }

    /**
     * setter for method
     */
    function setMethod($_method) {
        $this->method = $_method;
    }

    /**
     * resetter for method
     */
    function resetMethod() {
        $this->method = NULL;
    }

    /****************************** get set methods for arrays **********************************/

    /**
     * get the ith element of method
     */
    function getMethodAt($i) {
        if ($this->sizeofMethod() > $i)
            return $this->method[$i];
        else return NULL;
    }

    /**
     * set the ith element of method
     */
    function setMethodAt($i, $_method) {
        $this->method[$i] = $_method;
    }

    /**
     * add to method
     */
    function addMethod($_method) {
        if (is_array($this->method))
            array_push($this->method, $_method);
        else {
            $this->method = array();
            $this->addMethod($_method);
        }
    }

    /**
     * get the size of the method array
     */
    function sizeofMethod() {
        return sizeof($this->method);
    }

    /**
     * remove the ith element of method
     */
    function removeMethodAt($i) {
        if ($this->sizeofMethod() > $i)
            unset($this->method[$i]);
    }

    /******************** validator methods ********************/

    /**
     * validator for class supportedContentSelectionMethods
     */
    function validate() {
        // method must occur one or two times
        if ($this->isNoneEmptyArray($this->method, 'method', 2) === false)
            return false;
        $allowedValues = array('OUT_OF_BAND', 'BROWSE');
        if ($this->isArrayOfString($this->method, 'method', $allowedValues) === false)
            return false;

        return true;
    }
}

?>
