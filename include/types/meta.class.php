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

class meta extends AbstractType {

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $content;


    /******************** public functions ********************/

    /**
     * constructor for class meta
     */
    function __construct($_name = NULL, $_content = NULL) {
        if (is_string($_name)) $this->setName($_name);
        if (is_string($_content)) $this->setContent($_content);
    }


    /******************** class get set methods ********************/

    /**
     * getter for name
     */
    function getName() {
        return $this->name;
    }

    /**
     * setter for name
     */
    function setName($_name) {
        $this->name = $_name;
    }

    /**
     * resetter for name
     */
    function resetName() {
        $this->name = NULL;
    }

    /**
     * getter for content
     */
    function getContent() {
        return $this->content;
    }

    /**
     * setter for content
     */
    function setContent($_content) {
        $this->content = $_content;
    }

    /**
     * resetter for content
     */
    function resetContent() {
        $this->content = NULL;
    }


    /******************** validator methods ********************/

    /**
     * validator for class meta
     */
    function validate() {
        // attribute name is required
        if ($this->isNoneEmptyString($this->name, 'name') === false)
            return false;

        // attribute content is required
        if ($this->isNoneEmptyString($this->content, 'content') === false)
            return false;

        return true;
    }
}

?>
