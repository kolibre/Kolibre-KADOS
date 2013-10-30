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

require_once('resources.class.php');

class getContentResourcesResponse extends AbstractType {

    /**
     * @var (object)resources
     */
    public $resources;


    /******************** public functions ********************/

    /**
     * constructor for class getContentResourcesResponse
     */
    function __construct($_resources = NULL) {
        if (is_a($_resources, "resources")) $this->setResources($_resources);
    }


    /******************** class get set methods ********************/

    /**
     * getter for resources
     */
    function getResources() {
        return $this->resources;
    }

    /**
     * setter for resources
     */
    function setResources($_resources) {
        $this->resources = $_resources;
    }

    /**
     * resetter for resources
     */
    function resetResources() {
        $this->resources = NULL;
    }


    /******************** validator methods ********************/

    /**
     * validator for class getContentResourcesResponse
     */
    function validate() {
        // resources must occur exactly once
        if ($this->isInstanceOf($this->resources, 'resources') === false)
            return false;
        if ($this->resources->validate() === false) {
            $this->error = __CLASS__ . '.' . $this->resources->getError();
            return false;
        }

        return true;
    }
}

?>
