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

class mimeType extends AbstractType {

    /**
     * @var string
     */
    public $type;

    /**
     * @var language
     *     NOTE: lang should follow the following restrictions
     */
    public $lang;


    /******************** public functions ********************/

    /**
     * constructor for class mimeType
     */
    function __construct($_type = NULL, $_lang = NULL) {
        if (is_string($_type)) $this->setType($_type);
        if (is_string($_lang)) $this->setLang($_lang);
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

    /**
     * getter for lang
     */
    function getLang() {
        return $this->lang;
    }

    /**
     * setter for lang
     */
    function setLang($_lang) {
        $this->lang = $_lang;
    }

    /**
     * resetter for lang
     */
    function resetLang() {
        $this->lang = NULL;
    }


    /******************** validator methods ********************/

    /**
     * validator for class mimeType
     */
    function validate() {
        // attribute type is required
        if ($this->isNoneEmptyString($this->type, 'type') == false)
            return false;

        // attribute lang is optional
        if (!is_null($this->lang)) {
            if ($this->isNoneEmptyString($this->lang, 'lang') === false)
                return false;
        }

        return true;;
    }
}

?>
