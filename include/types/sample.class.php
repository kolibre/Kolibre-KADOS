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

class sample extends AbstractType {

    /**
     * @var string
     */
    public $id;


    /******************** public functions ********************/

    /**
     * constructor for class sample
     */
    function __construct($_id = NULL) {
        if (is_string($_id)) $this->setId($_id);
    }


    /******************** class get set methods ********************/

    /**
     * getter for id
     */
    function getId() {
        return $this->id;
    }

    /**
     * setter for id
     */
    function setId($_id) {
        $this->id = $_id;
    }

    /**
     * resetter for id
     */
    function resetId() {
        $this->id = NULL;
    }


    /******************** validator methods ********************/

    /**
     * validator for class sample
     */
    function validate() {
        // attribute id is required
        if ($this->isNoneEmptyString($this->id, 'id') === false)
            return false;

        return true;
    }
}

?>
