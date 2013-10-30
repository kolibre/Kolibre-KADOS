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

require_once('choice.class.php');

class choices extends AbstractType {

    /**
     * @var array[1, unbounded] of (object)choice
     */
    public $choice;


    /******************** public functions ********************/

    /**
     * constructor for class choices
     */
    function __construct($_choice = NULL) {
        if (is_array($_choice)) $this->setChoice($_choice);
    }


    /******************** class get set methods ********************/

    /**
     * getter for choice
     */
    function getChoice() {
        return $this->choice;
    }

    /**
     * setter for choice
     */
    function setChoice($_choice) {
        $this->choice = $_choice;
    }

    /**
     * resetter for choice
     */
    function resetChoice() {
        $this->choice = NULL;
    }


    /****************************** get set methods for arrays **********************************/

    /**
     * get the ith element of choice
     */
    function getChoiceAt($i) {
        if ($this->sizeofChoice() > $i)
            return $this->choice[$i];
        else return NULL;
    }

    /**
     * set the ith elemenent of choice
     */
    function setChoiceAt($i, $_choice) {
        $this->choice[$i] = $_choice;
    }

    /**
     * add to choice
     */
    function addChoice($_choice) {
        if (is_array($this->choice))
            array_push($this->choice, $_choice);
        else {
            $this->choice = array();
            $this->addChoice($_choice);
        }
    }

    /**
     * get the size of the choice array
     */
    function sizeofChoice() {
        return sizeof($this->choice);
    }

    /**
     * remove the ith element of choice
     */
    function removeChoiceAt($i) {
        if ($this->sizeofChoice() > $i)
            unset($this->choice[$i]);
    }


    /******************** validator methods ********************/

    /**
     * validator for class choices
     */
    function validate() {
        // choice must occur one or more times
        if ($this->isNoneEmptyArray($this->choice, 'choice') === false)
            return false;
        if ($this->isArrayOfInstanceOf($this->choice, 'choice') === false)
            return false;
        foreach ($this->choice as $index => $choice) {
            if ($choice->validate() === false) {
                $this->error = __CLASS__ . '.' . $choice->getError();
                $this->error = str_replace('choice', "choice[$index]");
                return false;
            }
        }

        return true;
    }
}

?>
