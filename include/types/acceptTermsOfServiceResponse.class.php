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

class acceptTermsOfServiceResponse extends AbstractType {

    /**
     * @var boolean
     */
    public $acceptTermsOfServiceResult;


    /******************** public functions ********************/

    /**
     * constructor for class acceptTermsOfServiceResponse
     */
    function __construct($_acceptTermsOfServiceResult = NULL) {
        if (is_bool($_acceptTermsOfServiceResult)) $this->setAcceptTermsOfServiceResult($_acceptTermsOfServiceResult);
    }


    /******************** class get set methods ********************/

    /**
     * getter for acceptTermsOfServiceResult
     */
    function getAcceptTermsOfServiceResult() {
        return $this->acceptTermsOfServiceResult;
    }

    /**
     * setter for acceptTermsOfServiceResult
     */
    function setAcceptTermsOfServiceResult($_acceptTermsOfServiceResult) {
        $this->acceptTermsOfServiceResult = $_acceptTermsOfServiceResult;
    }

    /**
     * resetter for acceptTermsOfServiceResult
     */
    function resetAcceptTermsOfServiceResult() {
        $this->acceptTermsOfServiceResult = NULL;
    }


    /******************** validator methods ********************/


    /**
     * validator for class acceptTermsOfServiceResponse
     */
    function validate() {
        // acceptTermsOfServiceResult must occur exactly once
        if ($this->isBoolean($this->acceptTermsOfServiceResult, 'acceptTermsOfServiceResult') === false)
            return false;

        return true;
    }
}

?>
