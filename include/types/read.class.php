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

class read extends AbstractType {

    /**
     * @var array[0, unbounded] of string
     */
    public $item;


    /******************** public functions ********************/

    /**
     * constructor for class read
     */
    function __construct($_item = NULL) {
        if (is_array($_item)) $this->setItem($_item);
    }

    /******************** class get set methods ********************/

    /**
     * getter for item
     */
    function getItem() {
        return $this->item;
    }

    /**
     * setter for item
     */
    function setItem($_item) {
        $this->item = $_item;
    }

    /**
     * resetter for item
     */
    function resetItem() {
        $this->item = NULL;
    }


    /****************************** get set methods for arrays **********************************/

    /**
     * get the ith element of item
     */
    function getItemAt($i) {
        if ($this->sizeofItem() > $i)
            return $this->item[$i];
        else return NULL;
    }

    /**
     * set the ith elemenent of item
     */
    function setItemAt($i, $_item) {
        $this->item[$i] = $_item;
    }

    /**
     * add to item
     */
    function addItem($_item) {
        if (is_array($this->item))
            array_push($this->item, $_item);
        else {
            $this->item = array();
            $this->addItem($_item);
        }
    }

    /**
     * get the size of the item array
     */
    function sizeofItem() {
        return sizeof($this->item);
    }

    /**
     * remove the ith element of item
     */
    function removeItemAt($i) {
        if ($this->sizeofItem() > $i)
            unset($this->item[$i]);
    }


    /******************** validator methods ********************/

    /**
     * validator for class read
     */
    function validate() {
        // item must occur zero or more times
        if (!is_null($this->item)) {
            if ($this->isArrayOfNoneEmptyString($this->item, 'item') === false)
                return false;
        }

        return true;
    }
}

?>
