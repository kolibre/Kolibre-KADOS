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

class credentials extends AbstractType {

    /**
     * @var string
     */
    public $username;

    /**
     * @var string
     */
    public $password;

    /**
     * @var string
     */
    public $encryptionScheme;

    /******************** public functions ********************/

    /**
     * constructor for class credentials
     */
    function __construct($_username = NULL, $_password = NULL, $_encryptionScheme = NULL) {
        if (is_string($_username)) $this->setUsername($_username);
        if (is_string($_password)) $this->setPassword($_password);
        if (is_string($_encryptionScheme)) $this->setEncryptionScheme($_encryptionScheme);
    }


    /******************** class get set methods ********************/

    /**
     * getter method for class variable $username
     */
    function getUsername() {
        return $this->username;
    }

    /**
     * setter method for class variable $username
     */
    function setUsername($_username) {
        $this->username = $_username;
    }

    /**
     * resetter method for class variable $username
     */
    function resetUsername() {
        $this->username = NULL;
    }

    /**
     * getter method for class variable $password
     */
    function getPassword() {
        return $this->password;
    }

    /**
     * setter method for class variable $password
     */
    function setPassword($_password) {
        $this->password = $_password;
    }

    /**
     * resetter method for class variable $password
     */
    function resetPassword() {
        $this->password = NULL;
    }

    /**
     * getter method for class variable $encryptionScheme
     */
    function getEncryptionScheme() {
        return $this->encryptionScheme;
    }

    /**
     * setter method for class variable $encryptionScheme
     */
    function setEncryptionScheme($_encryptionScheme) {
        $this->encryptionScheme = $_encryptionScheme;
    }

    /**
     * resetter method for class variable $encryptionScheme
     */
    function resetEncryptionScheme() {
        $this->encryptionScheme = NULL;
    }

    /******************** validator methods ********************/

    /**
     * validator for class credentials
     */
    function validate() {
        // username must occur exactly once
        if ($this->isNoneEmptyString($this->username, 'username') === false)
            return false;

        // password must occur exactly once
        if ($this->isNoneEmptyString($this->password, 'password') === false)
            return false;


        // encrytionScheme must match string "RSAES-OAEP"
        $allowedValues = array("RSAES-OAEP");
        if ($this->isString($this->encryptionScheme, 'encryptionScheme', $allowedValues) === false)
            return false;

        return true;
    }
}

?>
