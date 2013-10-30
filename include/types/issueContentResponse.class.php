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

class issueContentResponse extends AbstractType {

    /**
     * @var boolean
     */
    public $issueContentResult;


    /******************** public functions ********************/

    /**
     * constructor for class issueContentResponse
     */
    function __construct($_issueContentResult = NULL) {
        if (is_bool($_issueContentResult)) $this->setIssueContentResult($_issueContentResult);
    }


    /******************** class get set methods ********************/

    /**
     * getter for issueContentResult
     */
    function getIssueContentResult() {
        return $this->issueContentResult;
    }

    /**
     * setter for issueContentResult
     */
    function setIssueContentResult($_issueContentResult) {
        $this->issueContentResult = $_issueContentResult;
    }

    /**
     * resetter for issueContentResult
     */
    function resetIssueContentResult() {
        $this->issueContentResult = NULL;
    }


    /******************** validator methods ********************/

    /**
     * validator for class issueContentResponse
     */
    function validate() {
        // issueContentResult must occur exactly once
        if ($this->isBoolean($this->issueContentResult, 'issueContentResult') === false)
            return false;

        return true;
    }
}

?>
