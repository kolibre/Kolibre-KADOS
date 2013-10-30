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

class input extends AbstractType {

    /**
     * @var string
     *     NOTE: type should follow the following restrictions
     *     You can have one of the following value
     *     TEXT_NUMERIC
     *     TEXT_ALPHANUMERIC
     *     AUDIO
     */
    public $type;


    /******************** public functions ********************/

    /**
     * constructor for class input
     */
    function __construct($_type = NULL) {
        if (is_string($_type)) $this->setType($_type);
    }


    /******************** class get set methods ********************/

    /**
     * getter for type
     */
    function getType() {
        return $this->type;
    }

    /**
     * setter for type
     */
    function setType($_type) {
        $this->type = $_type;
    }

    /**
     * resetter for type
     */
    function resetType() {
        $this->type = NULL;
    }


    /******************** validator methods ********************/

    /**
     * validator for class input
     */
    function validate() {
        // attribute type is required
        $allowedValues = array("TEXT_NUMERIC", "TEXT_ALPHANUMERIC", "AUDIO");
        if ($this->isString($this->type, 'type', $allowedValues) === false)
            return false;

        return true;
    }
}

?>
