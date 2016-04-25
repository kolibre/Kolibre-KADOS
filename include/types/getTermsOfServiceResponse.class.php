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

require_once('label.class.php');

class getTermsOfServiceResponse extends AbstractType {

    /**
     * @var (object)label
     */
    public $label;


    /******************** public functions ********************/

    /**
     * constructor for class getTermsOfServiceResponse
     */
    function __construct($_label = NULL) {
        if (is_a($_label, "label")) $this->setLabel($_label);
    }


    /******************** class get set methods ********************/

    /**
     * getter for label
     */
    function getLabel() {
        return $this->label;
    }

    /**
     * setter for label
     */
    function setLabel($_label) {
        $this->label = $_label;
    }

    /**
     * resetter for label
     */
    function resetLabel() {
        $this->label = NULL;
    }

    /******************** validator methods ********************/

    /**
     * validator for class getTermsOfServiceResponse
     */
    function validate() {
        // label must oocur exactly once
        if ($this->isInstanceOf($this->label, 'label') === false)
            return false;
        if ($this->label->validate() === false) {
            $this->error = __CLASS__ . '.' . $this->label->getError();
            return false;
        }

        return true;
    }
}

?>
