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

require_once('contentMetadata.class.php');

class getContentMetadataResponse extends AbstractType {

    /**
     * @var (object)contentMetadata
     */
    public $contentMetadata;


    /******************** public functions ********************/

    /**
     * constructor for class getContentMetadataResponse
     */
    function __construct($_contentMetadata = NULL) {
        if (is_a($_contentMetadata, "contentMetadata")) $this->setContentMetadata($_contentMetadata);

    }


    /******************** class get set methods ********************/

    /**
     * getter for contentMetadata
     */
    function getContentMetadata() {
        return $this->contentMetadata;
    }

    /**
     * setter for contentMetadata
     */
    function setContentMetadata($_contentMetadata) {
        $this->contentMetadata = $_contentMetadata;
    }

    /**
     * resetter for contentMetadata
     */
    function resetContentMetadata() {
        $this->contentMetadata = NULL;
    }


    /******************** validator methods ********************/

    /**
     * validator for class getContentMetadataResponse
     */
    function validate() {
        // contentMetadata must occur exactly once
        if ($this->isInstanceOf($this->contentMetadata, 'contentMetadata') === false)
            return false;
        if ($this->contentMetadata->validate() === false) {
            $this->error = __CLASS__ . '.' . $this->contentMetadata->getError();
            return false;
        }

        return true;
    }
}

?>
