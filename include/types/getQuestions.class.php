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

require_once('userResponses.class.php');

class getQuestions extends AbstractType {

    /**
     * @var (object)userResponses
     */
    public $userResponses;


    /******************** public functions ********************/

    /**
     * constructor for class getQuestions
     */
    function __construct($_userResponses = NULL) {
        if (is_a($_userResponses, "userResponses")) $this->setUserResponses($_userResponses);
    }


    /******************** class get set methods ********************/

    /**
     * getter for userResponses
     */
    function getUserResponses() {
        return $this->userResponses;
    }

    /**
     * setter for userResponses
     */
    function setUserResponses($_userResponses) {
        $this->userResponses = $_userResponses;
    }

    /**
     * resetter for userResponses
     */
    function resetUserResponses() {
        $this->userResponses = NULL;
    }


    /******************** validator methods ********************/

    /**
     * validator for class getQuestions
     */
    function validate() {
        // userResponses must occur exactly once
        if ($this->isInstanceOf($this->userResponses, 'userResponses') === false)
            return false;
        if ($this->userResponses->validate() === false) {
            $this->error = __CLASS__ . '.' . $this->userResponses->getError();
            return false;
        }

        return true;
    }
}

?>
