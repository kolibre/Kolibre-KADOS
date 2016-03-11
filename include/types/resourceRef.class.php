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

class resourceRef extends AbstractType {

    /**
     * @var string
     */
    public $localURI;

   

    /******************** public functions ********************/

    /**
     * constructor for class package
     */
    function __construct($_localURI = NULL) {
        if (is_string($_localURI)) $this->setlocalURI($_localURI);
        
    }


    /******************** class get set methods ********************/

    /**
     * getter for localURI
     */
    function getlocalURI() {
        return $this->localURI;
    }

    /**
     * setter for localURI
     */
    function setlocalURI($_localURI) {
        $this->localURI = $_localURI;
    }

    /**
     * resetter for localURI
     */
    function resetlocalURI() {
        $this->localURI = NULL;
    }

    /******************** validator methods ********************/

    /**
     * validator for class package
     */
    function validate() {
        // localURI must occur exactly once
        if ($this->isNoneEmptyString($this->localURI, 'localURI') === false)
            return false;

        return true;
    }
}

?>
