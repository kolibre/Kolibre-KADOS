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
require_once('readingSystemAttributes.class.php');

class logOn extends AbstractType {

    /**
     * @var string
     */
    public $username;

    /**
     * @var string
     */
    public $password;

    /**
     * @var (object) readingSystemAttributes
     */
    public $readingSystemAttributes;


    /******************** public functions ********************/

    /**
     * constructor for class logOn
     */
    function __construct($_username = NULL, $_password = NULL, $_readingSystemAttributes = NULL) {
        if (is_string($_username)) $this->setUsername($_username);
        if (is_string($_password)) $this->setPassword($_password);
        if (is_a($_readingSystemAttributes, "readingSystemAttributes")) $this->setReadingSystemAttributes($_readingSystemAttributes);
    }


    /******************** class get set methods ********************/

    /**
     * getter for username
     */
    function getUsername() {
        return $this->username;
    }

    /**
     * setter for username
     */
    function setUsername($_username) {
        $this->username = $_username;
    }

    /**
     * resetter for username
     */
    function resetUsername() {
        $this->username = NULL;
    }

    /**
     * getter for password
     */
    function getPassword() {
        return $this->password;
    }

    /**
     * setter for password
     */
    function setPassword($_password) {
        $this->password = $_password;
    }

    /**
     * resetter for password
     */
    function resetPassword() {
        $this->password = NULL;
    }

    /**
     * getter for readingSystemAttributes
     */
    function getReadingSystemAttributes() {
        return $this->readingSystemAttributes;
    }

    /**
     * setter for readingSystemAttributes
     */
    function setReadingSystemAttributes($_readingSystemAttributes) {
        $this->readingSystemAttributes = $_readingSystemAttributes;
    }

    /**
     * resetter for readingSystemAttributes
     */
    function resetReadingSystemAttributes() {
        $this->readingSystemAttributes = NULL;
    }


    /******************** validator methods ********************/

    /**
     * validator for class logOn
     */
    function validate() {
        // username must occur exactly once
        if ($this->isNoneEmptyString($this->username, 'username') === false)
            return false;

        // password must occur exactly once
        if ($this->isNoneEmptyString($this->password, 'password') === false)
            return false;

        // readingSystemAttributes must occur exactly once
        if ($this->isInstanceOf($this->readingSystemAttributes, 'readingSystemAttributes') === false)
            return false;
        if ($this->readingSystemAttributes->validate() === false) {
            $this->error = __CLASS__ . '.' . $this->readingSystemAttributes->getError();
            return false;
        }

        return true;
    }
}

?>
