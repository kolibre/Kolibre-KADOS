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
require_once('bookmarkSet.class.php');


class bookmarkObject extends AbstractType {



    /**
     * @var (object)bookmarkSet
     */
    public $bookmarkSet;

     /**
     * @var string with format YYYY-MM-DDThh:mm:ss+hh:mm or YYYY-MM-DDThh:mm:ssZ
     */
    public $lastModifiedDate;

    /******************** public functions ********************/

    /**
     * constructor for class bookmarkObject
     */
    function __construct($_bookmarkSet = NULL,$_lastModifiedDate = NULL) {
        if(is_a($_bookmarkSet, 'bookmarkSet')) $this->setBookmarkSet($_bookmarkSet);
        if (is_string($_lastModifiedDate)) $this->setLastModifiedDate($_lastModifiedDate);

    }


    /******************** class get set methods ********************/

    /**
     * getter for lastModifiedDate
     */
    function getLastModifiedDate() {
        return $this->lastModifiedDate;
    }

    /**
     * setter for lastModifiedDate
     */
    function setLastModifiedDate($_lastModifiedDate) {
        $this->lastModifiedDate = $_lastModifiedDate;
    }

    /**
     * resetter for lastModifiedDate
     */
    function resetLastModifiedDate() {
        $this->lastModifiedDate = NULL;
    }

    /**
     * getter for bookmarkSet
     */
    function getBookmarkSet() {
        return $this->bookmarkSet;
    }

    /**
     * setter for bookmarkSet
     */
    function setBookmarkSet($_bookmarkSet) {
        $this->bookmarkSet = $_bookmarkSet;
    }

    /**
     * resetter for bookmarkSet
     */
    function resetBookmarkSet() {
        $this->bookmarkSet = NULL;
    }


    /******************** validator methods ********************/

    /**
     * validator for class bookmarkObject
     */
    function validate() {

        //bookmarkSet must be of type bookmarkSet and correct
        if ($this->isInstanceOf($this->bookmarkSet, 'bookmarkSet') === false){
            return false;
        }
        if ($this->bookmarkSet->validate() === false) {
            $this->error = __CLASS__ . '.' . $this->bookmarkSet->getError();
            return false;
        }

        //lastModifiedDate must occur exactly once
        if ($this->isDateTimeString($this->lastModifiedDate, 'lastModifiedDate') === false) {
            return false;
        }



        return true;
    }
}

?>
