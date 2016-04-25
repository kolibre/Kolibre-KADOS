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
require_once('credentials.class.php');

class getUserCredentialsResponse extends AbstractType {

    /**
     * @var (object) credentials
     */
    public $credentials;


    /******************** public functions ********************/

    /**
     * constructor for class getUserCredentialsResponse
     */
    function __construct($_credentials = NULL) {
        if (is_a($_credentials, 'credentials')) $this->setCredentials($_credentials);
    }


    /******************** class get set methods ********************/

    /**
     * getter for credentials
     */
    function getCredentials() {
        return $this->credentials;
    }

    /**
     * setter for credentials
     */
    function setCredentials($_credentials) {
        $this->credentials = $_credentials;
    }

    /**
     * resetter for credentials
     */
    function resetCredentials() {
        $this->credentials = NULL;
    }


    /******************** validator methods ********************/


    /**
     * validator for class getUserCredentialsResponse
     */
    function validate() {
        // credentials must occur exactly once
        if ($this->isInstanceOf($this->credentials, 'credentials') === false)
            return false;
        if ($this->credentials->validate() === false) {
            $this->error = __CLASS__ . '.' . $this->credentials->getError();
            return false;
        }

        return true;
    }
}

?>
