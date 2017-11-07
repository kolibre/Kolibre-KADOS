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

require_once('input.class.php');

class inputTypes extends AbstractType {

    /**
     * @var array[1, unbounded] of (object)input
     */
    public $input;


    /******************** public functions ********************/

    /**
     * constructor for class inputTypes
     */
    function __construct($_input = NULL) {
        if (is_array($_input)) $this->setInput($_input);
    }


    /******************** class get set methods ********************/

    /**
     * getter for input
     */
    function getInput() {
        return $this->input;
    }

    /**
     * setter for input
     */
    function setInput($_input) {
        $this->input = $_input;
    }

    /**
     * resetter for input
     */
    function resetInput() {
        $this->input = NULL;
    }


    /****************************** get set methods for arrays **********************************/

    /**
     * get the ith element of input
     */
    function getInputAt($i) {
        if ($this->sizeofInput() > $i)
            return $this->input[$i];
        else return NULL;
    }

    /**
     * set the ith elemenent of input
     */
    function setInputAt($i, $_input) {
        $this->input[$i] = $_input;
    }

    /**
     * add to input
     */
    function addInput($_input) {
        if (is_array($this->input))
            array_push($this->input, $_input);
        else {
            $this->input = array();
            $this->addInput($_input);
        }
    }

    /**
     * get the size of the input array
     */
    function sizeofInput() {
        return sizeof($this->input);
    }

    /**
     * remove the ith element of input
     */
    function removeInputAt($i) {
        if ($this->sizeofInput() > $i)
            unset($this->input[$i]);
    }


    /******************** validator methods ********************/

    /**
     * validator for class inputTypes
     */
    function validate() {
        // input must occur one or more times
        if ($this->isNoneEmptyArray($this->input, 'input') === false)
            return false;
        if ($this->isArrayOfInstanceOf($this->input, 'input') === false)
            return false;
        foreach ($this->input as $index => $input) {
            if ($input->validate() === false) {
                $this->error = __CLASS__ . '.' . $input->getError();
                $this->error = str_replace('input', "input[$index]", $this->error);
                return false;
            }
        }

        return true;
    }
}

?>
