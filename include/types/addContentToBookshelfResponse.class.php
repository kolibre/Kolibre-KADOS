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

class addContentToBookshelfResponse extends AbstractType {

    /**
     * @var boolean
     */
    public $addContentToBookshelfResult;


    /******************** public functions ********************/

    /**
     * constructor for class addContentToBookshelfResponse
     */
    function __construct($_addContentToBookshelfResult = NULL) {
        if (is_bool($_addContentToBookshelfResult)) $this->setAddContentToBookshelfResult($_addContentToBookshelfResult);
    }


    /******************** class get set methods ********************/

    /**
     * getter for addContentToBookshelfResult
     */
    function getAddContentToBookshelfResult() {
        return $this->addContentToBookshelfResult;
    }

    /**
     * setter for addContentToBookshelfResult
     */
    function setAddContentToBookshelfResult($_addContentToBookshelfResult) {
        $this->addContentToBookshelfResult = $_addContentToBookshelfResult;
    }

    /**
     * resetter for addContentToBookshelfResult
     */
    function resetAddContentToBookshelfResult() {
        $this->addContentToBookshelfResult = NULL;
    }


    /******************** validator methods ********************/


    /**
     * validator for class addContentToBookshelfResponse
     */
    function validate() {
        // addContentToBookshelfResult must occur exactly once
        if ($this->isBoolean($this->addContentToBookshelfResult, 'addContentToBookshelfResult') === false)
            return false;

        return true;
    }
}

?>
